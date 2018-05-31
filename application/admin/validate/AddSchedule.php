<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-5-29
 * Time: 下午4:16
 */
namespace app\admin\validate;

use think\Validate;

class AddSchedule extends Validate
{
    protected $rule = [
        'movie_name'            => 'require',
        'hall_id'               => 'require|number',
        'schedule_time'         => 'require',
        'schedule_price'        => 'require',
        'schedule_begin_time'   => 'require|date',
    ];
}