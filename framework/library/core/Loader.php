<?php
/**
 * Date: 2019/11/22
 * Time: 13:38
 * 注册模式
 */

namespace core;

class Loader
{

    protected static $objects = [];

    public static function autoload($className)
    {
        $coreNamespace = 'core';
        if (strpos($className, $coreNamespace) !== false) {
            $coreName  = basename(CORE_ROOT);
            $className = str_replace("{$coreName}\\", "", $className);
            $fileName  = self::_getFile($className);
            $fileName  = self::_parseDir($fileName);
        } elseif (strpos($className, 'app') !== false) {
            $fileName = self::_getFile($className, 1);
        }
        if (is_file($fileName)) {
            require $fileName;
        } else {
            return false;
        }
    }

    public static function register()
    {
        spl_autoload_register("core\\Loader::autoload");
    }

    private static function _getFile($className, $type = 0)
    {
        if ($type == 0) {
            return CORE_ROOT . "/" . $className . EXT;
        }
        $classArr = explode('\\', $className);
        $classArr = array_map(array(__CLASS__, '_parseStr'), $classArr);
        array_shift($classArr);
        $module  = array_shift($classArr);
        $class   = lcfirst(array_shift($classArr));
        $constro = Config::getInstance()->get('module.controller');
        $mdl     = Config::getInstance()->get('module.mdl');
        $driver  = Config::getInstance()->get('module.driver');
        $trait   = Config::getInstance()->get('module.trait');
        $view    = Config::getInstance()->get('module.view');
        $arr[]   = APP_ROOT;
        $arr[]   = $module;
        $class   = ucfirst($class);
        if (strpos($className, ucfirst($constro)) !== false) {
            $arr[] = $constro;
            $arr[] = str_replace($constro, '', $class) . ucfirst($constro) . EXT;
        } elseif (strpos($className, ucfirst($mdl)) !== false) {
            $arr[] = $mdl;
            $arr[] = str_replace($mdl, '', $class) . ucfirst($mdl) . EXT;
        } elseif (strpos($className, ucfirst($trait)) !== false) {
            $arr[] = $trait;
            $arr[] = str_replace($trait, '', $class) . ucfirst($trait) . EXT;
        } elseif (strpos($className, ucfirst($view)) !== false) {
            $arr[] = $view;
            $arr[] = str_replace($view, '', $class) . ucfirst($view) . EXT;
        } else {
            $arr[] = $driver;
            $arr[] = $class . EXT;
        }
        $fileName = implode('/', $arr);
        return $fileName;
    }

    public static function set($alias, $object = [])
    {
        if (is_array($alias)) {
            self::$objects = array_merge($alias, self::$objects);
        } else {
            self::$objects[$alias] = $object;
        }
    }

    public static function get($alias)
    {
        return self::$objects[$alias];
    }

    public static function clear($alias)
    {
        unset(self::$objects[$alias]);
    }

    private static function _parseDir($name)
    {
        return str_replace("\\", "/", $name);
    }

    private static function _parseStr($str)
    {
        return strtolower(trim($str));
    }
}