<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27
 * Time: 11:29
 */

namespace app\admin\controller;


use think\Db;
use think\Loader;
use think\Request;

class Menu extends Common
{
    /**
     * 后台菜单列表
     */
    public function adminList()
    {
        $list = Db::name('AuthRule')->order('sort ASC')->select();

        return $this->fetch('',array(
            'list'=>$this->getMenu($list),
        ));
    }

    /**
     * 添加后台菜单页面
     * 修改后台菜单页面
     */
    public function adminMenu()
    {
        $id = Request::instance()->param('id',0,'intval');

        //显示已有菜单
        $menu = Db::name('AuthRule')->where(array('status' => 1))->order('sort ASC')->select();
        $currentmenu = null;

        //查询需要修改的数据
        if($id){
            $currentmenu = Db::name('AuthRule')->where(array('id' => $id))->find();
        }

        return $this->fetch('adminMenu', array(
            'menuList' => $this->getMenu($menu),
            'currentmenu' => $currentmenu,
        ));
    }

    /**
     * 后台菜单添加
     * 后台菜单修改
     */
    public function adminUpdate()
    {
        $menu = Loader::model('Menu');
        $re = $menu->adminUpdate();

        if($re['status'] == 0){
            $this->error($re['msg']);
        }else {
            $this->success('操作成功！');
        }
    }

    /**
     * 后台菜单删除
     */
    public function adminDel()
    {
        $menu = Loader::model('Menu');
        $re = $menu->adminDel();

        if($re['status'] == 0){
            $this->error($re['msg']);
        }else {
            $this->success('操作成功！');
        }

    }

    /**
     * 前台菜单列表
     */
    public function homeList()
    {

    }

    /**
     * 添加前台菜单页面
     * 修改前台菜单页面
     */
    public function homeMenu()
    {
//        $menu = Db::name('AuthRule')->where(array('status'=>1))->order('sort ASC')->select();
//        return $this->fetch('adminMenu',array(
//            'title'=>'菜单管理',
//            'twotitle'=>' 新增后台菜单',
//            'menu'=>$this->getMenu($menu),
//            'currentmenu'=>null,
//        ));
    }

    /**
     * 前台菜单添加
     * 前台菜单修改
     */
    public function homeUpdate()
    {
//        $menu = Loader::model('Menu');
//        $re = $menu->adminUpdate();
//
//        if($re['status'] == 0){
//            $this->error($re['msg']);
//        }else {
//            $this->success('操作成功！');
//        }
    }


}