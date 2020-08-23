<?php
namespace app\index\controller;



use app\common\controller\jwt\JwtAuth;
use app\common\controller\redis\Predis;

class Index
{
    public function index()
    {
        return;
    }


    /**
     * redis测试
     */
   public function  test_redis()
   {

       $result = Predis::getInstance()->get("zq");
       echo $result;

   }


   /**
    * 测试发送邮件
    */
   public function  test_email()
   {
      $mail = new \app\common\controller\mail\SendMail();
      $result = $mail->send("820780348@qq.com",7845);
      var_dump($result);
   }


   /**
    * 测试
    */
   public function  test_obj()
   {
      require_once VENDOR_PATH."/yzalis/identicon/src/Identicon/Identicon.php";
       require_once VENDOR_PATH."/yzalis/identicon/src/Identicon/Generator/SvgGenerator.php";
     //  $identicon = new \Identicon(new \SvgGenerator());
      $identicon = new \Identicon\Identicon(new \Identicon\Generator\SvgGenerator());
      $nickname = "werwrewr";
       $imageDataUri = $identicon->getImageDataUri($nickname);
       var_dump($imageDataUri);
//       $token = JwtAuth::getInstance()->setUid(1)->encode()->getToken();
//       var_dump($token);
   }
}
