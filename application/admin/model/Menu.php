<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/27
 * Time: 16:58
 */

namespace app\admin\model;


use gmars\nestedsets\NestedSets;
use think\Loader;
use think\Model;
use think\Request;

class Menu extends Model
{
    //设置数据表名
    protected $name = 'auth_rule';
    //设置 查询返回数组的前置条件 ->select()->toArray()
    protected $resultSetType = 'collection';

    /**
     * 后台菜单添加
     * 后台菜单修改
     */
    public function adminUpdate()
    {
        try{
            if(!Request::instance()->isPost()){
                throw new \Exception('请求错误');
            }

            $data = Request::instance()->post();

            //验证数据
            $menu = Loader::validate('Menu');

            $nestedSets = new NestedSets($this,'left_key','right_key','pid','level','id');

            //执行操作
            if($data['id']){
                //修改
                //进行验证
                if(!$menu->scene('edit')->check($data))
                {
                    throw new \Exception($menu->getError());
                }
                //移动节点
                $this->startTrans();
                $pid = Menu::where(array('id'=>$data['id']))->value('pid');
                if($pid != $data['pid']){
                    $reN = $nestedSets->moveUnder($data['id'],$data['pid']);
                    if($reN == false){
                        $this->rollback();
                        throw new \Exception('移动节点失败');
                    }
                }
                //修改数据
                unset($data['__token__']);
                unset($data['pid']);
                $re = Menu::where(array())->update($data);
                if($re === false){
                    $this->rollback();
                    throw new \Exception('网络出现了小状况,请稍后再试');
                }
                $this->commit();
                add_log_admin('修改菜单：【'.$data['name'].'】');
            }else{
                //添加
                //进行验证
                if(!$menu->scene('add')->check($data))
                {
                    throw new \Exception($menu->getError());
                }
                //创建节点
                $data['sort'] = $data['sort'] ? $data['sort'] : rand(1,100);
                unset($data['id']);
                unset($data['__token__']);
                $re = $nestedSets->insert($data['pid'],$data);
                if($re === false){
                    throw new \Exception('网络出现了小状况,请稍后再试');
                }
                add_log_admin('新增菜单：【'.$data['name'].'】');
            }

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

    /**
     * 后台菜单删除
     */
    public function adminDel()
    {
        try {
            $ids = Request::instance()->param();

            if (!$ids) {
                throw new \Exception('参数错误');
            }

            $nestedSets = new NestedSets($this,'left_key','right_key','pid','level','id');

            $re = $nestedSets->delete($ids['ids']);

            if($re == false){
                throw new \Exception('删除菜单失败');
            }
            add_log_admin('删除菜单：【ID：'.$ids['ids'].'】');
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