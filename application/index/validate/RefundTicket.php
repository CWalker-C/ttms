<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-13
 * Time: 上午12:24
 */
namespace app\index\validate;

use think\Validate;

class RefundTicket extends Validate
{
    protected $rule = [
        'order_id'  => 'require|number'
    ];
}