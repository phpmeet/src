<?php

namespace app\home;

use core\Model;

class ConfigMdl extends Model
{
    public static $table = 'basic_config';

    public static function getOne($where = [], $column = [])
    {
        return parent::getOne($where, $column); // TODO: Change the autogenerated stub
    }

    public function getCount($where = [], $column = []){
        return parent::getOne($where,$column);
    }

    public static function select($where = [], $column = [], $order = '', $start = 0, $length = 0)
    {
        return parent::select($where, $column, $order, $start, $length); // TODO: Change the autogenerated stub
    }
}