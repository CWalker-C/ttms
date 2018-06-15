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
            ->where('is_active', '<>', -3)
            ->find();
        if ($res) {
            return 2;
        }
        $data = array_merge($data, ['is_active' => 1]);
        $seatInfo = $data['seat_info'];
        $admin = new Hall();
        $admin->allowField(true)->save($data);

        $hallId = Db::name('hall')->getLastInsID();
        for ($i = 0; $i < count($seatInfo); $i++) {
            $row = $seatInfo[$i][0];
            $col = $seatInfo[$i][1];

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
        if ($res) {
            return [
                'hall_id'           => $data['hall_id'],
                'hall_name'         => $data['hall_name'],
                'hall_seats'        => $data['hall_seats'],
                'seat_rows'         => $data['seat_rows'],
                'seat_cols'         => $data['seat_cols'],
                'hall_description'  => $data['hall_description'],
                'seat_info'         => $data['seat_info']
            ];
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
                ->where('seat_is_active', 1)
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
            ->delete();

        $seatInfo = $data['seat_info'];

        if (count($seatInfo) > 0) {
            /*$seatInfo = str_replace('[', '',$seatInfo);
            $seatInfo = str_replace(']', '',$seatInfo);
            $seatInfo = explode(",", $seatInfo);*/
            for ($i = 0; $i < count($seatInfo); $i++) {
                $row = $seatInfo[$i][0];
                $col = $seatInfo[$i][1];

                $seatInsert = [
                    'hall_id'           => $data['hall_id'],
                    'seat_row'          => $row,
                    'seat_col'          => $col,
                    'seat_is_active'    => 1 //不可用
                ];

                Db::table('seat')
                    ->insert($seatInsert);
            }
        }

        return  [
            'hall_id'           => $data['hall_id'],
            'hall_name'         => $data['hall_name'],
            'hall_seats'        => $data['hall_seats'],
            'seat_rows'         => $data['seat_rows'],
            'seat_cols'         => $data['seat_cols'],
            'hall_description'  => $data['hall_description'],
            'seat_info'         => $data['seat_info'],
            'seat_dis_cnt'      => $data['seat_dis_cnt']
        ];
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
        Db::table('schedule')
            ->where('hall_id', $data['hall_id'])
            ->update(['is_active' => -3]);

//        var_dump($res);
        return 0;
    }

}