<?php
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\Category;//<!-- 规格是根据三级分类确定的 -->
use app\system\model\SpecType;
use app\system\model\SpecValue;
class Spec extends Base//删除和修改未完成
{
    //删除 规格
    function spec_del(){
        //接收 规格ID
        $id = input('c_id');
        if(!$id){
            return json(['code'=>404,'msg'=>'数据有误']);
        }
        $id=(int)$id;//转换数据类型
        //获取 规格值的ID
        $value_id = SpecValue::where('f_id',$id)->select()->toArray();
        //if(!$value_id){return json(['code'=>404,'msg'=>'没有 规格值 数据']);}
        //删除 规格值
        $del_value = SpecValue::where('id','in',$value_id)->delete();//delete 方法返回影响数据的条数，没有删除返回 int 0
        /*if(!$del_value){
            return json(['code'=>404,'msg'=>'删除 规格值(条件是空数组) 失败']);
        }*/
        //删除 规格
        $del_spec = SpecType::where('id',$id)->delete();
        if($del_spec){
            return json(['code'=>404,'msg'=>'删除成功']);
        }else{
            return json(['code'=>404,'msg'=>'删除失败']);
        }
    }
    //规格 展示
    function spec_show(){
        $id=input('id');$id=(int)$id;
        $spec=SpecType::where('category_id',$id)->select()->toArray();
        foreach ($spec as $key => $value) {
            $spec[$key]['child']=SpecValue::where('f_id',$value['id'])->select()->toArray();
        }
        if($spec){
            return json(['code'=>200,'speclist'=>$spec]);
        }
        return json(['code'=>404,'msg'=>'no']);
    }
    //添加 新规格
    function add_new_spec(){
        $new=input('newspec');
        $category_id=input('category_id');
        $category_id=(int)$category_id;
        if(!$category_id){
            return json(['code'=>404,'msg'=>'添加新规格失败']);
        }
        $data=['name'=>$new,'category_id'=>$category_id];
        $res=SpecType::insert($data);
        if($res){
            return json(['code'=>200,'msg'=>'添加新规格成功']);
        }else{
            return json(['code'=>404,'msg'=>'添加新规格失败']);
        }
    }
    //添加 新的 规格值
    function add_new_spec_value(){
        $new=input('newspec');
        $f_id=input('f_id');$f_id=(int)$f_id;
        $data=['name'=>$new,'f_id'=>$f_id];
        $res=SpecValue::insert($data);
        if($res){
            return json(['code'=>200,'msg'=>'添加新的规格值成功']);
        }else{
            return json(['code'=>404,'msg'=>'添加新的规格值失败']);
        }
    }
    //展示 一级分类
    public function index(){
        $cate_one=Category::where('level',1)->select();
        return view('',['cate_one'=>$cate_one]);
    }
    //展示 二级分类
    function get_cate2(){
        $id=input('id');$id=(int)$id;
        $cate_two=Category::where('f_id',$id)->select()->toArray();
        if($cate_two){
            return json(['code'=>200,'list'=>$cate_two]);
        }
        return json(['code'=>404]);
    }
    //展示 三级分类
    function get_cate3(){
        $id=input('id');
        $id=(int)$id;
        $cate_three=Category::where('f_id',$id)->select()->toArray();
        if($cate_three){
            return json(['code'=>200,'list'=>$cate_three]);
        }
        return ['code'=>404];
    }
}