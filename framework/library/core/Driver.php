<?php
/**
 * Date: 2019/11/22
 * Time: 13:38
 */

namespace core;

use core\exception\ExtendException;
use core\exception\NotFoundException;

class Driver implements Init
{

    protected $__request = null;

    protected $mca = [];

    public function __construct()
    {
        $this->before();
        $this->init();
        $this->__request = Request::getInstance();
        $this->boostrap();
    }

    private function boostrap()
    {
        $mca = Route::getInstance()->rewrite()->mca();
        if ($mca) {
            $controller = $mca['controller'];
            $app        = $this->__request->app();
            $class      = $app . "\\" . $mca['controller'] . "Controller";
            $func       = $mca['action'];
            list($module, $controllerName) = explode("\\", $controller);
            Request::getInstance()->setModule(strtolower($module));
            Request::getInstance()->setController(strtolower($controllerName));
        } else {
            $controller = $this->__request->controller();
            $modulePath = $this->__request->modulePath();
            list($namespace, $module) = explode("/", $modulePath);
            $func  = $this->__request->func();
            $class = $modulePath . '/' . ucfirst($controller) . 'Controller';
        }
        $this->loadCommon($module);
        $class = str_replace('/', '\\', $class);
        if (!class_exists($class)) {
            throw new NotFoundException('controller:' . $controller . ' is not existed');
        }
        if (!is_subclass_of($class, Controller::class)) {
            throw new ExtendException('your ' . $class . ' is not extend Controller');
        }
        $controllerClass = new $class();
        $controllerClass->run();
        $this->adapter($controllerClass, $func);
    }

    public function loadCommon($module)
    {
        $fileName   = Config::getInstance()->get('app.common_name');
        $commonFile = APP_ROOT . "/" . $fileName . EXT;
        if (file_exists($commonFile)) {
            require_once $commonFile;
        }
        $file = APP_ROOT . "/" . $module . "/" . $fileName . EXT;
        if (file_exists($file)) {
            require_once $file;
        }
    }

    /**
     * 控制器依赖注入
     */
    public function adapter($instance, $method)
    {
        if (!method_exists($instance, $method)) {
            throw new NotFoundException('controller:' . $instance . ',method:' . $method . ' is not existed');
        }
        $reflector  = new \ReflectionMethod($instance, $method);
        $parameters = [];
        foreach ($reflector->getParameters() as $key => $parameter) {
            $class = $parameter->getClass();
            if ($class) {
                $param = new $class->name();
            } else {
                $param = Loader::get('route')->rewrite()->paramValue($parameter->getName());
            }
            array_splice($parameters, $key, 0, [$param]);
        }
        call_user_func_array([
            $instance,
            $method
        ], $parameters);
    }

    public function init()
    {
    }

    public function before()
    {
    }

    public function after()
    {
    }

}