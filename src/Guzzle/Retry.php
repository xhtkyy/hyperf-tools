<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Guzzle;

class Retry
{
    public static function handler($maxRetry = 3): \GuzzleHttp\HandlerStack
    {
        $handler = \GuzzleHttp\HandlerStack::create(new \GuzzleHttp\Handler\CurlHandler());
        $handler->push(\GuzzleHttp\Middleware::retry(
            function (
                $retries,
                \GuzzleHttp\Psr7\Request $request,
                \GuzzleHttp\Psr7\Response $response = null,
                \Throwable $exception = null
            ) use ($maxRetry) {
                if ($retries >= $maxRetry) {
                    return false;
                }

                // 请求失败，继续重试
                if ($exception instanceof \GuzzleHttp\Exception\ConnectException) {
                    return true;
                }

                if ($response && $response->getStatusCode() == 500) {
                    return true;
                }

                return false;
            },
            // next try
            function ($num) {
                return 1000 * $num;
            }
        ));
        return $handler;
    }
}