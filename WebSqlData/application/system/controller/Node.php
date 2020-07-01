<?php
namespace app\system\controller;
use app\system\controller\Base;

use think\facade\Request;
use think\facade\App;

use app\system\model\Users;
use app\system\model\Node as dao;
use app\system\model\Role;
use app\system\model\RoleNode;
class Node extends Base
{
    //删除权限
    public function del()
    {
        $id = input('id');
        $node = new dao();
        $res = dao::where('id',$id)->delete();     
        if($res){
            $this->json_return('0000','删除成功');
        }else{
            $this->json_return('0001','删除失败');
        }
    }
    //添加权限 页面 保存
    public function add()
    {
        if(request()->isajax()){
            $data = input('post.');
            $node = new dao();
            $res = $node->insert($data);
            if($res){
                $this->json_return('0000','添加成功');
            }else{
                $this->json_return('0001','添加失败');
            }
        }
        //不是ajax，则进入添加页面
        $nodes = dao::all();
        $this->assign('nodes',$nodes);

    	$res=dirname(__FILE__);  //获取当前文件的绝对路径
    	$arr=scandir($res);//获取所有的文件
    	//获取 文件名
    	$newarr=[];
    	foreach ($arr as $key=>$v)
        {
    		$newarr[]=pathinfo($v,PATHINFO_FILENAME);
    	}
    	//删除 当前路径 和 上级路径
    	unset($newarr[0],$newarr[1]);
    	$this->assign('arrs',$newarr); //传值   查询的控制器
        return view();
    }
    //获取 控制器中的所有操作
    public function get_fun(){
    	$class_name=input('post.key');
    	$class_name="app\system\controller\\".$class_name;
    	$c=new $class_name();//Request::instance(),App::instance()
        $arr3=[];
    	$arr1=get_class_methods($c);
    	if($parent_class=get_parent_class($c)){
    		//获取父类的方法
    		$arr2=get_class_methods($parent_class);
    		//删除父类的方法
    		$arr3=array_diff($arr1,$arr2);
    	}else{
    		//没有父类当前类方法 就是 所有的方法
    		$arr3=$arr1;
    	}
    	echo $this->json_return('0000','获取成功',$arr3);
    }
    //权限列表展示
    public function lists(){
        if(request()->isajax()){
            $pagesize = input('limit');
            $res = dao::paginate($pagesize);
            $this->json_return('0000','获取成功',$res);
        }
        return view();
    }
    //分配权限
    public function node_fp()
    {
        //获取全部角色
        $role = Role::all();
        $this->assign('role',$role);
        
        //获取全部权限
        $node = dao::all()->toArray();
        
        $data = [];
        foreach ($node as $k=>$v)
        {
            if($v['f_id'] == 0){
                $v['lists'] = [];
                $data[$v['id']] = $v;
            }
        }
        foreach ($node as $k=>$v)
        {
            if($v['f_id'] != 0){
                $f_id = $this->dg($node,$v);
                if($f_id != 0){
                    $data[$f_id]['lists'][] = $v;
                }
            }
        }
        $this->assign('node',$data);        
        $role_node = RoleNode::where('role_id',$role[0]['id'])->select();
        $nodes = [];
        foreach ($role_node as $k=>$v)
        {
            $nodes[] = $v['node_id'];
        }
        $this->assign('nodes',$nodes);
        return view();
    }
    //获取 权限的复选框
    public function node_checkbox()
    {
        //获取角色id
        $role_id = input('role_id');
        //获取全部权限
        $node = dao::all()->toArray();
        $data = [];
        foreach ($node as $k=>$v)
        {
            if($v['f_id'] == 0){
                $v['lists'] = [];
                $data[$v['id']] = $v;
            }
        }
        foreach ($node as $k=>$v)
        {
            if($v['f_id'] != 0){
                $f_id = $this->dg($node,$v);
                if($f_id != 0){
                    $data[$f_id]['lists'][] = $v;
                }
            }
        }
        $this->assign('node',$data);
        $role_node = RoleNode::where('role_id',$role_id)->select();
        $nodes = [];
        foreach ($role_node as $k=>$v)
        {
            $nodes[] = $v['node_id'];
        }
        $this->assign('nodes',$nodes);
        return view();
    }
    //保存权限与角色的关系
    public function node_save()
    {
        $data = input('get.');
        RoleNode::where('role_id',$data['role_id'])->delete();
        foreach ($data['nodes'] as $k=>$v)
        {
            $node = dao::get($v);
            $d = [
                'role_id'=>$data['role_id'],
                'node_id'=>$v,
                'controller'=>$node['controller'],
                'action'=>$node['action']
            ];
            RoleNode::insert($d);
        }
        $this->success('分配成功',url('lists'));
    }
    //查找最上级分类的 id
    public function dg($node,$value)
    {   
        foreach ($node as $k=>$v)
        {
            if($value['f_id'] == $v['id'] && $v['f_id'] == 0){
                return $v['id'];
            }
            if($value['f_id'] == $v['id']){
                return $this->dg($node, $v);
            }
        }
        return 0;   
    }
}