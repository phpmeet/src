<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;

use core\exception\ErrorException;

class Config
{

    private static $instance = null;

    private static $config = [];

    private static $dir = null;

    private static $module = [];

    private static $data = null;

    private function __construct()
    {

    }

    static public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::init();
        }
        return self::$instance;
    }

    public static function init()
    {
        self::$dir = CONFIG_ROOT;
        if (!self::$dir) {
            throw new ErrorException('config dir is not existed');
        }
        self::_parseConfig(self::$dir);
    }

    private static function _parseConfig($dir)
    {
        $file = scandir(self::$dir);
        foreach ($file as $item) {
            if ($item != '.' && $item != '..') {
                list($fileName, $val) = explode('.', $item);
                $fileName = trim($fileName);
                self::$config[$fileName] = include self::$dir . '/' . $item;
            }
        }
    }

    public function get($name = '')
    {
        if(!isset(self::$module[$name])){
            self::_loadData($name);
        }
        return self::$module[$name];
    }

    public function getModule(){
        return self::$module;
    }

    static private function _loadData($name = '')
    {
        $nameArr = explode('.', $name);
        self::$data = self::$config;
        foreach ($nameArr as $item) {
            self::$data = self::$data[$item];
        }
        self::$module[$name] = self::$data;
    }
}