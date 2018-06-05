<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午6:01
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Hall as AddHallModel;
use app\index\controller\User as User;

class Hall extends Controller
{
    public function index()
    {

    }

    //添加演出厅
    public function addHall()
    {
        header('Access-Control-Allow-Origin: *');


        $user = new User;
        $res = $user->isInLogin();
        if ($res == 0) {

            //用户没有登录
            return [
                'status'    => 1,
                'msg'       => 'the user didn\'t log in',
                'data'      => ''
            ];
        }
        if ($res != 1) {
            return [
                'status'    => 1,
                'msg'       => 'user does not have enough permissions',
                'data'      => ''
            ];
        }
        if (request()->isPost()/* || $res == 1*/) {
            $validate = validate('add_hall');
            if (!$validate->check(input('post.'))) {
                return [
                    'status'    => 1,
                    'msg'       => $validate->getError(),
                    'data'      => ''
                ];
            }
            $data = input('post.');

            $adminor = new AddHallModel();
            $res = $adminor->addHall($data);

            if (is_array($res)) {
                return [
                    'status:'   => 0,
                    'msg'       => '',
                    'data'      => $data
                ];
            }
            if ($res == 2) {
                return [
                    'status:'   => 0,
                    'msg'       => 'the hall has already existed',
                    'data'      => ''
                ];
            }
            if ($res == -1) {
                return [
                    'status:'   => 0,
                    'msg'       => 'the server is busy now',
                    'data'      => ''
                ];
            }
        }
    }
}