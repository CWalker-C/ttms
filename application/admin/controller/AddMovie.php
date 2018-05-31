<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-5-29
 * Time: 下午5:08
 */

namespace app\admin\controller;

use think\Controller;
use app\admin\model\AddMovie as AddMovieModel;

class AddMovie extends Controller
{
    public function index()
    {
        header('Access-Control-Allow-Origin: *');

        if (request()->isPost()) {
            $data = input('post.');

            $addMovie = new AddMovieModel();
            $res = $addMovie->index($data);

            if ($res == 1) {
                return ['success' => 'addSucc'];
            } else if ($res == -2){
                return ['success' => 'dataIllegal'];
            } else if ($res == 2) {
                return ['success' => 'movieExist'];
            } else if ($res == -1) {
                return ['success' => 'otherErr'];
            }
        }
    }
}