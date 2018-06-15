<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-6
 * Time: 上午11:01
 */

namespace app\index\validate;

use think\Validate;

class FindUserTicket extends Validate
{
    protected $rule = [
        'customer_id'     => 'require|number',
    ];
}
