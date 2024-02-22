<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/5
 * Time: 16:42
 */

namespace app\admin\model;

use think\Config;
use think\Db;
use think\Loader;
use think\Model;
use think\Request;

class User extends Model
{
    //设置数据表名
    protected $name = 'user_admin';
    //设置 查询返回数组的前置条件 ->select()->toArray()
    protected $resultSetType = 'collection';

    /**
     * 新增管理员
     * 修改管理员
     */
    public function updateUser()
    {
        try{
            if(!Request::instance()->isPost()){
                throw new \Exception('请求错误');
            }

            $data = Request::instance()->post();
            $photo = Request::instance()->file('photo');

            $user = Loader::validate('User');

            if($data['id']){

                if(!$user->scene('edit')->check($data)){
                    throw new \Exception($user->getError());
                }

                if($data['password']){
                    $saltValue = get_rand_srt(6,4);
                    $data['salt_value'] = $saltValue;
                    $data['password'] = md5(md5($data['password']).md5($saltValue));
                }else{
                    unset($data['password']);
                }

                if($photo){
                    $info = $photo->validate(['size'=>Config::get('upload_img_size'),'ext'=>Config::get('upload_img_ext')])->move(ROOT_PATH . 'public' . DS .Config::get('upload_img_path').'headImg');
                    if($info){
                        // 成功上传后 获取上传信息
                        $data['photo'] = Config::get('upload_img_path').'headImg'.DS.$info->getSaveName();
                    }else{
                        // 上传失败获取错误信息
                        throw new \Exception($photo->getError());
                    }
                }

                unset($data['__token__']);
                $group_id = $data['group_id'];
                unset($data['group_id']);

                $this->startTrans();
                $reU = User::update($data);
                if($reU === false){
                    $this->rollback();
                    throw new \Exception('修改管理员失败');
                }

                $re = Db::name('auth_user_group')->where(array('uid'=>$data['id']))->update(array('group_id'=>$group_id));
                if($re === false){
                    $this->rollback();
                    throw new \Exception('修改管理员失败');
                }
                add_log_admin('修改管理员：【'.$data['username'].'】');
            }else{

                if(!$user->scene('add')->check($data)){
                    throw new \Exception($user->getError());
                }

                //加盐加密
                $saltValue = get_rand_srt(6,4);
                $data['salt_value'] = $saltValue;
                $data['password'] = md5(md5($data['password']).md5($saltValue));

                if(!$photo){
                    throw new \Exception('请选择上传图片');
                }
                $info = $photo->validate(['size'=>Config::get('upload_img_size'),'ext'=>Config::get('upload_img_ext')])->move(ROOT_PATH . 'public' . DS .Config::get('upload_img_path').'headImg');
                if($info){
                    // 成功上传后 获取上传信息
                    $data['photo'] = Config::get('upload_img_path').'headImg'.DS.$info->getSaveName();
                }else{
                    // 上传失败获取错误信息
                    throw new \Exception($photo->getError());
                }

                unset($data['id']);
                $data['add_time'] = time();

                $this->startTrans();
                $reU = User::allowField(true)->save($data);
                if($reU === false){
                    $this->rollback();
                    throw new \Exception('新增管理员失败');
                }

                $re = Db::name('auth_user_group')->insert(array('uid'=>$this->id,'group_id'=>$data['group_id']));
                if($re === false){
                    $this->rollback();
                    throw new \Exception('新增管理员失败');
                }
                add_log_admin('添加管理员：【'.$data['username'].'】');
            }
            $this->commit();

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