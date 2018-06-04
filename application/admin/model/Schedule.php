<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-29
 * Time: 下午6:06
 */
namespace app\admin\model;

use think\Model;
use think\Db;

class Schedule extends Model
{
    protected $table = 'schedule';

    public function index($data)
    {
//        var_dump($data);

        $beginTimeSchStamp = $data['schedule_begin_time'];
        $movieName = $data['movie_name'];
        $hallName = $data['hall_name'];

        $movieInfo = Db::table('movie_info')
                    ->where('movie_name', $movieName)
                    ->find();
        $hallInfo = Db::table('hall')
                    ->where('hall_name', $data['hall_name'])
                    ->find();
        $hall_id = $hallInfo['hall_id'];

//        var_dump($movieInfo);

        if (!$movieInfo) {
            return -1;  //影片没有加入热映列表中
        }
        $movieId = $movieInfo['movie_id'];
        $endTimeSchStamp = $beginTimeSchStamp + $movieInfo['movie_duration'] * 60;

        if ($beginTimeSchStamp < time()) {   //时间过期
            return -2;
        }
        $data += ['hall_id' => $hall_id];
        $data += ['movie_id' => $movieId];
        $data['schedule_begin_time'] = date("Y-m-d H:i:s", $data['schedule_begin_time']);

        $res = Db::table('schedule')
               ->field('schedule_begin_time')
               ->where('hall_id', $data['hall_id'])
               ->select();

//        var_dump($res);
//        var_dump(count($res));
        for ($i = 0; $i < count($res); ++$i) {
            foreach ($res[$i] as $key => $value) {
                $beginTimeSched = $value;
                $beginTimeSchedStamp = strtotime($beginTimeSched);
                $endTimeSchedStamp = strtotime($beginTimeSched) + $movieInfo['movie_duration'] * 60;

                //安排时间段冲突
                if (($beginTimeSchStamp >= $beginTimeSchedStamp && $beginTimeSchStamp <= $endTimeSchedStamp)
                    || ($endTimeSchStamp >= $beginTimeSchedStamp && $endTimeSchStamp <= $endTimeSchedStamp)) {
                    return -1;
                }
            }
        }
        $adminor = new Schedule;
        if ($adminor->allowField(true)->save($data)) {  //添加安排记录成功
            return $data;
        } else {
            return -3;
        }
    }

}