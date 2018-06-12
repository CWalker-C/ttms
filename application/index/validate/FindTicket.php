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
        'schedule_id'   => 'require|number'
    ];
}