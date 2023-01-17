<?php

namespace Xhtkyy\HyperfTools\Grpc;

use Hyperf\HttpServer\Router\Router;
use PhpCsFixer\ConfigInterface;
use Xhtkyy\HyperfTools\Grpc\Health\HealthController;
use Xhtkyy\HyperfTools\GrpcReflection\GrpcReflection;

class GrpcHelper {
    //注册反射
    public static function RegisterReflection(): void {
        if (di(ConfigInterface::class)->get("kyy_tools.reflection.enable", true) !== true) {
            return;
        }
        Router::addGroup('/grpc.reflection.v1alpha.ServerReflection', function () {
            Router::post('/ServerReflectionInfo', [GrpcReflection::class, 'serverReflectionInfo']);
        }, [
            "register" => false
        ]);
    }

    //注册健康检查
    public static function RegisterHealthCheck(): void {
        Router::addGroup('/grpc.health.v1.Health', function () {
            Router::post('/Check', [HealthController::class, 'check']);
            Router::post('/Watch', [HealthController::class, 'watch']);
        }, [
            "register" => false
        ]);
    }
}