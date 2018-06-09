<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-9
 * Time: 上午8:23
 */
namespace app\admin\validate;

use think\Validate;

class DeleteHall extends Validate
{
    protected $rule = [
        'hall_id'   => 'require|number'
    ];
}