<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午4:55
 */
namespace app\admin\model;

use think\Db;
use think\Model;


class Movie extends Model
{
    protected $table = 'movie_info';

    public function index()
    {


    }

    //添加影片
    public function addMovie($data)
    {
        $validate = validate('add_movie');

        if (!$validate->check($data)) {
            return $validate->getError();
        }
        $res = Db::table('movie_info')->where('movie_name', $data['movie_name'])->find();
        if ($res) {
            return 2;
        }
        $admin = new Movie;

        if ($admin->allowField(true)->save($data)) {
            return $data;
        } else {
            return -1;
        }
    }
}
