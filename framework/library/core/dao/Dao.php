<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/25
 * Time: 16:20
 */

namespace core\dao;

use core\Config;

class Dao
{
    public static function getLink()
    {
        return Connect::getInstance(Config::getInstance()->get('database.mysql.db'))->link();
    }

    public static function index()
    {
        echo "dao";
    }
}