<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-5-29
 * Time: 下午6:01
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\AddHall as AddHallModel;
use app\index\controller\Useraction as User;

class AddHall extends Controller
{
    public function index()
    {
        header('Access-Control-Allow-Origin: *');


        $user = new User;
        $res = $user->isInlogin();
        if ($res = -1) {
            return ['sucess' => 'unLogin'];
        }
        if ($res == 2) {
            return ['success' => 'reLogin'];
        }
        if ($res = 0) {
            return ['success' => 'noAuthority'];
        }
        if (request()->isPost() || $res == 1) {
            $data = input('post.');

            $adminor = new AddHallModel();
            $res = $adminor->index($data);

            if ($res == 1) {
                return ['success' => 'addSucc'];
            } else if ($res == -2){
                return ['success' => 'dataIllegal'];
            } else if ($res == 2) {
                return ['success' => 'HallExist'];
            } else if ($res == -1) {
                return ['success' => 'otherErr'];
            }
        } else {
            return ['success' => 'requestErr'];
        }

    }
}