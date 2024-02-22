<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/10
 * Time: 12:10
 */

namespace app\admin\model;


use think\captcha\Captcha;
use think\Config;
use think\Cookie;
use think\Loader;
use think\Model;
use think\Request;
use think\Session;

class Login extends Model
{
    //设置数据表名
    protected $name = 'user_admin';
    //设置 查询返回数组的前置条件 ->select()->toArray()
    protected $resultSetType = 'collection';

    /**
     * 登录
     */
    public function login()
    {
        $data = Request::instance()->post();

        try{
            //字段验证
            $validate = Loader::validate('Login');
            if(!$validate->check($data)){
                throw new \Exception($validate->getError());
            }

            //验证码验证
            $captcha = new Captcha();
            if (!$captcha->check($data['verify'])) {
                throw new \Exception("验证码错误!");
            }

            //查询用户名
            $row = Login::where(array('username'=>$data['username']))->find()->toArray();

            if(!$row){
                throw new \Exception("未找到这个用户!");
            }

            if($row['password'] != md5(md5($data['password']).md5($row['salt_value']))){
                throw new \Exception("密码错误!");
            }

            $idKey = auto_code($row['id'],'ENCODE');

            Session::set('uid',$idKey);

            if(isset($data['remember'])){
                Cookie::set('auth',$idKey,3600 * 24 * 7);
            }else{
                Cookie::set('auth',$idKey);
            }
            add_log_admin('手动登录：【'.$row['username'].'】');
        }catch (\Exception $e){
            return array(
                "status"=>0,
                "msg"=>$e->getMessage()
            );
        }

        return array(
            "status"=>1,
        );

    }

}