<?php

namespace app\common\model;

use think\Model;

/**
 * Class User
 * @package app\common\model
 * 用户表模型
 */
class User extends  Model
{

     protected  $table = "sw_user";

     protected  $pk = "id";


    protected $autoWriteTimestamp=false;
}