<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/16
 * Time: 15:40
 */
namespace app\admin\behavior;

use think\Config;
use think\Db;
use think\Request;
use think\Session;

class Rbac
{
    public function run(&$params)
    {
        // 行为逻辑
        $this->autoRbac();
    }

    /**
     * 权限验证
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function autoRbac()
    {
        $uid = Session::get('uid');
        if(!$uid){
            return true;
        }

        $request = Request::instance();
        $prefix = Config::get('database.prefix');

        /**
         * 不需要登录的控制器/方法
         */
        if (in_array($request->controller().'/'.$request->action(), Config::get('no_login'))) {
            return true;
        }

        /**
         * 查询当前用户的所有权限
         */
        $uid = auto_code($uid);
        $rule = Db::name('auth_user_group')->alias('a')
            ->field('b.rules')
            ->join($prefix.'auth_group_rule b','b.id = a.group_id')
            ->where(array('b.status'=>1,'a.uid'=>$uid))
            ->find();

        if(!$rule){
            exit('不存在的管理员');
        }

        /**
         * 进行权限匹配
         */
        $data = Db::name('auth_rule')
            ->field('url')
            ->where(array('url'=>$request->controller() . '/' . $request->action(),'id'=>array('in',$rule['rules'])))
            ->find();

        if(!$data){
            exit('你没有权限');
        }

        return true;

    }

}