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
    public function addMovie() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

        if (request()->isPost()) {
            $user = new User;
            $res = $user->isInLogin();
            /*if ($res == 0) {

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

            $validate = validate('add_movie');
            if (!$validate->check(input('post.'))) {
                return [
                    'status'    => 1,
                    'msg'       => $validate->getError(),
                    'data'      => ''
                ];
            }

            $data = input('post.');

            $addMovie = new MovieModel();
            $res = $addMovie->addMovie($data);

            if (is_array($res)) {
                return [
                    'status'    => 0,
                    'msg'       => '',
                    'data'      => $data
                ];
            }
            if (is_string($res)){
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
}