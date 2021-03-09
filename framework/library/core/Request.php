<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;

class Request
{
    private static $instance = null;

    private $controllerPath = null;

    private $controller = null;

    private $modulePath = null;

    private $module = null;

    private $app = null;

    private $func = null;

    private $uri = null;

    private $cliParam = null;

    public function __construct()
    {
        $this->app = Config::getInstance()->get('app.namespace');
    }

    static public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function isGet()
    {
        return self::method() === 'GET';
    }

    public function isPost()
    {
        return self::method() === 'POST';
    }

    public function isOptions()
    {
        return self::method() === 'OPTIONS';
    }

    public function isHead()
    {
        return self::method() === 'HEAD';
    }

    public function isDelete()
    {
        return self::method() === 'DELETE';
    }

    public function isPut()
    {
        return self::method() === 'PUT';
    }

    public function isPatch()
    {
        return self::method() === 'PATCH';
    }

    public function isTrace()
    {
        return self::method() === 'TRACE';
    }

    public function isAjax()
    {
        $value = $_SERVER['HTTP_X_REQUESTED_WITH'];
        $bool  = $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        if ($value && $bool) {
            return true;
        }
        return false;
    }

    public function isPjax()
    {
        return self::isAjax() && $_SERVER['HTTP_X_PJAX'];
    }

    public function isCli()
    {
        return PHP_SAPI === 'cli';
    }

    public function serverIp()
    {
        $ip = $_SERVER['SERVER_ADDR'] ?: $_SERVER['LOCAL_ADDR'];
        if (!$ip) {
            $ip = getenv('SERVER_ADDR');
        }
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
    }

    public function post($name = null)
    {
        if (isset($name)) {
            if (isset($_POST[$name])) {
                return $_POST[$name];
            }
            return null;
        }
        return $_POST;
    }

    public function setUri($uri = null)
    {
        if (isset($uri)) {
            $this->uri = $uri;
        }
    }

    public function setCliParam($param = null)
    {
        if (isset($param)) {
            unset($param[0]);
            unset($param[1]);
            $this->cliParam = $param;
        }
    }

    public function cliParam()
    {
        return $this->cliParam;
    }

    public function servr($name = null)
    {
        if (isset($name)) {
            if (isset($_SERVER[$name])) {
                return $_SERVER[$name];
            }
            return null;
        }
        return $_SERVER;
    }

    public function get($name = null)
    {
        if (isset($name)) {
            if (isset($_GET[$name])) {
                return $_GET[$name];
            }
            return null;
        }
        return $_GET;
    }

    public function clientIp()
    {
        if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip  = current($arr);
        } elseif ($_SERVER['HTTP_CLIENT_IP']) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ($_SERVER['REMOTE_ADDR']) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '';
        }
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
    }

    public function uri()
    {
        if ($this->uri && $this->isCli()) {
            return $this->uri;
        } else {
            $uri   = $_SERVER['REQUEST_URI'];
            $index = strpos($uri, '?');
            if ($index) {
                $this->uri = substr($uri, 0, $index);
            } else {
                $this->uri = $uri;
            }
            return $this->uri;
        }
    }

    public static function method()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        } elseif (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }
        return 'GET';
    }

    public function modulePath()
    {
        return $this->modulePath;
    }

    public function moduleSourcePath()
    {
        return APP_ROOT . "/" . $this->module();
    }

    public function controllerPath()
    {
        return $this->controllerPath;
    }

    public function module()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function controller()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function driver()
    {
        return $this->driver;
    }

    public function driverPath()
    {
        return $this->driverPath;
    }


    public function func()
    {
        return $this->func;
    }

    public function setFunc($func)
    {
        $this->func = $func;
    }

    public function app()
    {
        return $this->app;
    }

    public function create()
    {
        $uri                  = ltrim($this->uri(), '/');
        $uriArr               = explode('/', $uri);
        $this->module         = array_shift($uriArr) ?: Config::getInstance()->get('module.default_module');
        $this->modulePath     = $this->app . '/' . $this->module;
        $this->controller     = array_shift($uriArr) ?: Config::getInstance()->get('module.default_controller');
        $this->controllerPath = $this->modulePath . '/' . $this->controller;
        $this->func           = array_shift($uriArr) ?: Config::getInstance()->get('module.default_func');
        $this->driver         = array_shift($uriArr) ?: Config::getInstance()->get('module.driver');
        $this->driverPath     = $this->modulePath;
        return $this;
    }


}