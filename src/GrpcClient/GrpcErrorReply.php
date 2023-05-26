<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\GrpcClient;

class GrpcErrorReply
{
    protected int $errCode = -1; //业务码
    protected string $errMsg = "fail";  //错误信息

    public function __construct(?string $reply)
    {
        if ($reply) {
            [$this->errCode, $this->errMsg] = str_contains($reply, "#") ? explode("#", $reply) : [-1, $reply];
        }
    }

    public function getCode(): int
    {
        return $this->errCode;
    }

    public function getMessage(): string
    {
        return $this->errMsg;
    }


    public function __toString(): string
    {
        return "{$this->errMsg}";
    }
}