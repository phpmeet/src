<?php

namespace core;

class Redis
{
    private static $instance = null;

    private static $connect = null;

    private $config = [];

    const AVALANCHE = 1;
    const PENETRATE = 2;
    const BREAKDOWN = 3;

    private function __construct()
    {
        $this->config  = Config::getInstance()->get("database.redis");
        self::$connect = new \Redis();
        self::$connect->connect($this->config['host'], $this->config['port']);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $key
     * @param $val
     * @param int $expi
     * @param int $type 1 防止缓存雪崩   2 防止缓存穿透  3 防止缓存击穿
     * @param int $breakTime 防止上面 1 2崩溃而设置的时间
     */
    public function set($key, $val, $expi = 0, $type = 0, $breakTime = 0)
    {
        if (!$type) {
            return self::$connect->set($key, $val, $expi);
        }
        if ($type == self::AVALANCHE) {
            $breakTime = $breakTime > 0 ? $breakTime : $this->config['avalanche'];
            return self::$connect->set($key, $val, $expi + mt_rand(0, $breakTime));
        } elseif ($type == self::PENETRATE) {
            $breakTime = $breakTime > 0 ? $breakTime : $this->config['penetrate'];
            return self::$connect->set($key, null, $breakTime);
        } elseif ($type == self::BREAKDOWN) {
            return self::$connect->set($key, $val);
        }
    }

    public function get($key)
    {
        return self::$connect->get($key);
    }

    public function push($queue, $val)
    {
        return self::$connect->lPush($queue, $val);
    }

    public function pop($queue)
    {
        return self::$connect->rPop($queue);
    }

    public function lLength($queue)
    {
        return self::$connect->lLen($queue);
    }


}