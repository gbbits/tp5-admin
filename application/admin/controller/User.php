<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/5
 * Time: 13:21
 */

namespace app\admin\controller;


use think\Config;
use think\Db;
use think\Loader;
use think\Request;

class User extends Common
{
    /**
     * 管理员列表
     */
    public function index()
    {
        $list = Db::name('user_admin')
            ->alias('a')
            ->field('a.*,b.*,c.name')
            ->join(Config::get('database.prefix').'auth_user_group b','a.id = b.uid')
            ->join(Config::get('database.prefix').'auth_group_rule c','b.group_id = c.id')
            ->paginate(10);

        return $this->fetch('',array(
            'list'=>$list,
            'page'=>$list->render(),
        ));
    }

    /**
     * 新增管理员页面
     * 修改管理员页面
     */
    public function add()
    {
        $id = Request::instance()->param('id',0,'intval');

        $user = null;
        if($id){
            $user = Db::name('user_admin')
                ->alias('a')
                ->field('a.*,b.*')
                ->join(Config::get('database.prefix').'auth_user_group b','a.id = b.uid')
                ->where(array('id'=>$id))
                ->find();
        }

        $group = Db::name('auth_group_rule')->where(array('status'=>1))->select();
        return $this->fetch('',array(
            'usergroup'=>$group,
            'user'=>$user
        ));
    }

    /**
     * 新增管理员
     * 修改管理员
     */
    public function update()
    {
        $user = Loader::model('User');
        $re = $user->updateUser();

        if($re['status'] == 1){
            $this->success('操作成功！');
        }else{
            $this->error($re['msg']);
        }

    }

    /**
     * 删除管理员
     */
    public function del()
    {
        $ids = Request::instance()->param();

        if(!$ids){
            $this->error('参数错误');
        }

        if(Request::instance()->isPost()){
            $id = implode(',',$ids['ids']);
        }else{
            $id = $ids['id'];
        }

        Db::startTrans();
        $reU = Db::name('user_admin')->where('id IN ('.$id.')')->delete();
        if($reU === false){
            Db::rollback();
            $this->error('删除管理员失败');
        }

        $re = Db::name('auth_user_group')->where('uid IN ('.$id.')')->delete();
        if($re === false){
            Db::rollback();
            $this->error('删除管理员失败');
        }

        Db::commit();
        add_log_admin('删除管理员：【ID：'.$id.'】');
        $this->success('删除管理员成功');

    }

}