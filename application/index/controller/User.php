<?php

namespace app\index\controller;

use app\index\model\User as UserModel;
use think\Controller;

class User extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return ['key' => 'value'];
    }

    //用户注册
    public function register() {
        header('Access-Control-Allow-Origin: *');
        if (request()->isPost()) {
            //验证数据是否合法
            $validate = validate('user_register');
            if (!$validate->check(input('post.'))) {
                return [
                    'status'    => 1,
                    'msg'       => $validate->getError(),
                    'data'      => ''
                ];
            }

            $data = input('post.');

            $user = new UserModel();
            $res = $user->register($data);

            if (is_array($res)) {    //注册成功
                return [
                    'status'    => 0,
                    'msg'       => '',
                    'data'      => [
                        'user_name' => $res['customer_name'],
                        'user_email'=> $res['customer_email'],
                        'authority' => 0,
                    ]
                ];
            }
            else if ($res == 0) {   //用户已存在
                return [
                    'status'    => 1,
                    'msg'       => 'The user has already existed.',
                    'data'      => ''
                ];
            }
            else if ($res == -1) {   //其他错误
                return [
                    'status'    => 1,
                    'msg'       => 'the server is busy now.',
                    'data'      => ''
                ];
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
                return [
                    'status'    => 1,
                    'msg'       => $validate->getError(),
                    'data'      => ''
                ];
            }

            $customer_email = input('customer_email');
            $customer_passwd = input('customer_passwd');

            $user = new UserModel();
            $res = $user->login($customer_email, $customer_passwd);
//            var_dump($res);
            if (is_array($res)) { //登录成功
                $this->status = 1;  //改变状态

//                return ['success'   => 'loginSuccess'] + $res;
                return [
                    'status'    => 0,
                    'msg'       => '',
                    'data'      => [
                        'user_name' => $res['customer_name'],
                        'user_email'=> $res['customer_email'],
                        'authority' => $res['authority']
                    ]
                ];
            }
            if ($res == 0) { //用户不存在
                return [
                    'status'    => 1,
                    'msg'       => 'the user doesn\'t exist.',
                    'data'      => ''
                ];
            }
            if ($res == -1) {
                return [
                    'status'    => 1,
                    'msg'       => 'the username or password is incorrect.',
                    'data'      => ''
                ];
            }
            if ($res == -2) {  //其他错误
                return [
                    'status'    => 1,
                    'msg'       => 'the server is busy now.',
                    'data'      => ''
                ];
            }
        }
    }

    //退出登录
    public function logout()
    {
//        $sessionValue = session($sessionKey);
//        $customer_email = session($sessionValue);
 //       $authority = session($customer_email);

        session(null);

        return [
            'status'    => 0,
            'msg'       => '',
            'data'      => ''
        ];
    }

    //判断用户是否在登录状态及权限
    public function isInLogin()
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
























