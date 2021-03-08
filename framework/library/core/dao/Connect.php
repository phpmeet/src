<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/25
 * Time: 16:20
 */

namespace core\dao;

use core\Config;

class Connect
{

    private $_db = null;

    private $_host = null;

    private $_user = null;

    private $_pwd = null;

    private $_config = null;

    private static $_instance;

    private $_link = null;

    private $_options = [];

    private function __construct()
    {
        $this->_config = Config::getInstance();
        $this->_init();
        $this->_link();
    }

    private function _init()
    {
        $this->_db = $this->_config->get('database.mysql.db');
        $this->_host = $this->_config->get('database.mysql.host');
        $this->_user = $this->_config->get('database.mysql.user');
        $this->_pwd = $this->_config->get('database.mysql.pwd');
        $this->_options = [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];
    }

    private function _link()
    {
        $dsn = 'mysql:dbname=' . $this->_db . ';host=' . $this->_host;
        $this->_link = new \PDO($dsn, $this->_user, $this->_pwd, $this->_options);
    }

    public static function getInstance($name, $bool = false)
    {
        $encryptName = $name;
        if ($bool) {
            $encryptName = md5(serialize($name));
        }
        if (!isset(self::$_instance[$encryptName])) {
            self::$_instance[$encryptName] = new self($name);
        }
        return self::$_instance[$encryptName];
    }

    public function link()
    {
        return $this->_link;
    }
}