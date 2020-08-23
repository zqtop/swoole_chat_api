<?php

namespace app\common\controller\redis;

/**
 * t同步redis 连接测试库
 * Class Predis
 * @package app\common\redis
 */
class Predis
{

    //定义一个静态对象
    static  $instance;


    //定义一个redis对象

    protected $redis;
    /**
     * 单例模式获取redis对象
     */
    public static  function  getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Predis constructor.
     * 构造方法
     */
    public  function  __construct()
    {
        $this->redis = new \Redis();

        //连接Redis
        $host = config("redis.host");
        $timeout = intval(config("redis.timeOut"));
        $port = intval(config("redis.port"));
        $password = config("redis.password");
        $this->redis->connect( $host, $port , $timeout);
        $this->redis->select(0);

        if ($password) {
            $this->redis->auth($password);
        }
    }



    /**
     * get redis的数据
     * @param $key redis 存放的键值
     */
    public function  get($key)
    {
       $result = $this->redis->get($key);
       if (!is_array($result)) {
           $result = json_decode($result,true);
       }
        return $result;
    }

    /**
     * set 数据
     * @param $key 存放的键值
     * @param $value 存放的value
     * @param $expireTime 生存时间
     */
    public  function  set($key,$value,$expireTime = 0 )
    {
        if (!$value) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $this->redis->set($key,$value,$expireTime);
        return $this ;
    }


    /**
     * 添加有序集合
     * @param  有序元素的key
     * @param  有序元素
     */
    public function  sAdd($key,$member)
    {
        if (is_array($member)) {
            $member = json_encode($member,JSON_UNESCAPED_UNICODE);
        }
        $this->redis->sAdd($key,$member);

       return $this;
    }

    /**
     * 删除有序集合中的元素
     * @param  有序元素的key
     * @param  有序元素
     */
    public function  sRem($key,$member)
    {
        if (is_array($member)) {
            $member = json_encode($member,JSON_UNESCAPED_UNICODE);
        }
         $this->redis->SREM($key,$member);

        return $this;
    }


    /**
     * 删除redisKey
     */
    public  function  del($key)
    {
       return $this->redis->del($key);
    }

    /**
     * 获取集合中的所有成员
     */
    public function  sMembers($key)
    {
        return $this->redis->sMembers($key);
    }
}