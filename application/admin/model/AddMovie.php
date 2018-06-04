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


class AddMovie extends Model
{
    protected $table = 'movie_info';

    public function index($data)
    {
        $validate = validate('AddMovie');

        if (!$validate->check($data)) {
            return -2;
        //            return $validate->getError();
        }
        $res = Db::table('movie_info')->where('movie_name', $data['movie_name'])->find();
        if ($res) {
            return 2;
        }
        $admin = new AddMovie;

        if ($admin->allowField(true)->save($data)) {
            return 1;
        } else {
            return -1;
        }

    }
}
