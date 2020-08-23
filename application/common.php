<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function apiJson($code=200,$msg="操作成功",$data=null)
{

    $json = array(
        "code" => $code,
        "msg" => $msg,
        "data" => $data
    );

    return json($json,200);

}


//判断是否满足邮箱格式
function isMail($email)
{
    $regex="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
    $result = preg_match($regex,$email);
    return $result;
}