<?php
namespace Lib\Store;

use \Kafka as KFK;

class RdKFK
{
    protected static $instance;
    private static $partitionsCount = -1;

    private function __construct()
    {
    }

    protected static function _init()
    {
        if (self::$instance !== null) {
            return;
        }
        $config = require(__DIR__ . '/../../config/rdkafka.php');

        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set("auto.commit.interval.ms", 1e3);
        $topicConf->set("offset.store.sync.interval.ms", 60e3);

        $rk = new \RdKafka\Producer();
        $rk->setLogLevel(LOG_DEBUG);
        $rk->addBrokers($config);

        $topic = $rk->newTopic(REC_LOG_TOPIC, $topicConf);

        $metadata = $rk->getMetadata(false, $topic, 5000);
        $topics = $metadata->getTopics();

        foreach ($topics as $var1) {
            RdKFK::$partitionsCount = count($var1->getPartitions());
            break;
        }

        self::$instance = $topic;
    }

    public static function getPartitionsCount()
    {
        if (RdKFK::$partitionsCount !== -1) {
            return RdKFK::$partitionsCount;
        }

        self::_init();

        return RdKFK::$partitionsCount;
    }

    public static function __callStatic($name, array $args)
    {
        self::_init();
        return call_user_func_array([self::$instance, $name], $args);
    }

    public static function getInstance()
    {
        self::_init();
        return self::$instance;
    }
}