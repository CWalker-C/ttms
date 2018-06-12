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
        $res = Db::table('movie_info')
            ->where('movie_name', $data['movie_name'])
            ->where('is_active', '<>', -3)
            ->find();

        if ($res) {
            return 2;
        }
        $admin = new Movie;

        if ($admin->allowField(true)->save($data)) {
            $res = Db::table('movie_info')->where('movie_name', $data['movie_name'])->find();
            $data = array_merge($data, ['movie_id' => $res['movie_id']]);
            return $data;
        } else {
            return -1;
        }
    }

    //查询影片
    public function findMovie()
    {
        $res = Db::table('movie_info')
            ->where('is_active', '<>', -3)
            ->select();

        return $res;
    }

    //修改影片
    public function modifyMovie($data)
    {
        $res = Db::table('movie_info')
            ->where('movie_id', $data['movie_id'])
            ->where('is_active', '<>', -3)
            ->select();

        if (!$res) {
            return -1;
        }
        if ($res[0]['is_active'] == -3) {
            return -1;
        }

//        $movieModel = new Movie();
        $res = Db::table('movie_info')
            ->where('movie_id', $data['movie_id'])
            ->update($data);

        return $data;
    }

    //删除电影信息
    public function deleteMovie($data)
    {
        $res = Db::table('movie_info')
            ->where('movie_id', $data['movie_id'])
            ->select();

        if (!$res) {
            return -1;
        }

        $res = Movie::where('movie_id', $data['movie_id'])
            ->update(['is_active' => -3]);
        Db::table('schedule')
            ->where('movie_id', $data['movie_id'])
            ->update(['is_active' => -3]);

        return $res;
    }
}
