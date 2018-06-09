<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-8
 * Time: 上午10:06
 */
namespace app\admin\validate;

use think\Validate;

class FindMovie extends Validate
{
    protected $rule = [
        'option'    => 'require|number'
    ];
}