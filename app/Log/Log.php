<?php

namespace App\Log;


use App\Helpers\Helper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{
    public static function debug($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->debug($message, $context);
    }

    public static function info($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->info($message, $context);
    }

    public static function notice($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->notice($message, $context);
    }

    public static function warning($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->warning($message, $context);
    }

    public static function error($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->error($message, $context);
    }

    public static function critical($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->critical($message, $context);
    }

    public static function alert($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->ALERT($message, $context);
    }

    public static function emergency($message, Array $context = [])
    {
        $day = Helper::currentTime('date');
        $log = new Logger('logger');
        $log->pushHandler(new StreamHandler(storage_path("logs/{$day}.log"), Logger::INFO));
        $log->emergency($message, $context);
    }
}
