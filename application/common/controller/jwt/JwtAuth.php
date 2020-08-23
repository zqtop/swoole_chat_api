<?php
namespace app\common\controller\jwt;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\ValidationData;

/**
 * json web token 的提供方法
 * Class JwtAuthController
 * @package App\Http\Controllers\Common
 * @author  zq
 * @date 2020.3.26
 */


class JwtAuth
{
    private static $instance;

    private $issue = "http://jwt.io/";


    private $aud = "http://www.baidu.com/";


    private $jti = "4f1g23a15fa44";//jwt id


    private $key = "python";

    private $expireTime = 3600;

    //设置jwt_token
    private $token;


    /**
     * @var 解析的token
     */
    private $decodeToken;

    private $uid;


    /**
     * 设置uid
     * @param $uid 用户id
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * 获取jwt对象对象
     */
    public static function getInstance()
    {

        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 私有克隆方法
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }


    /**
     * 获取jwtToken
     * @return $token 返回生成的jwt token
     */
    public function getToken()
    {
        return (string)$this->token;
    }


    /**
     * 设置token
     * @param 前段传入的token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * 生成jwt token
     */
    public function encode()
    {
        $time = time();
        $signer = new Sha256();
        $this->token = (new Builder())->issuedBy($this->issue)// Configures the issuer (iss claim)
        ->permittedFor($this->aud)// Configures the audience (aud claim)
        ->identifiedBy($this->jti, true)// Configures the id (jti claim), replicating as a header item
        ->issuedAt($time)// Configures the time that the token was issue (iat claim)
        ->canOnlyBeUsedAfter($time)// Configures the time that the token can be used (nbf claim)
        ->expiresAt($time + $this->expireTime)// Configures the expiration time of the token (exp claim)
        ->withClaim('uid', $this->uid)// Configures a new claim, called "uid"
        ->getToken($signer, new Key($this->key)); // Retrieves the generated token

        return $this;
    }


    /**
     * 设置token的过期有效时间
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
        return $this;
    }


    /**
     * 校验jwt_token
     */
    public function decode()
    {
//        if(!$this->decodeToken){
//            $this->decodeToken = (new Parser())->parse((string)$this->token);
//            $this->uid = $this->decodeToken->getClaim("uid");//解析出来用户id
//        }
        $this->decodeToken = (new Parser())->parse((string)$this->token);
        $this->uid = $this->decodeToken->getClaim("uid");//解析出来用户id
        return $this->decodeToken;
    }


    /**
     * 校验jwt_token
     */
    public function valiDate()
    {
        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer($this->issue);
        $data->setAudience($this->aud);
        $data->setId($this->jti);
        return $this->decode()->valiDate($data);
    }

    /**
     * 验证token
     */
    public function verify()
    {
        $result = $this->decode()->verify(new Sha256(), $this->key);
        return $result;
    }


    /**
     * 获取用户ID
     */
    public function getUid()
    {

        return $this->uid;
    }
}

