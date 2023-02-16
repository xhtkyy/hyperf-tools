<?php
declare(strict_types=1);
/**
 * @author   crayoon<https://github.com/crayoon>
 * @contact  so.wo@foxmail.com
 */

namespace Xhtkyy\HyperfTools\Tracer;

use Exception;
use Hyperf\Tracer\Contract\NamedFactoryInterface;
use Hyperf\Utils\Str;

class TracerFactory implements NamedFactoryInterface
{

    /**
     * @throws Exception
     */
    public function make(string $name): \OpenTracing\Tracer
    {
        $class = sprintf("OpenTracing\\%sTracer", Str::studly($name));
        if (!class_exists($class)) {
            throw new Exception("$class Tracer no found");
        }
        return new $class;
    }
}