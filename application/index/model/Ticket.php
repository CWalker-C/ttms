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
            ->where('schedule_begin_time', $beginTime)
            ->where('movie_id', $movieId)
            ->select();

        var_dump($scheInfo);

        if (!$scheInfo) {   //电影未加入演出计划
            return -2;
        }

//        var_dump($scheInfo);

        /*  $scheId = $scheInfo['schedule_id'];

        $seatInfo = Db::table('order_seat')
            ->where('schedule_id', $scheId)
            ->select();

        var_dump($seatInfo);*/

    }

    //购票
    public function buyTicket($data)
    {

    }

}