<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use app\index\model\GetIp as IpAction;

class Useraction extends Controller
{
    private $userExisErr = array('success' => 'userExist');   //用户已存在
    private $regSuccess = array('success'  => 'regSuccess');    //注册成功
    private $loginSuccess = array('success'  => 'loginSuccess');    //注册成功
    private $noUser = array('success'  =>  'nonUser');  //用户不存在
    private $passwdErr = array('success'    => 'passwdErr');   //密码错误
    private $otherErr = array('success'    => 'otherErr');   //其他错误
    private $sessionKey;

    public function __construct()
    {

    }

    //设置stasus
    protected $status = 0;

    public function index()
    {
        return ['key' => 'value'];
    }

    //用户注册
    public function register() {
        header('Access-Control-Allow-Origin: *');
        if (request()->isPost()) {
            $data = input('post.');

            $user = new User();
            $res = $user->register($data);

            if ($res == 1) {    //注册成功
                return $this->regSuccess;
            }
            else if ($res == 0) {   //用户已存在
                return $this->userExisErr;
            }
            else if ($res == -1) {   //其他错误
                return $this->otherErr;
            }
        }
    }

    //用户登录
    public function login()
    {
        header('Access-Control-Allow-Origin: *');

        if (request()->isPost()) {
            $customer_email = input('customer_email');
            $customer_passwd = input('customer_passwd');

            $user = new User();
            $res = $user->login($customer_email, $customer_passwd);
//            var_dump($res);
            if (is_array($res)) { //登录成功
                $this->status = 1;  //改变状态

                return ['success'   => 'loginSuccess'] + $res;
            } else if ($res == 0) { //用户不存
                return $this->noUser;
            } else if ($res == -1) {
                return $this->passwdErr;
            }
            else if ($res == -2) {  //其他错误
                return $this->otherErr;
            }
        }
    }

    //退出登录
    public function logout($sessionKey)
    {
        $sessionValue = session($sessionKey);
        $customer_email = session($sessionValue);
 //       $authority = session($customer_email);

        session($customer_email, null);
        session($sessionValue, null);
        session($sessionKey, null);

        return ['success'   => 'logoutSuccess'];
    }

    //判断用户是否在登录状态及权限
    public function isInlogin()
    {
        $requestInfo =  apache_request_headers();

        $sessionKey = $requestInfo['Cookie'];

 //       $sessionKey = input('cookie');
        $sessionValue = session($sessionKey);
        $customer_email = session($sessionValue);
        $authority = session($customer_email);

        if (!$sessionValue) {   //用户没有登录
            return -1;
        }
        $customer_email = session($sessionValue);
        $authority = session($customer_email);

        $ipAction = new IpAction;
        $ip = $ipAction->index();
        $sessionValueNow = $customer_email + $ip;
        $sessionValMd5 = md5($sessionValue);

        if ($sessionValue != $sessionValueNow) {    //用户在其他地方登录
            return 2;
        } else {
            return $authority;  //返回用户的权限值
        }

    }

    //验证码
    public function checkCode() {
        $verifyImage = new \VerifyImage();
        $verifyImage->CreateVerifyImage();
    }
}
























