<?php
/**
 * Created by PhpStorm.
 * User: zero
 * Date: 18-6-12
 * Time: 下午12:16
 */
namespace app\index\validate;

use think\Validate;

class FindMovieSche extends Validate
{
    protected $rule = [
        'movie_id'  => 'require|number'
    ];
}