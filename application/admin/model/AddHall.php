<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-5-29
 * Time: 下午5:05
 */
namespace app\admin\model;

use think\Db;
use think\Model;

class AddHall extends Model
{
    protected $table = 'hall';

    public function index($data)
    {
        $validate = validate('AddHall');

        if (!$validate->check($data)) {
            return -2;
//            return $validate->getError();
        }
        $res = Db::table('hall')->where('hall_name', $data['hall_name'])->find();
        if ($res) {
            return 2;
        }
        $admin = new AddHall;

        if ($admin->allowField(true)->save($data)) {
            return 1;
        } else {
            return -1;
        }
    }
}