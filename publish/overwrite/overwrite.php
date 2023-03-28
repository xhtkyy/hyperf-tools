<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

spl_autoload_register(function ($class) {
    $overwrites = [
        "Google\Protobuf\Internal\DescriptorPool" => __DIR__ . "/src/DescriptorPool.php"
    ];

    if (isset($overwrites[$class])) include $overwrites[$class];
}, true, true);