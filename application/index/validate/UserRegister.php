<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-4
 * Time: 上午9:09
 */
namespace app\index\validate;

use think\Validate;

class UserRegister extends Validate
{
    protected $rule = [
        'customer_name'         => 'require',
        'customer_mobile'       => 'require|mobile',
        'customer_email'        => 'require|email',
        'customer_passwd'       => 'require|length:6,16'
    ];
}