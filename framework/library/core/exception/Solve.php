<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 19:07
 */

namespace core\exception;

use core\Config;
use core\Log;
use core\Response;

class Solve
{

    protected static $instance = NULL;

    static public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function record(\Exception $exception)
    {
        $data = [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine()
        ];
        $log  = "code:{$data['code']}----message:{$data['message']}----file:{$data['file']}----line:{$data['line']}";
        $log  .= $exception->getTraceAsString();
        if (Config::getInstance()->get('app.debug')) {
            Log::getInstance()->record($log);
        }
    }

    public function output(\Exception $exception)
    {
        $data = [
            'name'    => get_class($exception),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'message' => $exception->getMessage(),
            'trace'   => $exception->getTrace(),
            'code'    => $exception->getCode(),
        ];
        $debug = Config::getInstance()->get('app.debug');
        if (!$debug){
            echo 'system error';die;
        }
        while (ob_get_level() > 1) {
            ob_end_clean();
        }
        $data['echo'] = ob_get_clean();
        ob_start();
        extract($data);
        $fu = include Config::getInstance()->get('app.exception_tmp');
        $content = ob_get_clean();
        Response::create(200,$content)->send();
    }
}