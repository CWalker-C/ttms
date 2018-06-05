<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午5:05
 */
namespace app\admin\model;

use think\Db;
use think\Model;

class Hall extends Model
{
    protected $table = 'hall';

    public function index($data)
    {

    }

    //添加影厅
    public function addHall($data)
    {
        $res = Db::table('hall')->where('hall_name', $data['hall_name'])->find();
        if ($res) {
            return 2;
        }
        $admin = new Hall;

        if ($admin->allowField(true)->save($data)) {
            return $data;
        } else {
            return -1;
        }
    }
}