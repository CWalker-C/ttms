<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-5
 * Time: 下午2:43
 */

namespace app\index\model;

use think\Model;
use think\Db;

class Ticket extends Model
{
    public function index()
    {

    }

    //查询是否有票
    public function findTicket($data) {
        $movieName = $data['movie_name'];
        $beginTime = $data['schedule_begin_time'];

        $movieInfo = Db::table('movie_info')
            ->where('movie_name', $movieName)
            ->select();
        if (!$movieInfo) {  //电影未上映
            return -1;
        }

        $movieId = $movieInfo[0]['movie_id'];

        $scheInfo = Db::table('schedule')
            ->where('schedule_begin_time', date('Y-m-d H:i:s', $beginTime))
            ->where('movie_id', $movieId)
            ->select();

//        var_dump($scheInfo);

        if (!$scheInfo) {   //电影未加入演出计划
            return -2;
        }

        $scheId = $scheInfo[0]['schedule_id'];

        $seatInfo = Db::table('order_seat')
            ->where('schedule_id', $scheId)
            ->order('seat_id')
            ->select();

//        var_dump($seatInfo);

        $seatSold = array();
        for ($i = 0; $i < count($seatInfo); ++$i) {
            $seatSold[$i] = ['row' => $seatInfo[$i]['seat_row'], 'col' => $seatInfo[$i]['seat_col']];
        }

        return $seatSold;
    }

    //购票
    public function buyTicket($data)
    {

    }

}