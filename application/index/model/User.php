<?php

namespace app\index\model;

use think\Model;
use think\Db;
use app\index\model\GetIp as IpAction;

class User extends Model
{
    protected $table = 'customer';
    protected $pk = 'customer_id';
    protected $prefix = '';
    protected $discount = 0.05;

    //用户注册
    public function register($data = '')
    {
        if ($data) {

            $validate = validate('Check');
            if ($validate->check($data['customer_name'])) {

                return $validate->getError();
            }
            else if ($validate->check($data['customer_passwd'])){
                return $validate->getError();
            }


            $res = Db::table('customer')->where('customer_email', $data['customer_email'])->find();

            if ($res) { //用户已存在
                return 0;
            }

            $data['customer_passwd'] = md5($data['customer_passwd']);
            $data['customer_create_time'] = date("Y-m-d H:i:s",time());
            $data['customer_update_time'] = date("Y-m-d H:i:s",time());
            $data['class_id'] = 0;
            $data['login_cnt'] = 0;

            $user = new User;

            if ($user->allowField(true)->save($data)) { //注册成功

                return 1;
            } else {    //其他错误

                return -1;
            }
        }
    }

    //用户登录
    public function login($customer_email = '', $customer_passwd = '')
    {
        $validate = validate('Check');
        if ($validate->check($customer_email)) {
            return $validate->getError();
        }
        if ($validate->check($customer_passwd)) {
            return $validate->getError();
        }
        $res = Db::table('customer')->where('customer_email', '=', $customer_email)->find();
//        $res = Db::table('user_login')->where('user_name', '')->find();


        if (!$res) {    //用户不存在
            return 0;
        }
        $customer_passwd = md5($customer_passwd);

//        var_dump($res['user_password']);
//        var_dump($user_password);

        if ($res['customer_passwd'] == $customer_passwd) { //用户名和密码一致
            $user = new User;
            $user->startTrans();
            $ret1 = Db::table('customer')
                ->where('customer_email', $customer_email)
                ->setInc('login_cnt');
            if (!$ret1 ) {
                $user->rollback();
                return -2;
            }
            $ret2 = Db::table('customer')->where('customer_email', $customer_email)->update(['update_time' => date("Y-m-d H:i:s", time())]);
            if (!$ret2) {
                $user->rollback();
                return -2;
            }
            $user->commit();


            session($customer_email, $res);

//            $sessionKey = md5($customer_email);
//            $ipAction = new IpAction;
//            $ip = $ipAction->index();
//            $sessionValue = $customer_email + $ip;
//            $sessionValMd5 = md5($sessionValue);
//
//            session($sessionKey, $sessionValMd5);
//            session($sessionValMd5, $customer_email);
//            session($customer_email, $res['authority']);
//            session([
//                'expire'    => 3600,
//                'name'      => $sessionKey
//            ]);
//            session([
//                'expire'    => 3600,
//                'name'      => $sessionValMd5
//            ]);

            return [
                'user_name'     => $res['customer_name'],   //顾客昵称
                'cookie'        => $sessionKey, //cookie
                'authority'     => $res['authority'],   //用户权限
            ];
        } else if ($res['customer_passwd'] != $customer_passwd) {
            return -1;
        } else {
            return -2;
        }
    }


    public function createCheckCode() {
        /*--创建一个大小为 100*30 的验证码--*/
        $image = imagecreatetruecolor(100, 30);
        $bgcolor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgcolor);

        $captch_code = '';
        for($i=0;$i<4;$i++) {
            $fontsize = 6;
            $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120),rand(0, 120));

            $data = 'abcdefghijkmnpqrstuvwxy3456789';
            $fontcontent = substr($data, rand(0, strlen($data)-1), 1);
            $captch_code .= $fontcontent;

            $x = ($i*100/4) + rand(5, 10);
            $y = rand(5, 10);

            imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
        }
        $_SESSION['authcode'] = $captch_code;

        //增加点干扰元素
        for($i=0; $i<200;$i++) {
            $pointcolor = imagecolorallocate($image, rand(50,200), rand(50,200), rand(50,200));
            imagesetpixel($image, rand(1,99), rand(1,29), $pointcolor);
        }

        //增加线干扰元素
        for($i=0;$i<3;$i++) {
            $linecolor = imagecolorallocate($image, rand(80,220), rand(80,220), rand(80, 220));
            imageline($image, rand(1,99), rand(1,29), rand(1,99), rand(1,29), $linecolor);
        }

//        header('content-type:image/gif');
        header('content-type:image/png');

        imagepng($image);

        imagedestroy($image);

    }
}