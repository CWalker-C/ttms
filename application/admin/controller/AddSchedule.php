<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-5-29
 * Time: 下午6:07
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\AddSchedule as AddSchModel;

class AddSchedule extends Controller
{
    public function index()
    {
        if (request()->isPost()) {
            $data = input('post.');

            $adminor = new AddSchModel();
            $res = $adminor->index($data);
            if ($res) {

            } else if ($res) {

            } else if ($res) {

            }

        }
    }
}