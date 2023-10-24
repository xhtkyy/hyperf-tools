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
    protected array $service_alias = [];
    protected array $service_hostname = [];


    public function __construct(protected ContainerInterface $container)
    {
        $this->config = $this->container->get(ConfigInterface::class);
        $this->governanceManager = $this->container->get(DriverManager::class);
        $this->logger = $this->container->get(StdoutLoggerInterface::class);
        //
        $this->algo = $this->config->get("kyy_tools.register.algo", "round-robin");
        $this->service_alias = $this->config->get("kyy_tools.service_alias", []);
        $this->config->get("app_env") == "dev" && $this->service_hostname = $this->config->get("hosts", []);
    }

    public function getNode(string $server): ?Node
    {
        // service alias
        $server = $this->service_alias[$server] ?? $server . ".grpc";

        //获取配置，兼容调试使用
        if (isset($this->service_hostname[$server]) && !empty($node = $this->service_hostname[$server])) {
            [$host, $port] = explode(":", $node);
            return new Node($host, $port);
        }

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
                $this->logger->error(sprintf("服务 %s 在 %s 获取失败 策略：%s 原因：%s", $server, $driverName, $this->algo, $throwable->getMessage()));
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
                'timeout' => 8.0
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

    public function invoke(?string $hostname, string $method, Message $argument, $deserialize, array $metadata = [], array $options = []): array
    {
        //响应
        try {
            if (empty($hostname)) {
                $node = null;
                //根据/分割 获取服务名称
                $server = explode('.', trim(current(explode("/", $method)), "/"));
                //增加支持多级服务
                for ($i = count($server); $i > 0; $i--) {
                    if (isset($server[$i])) unset($server[$i]);
                    //获取节点地址
                    $node = $this->getNode(implode('.', $server));
                    if ($node) break;
                }
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
                $instance = $loadBalancerManager->getInstance($server, $this->algo);
                if ($instance->removeNode($node) && count($instance->getNodes()) > 0) {
                    $this->logger->debug(sprintf("%s 重试获取节点，%s %s", $server, $hostname, $method));
                    return $this->invoke(null, $method, $argument, $deserialize, $metadata, $options);
                }
            }
            $this->logger->error(sprintf("服务Client获取失败 %s %s,错误信息：%s", $hostname, $method, $e->getMessage()));
            return [null, StatusCode::ABORTED];
        }
    }
}