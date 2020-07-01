<?php
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\SpecType;
use app\system\model\SpecValue;
use app\system\model\Category as CategoryModel;//商品分类
class Category extends Base
{
    //树形列表
    public function index(){
        if(request()->isajax()){
            $list=CategoryModel::field('id,name as title,f_id')->select()->toArray();
            $tree = $this->digui($list);
            $this->json_return('200','数据存在',$tree);
        }
        return view('');
    }
    //递归
    public function digui($data,$f_id=0){
        $arr = [];
        foreach ($data as $k=>$v)
        {
            if($v['f_id'] == $f_id){
                $v['children'] = $this->digui($data,$v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }
    //添加 分类 页面
    function addshow(){
        $f_id = input('f_id');$f_id = (int)$f_id;
        if($f_id==0){
            //添加 顶级 页面
            $this->assign('cate','一级');
            $this->assign('f_id',0);
            return $this->fetch();
        }else{
            $level = CategoryModel::where('id',$f_id)->value('level');
            if($level==3){
                $this->error('不能添加四级菜单');
            }
            //添加 二级 三级 页面
            switch ($level) {
                case 1:$this->assign('cate','二级');break;
                default:$this->assign('cate','三级');break;
            }
            $this->assign('f_id',$f_id);
            return $this->fetch();
        }
    }
    //保存添加的分类 //顶级 二级 三级
    function save(){
        if(request()->isajax()){
            //接收参数
            $f_id = input('f_id');$f_id = (int)$f_id;
            //获取 等级
            $level = CategoryModel::where('id',$f_id)->value('level');
            $name = input('name');
            //添加 二级
            if($level===1){
                $res = CategoryModel::insert(['name'=>$name,'f_id'=>$f_id,'level'=>2]);
            }
            //添加 三级
            if($level===2){
                $res = CategoryModel::insert(['name'=>$name,'f_id'=>$f_id,'level'=>3]);
            }
            //添加 顶级
            if($level==null){
                $res = CategoryModel::insert(['name'=>$name,'f_id'=>0,'level'=>1]);
            }
            //判断删除结果
            if($res){
                $this->json_return('0000','添加成功');
            }else{
                $this->json_return('0001','添加失败');
            }
        }else{echo "不是ajax";die;}
    }
    //删除 规格值
    function del_spec_value($id){
        $res_v=SpecValue::where('id',$id)->delete();
        if($res_v){}else{echo "删除 规格 值 失败";die;}
    }
    //删除 规格分类
    function del_spec_type($id){
        //获取 规格 值的ID
        $spec_value_id=SpecValue::where('f_id',$id)->column('id');//返回数组
        if($spec_value_id){
            //删除 规格值
            foreach ($spec_value_id as $key => $value) {
                $this->del_spec_value($value);
            }
        }else{}
        //删除 规格分类
        $res_type=SpecType::where('id',$id)->delete();
        if($res_type){}else{echo "删除 规格 分类 失败";die;}
    }
    //删除 三级
    function del_cate3($id){
        //获取 规格分类 ID
        $spec_type_id=SpecType::where('category_id',$id)->column('id');//返回数组
        if($spec_type_id){
            //删除 规格分类
            foreach ($spec_type_id as $key => $value) {
                $this->del_spec_type($value);
            }
        }else{}
        //删除 三级
        $res_3 = CategoryModel::where('id',$id)->delete();
        if($res_3){}else{echo "删除 三级 失败";die;}
    }
    //删除 二级
    function del_cate2($id){
        //获取 三级ID
        $third_id=CategoryModel::where('f_id',$id)->column('id');//返回数组
        if($third_id){
            foreach ($third_id as $key => $value) {
                //删除 三级
                $this->del_cate3($value);
            }
        }
        //删除 二级
        $res_2=CategoryModel::where('id',$id)->delete();
        if($res_2){}else{echo "删除 二级 失败";die;}
    }
    //删除 一级
    function del_cate1($id){
        //获取 二级ID
        $second_id=CategoryModel::where('f_id',$id)->column('id');//返回数组
        if($second_id){
            //删除 二级
            foreach ($second_id as $key => $value) {
                $this->del_cate2($value);
            }
        }
        //删除 一级
        $res_1=CategoryModel::where('id',$id)->delete();
        if($res_1){}else{echo "删除 一级 失败";die;}
    }
    //删除 商品分类
    function del(){
        $id = input('id');$id=(int)$id;//转换数据类型
        //查询 等级
        $level=CategoryModel::where('id',$id)->value('level');
        //判断 等级
        switch ($level) {
            case 3:$this->del_cate3($id);break;
            case 2:$this->del_cate2($id);break;
            case 1:$this->del_cate1($id);break;
        }
    }
    //修改 商品分类
    function update(){
        $id =input('id');
        $name =input('name');
        $res =CategoryModel::where("id=$id")->update(['name'=>$name]);
        if($res){
            $this->json_return('0000','修改成功');
        }else{
            $this->json_return('0001','修改失败');
        }
    }
}