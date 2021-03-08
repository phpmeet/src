<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 18:49
 */

namespace core\exception;

use core\Config;

class Error
{

    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, "errorHandler"]);
        set_exception_handler([__CLASS__, "appException"]);
        register_shutdown_function([__CLASS__, "stop"]);
    }

    public static function appException($error)
    {
        if (!$error instanceof \Exception) {
            $error = new ThrowableError($error);
        }
        self::getHandler()->output($error);
        Solve::getInstance()->record($error);
    }

    public static function errorHandler($errno, $errstr, $errfile = '', $errline = 0)
    {
        $errorExp = new ErrorException($errno, $errstr, $errfile, $errline);
        if (error_reporting() & $errno) {//这个错误代码包含在 error_reporting中
            throw $errorExp;
        }
        Solve::getInstance()->record($errorExp);
    }

    public static function stop()
    {
        $error = error_get_last();
        if (!is_null($error) && self::isFatal($error['type'])) {
            $errorExp = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);
            self::getHandler()->output($errorExp);
            Solve::getInstance()->record($errorExp);
        }
    }

    public static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    public static function getHandler()
    {
        static $class;
        if (!$class) {
            $solve = Config::getInstance()->get('app.exception_solve');
            if ($solve && is_string($solve) && class_exists($solve) && is_subclass_of($solve, "\\core\\exception\\solve")) {
                $class = new $solve;
            } else {
                $class = Solve::getInstance();
            }
        }
        return $class;
    }
}