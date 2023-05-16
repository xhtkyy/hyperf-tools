<?php

namespace Xhtkyy\HyperfTools\Grpc;

use Hyperf\HttpServer\Router\Router;
use Xhtkyy\HyperfTools\Grpc\Health\HealthController;
use Xhtkyy\HyperfTools\Grpc\Middleware\GrpcTraceMiddleware;
use Xhtkyy\HyperfTools\GrpcReflection\GrpcReflection;

class GrpcHelper
{
    //注册反射
    public static function RegisterRoutes(callable $callback, string $serverName = "grpc"): void
    {
        Router::addServer($serverName, function () use ($callback) {
            //注册反射
            if (env("REFLECTION_ENABLE", true)) {
                Router::addGroup('/grpc.reflection.v1alpha.ServerReflection', function () {
                    Router::post('/ServerReflectionInfo', [GrpcReflection::class, 'serverReflectionInfo']);
                }, [
                    "register" => false
                ]);
            }
            //注册健康检查
            Router::addGroup('/grpc.health.v1.Health', function () {
                Router::post('/Check', [HealthController::class, 'check']);
                Router::post('/Watch', [HealthController::class, 'watch']);
            }, [
                "register" => false
            ]);
            //注册其他
            Router::addGroup("", $callback, ["middleware" => [GrpcTraceMiddleware::class]]);
        });
    }
}