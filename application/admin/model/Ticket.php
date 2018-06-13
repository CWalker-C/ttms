<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-13
 * Time: 上午9:52
 */
namespace app\admin\model;

use think\Db;
use think\Model;

class Ticket extends Model
{
    //票房信息
    public function findBoxOffice()
    {
        $movieInfo = Db::table('movie_info')
            ->where('is_active', '<>', 0)
            ->where('is_active', '<>', -3)
            ->select();

        $data = array();
        $k = 0;
        for ($i = 0; $i < count($movieInfo); ++$i) {
            $movieId = $movieInfo[$i]['movie_id'];
            $scheInfo = Db::table('schedule')
                ->where('movie_id', $movieId)
                ->where('is_active', 1)
                ->select();
            $ticketCnt = 0;
            $moneyCnt = 0;
            $seatCnt = 0;
            for ($j = 0; $j < count($scheInfo); $j++) {
                $tickeInfo = Db::table('order')
                    ->where('schedule_id', $scheInfo[$j]['schedule_id'])
                    ->where('is_active', 1)
                    ->select();
                $ticketCnt += count($tickeInfo);
                for ($m = 0; $m < count($tickeInfo); ++$m) {
                    $moneyCnt += $tickeInfo[$m]['order_discount_price'];
                }
                $hallInfo = Db::table('hall')
                    ->where('hall_id', $scheInfo[$j]['hall_id'])
                    ->select();
                $seatCnt += $hallInfo[0]['hall_seats'];
            }
            $data[$k++] = [
                'movie_name'    => $movieInfo[$i]['movie_name'],
                'schedule_cnt'  => count($scheInfo),
                'ticket_cnt'    => $ticketCnt,
                'money_cnt'     => $moneyCnt,
                'seat_cnt'      => $seatCnt
            ];
        }
        return $data;
    }
}