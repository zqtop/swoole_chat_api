<?php


namespace app\common\controller\mail;
use PHPMailer\PHPMailer\PHPMailer;


/**
 * 发送邮件类库
 * Class SendMail
 * @package app\common\controller\mail
 */
class SendMail
{

  protected  $host;

  protected  $smtpSecure;

  protected   $smtpAuth;


  protected  $username;

   protected  $password;

   protected  $port;


   public  function  __construct()
   {
       $this->host = config("mail.host");

       $this->smtpSecure = config("mail.smtpSecure");

       $this->smtpAuth = config("mail.smtpAuth");

       $this->username = config("mail.username");

       $this->password = config("mail.password");

       $this->port = config("mail.port");
   }

    /**
     * 发送邮件登陆提醒服务
     * @param $address 接受邮件用户的邮箱
     * @param $code 4位验证码数字
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function  send($address,$code)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPSecure = $this->smtpSecure;
        $mail->SMTPAuth = $this->smtpAuth;
        $mail->Username = $this->username;
        $mail->Password = $this->password;
        $mail->Port = $this->port;
       $mail->CharSet = "utf-8";                     //utf-8;
//        $mail->Encoding = "base64";
        $mail->setFrom($this->username,"=?utf-8?B?" . base64_encode(config("mail.remark")) . "?=");
        $mail->addAddress($address);
        $mail->Subject = "=?utf-8?B?" . base64_encode( "验证码" ) . "?=";//=?utf-8?B?" . base64_encode( "我是标题" ) . "?=
        $mail->Body = "尊贵的用户，您本次登录的验证码为".$code;
        $result = $mail->send();
        return $result;

    }

}