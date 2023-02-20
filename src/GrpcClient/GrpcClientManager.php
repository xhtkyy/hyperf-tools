<?php

namespace Xhtkyy\HyperfTools\GrpcClient;

use Exception;
use Google\Protobuf\Internal\Message;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Grpc\StatusCode;
use Hyperf\LoadBalancer\LoadBalancerManager;
use Hyperf\LoadBalancer\Node;
use Hyperf\ServiceGovernance\DriverManager;
use Psr\Log\LoggerInterface;
use Xhtkyy\HyperfTools\App\ContainerInterface;

class GrpcClientManager
{
    private string|null $credentials = null;
    private array $pools = [];

    protected DriverManager $governanceManager;
    protected ConfigInterface $config;
    protected LoggerInterface $logger;
    protected string $algo;

    public function __construct(protected ContainerInterface $container)
    {
        $this->config = $this->container->get(ConfigInterface::class);
        $this->governanceManager = $this->container->get(DriverManager::class);
        $this->logger = $this->container->get(StdoutLoggerInterface::class);
        //
        $this->algo = $this->config->get("kyy_tools.register.algo", "round-robin");
    }

    public function getNode(string $server): ?Node
    {
        var_dump($this->algo);
        $driverName = $this->config->get("kyy_tools.register.driver_name", "nacos");
        $consulDriverPath = $driverName == "nacos" ? "" : $this->config->get("services.drivers.consul.uri", "consul:8500");
        if ($governance = $this->governanceManager->get($driverName)) {
            try {
                /**
                 * @var LoadBalancerManager $loadBalancerManager
                 */
                $loadBalancerManager = $this->container->get(LoadBalancerManager::class);
                $serverLB = $loadBalancerManager->getInstance($server, $this->algo);
                if (!$serverLB->isAutoRefresh()) {
                    $fun = function () use ($governance, $server, $consulDriverPath) {
                        $nodes = [];
                        foreach ($governance->getNodes($consulDriverPath, $server, ['protocol' => 'grpc']) as $node) {
                            $nodes[] = new Node($node['host'], $node['port'], $node['weight'] ?? 1);
                        }
                        return $nodes;
                    };
                    //设置滚动刷新 异步
                    $serverLB->setNodes($fun())->refresh($fun);
                }
                return $serverLB->select();
            } catch (\Throwable $throwable) {
                $this->logger->error(sprintf("服务 %s 在 %s 获取失败 策略：%s 原因：%s", $server, $driverName, $algo, $throwable->getMessage()));
            }
        }
        return null;
    }

    /**
     * 获取请求客户端
     * @param string $hostname
     * @param string $method
     * @return GrpcClient
     * @throws Exception
     */
    public function getClient(string $hostname, string $method): GrpcClient
    {
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

    public function addClient(string $hostname, GrpcClient $client): void
    {
        $this->pools[$hostname] = $client;
    }

    public function removeClient(string $hostname, GrpcClient $client): void
    {
        if (isset($this->pools[$hostname])) {
            unset($this->pools[$hostname]);
        }
    }

    public function invoke(string $hostname, string $method, Message $argument, $deserialize, array $metadata = [], array $options = []): array
    {
        //响应
        try {
            if (empty($hostname)) {
                //获取服务名称
                $server = trim(current(explode(".", $method)), "/");
                //获取节点地址
                $node = $this->getNode($server);
                if (!$node) return ["无服务节点", StatusCode::ABORTED];
                $hostname = sprintf("%s:%d", $node->host, $node->port);
            }
            return $this->getClient($hostname, $method)->invoke($method, $argument, $deserialize, $metadata, $options);
        } catch (Exception $e) {
            if (isset($node, $server) && str_contains($e->getMessage(), "Connect failed")) {
                /**
                 * @var LoadBalancerManager $loadBalancerManager
                 */
                $loadBalancerManager = $this->container->get(LoadBalancerManager::class);
                $loadBalancerManager->getInstance($server, $this->algo)->removeNode($node);
            }
            $this->logger->error(sprintf("服务Client获取失败 %s %s,错误信息：%s", $hostname, $method, $e->getMessage()));
            return [null, StatusCode::ABORTED];
        }
    }
}