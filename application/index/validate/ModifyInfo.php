<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-13
 * Time: 下午12:06
 */
namespace app\index\validate;

use think\Validate;

class ModifyInfo extends Validate
{
    protected $rule = [
        'customer_name'         => 'require',
        'customer_mobile'       => 'require|mobile',
        'customer_id'        => 'require|number',
        'customer_passwd'       => 'require|length:6,16'
    ];
}