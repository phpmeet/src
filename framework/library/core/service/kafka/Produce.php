<?php

namespace core\service\kafka;

class Produce
{

    private static $obj = null;

    private $topic = null;

    private $produce = null;

    private function __construct()
    {
        $config = Config::getInstance()->get("database.kafka");
        $this->produce = new \RdKafka\Producer();
        $this->produce->setLogLevel(LOG_DEBUG);
        $this->produce->addBrokers($config['host'] . ":" . $config['port']);
    }

    public static function create()
    {
        if (!self::$obj) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    public function setTopic($name)
    {
        $this->topic = $this->produce->newTopic($name);
        return $this;
    }

    public function topic()
    {
        return $this->topic;
    }

    public function send($msg = '')
    {
        if ($msg) {
            $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $msg);
        }
        $this->produce->flush(1000);
        return $this;
    }
}