<?php

namespace core;

use core\exception\Exception;

class Route
{

    private $route = [];

    private static $instance = null;

    private $paramValue = [];

    private $param = [];

    private $isPreg = false;

    /**
     * 获取匹配的控制器和方法
     * @var array
     */
    private $mca = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new  self();
        }
        return self::$instance;
    }

    public static function get($path, $controller, $action)
    {
        self::getInstance()->createRoute('get', $path, $controller, $action);
    }

    public static function post($path, $controller, $action)
    {
        self::getInstance()->createRoute('post', $path, $controller, $action);
    }

    public static function request($path, $controller, $action)
    {
        self::getInstance()->createRoute('request', $path, $controller, $action);
    }

    public function createRoute($method, $path, $controller, $action)
    {
        $path                        = $this->filter($path);
        $this->route[$method][$path] = ['controller' => $controller, 'action' => $action];
    }

    public function rewrite()
    {
        $route = $this->route();
        if (Request::getInstance()->isCli()) {
            $url = Cli::create()->uri();
        } else {
            $url = Request::getInstance()->create()->servr('REQUEST_URI');
        }
        $method = Request::getInstance()->method();
        if (strpos($url, "?") !== false) {
            list($uri, $param) = explode("?", $url);
        } else {
            $uri = $url;
        }
        $uriArr = explode("/", $uri);
        $mca    = $pregMca = [];
        if (is_array($route) && $route) {
            $route = $route[strtolower($method)];
            foreach ($route as $routeTag => $routeItem) {
                $routeArr = explode("/", $routeTag);
                if ($this->compareArr($uriArr, $routeArr)) {
                    $mca = $routeItem;
                }
                if ($this->comparePregArr($uriArr, $routeArr)) {
                    $pregMca = $routeItem;
                }
            }
            if ($mca) {
                $this->mca = $mca;
            } else {
                $this->mca = $pregMca;
            }
            if ($this->mca) {
                $this->isPreg = true;
            }
        }
        return $this;
    }

    public function mca()
    {
        return $this->mca;
    }

    public function clear()
    {
        $this->route = [];
    }

    public function route()
    {
        return $this->route;
    }

    public function param()
    {
        return $this->param;
    }

    public function isPreg()
    {
        return $this->isPreg;
    }

    public function paramValue($name = '')
    {
        if ($name) {
            return $this->paramValue[$name];
        }
        return $this->paramValue;
    }

    /**
     * 对比两数组是否完全相同
     * @param $arr1
     * @param $arr2
     * @return bool
     */
    public function compareArr($arr1, $arr2)
    {
        $data = self::decideArr($arr1, $arr2);
        foreach ($data as $key => $item) {
            if (isset($arr1[$key]) && isset($arr2[$key])) {
                if ($arr1[$key] !== $arr2[$key]) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function comparePregArr($arr1, $arr2)
    {
        $data = self::decideArr($arr1, $arr2);
        foreach ($data as $key => $item) {
            if (isset($arr2[$key]) && isset($arr1[$key])) {
                if (preg_match('/{[A-Za-z]+}/', $arr2[$key], $match)) {
                    $param                    = str_replace('{', '', $match[0]);
                    $param                    = str_replace('}', '', $param);
                    $this->param[]            = $param;
                    $this->paramValue[$param] = $arr1[$key];
                    continue;
                }
                if ($arr1[$key] !== $arr2[$key]) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function decideArr($arr1, $arr2)
    {
        if (count($arr1) > count($arr2)) {
            $data = $arr1;
        } else {
            $data = $arr2;
        }
        return $data;
    }

    public function filter($str)
    {
        $firstStr = substr($str, 0, 1);
        if ($firstStr != '/') {
            throw new Exception("route format first str must be /");
        }
        return $str;
    }

    public function driver()
    {
        $dir = ROUTE_ROOT;
        if (!is_dir($dir)) {
            throw new Exception("route dir is not exist");
        }
        $files = scandir($dir);
        foreach ($files as $key => $item) {
            if ($item != '.' && $item != '..') {
                $prefix = substr($item, strripos($item, ".") + 1);
                if ($prefix == substr(EXT, 1)) {
                    require ROUTE_ROOT . "/" . $item;
                }
            }
        }
        Route::getInstance()->rewrite();
        return $this;
    }
}