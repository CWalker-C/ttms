<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午6:07
 */
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Schedule as ScheModel;
use app\index\controller\User as User;

class Schedule extends Controller
{
    public function index()
    {
        return ['key' => 'value'];
    }

    //添加影厅安排
    public function addSche()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        if (request()->isPost()) {
            /*$user = new User;
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
            }*/

            $validate = validate('add_schedule');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }
            $data = input('post.');

            $adminor = new ScheModel();
            $res = $adminor->addSchedule($data);
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
                    'msg'   => 'the film has gone down',
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
                    'msg' => 'the time you arrange is contradictory',
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

    //查询已安排的电影时间段
    public function findHallScheTime()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        if (request()->isPost()) {
            /*$user = new User;
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
            }*/

            $validate = validate('find_hall_sche_time');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }
            $data = input('post.');
            $adminor = new ScheModel;
            $res = $adminor->findHallScheTime($data);
            if (is_array($res)) {
                return [
                    'status'    => 0,
                    'msg'       => '',
                    'data'      => $res
                ];
            }
            if ($res == -1) {
                return [
                    'status'    => 1,
                    'msg'       => 'the hall dosen\'t exists'
                ];
            }
        }
    }

    //修改演出计划
    public function modifySche()
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

            $validate = validate('add_schedule');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }

            $data = input('post.');

            $admin = new ScheModel();
            $res = $admin->modifySchedule($data);


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
                    'msg' => 'the film has gone down',
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
                    'msg' => 'the time you arrange is contradictory',
                    'data' => ''
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
    public function deleteSche()
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
            $validate = validate('delete_schedule');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }

            $data = input('post.');

            $admin = new ScheModel();
            $res = $admin->deleteSche($data);

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
                    'msg'       => 'the schedule you delete is not existent',
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

    //查询已安排的演出计划
    public function findSche()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        $user = new ScheModel();
        $res = $user->findSche();
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
}





























