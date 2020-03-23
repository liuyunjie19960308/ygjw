<?php
namespace FrontC\Core\Redis;

/**
 * 
 */
class Redis
{
    /**
     * [$instance 本类实例]
     * @var [type]
     */
    protected static $instance;

    /**
     * [$redis redis链接实例]
     * @var [type]
     */
    protected $redis;

    /**
     * [__construct description]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-07T10:18:10+0800
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * [getInstance description]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-07T11:00:21+0800
     * @return   [type]                   [description]
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [connect 链接redis]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-07T10:16:38+0800
     * @return   [type]                   [description]
     */
    public function connect()
    {
        $redis   = new \Redis();
        $connect = $redis->connect('127.0.0.1', 6388);
        if (!$connect) {
            throw new \Exception('链接redis服务器失败！');
        }
        $this->redis = $redis;
    }

    /**
     * [close 释放redis]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-07T10:16:47+0800
     * @param    [type]                   $redis [description]
     * @return   [type]                          [description]
     */
    public function close($redis = null)
    {
        $this->redis->close();
    }

    /**
     * [redisInstance 外部获取redis实例]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-07T11:02:49+0800
     * @return   [type]                   [description]
     */
    public function redisInstance()
    {
        return $this->redis;
    }

    /**
     * [clearList 清除队列]
     * @Author   黑暗中的武者
     * @DateTime 2019-09-07T11:22:52+0800
     * @param    [type]                   $key [description]
     * @return   [type]                        [description]
     */
    public function clearList($key)
    {
        // 
        $this->redis->delete($key);
        // 释放redis
        $this->close();
    }

    /**
     * @param $key
     * @return int
     */
    public function getLen($key)
    {
        // 获取队列长度
        $len = $this->redis->lLen($key);
        // 释放redis
        $this->close();
        // 返回
        return $len;
    }
}
