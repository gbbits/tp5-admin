<?php
namespace app\admin\controller;



use Think\Db;

class Index extends Common
{
    /**
     * 后台首页
     * @return string
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $list = Db::name('log_admin')->order('add_time DESC')->paginate(5);
        return $this->fetch('',array(
            'title'=>'仪表板',
            'twotitle'=>' 概况及统计',
            'list'=>$list,
            'page'=>$list->render(),
        ));
    }

    public function add()
    {
        $str = 'think|a:2:{s:3:"uid";s:60:"5e78bcd4904ee517eb31ca7059fc7efd04f71ff6c47bcc55068ab3d4be2e";s:9:"__token__";s:32:"1678ccb3b1eec95d767b663a2452110e";}';
        dump(mb_substr($str,6,mb_strlen($str),'utf-8'));
    }
}
