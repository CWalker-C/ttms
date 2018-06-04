<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午6:07
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Schedule as AddSchModel;
use app\index\controller\User as User;

class Schedule extends Controller
{
    public function index()
    {

    }

    //添加影厅安排
    public function addSchedule()
    {
        if (request()->isPost()) {
            $user = new User;
            $res = $user->isInLogin();
            if ($res == 0) {

                //用户没有登录
                return [
                    'status' => 1,
                    'msg' => 'the user didn\'t log in',
                    'data' => ''
                ];
            }
            if ($res != 1) {
                return [
                    'status' => 1,
                    'msg' => 'user does not have enough permissions',
                    'data' => ''
                ];
            }

            $validate = validate('add_schedule');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => $validate->getError(),
                    'data' => ''
                ];
            }
            $data = input('post.');

            $adminor = new AddSchModel();
            $res = $adminor->index($data);
            if (is_array($res)) {
                return [
                    'status' => 0,
                    'msg' => '',
                    'data' => $res
                ];
            }
            if ($res == -1) {   //时间冲突
                return [
                    'status' => 1,
                    'msg' => 'the time you arrange is contradictory',
                    'data' => ''
                ];

            }
            if ($res == -2) {    //时间过期
                return [
                    'status' => 1,
                    'msg' => 'the time you arrange is overdue',
                    'data' => ''
                ];
            }
            if ($res == -3) {
                return [
                    'status' => 1,
                    'msg' => 'the server is busy now',
                    'data' => ''
                ];
            }

        }
    }
}