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

    //查询票务状况
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

 //       var_dump($scheInfo);
        if (!$scheInfo) {   //电影未加入演出计划
            return -2;
        }
        $scheId = $scheInfo[0]['schedule_id'];
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
        $movieName = $data['movie_name'];
        $scheBegTime = $data['schedule_begin_time'];
        $this->seatRow = $data['seat_row'];
        $this->seatCol = $data['seat_col'];
        $this->cusInfo = session('user'); //顾客信息

        $this->movieInfo = Db::table('movie_info')
            ->where('movie_name', $movieName)
            ->select();
        if (!$this->movieInfo) {  //电影已下架
            return -1;
        }
//        var_dump($this->movieInfo);

        //影片安排信息
        $this->scheInfo = Db::table('schedule')
            ->where('movie_id', $this->movieInfo[0]['movie_id'])
            ->where('schedule_begin_time', date('Y-m-d H:i:s', $scheBegTime))
            ->select();
        if (!$this->scheInfo) {   //电影未上映
            return -2;
        }
//        var_dump($this->scheInfo);

        $this->hallInfo = Db::table('hall')
            ->where('hall_id', $this->scheInfo[0]['hall_id'])
            ->select();

        $disPrice = $this->scheInfo[0]['schedule_price'] * (1 - $this->cusInfo['class_id'] * 0.5);

//        var_dump($disPrice);
        $orderInfo = Ticket::where('customer_id', /*$this->cusInfo['customer_id']*/4)
            ->where('schedule_id', $this->scheInfo[0]['schedule_id'])
            ->where('seat_row', $this->seatRow)
            ->where('seat_col', $this->seatCol)
            ->where('is_active', '<>', -1)
            ->select();

        if (count($orderInfo) > 6) {  //用户买票数已达6个
            return 1;
        }
       /* for ($i = 0; $i < count($orderInfo); ++$i) {
            if ($orderInfo[$i]['is_active'] == 1) { //票已被购买
                return -3;
            }
            if ($orderInfo[$i]['is_active'] == 0) {
                if (time() - strtotime($orderInfo[$i]['order_date']) < 900) {
                    return -3;
                }
            }
        }*/
       var_dump($orderInfo);
//       if ($orderInfo) {
//           if ($orderInfo['is_active'] == 1) {  //票已被购买
//               return -3;
//           }
//           if ($orderInfo['is_active']   == 0) {
//               if (time() - strtotime($orderInfo['order_date']) < 900) {
//                   return -3;
//               } else {
//                   $this->orderInfo = [
//                       'customer_id'   => /*$this->cusInfo['customer_id']*/4,
//                       'schedule_id'   => $this->scheInfo['schedule_id'],
//                       'order_discount_price'=> $disPrice,
//                       'order_date'    => date('Y-m-d H:i:s', time()),
//                       'is_active'     => 0,
//                       'seat_row'      => $this->seatRow,
//                       'seat_col'      => $this->seatCol
//                   ];
//                   $res = Db::table('order')
//                       ->where('order_id', $orderInfo['order_id'])
//                       ->update($this->orderInfo);
//                   if ($res) {
//                       return $this->orderInfo;
//                   } else {
//                       return ;
//                   }
//               }
//           }
//       }
/*
        $orderRes = Db::table('order')
            ->insert($this->orderInfo);
        if ($orderRes) {
            return $this->orderInfo;
        } else {
            return ;
        }*/

    }

    //购票
    public function buyTicket($data)
    {
        $seatScheInfo = Db::table('order_seat')
            ->where('schedule_id', $this->scheId)
            ->where('seat_row', $this->seatRow)
            ->where('seat_col', $this->seatCol)
            ->select();
        if (!$seatScheInfo) {
            return -1;
        }
        $seatStatus = $seatScheInfo['is_active'];
        if ($seatStatus != 0) { //未按正常流程购票
            return -1;
        }
        $seatPayTime = $seatScheInfo['pay_time'];
        if (strtotime($seatPayTime) + 10 * 60 < time()) {   //付款超时
            $res1 = Db::table('order_seat')
                ->where('schedule_id', $this->scheInfo[0]['schedule_id'])
                ->where('seat_row', $this->seatRow)
                ->where('seat_col', $this->seatCol)
                ->setField('is_active', -1);
            $res2 = Db::table('order_seat')
                ->where('schedule_id', $this->scheInfo[0]['schedule_id'])
                ->where('seat_row', $this->seatRow)
                ->where('seat_col', $this->seatCol)
                ->select();
            $orderId = $res2['order_id'];
            $res3 = Db::table('order')
                ->where('order_id', $orderId)
                ->setField('is_active', -1);
            return -2;
        } else {
            $res2 = Db::table('order_seat')
                ->where('schedule_id', $this->scheInfo[0]['schedule_id'])
                ->where('seat_row', $this->seatRow)
                ->where('seat_col', $this->seatCol)
                ->update([
                    'is_active'     => 1,
                    'pay_time'      => date('Y-m-d H:i:s', time())
                ]);
            if ($res2) {    //购票成功
                return [
                    'movie_name'    => $this->movieInfo[0]['movie_name'],
                    'customer_name' => $this->cusInfo['customer_name'],
                    'hall_name'     => $this->hallInfo[0]['hall_name'],
                    'order_id'      => $this->orderInfo['order_id'],
                    'seat_row'      => $this->seatRow,
                    'seat_col'      => $this->seatCol,
                    'pay_time'      => time()
                ];
            } else {
                return -3;
            }
        }
    }
}