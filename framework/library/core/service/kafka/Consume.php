<?php
/**
 * 需安装 librdkafka库和php-rdkafka扩展
 */
namespace core\service\kafka;

use core\Config;

class Consume
{
    private static $obj = null;

    private $topic = null;

    private $produce = null;

    /**
     * @param  string $topicName topic名称
     * @param int $partition 第一个参数标识分区，生产者是往分区0发送的消息，这里也从分区0拉取消息
     * @param $type
     *     RD_KAFKA_OFFSET_BEGINNING  从开始拉取消息
     *     RD_KAFKA_OFFSET_END        从当前位置开始拉取消息
     *     RD_KAFKA_OFFSET_STORED     从最新的未被消息的位置开始拉取
     * @return $this
     *
     */
    private function __construct($topicName = '', $partition = 0, $type = RD_KAFKA_OFFSET_STORED)
    {
        $config = Config::getInstance()->get("database.kafka");
        $conf   = new \RdKafka\Conf();
        //当设置RD_KAFKA_OFFSET_STORED时，需要设置group id
        $conf->set('group.id', 'myConsumerGroup');
        $this->produce = new \RdKafka\Consumer($conf);
        $this->produce->setLogLevel(LOG_DEBUG);
        $this->produce->addBrokers($config['host'] . ":" . $config['port']);

        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set('auto.commit.interval.ms', 100);
        // 'smallest': start from the beginning
        $topicConf->set('auto.offset.reset', 'smallest');
        $this->topic = $this->produce->newTopic($topicName,$topicConf);
        $this->topic->consumeStart($partition, $type);
    }

    public static function create($topicName = '', $partition = 0, $type = RD_KAFKA_OFFSET_STORED)
    {
        if (!self::$obj) {
            self::$obj = new self($topicName, $partition, $type);
        }
        return self::$obj;
    }

    public function consume($partition = 0, $timeout = 1000)
    {
        $msg = $this->topic->consume($partition, $timeout);
        return $msg;
    }

}
