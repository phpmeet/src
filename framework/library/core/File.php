<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;

class File
{

    private static $stance = null;

    public static function getInstance()
    {
        if (!self::$stance) {
            self::$stance = new self();
        }
        return self::$stance;
    }

    public function read($dir)
    {
        $fp = fopen($dir, 'r');
        $data = "";
        while (!feof($fp)) {
            $data .= fread($fp, 1024);
        }
        fclose($fp);
        return $data;
    }

    public function write($dir, $data)
    {
        $fp = fopen($dir,"w");
        fwrite($fp,$data);
        fclose($fp);
    }

}