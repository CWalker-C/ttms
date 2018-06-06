<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-6
 * Time: 上午8:16
 */

namespace app\index\validate;

use think\Validate;

class FindTicket extends Validate
{
    protected $rule = [
        'movie_name' => 'require',
        'schedule_begin_time'   => 'require|number'
    ];
}