<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-6
 * Time: 上午11:01
 */

namespace app\index\validate;

use think\Validate;

class InPayment extends Validate
{
    protected $rule = [
        'movie_name'            => 'require',
        'customer_email'        => 'require|email',
        'schedule_begin_time'   => 'require|number',
        'seat_row'              => 'require|number',
        'seat_col'              => 'require|number'
    ];
}