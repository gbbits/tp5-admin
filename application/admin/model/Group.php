<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1
 * Time: 17:32
 */

namespace app\admin\model;


use think\Loader;
use think\Model;
use think\Request;

class Group extends Model
{
    //设置数据表名
    protected $name = 'auth_group_rule';
    //设置 查询返回数组的前置条件 ->select()->toArray()
    protected $resultSetType = 'collection';

    /**
     * 添加管理员/管理员权限
     * 修改管理员/管理员权限
     */
    public function edit()
    {
        try{
            if(!Request::instance()->isPost()){
                throw new \Exception('请求错误');
            }

            $data = Request::instance()->post();

            $group = Loader::validate('Group');

            //修改
            $re = null;
            if($data['id']){
                //验证数据
                if(!$group->scene('edit')->check($data)){
                    throw new \Exception($group->getError());
                }
                unset($data['__token__']);
                $data['rules'] = implode(',',$data['rules']);
                $re = Group::allowField(true)->update($data);

                add_log_admin('修改管理员：【'.$data['name'].'】管理权限：【'.$data['rules'].'】');
            }else{
                //添加
                //验证数据
                if(!$group->scene('add')->check($data)){
                    throw new \Exception($group->getError());
                }
                unset($data['id']);
                $data['rules'] = implode(',',$data['rules']);
                $re = Group::allowField(true)->save($data);

                add_log_admin('添加管理员：【'.$data['name'].'】管理权限：【'.$data['rules'].'】');
            }

            if($re === false){
                throw new \Exception('添加管理员/管理权限失败');
            }

        }catch (\Exception $e){

            return array(
                'status'=>0,
                'msg'=>$e->getMessage()
            );

        }

        return array(
            'status'=>1
        );

    }

}