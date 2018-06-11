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

        $seatInfo = $data['seat_info'];
        $seatInfo = str_replace('[', '',$seatInfo);
        $seatInfo = str_replace(']', '',$seatInfo);
        $seatInfo = explode(",", $seatInfo);

        $admin = new Hall();
        if ($admin->allowField(true)->save($data)) {
            $res = Db::table('hall')
                ->where('hall_name', $data['hall_name'])
                ->find();
            $hallId = $res['hall_id'];
            for ($i = 0; $i < count($seatInfo); $i += 2) {
                $row = $seatInfo[$i];
                $col = $seatInfo[$i + 1];

                $seatInsert = [
                   'hall_id'           => $hallId,
                   'seat_row'          => $row,
                   'seat_col'          => $col,
                   'seat_is_active'    => 1 //不可用
                ];

               Db::table('seat')
                   ->insert($seatInsert);
            }
            $res = Db::table('hall')
                ->where('hall_name', $data['hall_name'])
                ->find();
            $data = array_merge($data, ['hall_id' => $res['hall_id']]);
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

        for ($i = 0; $i < count($res); ++$i) {
            $cnt = Db::table('seat')
                ->where('hall_id', $res[$i]['hall_id'])
                ->count();
            $res[$i] = array_merge($res[$i], ['seat_dis_cnt' => $cnt]);
        }

        return $res;
    }

    //查询演出厅座位
    public function findSeat($data)
    {
        $hallId = $data['hall_id'];
        $res = Db::table('seat')
            ->where('hall_id', $hallId)
            ->where('seat_is_active', 1)
            ->select();
        $dis_seat = array();
        for ($i = 0; $i < count($res); ++$i) {
            $dis_seat = array_merge($dis_seat, [[$res[$i]['seat_row'], $res[$i]['seat_col']]]);
        }

        return $dis_seat;
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

        $hallInfo = [
            'hall_name'         => $data['hall_name'],
            'hall_description'  => $data['hall_description'],
            'seat_rows'         => $data['seat_rows'],
            'seat_cols'         => $data['seat_cols'],
            'hall_seats'        => $data['hall_seats'],
            'is_active'         => 1
        ];

        Db::table('hall')
            ->where('hall_id', $data['hall_id'])
            ->update($hallInfo);
        Db::table('seat')
            ->where('hall_id', $data['hall_id'])
            ->update(['seat_is_active'   => 0]);

        $seatInfo = $data['seat_info'];
        $seatInfo = str_replace('[', '',$seatInfo);
        $seatInfo = str_replace(']', '',$seatInfo);
        $seatInfo = explode(",", $seatInfo);

        for ($i = 0; $i < count($seatInfo); $i += 2) {
            $row = $seatInfo[$i];
            $col = $seatInfo[$i + 1];

            $seatInsert = [
                'hall_id'           => $data['hall_id'],
                'seat_row'          => $row,
                'seat_col'          => $col,
                'seat_is_active'    => 1 //不可用
            ];

            Db::table('seat')
                ->insert($seatInsert);
        }

        return $data;
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