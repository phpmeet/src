<?php
/**
 * 服务治理
 * 令牌桶处理
 */

namespace core\service;

use core\Redis;

class Bucket
{
    private static $instance;

    private $redis;

    private $queue;

    private $max;

    public function __construct($queue, $max = 0)
    {
        $this->queue = $queue;
        $this->max   = $max;
        $this->redis = Redis::getInstance();
    }

    public static function getInstance($queue, $max = 0)
    {
        if (!self::$instance) {
            self::$instance = new self($queue, $max);
        }
        return self::$instance;
    }

    /**
     * 加入令牌
     * @param int $num
     */
    public function add($num = 0)
    {
        $currentNum = $this->redis->lLength($this->queue);
        $maxNum     = intval($this->max);
        $num        = $maxNum >= ($currentNum + $num) ? $num : ($maxNum - $currentNum);
        if ($num > 0) {
            $token = 1;
            for ($index = 0; $index < $num; $index++) {
                $this->redis->push($this->queue, $token);
            }
            return $num;
        }
        return 0;
    }

    public function get()
    {
        return $this->redis->pop($this->queue);
    }

    public function reset()
    {
        $this->redis->delete($this->queue);
        $this->add($this->max);
    }
}

