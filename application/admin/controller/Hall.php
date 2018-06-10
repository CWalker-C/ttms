<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午6:01
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Hall as HallModel;
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
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        $user = new User;
        $res = $user->isInLogin();
       /* if ($res == 0) {

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
        }*/
        if (request()->isPost()/* || $res == 1*/) {
            $validate = validate('add_hall');
            if (!$validate->check(input('post.'))) {
                return [
                    'status'    => 1,
                    'msg'       => 'the data you input is not legal',
                    'data'      => ''
                ];
            }
            $data = input('post.');

            $adminor = new HallModel();
            $res = $adminor->addHall($data);

            if (is_array($res)) {
                return [
                    'status:'   => 0,
                    'msg'       => '',
                    'data'      => $res
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

    //查询演出厅信息
    public function findHall()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        $user = new HallModel();
        $res = $user->findHall();
        if (is_array($res)) {
            return [
                'status' => 0,
                'msg' => '',
                'data' => $res
            ];
        } else {
            return [
                'status' => 1,
                'msg' => 'the server is busy now',
                'data' => ''
            ];
        }
    }

    //修改电影信息
    public function modifyHall()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        if (request()->isPost()) {
            $user = new User;
            $res = $user->isInLogin();
            /*if ($res == 0) {

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
            }*/

            $validate = validate('add_hall');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }
            $data = input('post.');

            $admin = new HallModel();
            $res = $admin->modifyHall($data);
            if (is_array($res)) {
                return [
                    'status' => 0,
                    'msg' => '',
                    'data' => $res
                ];
            }
            if ($res == -1) {
                return [
                    'status'    => 1,
                    'msg'       => 'the hall you delete is not existent',
                    'data'      => ''
                ];
            } else {
                return [
                    'status' => 1,
                    'msg' => 'the server is busy now',
                    'data' => ''
                ];
            }
        }
    }

    //删除电影
    public function deleteHall()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        if (request()->isPost()) {
            $user = new User;
            $res = $user->isInLogin();
            /*if ($res == 0) {

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
            }*/
            $validate = validate('delete_hall');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }

            $data = input('post.');

            $admin = new HallModel();
            $res = $admin->deleteHall($data);
            if ($res == 0) {
                return [
                    'status' => 0,
                    'msg' => '',
                    'data' => ''
                ];
            }
            if ($res == -1) {
                return [
                    'status'    => 1,
                    'msg'       => 'the hall you delete is not existent',
                    'data'      => ''
                ];
            }
            else {
                return [
                    'status' => 1,
                    'msg' => 'the server is busy now',
                    'data' => ''
                ];
            }
        }
    }
}