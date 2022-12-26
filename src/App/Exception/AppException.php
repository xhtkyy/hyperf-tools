<?php

namespace Xhtkyy\HyperfTools\App\Exception;

class AppException extends \Exception {
    protected $code = 1;
    protected $message = "操作异常，稍后重试";
}