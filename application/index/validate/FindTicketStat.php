<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-6
 * Time: 上午8:16
 */

namespace app\index\validate;

use think\Validate;

class FindTicketStat extends Validate
{
    protected $rule = [
        'order_num'   => 'require|number'
    ];
}