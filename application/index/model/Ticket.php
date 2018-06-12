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
    protected $table = 'order';

    private $movieInfo;
    private $hallInfo;
    private $cusInfo;
    private $seatRow;
    private $scheInfo;
    private $orderInfo;
    private $seatCol;

    public function index()
    {

    }

    //查询某电影的演出计划
    public function findMovieSche($data)
    {
        $movieId = $data['movie_id'];
        $movieInfo = Db::table('movie_info')
            ->where('movie_id', $movieId)
            ->where('is_active', '<>', -3)
            ->select();
        if (!$movieInfo) {  //没有该电影
            return -1;
        }
        $movieDur = $movieInfo[0]['movie_duration'];
        $res = Db::table('schedule')
            ->where('movie_id', 1)
            ->where('is_active', 1)
            ->select();

        for ($i = 0; $i < count($res); ++$i) {
            $endTime = date('Y-m-d H:i:s',strtotime($res[$i]['schedule_begin_time']) + $movieDur * 60);
            $hallInfo = Db::table('hall')
                ->where('hall_id', $res[$i]['hall_id'])
                ->where('is_active', 1)
                ->select();

            $res[$i] = array_merge($res[$i], [
                'movie_name'            => $movieInfo[0]['movie_name'],
                'hall_name'             => $hallInfo[0]['hall_name'],
                'schedule_end_time'     => $endTime
            ]);
        }

        return $res;
    }

    //查询某场次的电影票的售出状况
    public function findTicket($data) {
        $scheId = $data['schedule_id'];
        $scheInfo = Db::table('schedule')
            ->where('schedule_id', $scheId)
            ->where('is_active', 1)
            ->select();

//        var_dump($scheInfo);
        if (!$scheInfo) {   //电影未加入演出计划
            return -2;
        }

        //处理时间戳过期的订单
        $orderInfo = Db::table('order')
            ->where('schedule_id', $scheId)
            ->where('is_active', 0)
            ->select();
        for ($i = 0; $i < count($orderInfo); ++$i) {
            if (time() - strtotime($orderInfo[$i]['order_date']) > 900) {
                Db::table('order')
                    ->where('order_id', $orderInfo[$i]['order_id'])
                    ->update(['is_active' => -3]);
            }
        }
        //查询已购票的信息
        $seatInfo = Db::table('order')
            ->where('schedule_id', $scheId)
            ->where('is_active', '1')
            ->order('order_date')
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
        $this->scheInfo['schedule_id'] = $data['schedule_id'];
        $this->seatRow = $data['seat_row'];
        $this->seatCol = $data['seat_col'];
        $this->cusInfo = session('user'); //顾客信息
        $this->scheInfo = Db::table('schedule')
            ->where('schedule_id', $this->scheInfo['schedule_id'])
            ->where('is_active', 1)
            ->select();
        if (!$this->scheInfo) {   //电影未安排演出计划
            return -2;
        }

        $this->movieInfo = Db::table('movie_info')
            ->where('movie_id', $this->scheInfo[0]['movie_id'])
            ->select();
        if (!$this->movieInfo) {  //电影已下架
            return -1;
        }
//        var_dump($this->movieInfo);

        $this->hallInfo = Db::table('hall')
            ->where('hall_id', $this->scheInfo[0]['hall_id'])
            ->select();

        //查询此顾客已购票数
        $ticCnt = Db::table('order')
            ->where('customer_id', $this->cusInfo['customer_id'])
            ->where('schedule_id', $this->scheInfo[0]['schedule_id'])
            ->count();
        if ($ticCnt > 5) {   //该顾客购票数已达上限（6张）
            return 2;
        }

        $disPrice = $this->scheInfo[0]['schedule_price'] * (1 - $this->cusInfo['class_id'] * 0.5);

//        var_dump($disPrice);
        $orderInfo = Db::table('order')
            ->where('schedule_id', $this->scheInfo[0]['schedule_id'])
            ->where('seat_row', $this->seatRow)
            ->where('seat_col', $this->seatCol)
            ->where('is_active', '<>', -1)
            ->select();
//        var_dump($orderInfo);
        if ($orderInfo) {
            if ($orderInfo[0]['is_active'] == 1) {   //电影已被购买
                return 1;
            }

            if ($orderInfo[0]['is_active'] == 0) {   //票正在被购买，检查时间戳是否过期
                if (time() - strtotime($orderInfo[0]['order_date']) <= 900) { //没过期
                    return 1;
                } else {
                    Db::table('order')
                        ->where('order_id', $orderInfo[0]['order_id'])
                        ->update(['is_active' => -3]);

                }

            }
        }
        $dataInsert = [
            'customer_id'   => $data['customer_id'],
            'schedule_id'=> $this->scheInfo[0]['schedule_id'],
            'order_discount_price'=> $disPrice,
            'order_date'    => date('Y-m-d H:i:s', time()),
            'is_active'     => 0,
            'seat_row'      => $this->seatRow,
            'seat_col'      => $this->seatCol
        ];
        $orderId = Db::name('order')->insertGetId($dataInsert);
        $dataInsert2 = [
            'customer_name' => $this->cusInfo['customer_name'],
            'movie_name'    => $this->movieInfo[0]['movie_name'],
            'hall_name'     => $this->hallInfo[0]['hall_name'],
            'order_id'      => $orderId
        ];
        return array_merge($dataInsert, $dataInsert2);
    }

    //购票
    public function buyTicket($data)
    {
        if (!$data['is_paid'] != 1) {    //未付款
            return -1;
        }
        $orderInfo = Db::table('order')
            ->where('order_id', $data['order_id'])
            ->where('is_active', 0)
            ->select();
        if ($orderInfo) {
            return -2;
        }
        if (time() - strtotime($orderInfo[0]['order_date']) > 900) {    //订单超时
            return -3;
        }
        Db::table('order')
            ->where('order_id', $data['order_id'])
            ->where('is_active', 0)
            ->update(['is_active' => 1]);

        $this->scheInfo['schedule_id'] = $data['schedule_id'];
        $this->seatRow = $data['seat_row'];
        $this->seatCol = $data['seat_col'];
        $this->cusInfo = session('user'); //顾客信息
        $this->scheInfo = Db::table('schedule')
            ->where('schedule_id', $this->scheInfo['schedule_id'])
            ->where('is_active', 1)
            ->select();
        if (!$this->scheInfo) {   //电影未安排演出计划
            return -2;
        }

        $this->movieInfo = Db::table('movie_info')
            ->where('movie_id', $this->scheInfo[0]['movie_id'])
            ->select();
        if (!$this->movieInfo) {  //电影已下架
            return -1;
        }
//        var_dump($this->movieInfo);
        $disPrice = $this->scheInfo[0]['schedule_price'] * (1 - $this->cusInfo['class_id'] * 0.5);

        $this->hallInfo = Db::table('hall')
            ->where('hall_id', $this->scheInfo[0]['hall_id'])
            ->select();
        return [
            'customer_id'   => $data['customer_id'],
            'schedule_id'=> $this->scheInfo[0]['schedule_id'],
            'order_discount_price'=> $disPrice,
            'order_date'    => date('Y-m-d H:i:s', time()),
            'seat_row'      => $this->seatRow,
            'seat_col'      => $this->seatCol,
            'customer_name' => $this->cusInfo['customer_name'],
            'movie_name'    => $this->movieInfo[0]['movie_name'],
            'hall_name'     => $this->hallInfo[0]['hall_name'],
            'order_id'      => $data['order_id']
        ];
    }

    //退票
    public function refundTicket($data)
    {
        $orderId = $data['order_id'];
        $res = Db::table('order')
        ->where('order_id', $orderId)
        ->update(['is_active' => -1]);

        return 0;
    }

}