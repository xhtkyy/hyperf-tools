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

class GrpcClientManager {
    private string|null $credentials = null;
    private array $pools = [];

    protected DriverManager $governanceManager;
    protected ConfigInterface $config;
    protected LoggerInterface $logger;

    public function __construct(protected ContainerInterface $container) {
        $this->config            = $this->container->get(ConfigInterface::class);
        $this->governanceManager = $this->container->get(DriverManager::class);
        $this->logger            = $this->container->get(StdoutLoggerInterface::class);
    }

    public function getNode(string $server): string {
        $driverName       = $this->config->get("kyy_tools.register.driver_name", "nacos");
        $consulDriverPath = $driverName == "nacos" ? "" : $this->config->get("services.drivers.consul.uri", "consul:8500");
        $algo             = $this->config->get("kyy_tools.register.algo", "round-robin");
        if ($governance = $this->governanceManager->get($driverName)) {
            try {
                /**
                 * @var LoadBalancerManager $loadBalancerManager
                 */
                $loadBalancerManager = $this->container->get(LoadBalancerManager::class);
                $serverLB            = $loadBalancerManager->getInstance($server, $algo);
                if (!$serverLB->isAutoRefresh()) {
                    $fun = function () use ($governance, $server, $consulDriverPath) {
                        $nodes = [];
                        foreach ($governance->getNodes($consulDriverPath, $server, ['protocol' => 'grpc']) as $node) {
                            $nodes[] = new Node($node['host'], $node['port'], $node['weight'] ?? 1);
                        }
                        return $nodes;
                    };
                    //?????????????????? ??????
                    $serverLB->setNodes($fun())->refresh($fun);
                }
                $node = $serverLB->select();
                return sprintf("%s:%d", $node->host, $node->port);
            } catch (\Throwable $throwable) {
                $this->logger->error(sprintf("?????? %s ??? %s ???????????? ?????????%s ?????????%s", $server, $driverName, $algo, $throwable->getMessage()));
            }
        }
        return "";
    }

    /**
     * ?????????????????????
     * @param string $hostname
     * @param string $method
     * @return GrpcClient
     * @throws Exception
     */
    public function getClient(string $hostname, string $method): GrpcClient {
        if (empty($hostname)) {
            //??????????????????
            $server = trim(current(explode(".", $method)), "/");
            //??????????????????
            $hostname = $this->getNode($server);
        }

        if (empty($hostname)) {
            throw new Exception("hostname not found!");
        }
        //?????????????????? ?????????????????? ???????????????
        if (!isset($this->pools[$hostname])) {
            //??????????????????
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
        //??????
        try {
            return $this->getClient($hostname, $method)->invoke($method, $argument, $deserialize, $metadata, $options);
        } catch (Exception $e) {
            $this->logger->error(sprintf("??????Client???????????? %s %s,???????????????%s", $hostname, $method, $e->getMessage()));
            return [null, StatusCode::ABORTED];
        }
    }
}