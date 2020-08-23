<?php
/**
 * Created by PhpStorm.
 * User: php
 * Date: 2020/8/22
 * Time: 15:14
 */

namespace app\common\controller\redis;


/**
 * 管理Rediskey
 * Class ManageRedisKey
 * @package app\common\controller\redis
 */

class ManageRedisKey
{

    /**
     *存放在redis中验证码登陆的Key
     * @param  $email
     * @return 返回登陆的Key
     */
    public static  function  emailKey($email)
    {
        return "sw_login_".$email;
    }


    public static  function  friendKey($friend_user_id)
    {
        return "sw_chat_".$friend_user_id;
    }
}