<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class Http extends HttpException
{
    const CODE_100 = 100; // Continue
    const CODE_101 = 101; // Switching Protocol
    const CODE_103 = 103; // Early Hints
    const CODE_200 = 200; // OK
    const CODE_201 = 201; // Created
    const CODE_202 = 202; // Accepted
    const CODE_203 = 203; // Non-Authoritative Information
    const CODE_204 = 204; // No Content
    const CODE_205 = 205; // Reset Content
    const CODE_206 = 206; // Partial Content
    const CODE_300 = 300; // Multiple Choices
    const CODE_301 = 301; // Moved Permanently
    const CODE_302 = 302; // Found
    const CODE_303 = 303; // See Other
    const CODE_304 = 304; // Not Modified
    const CODE_307 = 307; // Temporary Redirect
    const CODE_308 = 308; // Permanent Redirect
    const CODE_400 = 400; // Bad Request
    const CODE_401 = 401; // Unauthorized
    const CODE_402 = 402; // Payment Required
    const CODE_403 = 403; // Forbidden
    const CODE_404 = 404; // Not Found
    const CODE_405 = 405; // Method Not Allowed
    const CODE_406 = 406; // Not Acceptable
    const CODE_407 = 407; // Proxy Authentication Required
    const CODE_408 = 408; // Request Timeout
    const CODE_409 = 409; // Conflict
    const CODE_410 = 410; // Gone
    const CODE_411 = 411; // Length Required
    const CODE_412 = 412; // Precondition Failed（先决条件失败）
    const CODE_413 = 413; // Payload Too Large
    const CODE_414 = 414; // URI Too Long
    const CODE_415 = 415; // Unsupported Media Type
    const CODE_416 = 416; // Range Not Satisfiable
    const CODE_417 = 417; // Expectation Failed
    const CODE_418 = 418; // I'm a teapot
    const CODE_422 = 422;  //Unprocessable Entity
    const CODE_425 = 425; // Too Early
    const CODE_426 = 426; // Upgrade Required
    const CODE_428 = 428; // Precondition Required
    const CODE_429 = 429; // Too Many Requests
    const CODE_431 = 431; // Request Header Fields Too Large
    const CODE_451 = 451; // Unavailable For Legal Reasons
    const CODE_500 = 500; // 内部服务器错误
    const CODE_501 = 501; // Not Implemented
    const CODE_502 = 502; // Bad Gateway
    const CODE_503 = 503; // Service Unavailable
    const CODE_504 = 504; // Gateway Timeout
    const CODE_505 = 505; // HTTP Version Not Supported
    const CODE_511 = 511; // Network Authentication Required

    public static $httpCodeMap = [
        self::CODE_100 => 'Continue',
        self::CODE_101 => 'Switching Protocol',
        self::CODE_103 => 'Early Hints',
        self::CODE_200 => 'OK',
        self::CODE_201 => 'Created',
        self::CODE_202 => 'Accepted',
        self::CODE_203 => 'Non-Authoritative Information',
        self::CODE_204 => 'No Content',
        self::CODE_205 => 'Reset Content',
        self::CODE_206 => 'Partial Content',
        self::CODE_300 => 'Multiple Choices',
        self::CODE_301 => 'Moved Permanently',
        self::CODE_302 => 'Found',
        self::CODE_303 => 'See Other',
        self::CODE_304 => 'Not Modified',
        self::CODE_307 => 'Temporary Redirect',
        self::CODE_308 => 'Permanent Redirect',
        self::CODE_400 => 'Bad Request',
        self::CODE_401 => 'Unauthorized',
        self::CODE_402 => 'Payment Required',
        self::CODE_403 => 'Forbidden',
        self::CODE_404 => 'Not Found',
        self::CODE_405 => 'Method Not Allowed',
        self::CODE_406 => 'Not Acceptable',
        self::CODE_407 => 'Proxy Authentication Required',
        self::CODE_408 => 'Request Timeout',
        self::CODE_409 => 'Conflict',
        self::CODE_410 => 'Gone',
        self::CODE_411 => 'Length Required',
        self::CODE_412 => 'Precondition Failed',
        self::CODE_413 => 'Payload Too Large',
        self::CODE_414 => 'URI Too Long',
        self::CODE_415 => 'Unsupported Media Type',
        self::CODE_416 => 'Range Not Satisfiable',
        self::CODE_417 => 'Expectation Failed',
        self::CODE_418 => 'I\'m a teapot',
        self::CODE_422 => 'Unprocessable Entity',
        self::CODE_425 => 'Too Early',
        self::CODE_426 => 'Upgrade Required',
        self::CODE_428 => 'Precondition Required',
        self::CODE_429 => 'Too Many Requests',
        self::CODE_431 => 'Request Header Fields Too Large',
        self::CODE_451 => 'Unavailable For Legal Reasons',
        self::CODE_500 => '内部服务器错误',
        self::CODE_501 => 'Not Implemented',
        self::CODE_502 => 'Bad Gateway',
        self::CODE_503 => 'Service Unavailable',
        self::CODE_504 => 'Gateway Timeout',
        self::CODE_505 => 'HTTP Version Not Supported',
        self::CODE_511 => 'Network Authentication Required',
    ];

}
