<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/10
 * Time: 13:34
 */

namespace app\admin\validate;


use think\Validate;

class Login extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        '__token__'  =>  'require|max:32|token',
        'username'   =>  'require',
        'password'   =>  'require',
        'verify'     =>  'require',
    ];

    /**
     * 提示信息
     * @var array
     */
    protected $message  =   [
        'username.require'    => '用户名不能为空',
        'username.password'   => '密码不能为空',
        'username.verify'     => '验证码不能为空',
    ];

}