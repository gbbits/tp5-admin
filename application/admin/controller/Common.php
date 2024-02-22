<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/10
 * Time: 10:16
 */

namespace app\admin\controller;


use think\captcha\Captcha;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Request;
use think\Session;

class Common extends Controller
{
    public function _initialize()
    {
        /**
         * 获取当前控制器和方法 并进行查询
         */
        $prefix = Config::get('database.prefix');
        $request = Request::instance();

        $url = Db::name('auth_rule')->alias('a')
            ->field('a.id,a.name,a.url,a.tips,a.pid,b.pid as up_pid,b.name as up_name')
            ->join($prefix.'auth_rule b','b.id = a.pid','LEFT')
            ->where(array('a.url'=>$request->controller() . '/' . $request->action()))
            ->find();

        $this->assign('url',$url);

        /**
         * 不需要登录的控制器/方法
         */
        if (in_array($request->controller().'/'.$request->action(), Config::get('no_login'))) {
            return true;
        }

        /**
         * 是否登录
         */
        if(!$this->isLogin()){
            $this->error('请先登录',url('Login/index'));
        }

        $uid = auto_code(Session::get('uid'));
        /**
         * 菜单显示控制
         */
        $user = Db::name('user_admin')->where('id',$uid)->find();

        $userinfo = Db::name('auth_group_rule')
            ->alias('a')
            ->field('a.*,b.*')
            ->join('auth_user_group b','a.id = b.uid')
            ->where(array('a.id'=>$uid))
            ->find();

        $menu_where = '';
        if ($userinfo['group_id'] != 1) {
            $menu_where = "AND id in ({$userinfo['rules']})";
        }

        /**
         * 菜单
         */
        $redis = newRedis();
        $keys = $redis->hKeys('menu_admin'.$uid);
        if(!$keys) {
            $menu = Db::name('auth_rule')->field('id,name,pid,url,icon')->where("status = 1 {$menu_where}")->order('sort ASC')->select();
            foreach ($menu as $k => $v) {
                $redis->hSet('menu_admin'.$uid, $k, serialize($v));
            }
            $redis->expire('menu_admin'.$uid,3600*24);//设置过期时间 1天
        }else{
            foreach ($keys as $key=>$value){
                $menu[] = unserialize($redis->hGet('menu_admin'.$uid,$key));
            }
        }

        $menu = $this->getMenu($menu);


        $this->assign(array(
            'user'=>$user,
            'menu'=>$menu,
        ));
    }

    /**
     * 无限级分类整合
     * @param array $array 数据库查询的数据
     * @param string $name 子节点名称
     * @param int $pid 父id
     * @return array 多维数组
     */
    protected function getMenu($array = array(),$name = 'children',$pid = 0)
    {
        $tree = array();
        foreach($array as $v){
            if( $v['pid'] == $pid){
                $v[$name] = $this->getMenu($array, $name, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;

    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function captcha()
    {
        $config =    [
            // 验证码字体大小(px)
            'fontSize'    =>    50,
            // 是否画混淆曲线
            'useCurve'    =>    true,
            // 是否添加杂点
            'useNoise'    =>    true,
            // 验证码位数
            'length'      =>    3,
            // 验证码的字体是随机使用扩展包内 think-captcha/assets/ttfs目录下面的字体文件
            'fontttf'     =>    '5.ttf',
            // 验证成功后是否重置
            'reset'       =>    true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    /**
     * 验证用户是否登录
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function isLogin()
    {
        $is = false;
        $auth = Cookie::get('auth');
        $uid = Session::get('uid');
        if ($uid) {
            $user = Db::name('user_admin')->where(array('id' => auto_code($uid)))->find();

            if ($user) {
                if ($auth ==  $uid) {
                    $is = true;
                }
            }
        }
        elseif ($auth){

            $user = Db::name('user_admin')->where(array('id' => auto_code($auth)))->find();
            if ($user) {
                $idKey = auto_code($user['id'],'ENCODE');
                Session::set('uid',$idKey);

                Cookie::set('auth',$idKey);

                add_log_admin('自动登录：【'.$user['username'].'】');
                $is = true;

            }
        }
        return $is;
    }




}