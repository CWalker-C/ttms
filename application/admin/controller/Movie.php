<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午5:08
 */

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Movie as MovieModel;
use app\index\controller\User as User;

class Movie extends Controller
{
    public function index()
    {

    }

    //添加影片
    public function addMovie()
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

            $validate = validate('add_movie');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }

            $data = input('post.');

            $addMovie = new MovieModel();
            $res = $addMovie->addMovie($data);

            if (is_array($res)) {
                return [
                    'status'    => 0,
                    'msg'       => '',
                    'data'      => $res
                ];
            }
            if (is_string($res)) {
                return [
                    'status'    => 1,
                    'msg'       => $res,
                    'data'      => ''
                ];
            }
            if ($res == 2) {
                return [
                    'status'    => 1,
                    'msg'       => 'the movie has already existed',
                    'data'      => ''
                ];
            }
            if ($res == -1) {
                return [
                    'status'    => 1,
                    'msg'       => 'the server is busy now',
                    'data'      => ''
                ];
            }
        }
    }

    //查询电影信息
    public function findMovie()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        $validate = validate('find_movie');
        if (!$validate->check(input('post.'))) {
            return [
                'status' => 1,
                'msg' => 'the data you input is not legal',
                'data' => ''
            ];
        }
        $data = input('post.');
        $user = new MovieModel();
        $res = $user->findMovie();
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
    public function modifyMovie()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        if (request()->isPost()) {
//            $user = new User;
//            $res = $user->isInLogin();
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

            $data = input('post.');
            $validate = validate('add_movie');
            if (!$validate->check($data)) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }

            $admin = new MovieModel();
            $res = $admin->modifyMovie($data);

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
                    'msg'       => 'the film you modify is not existent',
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
    public function deleteMovie()
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

            $validate = validate('delete_movie');
            if (!$validate->check(input('post.'))) {
                return [
                    'status' => 1,
                    'msg' => 'the data you input is not legal',
                    'data' => ''
                ];
            }

            $data = input('post.');

            $admin = new MovieModel();
            $res = $admin->deleteMovie($data);

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
                    'msg'       => 'the film you delete is not existent',
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