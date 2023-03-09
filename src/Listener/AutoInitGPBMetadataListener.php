<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Listener;

use HaydenPierce\ClassFinder\ClassFinder;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\MainWorkerStart;

class AutoInitGPBMetadataListener implements ListenerInterface
{
    public function __construct(
        protected StdoutLoggerInterface $stdoutLogger
    )
    {
    }

    public function listen(): array
    {
        return [
            MainWorkerStart::class,
        ];
    }

    public function process(object $event): void
    {
        try {
            ClassFinder::disablePSR4Vendors();
            //todo 命名空间 需要在配置中获取
            $class = ClassFinder::getClassesInNamespace("App\\Grpc\\GPBMetadata", ClassFinder::RECURSIVE_MODE);
        } catch (\Exception $e) {
            return;
        }
        foreach ($class as $item) {
            call_user_func("{$item}::initOnce");
        }
        $this->stdoutLogger->info("GPBMetadata Load Finish");
    }
}