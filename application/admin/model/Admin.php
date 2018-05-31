<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-5-29
 * Time: 下午8:44
 */
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    protected $table = '';

    public function index()
    {

    }

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
    public function login($customer_name = '', $customer_passwd = '')
    {
        $validate = validate('Check');
        if ($validate->check($customer_name)) {
            return $validate->getError();
        }
        if ($validate->check($customer_passwd)) {
            return $validate->getError();
        }
        $res = Db::table('customer')->where('customer_email', '=', $customer_name)->find();
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
                ->where('customer_email', $customer_name)
                ->setInc('login_cnt');
            if (!$ret1 ) {
                $user->rollback();
                return -2;
            }
            $ret2 = Db::table('customer')->where('customer_email', $customer_name)->update(['update_time' => date("Y-m-d H:i:s", time())]);
            if (!$ret2) {
                $user->rollback();
                return -2;
            }
            $user->commit();

            return 1;
        } else if ($res['customer_passwd'] != $customer_passwd) {
            return -1;
        } else {
            return -2;
        }
    }
}