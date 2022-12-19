<?php

namespace Xhtkyy\HyperfTools\GrpcClient;

use Exception;
use Google\Protobuf\Internal\Message;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Grpc\StatusCode;
use Hyperf\LoadBalancer\LoadBalancerManager;
use Hyperf\LoadBalancer\Node;
use Hyperf\ServiceGovernance\DriverManager;
use Psr\Container\ContainerInterface;

class GrpcClientManager {
    private string $governanceDriverPath;
    private string|null $credentials = null;
    private array $pools = [];


    #[Inject]
    protected DriverManager $governanceManager;
    #[Inject]
    protected ContainerInterface $container;

    public function __construct(ConfigInterface $config) {
        $this->governanceDriverPath = $config->get("kyy_tools.consul.hostname", "consul:8500");
    }

    public function getNode(string $server): string {
        if ($governance = $this->governanceManager->get('consul4grpc')) {
            try {
                /**
                 * @var LoadBalancerManager $loadBalancerManager
                 */
                $loadBalancerManager = $this->container->get(LoadBalancerManager::class);
                $serverLB            = $loadBalancerManager->getInstance($server, "round-robin");
                if (!$serverLB->isAutoRefresh()) {
                    $fun = function () use ($governance, $server) {
                        $nodes = [];
                        foreach ($governance->getNodes($this->governanceDriverPath, $server, ['protocol' => 'grpc']) as $node) {
                            $nodes[] = new Node($node['host'], $node['port']);
                        }
                        return $nodes;
                    };
                    //设置滚动刷新 异步
                    $serverLB->setNodes($fun())->refresh($fun);
                }
                $node = $serverLB->select();
                return sprintf("%s:%d", $node->host, $node->port);
            } catch (\Throwable $throwable) {
                //todo 日志
            }
        }
        return "";
    }

    /**
     * 获取请求客户端
     * @param string $hostname
     * @param string $method
     * @return GrpcClient
     * @throws Exception
     */
    public function getClient(string $hostname, string $method): GrpcClient {
        if (empty($hostname)) {
            //获取服务名称
            $server = trim(current(explode(".", $method)), "/");
            //获取节点地址
            $hostname = $this->getNode($server);
        }

        if (empty($hostname)) {
            throw new Exception("hostname not found!");
        }
        //通过节点地址 在容器里获取 节点客户端
        if (!isset($this->pools[$hostname])) {
            //丢进池子复用
            $this->pools[$hostname] = new GrpcClient($hostname, [
                'credentials' => $this->credentials,
            ]);
        }
        return $this->pools[$hostname];
    }

    public function addClient(string $hostname, GrpcClient $client): void {
        $this->pools[$hostname] = $client;
    }

    public function removeClient(string $hostname, GrpcClient $client): void {
        if (isset($this->pools[$hostname])) {
            unset($this->pools[$hostname]);
        }
    }

    public function invoke(string $hostname, string $method, Message $argument, $deserialize, array $metadata = [], array $options = []): array {
        //响应
        try {
            return $this->getClient($hostname, $method)->invoke($method, $argument, $deserialize, $metadata, $options);
        } catch (Exception $e) {
            //todo 写入日志
            return [null, StatusCode::ABORTED];
        }
    }
}