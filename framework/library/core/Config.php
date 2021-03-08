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

    private static $_instance = null;

    private static $_config = [];

    private static $_dir = null;

    private static $module = [];

    private static $_data = null;

    private function __construct()
    {

    }

    static public function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
            self::init();
        }
        return self::$_instance;
    }

    public static function init()
    {
        self::$_dir = CONFIG_ROOT;
        if (!self::$_dir) {
            throw new ErrorException('config dir is not existed');
        }
        self::_parseConfig(self::$_dir);
    }

    private static function _parseConfig($dir)
    {
        $file = scandir(self::$_dir);
        foreach ($file as $item) {
            if ($item != '.' && $item != '..') {
                list($fileName, $val) = explode('.', $item);
                $fileName = trim($fileName);
                self::$_config[$fileName] = include self::$_dir . '/' . $item;
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
        self::$_data = self::$_config;
        foreach ($nameArr as $item) {
            self::$_data = self::$_data[$item];
        }
        self::$module[$name] = self::$_data;
    }
}