<?php
/**
 * Created by PhpStorm.
 * User: php
 * Date: 2020/8/22
 * Time: 20:45
 */

namespace app\api\controller;


use app\common\controller\code\ErrCode;
use app\common\controller\jwt\JwtAuth;
use app\common\controller\tools\Tools;
use think\Db;

class User extends  Base
{




    public function  get_user_info()
    {
        $code = Tools::checkToken();
        if ($code == 40003) {
           return apiJson(ErrCode::TOKEN,"token失效");
        }
        $user_id = JwtAuth::getInstance()->getUid();

        $user_info = Db::name("user")->where("id",$user_id)->find();
        return apiJson(ErrCode::SUCCESS,"success",$user_info);
    }

    public function  get_friend_user_info()
    {
        $code = Tools::checkToken();
        if ($code == 40003) {
            return apiJson(ErrCode::TOKEN,"token失效");
        }
        $user_id = JwtAuth::getInstance()->getUid();
        $user_info = Db::name("user")->where("id",$user_id)->find();
        $data_id = $_POST['data_id'];
        $friend_user_info = Db::name("user")->where("id",$data_id)->find();
        if (!$friend_user_info) {
            return apiJson(ErrCode::ERROR,"您没有此好友");
        }
        $user_info['friend_avatar'] = $friend_user_info['avatar'];
        $user_info['friend_nickname'] = $friend_user_info['nickname'];
        $user_info['friend_user_id'] = $friend_user_info['id'];
        $user_info['user_id'] = $user_info['id'];
        return apiJson(ErrCode::SUCCESS,"success",$user_info);
    }
    public function  findAll()
    {
        $code = Tools::checkToken();
        if ($code == 40003) {
            return apiJson(ErrCode::TOKEN,"token失效");
        }
        $user_id = JwtAuth::getInstance()->getUid();
        $all = Db::name("user")->where("id","<>",$user_id)->select();
        return apiJson(ErrCode::SUCCESS,"success",$all);
    }
}