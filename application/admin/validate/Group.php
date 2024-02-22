<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 14:52
 */

namespace app\admin\validate;


use think\Validate;

class Group extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        '__token__'  =>  'require|max:32|token',
        'name'   =>  'require',
        'rules'   =>  'require',

        'id'=>'require|number',
    ];

    /**
     * 提示信息
     * @var array
     */
    protected $message  =   [
        'name.require'   => '用户名称不能为空',
        'rules.require'    => '请选择权限',

        'id.require'=>'参数错误',
        'id.number'=>'参数错误',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'add'   =>  ['__token__','name','rules'],//添加
        'edit'  =>  ['__token__','name','rules','id'],//修改
    ];
}