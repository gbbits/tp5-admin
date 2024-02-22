<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27
 * Time: 17:29
 */

namespace app\admin\validate;


use think\Validate;

class Menu extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        '__token__'  =>  'require|max:32|token',
        'pid'   =>  'require|number',
        'name'   =>  'require',
        'url'   =>  'requireIf',

        'id'=>'require|number',
    ];

    /**
     * 提示信息
     * @var array
     */
    protected $message  =   [
        'pid.require'    => '请选择上级菜单',
        'pid.number'    => '小伙子不学好',
        'name.require'   => '菜单名称不能为空',
        'url.requireIf'    => '二级菜单必须填写链接',

        'id.require'=>'参数错误',
        'id.number'=>'参数错误',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'add'   =>  ['__token__','pid','name','url'],//添加
        'edit'  =>  ['__token__','pid','name','url','id'],//修改
    ];

    /**
     * 重写内置验证规则
     * 当为二级菜单的时候 验证链接是否为空
     * @param $value
     * @param $rule
     * @param $data
     * @return bool
     */
    protected function requireIf($value,$rule,$data)
    {
        if($data['pid'] > 0){
            if($value){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

}