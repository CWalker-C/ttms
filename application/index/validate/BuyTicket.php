<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-6
 * Time: 上午11:01
 */

namespace app\index\validate;

use think\Validate;

class BuyTicket extends Validate
{
    protected $rule = [
        'order_id'            => 'require',
        'is_paid'             => 'require|number'
    ];
}