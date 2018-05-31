<?php
/**
 * Created by PhpStorm.
 * User: zero
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


        $beginTime = $data['schedule_begin_time'];
        $movieName = $data['movie_name'];

        $movieInfo = Db::table('movie_info')
                    ->where('movie_name', $movieName)
                    ->find();

        var_dump($movieInfo);

        if ($movieInfo) {
            return -1;  //影片没有加入热映列表中
        }
        $movieId = $movieInfo['movie_id'];
        $endTime = $beginTime + $movieInfo['movie_duration'];

        $adminor = new AddSchedule;

        $res = $adminor->field('schedule_begin_time', true)
            ->where('hall_id', $data['hall_id'])
            ->where('movie_id', $movieId)
            ->select();

        var_dump($res);

        foreach ($res as $key => $value) {

        }

    }

        /*$schedInfo = Db::table('schedule')
                    ->where('hall_id', $data['hall_id'])
                    ->where('movie_id', $movieId)
                    ->select();*/

}