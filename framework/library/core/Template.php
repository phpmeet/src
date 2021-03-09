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

    private $config = [];

    private $dir = '';

    private $cacheFileArr = [];

    private $var = [];

    private function __construct()
    {
        $this->config['constroller'] = Request::getInstance()->controller();
        $this->config['module']      = Request::getInstance()->module();
        $this->config['func']        = Request::getInstance()->func();
        $this->config['namespace']   = Config::getInstance()->get('app.namespace');
        $this->config['cache']       = CACHE_ROOT . '/template';
        $this->dir                   = APP_ROOT;
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
        $file        = $this->dir . '/' . $this->config['module'] . '/view/';
        $this->var   = $var;
        $templateExt = Config::getInstance()->get('app.template.ext');
        if (!$fileName) {
            $templateFileName = $file . $this->config['constroller'] . '/' . $this->config['func'];
        } else {
            $templateFileName = $file . $fileName;
        }
        $file = $templateFileName . '.' . $templateExt;
        if (!is_file($file)) {
            throw new ErrorException(1001, $file . ' is not exist', $file, 0);
        }
        $hash     = md5_file($file);
        $cacheDir = $this->config['cache'];
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $name                      = $this->config['cache'] . '/' . $hash;
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