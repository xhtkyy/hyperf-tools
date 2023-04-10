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
            'uri' => env("CONSUL_URI", "consul:8500"),
            'token' => '',
            'check' => [
                'deregister_critical_service_after' => '90m',
                'interval' => '1s',
            ],
        ],
        'nacos' => [
            // nacos server url like https://nacos.hyperf.io, Priority is higher than host:port
            'uri' => env('NACOS_URI', ''),
            // The nacos host info
            'host' => env('NACOS_HOST', 'nacos'),
            'port' => intval(env('NACOS_PORT', 8848)),
            // The nacos account info
            'username' => env('NACOS_USERNAME', 'nacos'),
            'password' => env('NACOS_PASSWORD', 'nacos'),
            'guzzle' => [
                'config' => [
                    'headers' => [
                        'charset' => 'UTF-8',
                    ],
                    'http_errors' => false,
                    'handler' => \Xhtkyy\HyperfTools\Guzzle\Retry::handler()
                ],
            ],
            'group_name' => env('NACOS_GROUP_NAME', 'api'),
            'namespace_id' => env('NACOS_NAMESPACE'),
            'heartbeat' => 5,
            'ephemeral' => env('NACOS_EPHEMERAL', true),
        ],
    ],
];
