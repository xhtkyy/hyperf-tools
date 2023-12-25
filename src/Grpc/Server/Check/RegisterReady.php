<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Grpc\Server\Check;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Contract\IPReaderInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\ServiceGovernance\DriverManager;
use InvalidArgumentException;

class RegisterReady
{
    protected ConfigInterface $config;
    protected IPReaderInterface $ipReader;
    protected DriverManager $governanceManager;

    public function __construct(protected ContainerInterface $container)
    {
        $this->config = $this->container->get(ConfigInterface::class);
        $this->ipReader = $container->get(IPReaderInterface::class);
        $this->governanceManager = $container->get(DriverManager::class);
    }

    public function handle(array $services, string $serverName = 'grpc'): \Psr\Http\Message\ResponseInterface
    {
        [$host, $port] = $this->getServers()[$serverName] ?? throw new InvalidArgumentException("Invalid server name");
        $driver = $this->governanceManager->get($this->config->get('kyy_tools.register.driver_name', 'nacos-grpc'));
        $response = $this->container->get(ResponseInterface::class);
        foreach ($services as $service => $metadata) {
            if (!$driver->isRegistered($service, $host, $port, $metadata)) {
                return $response->raw("service: $service unregister")->withStatus(500);
            }
        }
        return $response->raw('ok')->withStatus(200);
    }

    protected function getServers(): array
    {
        $result = [];
        $servers = $this->config->get('server.servers', []);
        foreach ($servers as $server) {
            if (!isset($server['name'], $server['host'], $server['port'])) {
                continue;
            }
            if (!$server['name']) {
                throw new InvalidArgumentException('Invalid server name');
            }
            $host = $server['host'];
            if (in_array($host, ['0.0.0.0', 'localhost'])) {
                $host = $this->ipReader->read();
            }
            if (!filter_var($host, FILTER_VALIDATE_IP)) {
                throw new InvalidArgumentException(sprintf('Invalid host %s', $host));
            }
            $port = $server['port'];
            if (!is_numeric($port) || ($port < 0 || $port > 65535)) {
                throw new InvalidArgumentException(sprintf('Invalid port %s', $port));
            }
            $port = (int)$port;
            $result[$server['name']] = [$host, $port];
        }
        return $result;
    }
}