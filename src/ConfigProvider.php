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

namespace Xhtkyy\HyperfTools;

use Xhtkyy\HyperfTools\App\Container;
use Xhtkyy\HyperfTools\App\ContainerInterface;
use Xhtkyy\HyperfTools\Grpc\RegisterGrpcServiceListener;
use Xhtkyy\HyperfTools\Grpc\Server\ServerStartListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                //接管容器
                ContainerInterface::class => Container::class,
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ]
            ],
            'listeners' => [
                RegisterGrpcServiceListener::class,
                ServerStartListener::class
            ],
            'publish' => [
                [
                    'id' => 'config1',
                    'description' => 'the config for kyy_tools',
                    'source' => __DIR__ . '/../publish/kyy_tools.php',
                    'destination' => BASE_PATH . '/config/autoload/kyy_tools.php',
                ],
                [
                    'id' => 'config2',
                    'description' => 'The config for tracer.',
                    'source' => __DIR__ . '/../publish/opentracing.php',
                    'destination' => BASE_PATH . '/config/autoload/opentracing.php',
                ],
                [
                    'id' => 'config3',
                    'description' => 'The config for services.',
                    'source' => __DIR__ . '/../publish/services.php',
                    'destination' => BASE_PATH . '/config/autoload/services.php',
                ],
                [
                    'id' => 'config4',
                    'description' => 'The config for bot notice.',
                    'source' => __DIR__ . '/../publish/bot_notice.php',
                    'destination' => BASE_PATH . '/config/autoload/bot_notice.php',
                ]
            ]
        ];
    }
}
