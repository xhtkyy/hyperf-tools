<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'enable' => [
        'discovery' => true,
        'register' => true,
    ],
    'consumers' => [],
    'providers' => [],
    'drivers' => [
        'consul' => [
            'uri' => \Hyperf\Support\env("CONSUL_URI", "consul:8500"),
            'token' => '',
            'check' => [
                'deregister_critical_service_after' => '90m',
                'interval' => '1s',
            ],
        ],
        'nacos' => [
            // nacos server url like https://nacos.hyperf.io, Priority is higher than host:port
            'uri' => \Hyperf\Support\env('NACOS_URI', ''),
            // The nacos host info
            'host' => \Hyperf\Support\env('NACOS_HOST', 'nacos'),
            'port' => intval(\Hyperf\Support\env('NACOS_PORT', 8848)),
            // The nacos account info
            'username' => \Hyperf\Support\env('NACOS_USERNAME'),
            'password' => \Hyperf\Support\env('NACOS_PASSWORD'),
            // Mes config
            'access_key' => \Hyperf\Support\env("NACOS_ACCESS_KEY"),
            'access_secret' => \Hyperf\Support\env("NACOS_ACCESS_SECRET"),
            'guzzle' => [
                'config' => [
                    'headers' => [
                        'charset' => 'UTF-8',
                    ],
                    'http_errors' => false,
                    'handler' => \Xhtkyy\HyperfTools\Guzzle\Retry::handler()
                ],
            ],
            'group_name' => \Hyperf\Support\env('NACOS_GROUP_NAME', 'api'),
            'namespace_id' => \Hyperf\Support\env('NACOS_NAMESPACE'),
            'heartbeat' => 5,
            'ephemeral' => \Hyperf\Support\env('NACOS_EPHEMERAL', true),
        ],
    ],
];
