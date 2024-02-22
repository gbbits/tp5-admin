<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/10
 * Time: 10:17
 */

namespace app\admin\controller;


use think\Cookie;
use think\Loader;
use think\Session;
use think\Url;

class Login extends Common
{

    /**
     * 后台登录页面
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {

        $isLogin = $this->isLogin();

        if ($isLogin) {
            $this->success('您已经登录,正在跳转到主页', url("Index/index"));
        }

        return $this->fetch('',array(
            "title"=>'登录'
        ));
    }

    /**
     * 后台登录
     */
    public function login()
    {
        $login = Loader::model('Login');
        $re = $login->login();

        if($re['status'] == 0){
            $this->error($re['msg']);
        }
        else{
            $url = url('Index/index');
            header("Location: $url");
            exit;
        }

    }

    public function logout()
    {
        Session::delete('uid');
        Cookie::delete('auth');
        $this->success('退出登录成功',Url::build('Login/index'));
    }



}