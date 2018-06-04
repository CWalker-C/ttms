<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午6:07
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\AddSchedule as AddSchModel;
use app\index\controller\Useraction as User;

class AddSchedule extends Controller
{
    public function index()
    {
        if (request()->isPost()) {
            $validate = validate('add_schedule');
            if (!$validate->check(input('post.'))) {
                return ['success' => 'data'];
            }

            $user = new User;
            $res = $user->isInlogin();
//            if ($res == 0) {
//                return ['success' => 'notLogin'];
//
//            }
//            if ($res != 1) {
//                return ['success' => 'noAuthority'];
//            }

            $data = input('post.');

            $adminor = new AddSchModel();
            $res = $adminor->index($data);
            if ($res) {

            }
            if ($res == -1) {
                return ['success' => 'timeConflict'];

            } else if ($res) {

            }

        }
    }
}