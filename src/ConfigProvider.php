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

use DtmClient\Api\GrpcApi;
use Xhtkyy\HyperfTools\App\Container;
use Xhtkyy\HyperfTools\App\ContainerInterface;
use Xhtkyy\HyperfTools\Casbin\CasbinInterface;
use Xhtkyy\HyperfTools\Casbin\src\Casbin;
use Xhtkyy\HyperfTools\Consul\Listener\RegisterConsul4GrpcDriverListener;
use Xhtkyy\HyperfTools\Consul\Listener\RegisterGrpcServiceListener;
use Xhtkyy\HyperfTools\Dtm\DtmGrpcApi;
use Xhtkyy\HyperfTools\Dtm\GrpcClientManagerFactory;
use Xhtkyy\HyperfTools\GrpcClient\GrpcClientManager;
use Xhtkyy\HyperfTools\Listener\AutoInitGPBMetadataListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                GrpcClientManager::class => GrpcClientManagerFactory::class,
                GrpcApi::class => DtmGrpcApi::class,
                //接管容器
                ContainerInterface::class => Container::class,
                CasbinInterface::class => Casbin::class
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'listeners' => [
                RegisterConsul4GrpcDriverListener::class,
                RegisterGrpcServiceListener::class,
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
                    'id' => 'overwrite-class-DescriptorPool',
                    'description' => 'overwrite file',
                    'source' => __DIR__ . '/../publish/overwrite/src/DescriptorPool.php',
                    'destination' => BASE_PATH . '/overwrite/src/DescriptorPool.php',
                ],
                [
                    'id' => 'overwrite-file',
                    'description' => 'overwrite file',
                    'source' => __DIR__ . '/../publish/overwrite/overwrite.php',
                    'destination' => BASE_PATH . '/overwrite/overwrite.php',
                ],
                [
                    'id' => 'protoc-auto-file',
                    'description' => 'protoc-auto-file',
                    'source' => __DIR__ . '/../publish/protoc.sh',
                    'destination' => BASE_PATH . '/protoc.sh',
                ],
            ]
        ];
    }
}
