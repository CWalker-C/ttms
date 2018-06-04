<?php
/**
 * Created by PhpStorm.
 * UserLogin: zero
 * Date: 18-5-27
 * Time: 下午4:08
 */

namespace app\admin\validate;

use think\Validate;

class AddMovie extends Validate
{
    protected $rule = [
        'movie_name'            => 'require',
        'movie_main_actor'      => 'require',
        'movie_director'        => 'require',
        'movie_duration'        => 'require|number',
        'movie_description'     => 'require'
    ];
}