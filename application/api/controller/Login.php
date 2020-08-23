<?php
/**
 * Created by PhpStorm.
 * User: php
 * Date: 2020/8/22
 * Time: 14:50
 */

namespace app\api\controller;




use app\common\controller\code\ErrCode;
use app\common\controller\jwt\JwtAuth;
use app\common\controller\redis\ManageRedisKey;
use app\common\controller\redis\Predis;
use app\common\controller\tools\Tools;
use app\common\model\User;
use think\Controller;


class Login extends  Controller
{


    /**
     * 处理用户登陆
     */
    public  function  login()
    {


        if (!isset($_POST['email'])) {
            return apiJson(ErrCode::ERROR,"缺少email参数");
        }
        $mail = $_POST['email'];
        $yzm = $_POST['yzm'];


        if (empty($mail)) {
           return apiJson(ErrCode::ERROR,"请填写邮箱");
        }

        if (!isMail($mail)) {
            return apiJson(ErrCode::ERROR,"邮箱格式错误");
        }

        //判断邮箱是否存在
        $userInfo = User::where("email",$mail)->find();


        if (!$userInfo) {
            $nickname = Tools::getSysRandName();
            require_once VENDOR_PATH."/yzalis/identicon/src/Identicon/Identicon.php";
            require_once VENDOR_PATH."/yzalis/identicon/src/Identicon/Generator/SvgGenerator.php";
            $identicon = new \Identicon\Identicon(new \Identicon\Generator\SvgGenerator());
            $imageDataUri = $identicon->getImageDataUri($nickname);
            //将数据插入用户表中
            $userObj = new User();
            $userObj->create_time = date("Y-m-d H:i:s",time());
            $userObj->nickname = $nickname;
            $userObj->email = $mail;
            $userObj->avatar = $imageDataUri;
            $userObj->save();
            $tmp = $userObj->toArray();
            $user_id = $tmp['id'];
        } else {
            $user_id = $userInfo->id;
        }


        if (!$yzm) {
            return apiJson(ErrCode::ERROR,"请填写验证码");
        }



        //判断邮箱是否存在
        $key = ManageRedisKey::emailKey($mail);

        $result = Predis::getInstance()->get($key);
        if (!$result) {
            return apiJson(ErrCode::ERROR,"验证码已经过期");
        }

        if ($result['code'] != $yzm) {
            return apiJson(ErrCode::ERROR,"验证码错误");
        }

        //更新验证码使用状态
        Predis::getInstance()->del($key);


        //生成用户验证token
        $token = JwtAuth::getInstance()->setUid($user_id)->encode()->getToken();

        return apiJson(ErrCode::SUCCESS,"ok",[
            "token" => $token
        ]);


    }



    /**
     * 发送验证码
     */
    public function  send_yzm()
    {

        if (!isset($_POST['email'])) {
            return apiJson(ErrCode::ERROR,"缺少email参数");
        }
        $eamil = $_POST['email'];



        if (empty($eamil)) {
            return apiJson(ErrCode::ERROR,"请填写邮箱");
        }

        if (!isMail($eamil)) {
            return apiJson(ErrCode::ERROR,"邮箱格式错误");
        }
        if ($eamil == "447382562@qq.com") {
            return apiJson(ErrCode::ERROR,"接收邮箱不能为发送邮箱");
        }

        //利用swoole的task进程给用户发送邮件消息
        $data = [
            "method" => "sendMail",
            "email" => $eamil,
            "timeOut" => config("mail.timeOut")
        ];
        $ws = $_POST['ws'];//获取websocket 超全局对象
        $ws->task($data);
        return apiJson(ErrCode::SUCCESS,"ok");
    }

}