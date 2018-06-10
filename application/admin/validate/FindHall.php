<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-11
 * Time: 上午12:02
 */
namespace app\admin\validate;

use think\Validate;

class FindHall extends Validate
{
    protected $rule = [
        'hall_id'   => 'require|number'
    ];
}