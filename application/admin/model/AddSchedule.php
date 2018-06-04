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

class AddSchedule extends Model
{
    protected $table = 'schedule';

    public function index($data)
    {
//        var_dump($data);

        $beginTimeSch = $data['schedule_begin_time'];
        $movieName = $data['movie_name'];

        $movieInfo = Db::table('movie_info')
                    ->where('movie_name', $movieName)
                    ->find();

//        var_dump($movieInfo);

        if (!$movieInfo) {
            return -1;  //影片没有加入热映列表中
        }
        $movieId = $movieInfo['movie_id'];
        $endTimeSch = $beginTimeSch + $movieInfo['movie_duration'];

        $adminor = new AddSchedule;

//        $res = $adminor->field('schedule_begin_time', true)
//            ->where('hall_id', $data['hall_id'])
//            ->where('movie_id', $movieId)
//            ->select();

        $res = Db::table('schedule')
               ->field('schedule_begin_time')
               ->where('movie_id', $movieId)
               ->where('hall_id', $data['hall_id'])
               ->select();

//        var_dump($res);
//        var_dump(count($res));
        for ($i = 0; $i < count($res); ++$i) {
            foreach ($res[$i] as $key => $value) {
                $beginTimeSched = $value;
                $endTimeSched = $value + $movieInfo['movie_duration'];

                //安排时间段冲突
                if (($beginTimeSch > $beginTimeSched && $beginTimeSch < $endTimeSched)
                    || ($endTimeSch >= $beginTimeSched && $endTimeSch <= $endTimeSched)) {
                    return -1;
                }
            }
        }
        $adminor = new AddSchedule;
        if ($adminor->allowField(true)->save($data)) {  //添加安排记录成功
            return 1;
        } else {
            return -2;
        }

    }

}