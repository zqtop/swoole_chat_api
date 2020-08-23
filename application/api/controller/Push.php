<?php
/**
 * Created by PhpStorm.
 * User: php
 * Date: 2020/8/22
 * Time: 22:22
 */

namespace app\api\controller;


use app\common\controller\code\ErrCode;
use app\common\controller\redis\Predis;
use think\Controller;

class Push extends Controller
{


    /**
     * 推送内容给所有的客户端
     */
    public  function  push()
    {
         $text = $_POST['text']; //推送文本消息
         if (!$text) {
             return apiJson(ErrCode::ERROR,"请填写推送消息");
         }
         $ws = $_POST['ws'];// websocket 服务器对象
        //获取所有在线客户端
        $clients = Predis::getInstance()->sMembers(config("live.live_list_key"));
        if (count($clients)<=0) {
            return apiJson(ErrCode::ERROR,"暂无客户端在线");
        }
        foreach ($clients as $fd) {
            $ws->push($fd,$text);
        }
        return apiJson(ErrCode::SUCCESS,"ok");
    }
}