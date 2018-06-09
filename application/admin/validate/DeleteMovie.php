<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-8
 * Time: 上午9:45
 */
namespace app\admin\validate;

use think\Validate;

class DeleteMovie extends Validate
{
    protected $rule = [
        'movie_id'    => 'require|number'
    ];
}