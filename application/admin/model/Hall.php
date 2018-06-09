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
        $res = Db::table('hall')
            ->where('hall_name', $data['hall_name'])
            ->find();
        if ($res) {
            return 2;
        }
        $res = Db::table('hall')
            ->where('hall_name', $data['hall_name'])
            ->find();
        $hallId = $res['hall_id'];
        $seatInfo = $data['seat_info'];
        $seatInfo = str_replace('[', '',$seatInfo);
        $seatInfo = str_replace(']', '',$seatInfo);
        $seatInfo = explode(",", $seatInfo);

        $admin = new Hall();
        if ($admin->allowField(true)->save($data)) {

            for ($i = 0; $i < count($seatInfo); $i += 2) {
                $row = $seatInfo[$i];
                $col = $seatInfo[$i + 1];

                $seatInsert = [
                   'hall_id'           => $hallId,
                   'seat_row'          => $row,
                   'seat_col'          => $col,
                   'seat_is_active'    => 1
                ];

               Db::table('seat')
                   ->insert($seatInsert);
            }

                return $data;
        } else {
            return -1;
        }
    }

    //查询演出厅
    public function findHall()
    {
        $res = Db::table('hall')
            ->where('is_active', 1)
            ->select();

        return $res;
    }

    //修改演出厅信息
    public function modifyHall($data)
    {
        $res = Db::table('hall')
            ->where('hall_id', $data['hall_id'])
            ->where('is_active', 1)
            ->select();

        if (!$res) {
            return -1;
        }

        $movieModel = new Hall();
        $res = $movieModel->where('hall_id', $data['hall_id'])
            ->update($data);
        if ($res == 0){
            return $data;
        }
    }

    //删除演出厅
    public function deleteHall($data)
    {
        $res = Db::table('hall')
            ->where('hall_id', $data['hall_id'])
            ->select();

        if (!$res) {
            return -1;
        }

        $res = Hall::where('hall_id', $data['hall_id'])
            ->update(['is_active' => -3]);

//        var_dump($res);
        return $res;
    }

}