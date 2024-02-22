<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1
 * Time: 16:12
 */

namespace app\admin\controller;


use think\Db;
use think\Loader;
use think\Request;

class Group extends Common
{
    /**
     * 管理员列表
     */
    public function index()
    {
        $group = Db::name('auth_group_rule')->select();

        return $this->fetch('',array(
            'list'=>$group,
        ));
    }

    /**
     * 添加管理员/管理员权限页面
     * 修改管理员/管理员权限页面
     */
    public function add()
    {
        $id = Request::instance()->param('id',0,'intval');

        //添加
        $rule = Db::name('AuthRule')->field('id,pid,name')->where(array('status' => 1))->order('sort ASC')->select();
        $group = null;

        //修改
        if($id){
            $group = Db::name('auth_group_rule')->where(array('id' => $id))->find();
            $group['rules'] = explode(',',$group['rules']);
        }

        return $this->fetch('', array(
            'rule' => $this->getMenu($rule),
            'group' => $group,
        ));
    }

    /**
     * 添加管理员/管理员权限
     * 修改管理员/管理员权限
     */
    public function update()
    {
        $group = Loader::model('Group');
        $re = $group->edit();

        if($re['status'] == 0){
            $this->error($re['msg']);
        }else {
            $this->success('操作成功');
        }
    }

    /**
     * 修改管理员状态
     */
    public function status()
    {
        $id = Request::instance()->param('id',0,'intval');

        if(!$id){
            $this->error('参数错误');
        }
        if ($id == 1) {
            $this->error('此用户组不可变更状态');
        }
        $row = Db::name('auth_group_rule')->where(array('id'=>$id))->find();
        $status = $row['status'];
        if(!$status){
            $this->error('没有此用户组');
        }
        if($status == 1){
            $re = Db::name('auth_group_rule')->where(array('id'=>$id))->update(array('status'=>2));
        }else{
            $re = Db::name('auth_group_rule')->where(array('id'=>$id))->update(array('status'=>1));
        }
        if($re === false){
            $this->error('状态更新失败');
        }

        add_log_admin('修改管理员状态：【'.$row['name'].'】');
        $this->success('状态更新成功');

    }

    /**
     * 删除管理员
     */
    public function del()
    {
        $ids = Request::instance()->post();

        if(!$ids){
            $this->error('参数错误');
        }
        if(!is_array($ids)){
            $this->error('参数错误');
        }
        foreach ($ids['ids'] as $k => $v) {
            $id[] = intval($v);
        }

        $id = implode(',', $id);

        $re = Db::name('auth_group_rule')->where('id IN ('.$id.')')->delete();
        if($re === false){
            $this->error('删除失败');
        }

        add_log_admin('删除管理员：【ID值:'.$id.'】');
        $this->success('删除成功');
    }

}