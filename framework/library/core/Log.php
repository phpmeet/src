<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;

class Log
{

    private static $stance = null;

    static public function getInstance()
    {
        if (!self::$stance) {
            self::$stance = new self();
        }
        return self::$stance;
    }

    public function record($log)
    {
        $name = $this->getParseFile();
        file_put_contents($name, $log.PHP_EOL, FILE_APPEND);
    }

    public function getParseFile()
    {
        $dir = CACHE_ROOT . '/log/'.date('Ymd');
        if (!file_exists($dir)) {
            mkdir(iconv("UTF-8", "GBK", $dir), 0777, true);
        }
        $name = $dir . "/" . date('H') . '.log';
        return $name;
    }
}