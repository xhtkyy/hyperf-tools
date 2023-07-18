<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Nacos;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Nacos\Config;
use Hyperf\ServiceGovernanceNacos\Client;
use Psr\Container\ContainerInterface;

class ClientFactory
{
    public function __invoke(ContainerInterface $container): Client
    {
        $config = $container->get(ConfigInterface::class)->get('services.drivers.nacos', []);
        if (! empty($config['uri'])) {
            $baseUri = $config['uri'];
        } else {
            $baseUri = sprintf('http://%s:%d', $config['host'] ?? '127.0.0.1', $config['port'] ?? 8848);
        }

        return new Client(new Config([
            'base_uri' => $baseUri,
            'username' => $config['username'] ?? null,
            'password' => $config['password'] ?? null,
            'access_key' => $config['access_key'] ?? null,
            'access_secret' => $config['access_secret'] ?? null,
            'guzzle_config' => $config['guzzle']['config'] ?? null,
            'host' => $config['host'] ?? null,
            'port' => $config['port'] ?? null,
        ]));
    }
}