<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;

use core\dao\Dao;
use core\dao\Table;

class Model
{
    public static $table = null;

    public static function getDao()
    {
        $link = Dao::getLink();
        return Table::getInstance(static::$table, $link);
    }

    public static function create($data = [])
    {
        return self::getDao()->create($data);
    }

    public static function delete($where = [])
    {
        return self::getDao()->delete($where);
    }

    public static function select($where = [], $column = [], $order = '', $start = 0, $length = 0)
    {
        return self::getDao()->select($where, $column, $order, $start, $length);
    }

    public static function getOne($where = [], $column = [])
    {
        return self::getDao()->getOne($where, $column);
    }

    public static function update($where = [], $data)
    {
        return self::getDao()->update($where, $data);
    }
}