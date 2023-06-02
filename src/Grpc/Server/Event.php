<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Grpc\Server;

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ContainerInterface;
use Swoole\Http\Server;

class Event
{
    public function onStart(Server $server): void
    {
        /**
         * @var ContainerInterface $container
         */
        $container = ApplicationContext::getContainer();
        $container->set(Server::class, $server);
    }
}