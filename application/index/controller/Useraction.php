<?php

namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Loader;
class Useraction extends Controller
{
    private $userExisErr = array('success' => 'userExist');   //用户已存在
    private $regSuccess = array('success'  => 'regSuccess');    //注册成功
    private $loginSuccess = array('success'  => 'loginSuccess');    //注册成功
    private $noUser = array('success'  =>  'nonUser');  //用户不存在
    private $passwdErr = array('success'    => 'passwdErr');   //密码错误
    private $otherErr = array('success'    => 'otherErr');   //其他错误

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
            $validate = validate('user_register');
            if (!$validate->check(input('post.'))) {
                return ['success'=>'dataIllegal'];
            }

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

            $validate = validate('user_login');
            if (!$validate->check(input('post.'))) {
                return ['success'=>'dataIllegal'];
            }

            $customer_email = input('customer_email');
            $customer_passwd = input('customer_passwd');

            $user = new User();
            $res = $user->login($customer_email, $customer_passwd);
//            var_dump($res);
            if (is_array($res)) { //登录成功
                $this->status = 1;  //改变状态

                return ['success'   => 'loginSuccess'] + $res;
            }
            if ($res == 0) { //用户不存
                return $this->noUser;
            }
            if ($res == -1) {
                return $this->passwdErr;
            }
            if ($res == -2) {  //其他错误
                return $this->otherErr;
            }
        }
    }

    //退出登录
    public function logout($sessionKey)
    {
//        $sessionValue = session($sessionKey);
//        $customer_email = session($sessionValue);
 //       $authority = session($customer_email);

        session(null);

        return ['success'   => 'logoutSuccess'];
    }

    //判断用户是否在登录状态及权限
    public function isInlogin()
    {
        if (session('?user')) {
            $userInfo = session('user');
            $authority = $userInfo['authority'];

            return $authority;
        } else {
            return 0;
        }
    }

    //验证码
    public function checkCode() {
        $verifyImage = new \VerifyImage();
        $verifyImage->CreateVerifyImage();
    }
}
























