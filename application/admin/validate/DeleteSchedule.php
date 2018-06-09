<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-9
 * Time: 上午9:22
 */
namespace app\admin\validate;

use think\Validate;

class DeleteSchedule extends Validate
{
    protected $rule = [
        'schedule_id'   => 'require|number'
    ];
}

