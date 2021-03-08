<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 * 单例模式
 * 只能有一个实例，把实例赋值给一个变量，__construct（构造器设置成private私有，访问外部代码使用new进行实例）
 * 通过一个静态方法获取这个实例对象
 */

namespace core;

use core\exception\ExtendException;
use core\exception\NotFoundException;

class App
{

    private static $_instance = null;

    private function __construct()
    {

    }

    static public function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function run()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $routeArr = Route::getInstance()->driver()->route();
        $mca      = Route::getInstance()->mca();
        $isPreg   = Route::getInstance()->isPreg();
        if (is_array($routeArr) && $routeArr && $isPreg) {
            list($namespace, $controller) = explode('\\', $mca['controller']);
            $driverClass = Request::getInstance()->app() . '\\' . strtolower($namespace) . '\\Load';
        } else {
            $driverPath = Request::getInstance()->create()->driverPath();
            $module     = Request::getInstance()->module();
            if (!file_exists(Request::getInstance()->moduleSourcePath())) {
                throw new NotFoundException($module . ' module not found');
            }
            $driverClass = str_replace('/', '\\', $driverPath) . '\\Load';
        }
        if (!class_exists($driverClass)) {
            if (is_array($routeArr) && $routeArr) {
                if (!$isPreg) {
                    throw new NotFoundException('route not preg');
                }
            } else {
                throw new NotFoundException($driverClass. 'dirverclass not found');
            }
        }
        if (!is_subclass_of($driverClass, Driver::class)) {
            throw new ExtendException('your ' . $driverClass . 'is not extend Driver');
        }
        $driver = new $driverClass();
        $this->bootstrap($driver);
    }

    private function bootstrap($driver)
    {
        if (method_exists($driver, 'after')) {
            $driver->after();
        }
    }

}