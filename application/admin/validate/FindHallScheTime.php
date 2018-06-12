<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-5
 * Time: 上午8:19
 */

namespace app\admin\validate;

use think\Validate;

class FindHallScheTime extends Validate
{
    protected $rule = [
        'hall_name' => 'require',
        'date_time' => 'require'
    ];
}
