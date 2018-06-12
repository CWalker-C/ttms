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

    }

    //添加演出安排
    public function addSchedule($data)
    {
        //        var_dump($data);

        $beginTimeSchStamp = $data['schedule_begin_time'];
        $movieName = $data['movie_name'];
        $hallName = $data['hall_name'];

        $movieInfo = Db::table('movie_info')
            ->where('movie_name', $movieName)
            ->find();
        $hallInfo = Db::table('hall')
            ->where('hall_name', $hallName)
            ->find();
//        var_dump($movieInfo);

        if (!$movieInfo) {
            return -1;  //影片没有加入热映列表中
        }
        $hall_id = $hallInfo['hall_id'];
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
                    return -3;
                }
            }
        }
        $adminor = new Schedule;
        if ($adminor->allowField(true)->save($data)) {  //添加安排记录成功
            $scheId = Db::name('schedule')->getLastInsID();
            return array_merge($data, ['sche_id' => $scheId]);
        } else {
            return -4;
        }
    }

    //查询已安排的电影时间段(当天)
    public function findHallScheTime($data)
    {
        $hallId = $data['hall_id'];
        $dateTimeStamp = $data['date_time'];
        $res = Db::table('hall')
            ->where('hall_id', $hallId)
            ->select();

        //演出厅不存在
        if (!$res) {
            return -1;
        }
        $dayTime = date('Y-m-d', $dateTimeStamp);
        $expiryTime = date('Y-m-d', strtotime($dayTime) + 3600 * 24);;

        $hall_id = $res[0]['hall_id'];
        $res = Db::table('schedule')
            ->where('hall_id', $hall_id)
            ->whereTime('schedule_begin_time', 'between', [$dayTime, $expiryTime])
            ->order('schedule_begin_time')
            ->select();

        $k = 0;
        $movieSchedInfo = array();
        for ($i = 0; $i < count($res); ++$i) {
            $movieId = $res[$i]['movie_id'];
            $movieInfo = Db::table('movie_info')
                ->where('movie_id', $movieId)
                ->select();
            $movieName = $movieInfo[0]['movie_name'];
            $movieBeginTime = strtotime($res[$i]['schedule_begin_time']);
            $movieEndTime = strtotime($res[$i]['schedule_begin_time']) + $movieInfo[0]['movie_duration'];
            $movieSchedInfo[$k++] = [
                'movie_name'        => $movieName,
                'schedule_price'    => $res[$i]['schedule_price'],
                'movie_begin_time'  => $movieBeginTime,
                'movie_end_time'    => $movieEndTime,
                'hall_name'         => $data['hall_name']
            ];
        }

        return $movieSchedInfo;
    }

    //修改演出计划
    public function modifySchedule($scheInfo)
    {
        $beginTimeSchStamp = $scheInfo['schedule_begin_time'];
        $movieName = $scheInfo['movie_name'];
        $hallName = $scheInfo['hall_name'];
        $scheId = $scheInfo['schedule_id'];

        $movieInfo = Db::table('movie_info')
            ->where('movie_name', $movieName)
            ->find();
        $hallInfo = Db::table('hall')
            ->where('hall_name', $hallName)
            ->find();
//        var_dump($movieInfo);

        if (!$movieInfo) {
            return -1;  //影片没有加入热映列表中
        }
        $hall_id = $hallInfo['hall_id'];
        $movieId = $movieInfo['movie_id'];
        $endTimeSchStamp = $beginTimeSchStamp + $movieInfo['movie_duration'] * 60;

//        var_dump($beginTimeSchStamp);
//        var_dump(time());

        if ($beginTimeSchStamp < time()) {   //时间过期
            return -2;
        }
        $data = [
            'movie_id'              => $movieId,
            'hall_id'               => $hall_id,
            'schedule_price'        => $scheInfo['schedule_price'],
            'schedule_begin_time'   => date("Y-m-d H:i:s", $scheInfo['schedule_begin_time'])
        ];
        $res = Db::table('schedule')
            ->field('schedule_begin_time')
            ->where('hall_id', $data['hall_id'])
            ->where('schedule_id', '<>', $scheInfo['schedule_id'])
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
                    return -3;
                }
            }
        }

        $adminor = new Schedule;

        if ($adminor->allowField(true)->where('schedule_id', $scheId)->update($data) == 0) {  //添加安排记录成功
            return [
                'movie_name'    => $movieInfo['movie_name'],
                'hall_name'     => $hallInfo['hall_name'],
                'schedule_id'           => $scheInfo['schedule_id'],
                'schedule_begin_time'   => date("Y-m-d H:i:s", $scheInfo['schedule_begin_time']),
                'schedule_end_time'     => date("Y-m-d H:i:s", $endTimeSchStamp)
            ];
        } else {
            return -4;
        }
    }

    //删除演出计划信息
    public function deleteSche($data)
    {
        $res = Db::table('schedule')
            ->where('schedule_id', $data['schedule_id'])
            ->select();

        if (!$res) {
            return -1;
        }

        $res = Model::where('schedule_id', $data['schedule_id'])
            ->update(['is_active' => -3]);

        return $res;
    }

    //查询已安排的演出计划
    public function findSche()
    {
        $res = Db::table('schedule')
            ->whereTime('schedule_begin_time','>=', 'today')
            ->where('is_active', 1)
            ->select();
        $data = array();
        $k = 0;
        for ($i = 0; $i < count($res); ++$i) {
            $scheId = $res[$i]['schedule_id'];
            $movieId = $res[$i]['movie_id'];
            $hallId = $res[$i]['hall_id'];
            $movieInfo = Db::table('movie_info')
                ->where('movie_id', $movieId)
                ->select();
            $hallInfo = Db::table('hall')
                ->where('hall_id', $hallId)
                ->select();
//            var_dump($movieInfo);
//            var_dump($hallInfo);
            $data[$k++] = [
                'schedule_id'   => $scheId,
                'movie_name'    => $movieInfo[0]['movie_name'],
                'hall_name'     => $hallInfo[0]['hall_name'],
                'schedule_begin_time'   => $res[$i]['schedule_begin_time'],
                'schedule_price'        => $res[$i]['schedule_price'],
                'schedule_end_time'     => date('Y-m-d H:i:s',
                    strtotime($res[$i]['schedule_begin_time']) + $movieInfo[0]['movie_duration'] * 60)
            ];
        }

        return $data;
    }

}