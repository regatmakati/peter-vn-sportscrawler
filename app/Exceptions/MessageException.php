<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MessageException extends HttpException
{
    const CODE_SUCCESS = 200;                       //请求成功
    const CODE_FAIL = 400;                          //请求失败
    const CODE_SIGNATURE_FAIL = 401;                //签名校验失败
    const CODE_MISS_PARAMS = 402;                   //参数不完整

    public static $MessageMap = [
        self::CODE_SUCCESS => '请求成功',
        self::CODE_FAIL => '请求失败',
        self::CODE_SIGNATURE_FAIL => '签名校验失败',
        self::CODE_MISS_PARAMS => '参数不完整',
];

}
