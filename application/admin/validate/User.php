<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/5
 * Time: 16:49
 */

namespace app\admin\validate;


use think\Validate;

class User extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        //添加
        '__token__'  =>  'require|max:32|token',
        'group_id'   =>  'require|number',
        'username'   =>  'require|alphaNum|unique:user_admin',
        'password'   =>  'require',
        'sex'   =>  'require|in:0,1,2',
        'tel'   =>  'require|number|length:11',
        'email'   =>  'require|email',

        //修改
        'id'=>'require|number',
    ];

    /**
     * 提示信息
     * @var array
     */
    protected $message  =   [
        //添加
        'group_id.require'   => '必须选择用户组',
        'group_id.number'   => '小伙子不学好',

        'username.require'    => '请输入用户名',
        'username.alphaNum'    => '用户名只能是数字和字母的组合',
        'username.unique'    => '已有该用户名',

        'password.require'    => '请输入密码',

        'sex.require'    => '请选择性别',
        'sex.in'    => '请不要乱选性别哦',

        'tel.require'    => '请输入电话号码',
        'tel.number'    => '请正确输入电话号码',
        'tel.length'    => '请正确输入电话号码',

        'email.require'    => '请输入邮箱',
        'email.email'    => '请正确输入邮箱',

        //修改
        'id.require'=>'参数错误',
        'id.number'=>'参数错误',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        //添加
        'add'   =>  ['__token__','group_id','username','password','sex','tel','email'],
        //修改
        'edit'  =>  ['__token__','group_id','username',           'sex','tel','email','id'],
    ];

}