<?php
/**
 * Created by PhpStorm.
 * User: php
 * Date: 2020/8/22
 * Time: 15:29
 */

namespace app\common\controller\task;
use app\common\controller\redis\ManageRedisKey;
use app\common\controller\redis\Predis;


/**
 * Class Task
 * @package app\common\controller\task
 * 处理task任务
 */
class Task
{



    /***
     * 异步处理task任务
     */
    public function  sendMail($data)
    {
        echo "send Mail working  start \n";
        $email = $data['email'];
        if ($email == "447382562@qq.com") {
            echo "邮箱不能是发送放自己哦\n";
        }
        if (!$email || !isMail($email)) {
            echo "邮箱为空或者邮箱格式错误\n";
            return ;
        }
        try{
            $code = mt_rand(1000,9999);
            //将产生的验证码放到redis中
            $key = ManageRedisKey::emailKey($email);
            $timeOut = $data['timeOut'];
            Predis::getInstance()->set($key,["code"=>$code,"use" =>"N","time"=>time()],$timeOut);
            $mail = new \app\common\controller\mail\SendMail();
            $result = $mail->send($email,$code);
           var_dump($result);
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
    }
}