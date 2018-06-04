<?php
namespace app\index\validate;

use think\Validate;

class UserLogin extends Validate
{
    protected $rule = [
//        'customer_name'  => 'require|^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"',
        'customer_email'    => 'require|email',
        'customer_passwd'  => 'require|length:6,16'
    ];
}
