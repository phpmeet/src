<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/25
 * Time: 16:20
 */

namespace core\dao;

use core\Config;
use core\exception\ErrorException;
use core\exception\Exception;

class Table
{
    private static $_instance = [];

    private $_link;

    private $_table;

    private $_prefix;

    private function __construct($link, $table)
    {
        $this->_prefix = Config::getInstance()->get('database.mysql.prefix');
        $this->_table  = $this->_prefix . $table;
        $this->_link   = $link;
    }

    public static function getInstance($name, $link, $bool = false)
    {
        if ($bool) {
            $name = md5(serialize($name));
        }
        if (!isset(self::$_instance[$name])) {
            self::$_instance[$name] = new self($link, $name);
        }
        return self::$_instance[$name];
    }

    public function create($data)
    {
        $sql = $this->_parseInsert($data);
        $this->_link->exec($sql);
        return $this->_link->lastInsertId();
    }

    public function select($where = [], $field = [], $order = '', $start = '', $length = '')
    {
        $res = $this->_select($where, $field, $order, $start, $length);
        return $res ?: [];
    }

    public function delete($where)
    {
        $res = $this->_delete($where);
        return $res;
    }

    public function getOne($where = [], $field = [])
    {
        $res = $this->_select($where, $field);
        return $res[0] ?: [];
    }

    public function update($where = [], $data)
    {
        $res = $this->_update($where, $data);
        return $res;
    }

    private function _delete($where = [])
    {
        $sql = "DELETE FROM " . $this->_table . " WHERE " . current($where);
        $smt = $this->_execute($sql, next($where));
        return $smt->rowCount();
    }

    private function _update($where = [], $data = [])
    {
        $sql    = "UPDATE " . $this->_table . " SET";
        $keyArr = array_keys($data);
        foreach ($keyArr as $item) {
            $sql .= " {$item}=?,";
        }
        $sql  = trim($sql, ",");
        $sql  .= " WHERE " . current($where);
        $data = array_merge($data, next($where));;
        $smt = $this->_execute($sql, $data);
        return $smt->rowCount();
    }

    private function _execute($sql, $data)
    {
        if ($data && is_array($data)) {
            $smt   = $this->_link->prepare($sql);
            $index = 0;
            foreach ($data as $key => $item) {
                $smt->bindValue(++$index, $item);
            }
            $res = $smt->execute();
        } else {
            $smt = $this->_link->exec($sql);
        }
        return $smt;
    }

    private function _select($where = [], $field = [], $order = '', $start = 0, $length = 0)
    {
        $field = implode(',', $field);
        if (!$field) {
            $field = '*';
        }
        $sql = "SELECT " . $field . " FROM " . $this->_table . " WHERE ";
        $where = $this->_parseWhere($where);
        $sql   .= $where['col'];
        if ($order) {
            $sql .= " " . $order;
        }
        if ($start) {
            $sql .= " " . $start;
        }
        if ($length) {
            $sql .= "," . $length;
        }
        $smt = $this->_query($sql, $where['val']);
        $res = $smt->fetchAll();
        return $res;
    }

    private function _query($sql, $param)
    {
        if ($param) {
            $smt = $this->_link->prepare($sql);
            $smt->execute($param);
        } else {
            $smt = $this->_link->query($sql);;
        }
        return $smt;
    }

    private function _parseWhere($where = [])
    {
        if (!$where) {
            return ['col' => '', 'val' => []];
        } else {
            if (is_array($where)) {
                $col = current($where);
                $val = next($where);
                return ['col' => $col, 'val' => $val];
            } else {
                throw new ErrorException('where is not valid');
            }
        }
    }

    private function _parseInsert($data)
    {
        $columnArr = array_keys($data);
        $valArr    = array_values($data);
        $sql       = "INSERT INTO " . $this->_table . " (";
        $columnSql = "";
        foreach ($columnArr as $item) {
            $columnSql .= "`{$item}`,";
        }
        $columnSql = rtrim($columnSql, ',');
        $valSql    = "";
        foreach ($valArr as $item) {
            if ($item === '' || $item === null) {
                $valSql .= "'',";
            } else {
                $valSql .= "{$item},";
            }
        }
        $valSql = trim($valSql, ',');
        $sql    = $sql . $columnSql . ') values(' . $valSql . ')';
        return $sql;
    }
}