<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-5-29
 * Time: 下午4:28
 */
namespace app\admin\validate;

use think\Validate;

class AddHall extends Validate
{
    protected $rule = [
        'hall_name'         => 'require',
        'hall_seats'        => 'require|number',
        'hall_description'  => 'require',
    ];
}