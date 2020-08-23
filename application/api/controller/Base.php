<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

/**
 * Class Base
 * @package app\api\controller
 * 控制器基类
 */

class Base extends Controller
{


    public function  __construct(Request $request = null)
    {
        parent::__construct($request);


    }
}