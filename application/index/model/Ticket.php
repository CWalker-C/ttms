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

    //支付中
    public function inPayment($data)
    {
        $movieName = $data['movie_name'];
        $scheBegTime = $data['schedule_begin_time'];
        $seatRow = $data['seat_row'];
        $seatCol = $data['seat_col'];
        $cusInfo = session(''); //顾客信息

        $movieInfo = Db::table('movie_info')
            ->where('$movie_name', $movieName)
            ->select();
        if (!$movieInfo) {  //电影已下架
            return -1;
        }

        //影片安排信息
        $scheInfo = Db::table('schedule')
            ->where('movie_id', $movieInfo[0]['movie_id'])
            ->where('schedule_begin_time', date('Y-m-d H:i:s', $scheBegTime))
            ->select();
        if (!$scheInfo) {   //电影未上映
            return -2;
        }
        $disPrice = $scheInfo[0]['schedule_price'] * (1 - $cusInfo['class_id'] * 0.5);

        $orderInfo = [
            'customer_id'   => $cusInfo['customer_id'],
            'schedule_id'   => $scheInfo[0]['schedule_id'],
            'schedule_price'=> $disPrice,
            'order_date'    => date('Y-m-d H:i:s', time()),
            'is_active'     => 0
        ];
        $orderRes = Db::table('order')->insert($orderInfo);
        if (!$orderRes) {
            return -3;
        }
        $orderInfo = Db::table('order')
            ->where('customer_id', $cusInfo['customer_id'])
            ->where('schedule_id', $scheInfo[0]['schedule_id'])
            ->select();

        $orderId = $orderInfo[0]['order_id'];

        $seatInfo = [
            'order_id'      => $orderId,
            'seat_row'      => $seatRow,
            'seat_col'      => $seatCol,
            'schedule_id'   => $scheInfo[0]['schedule_id'],
            'is_active'     => 0,
            'pay_time'      => date('Y-m-d H:i:s', time())
        ];
        
    }

    //购票
    public function buyTicket($data)
    {

    }

}