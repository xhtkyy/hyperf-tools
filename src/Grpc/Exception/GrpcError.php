<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Grpc\Exception;

use Hyperf\Grpc\StatusCode;
use Hyperf\GrpcServer\Exception\GrpcException;

class GrpcError
{
    /**
     * 返回异常
     * @param string $message
     * @param int $code
     * @param int $statusCode
     * @return void
     */
    public static function throw(string $message, int $code = -1, int $statusCode = StatusCode::ABORTED): void
    {
        throw new GrpcException("$code#$message", $statusCode);
    }
}