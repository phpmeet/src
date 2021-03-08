<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;

use core\exception\ErrorException;

class Template
{

    private static $stance = null;

    private $_config = [];

    private $_dir = '';

    private $cacheFileArr = [];

    private $var = [];

    private function __construct()
    {
        $this->_config['constroller'] = Request::getInstance()->controller();
        $this->_config['module']      = Request::getInstance()->module();
        $this->_config['func']        = Request::getInstance()->func();
        $this->_config['namespace']   = Config::getInstance()->get('app.namespace');
        $this->_config['cache']       = CACHE_ROOT . '/template';
        $this->_dir                   = APP_ROOT;
    }

    public static function getInstance()
    {
        if (!self::$stance) {
            self::$stance = new self();
        }
        return self::$stance;
    }

    public function load($var = [], $fileName = '')
    {
        $file        = $this->_dir . '/' . $this->_config['module'] . '/view/';
        $this->var   = $var;
        $templateExt = Config::getInstance()->get('app.template.ext');
        if (!$fileName) {
            $templateFileName = $file . $this->_config['constroller'] . '/' . $this->_config['func'];
        } else {
            $templateFileName = $file . $fileName;
        }
        $file = $templateFileName . '.' . $templateExt;
        if (!is_file($file)) {
            throw new ErrorException(1001, $file . ' is not exist', $file, 0);
        }
        $hash     = md5_file($file);
        $cacheDir = $this->_config['cache'];
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $name                      = $this->_config['cache'] . '/' . $hash;
        $cacheFile                 = $name . '.php';
        $this->cacheFileArr[$name] = $cacheFile;
        Compile::getInstance()->bootstrap($file, $cacheFile);
        return $this;
    }

    public function resourceModule($str)
    {
        $path = RESOURCE_ROOT . "/" . $str;
        return 'http://' . Request::getInstance()->servr('HTTP_HOST') . $path;
    }

    public static function loadTemplate($path)
    {
        $path = str_replace(".", "/", $path);
        self::getInstance()->load([], $path);
    }

    public function driver()
    {
        extract($this->var);
        foreach ($this->cacheFileArr as $item) {
            require $item;
        }
    }
}