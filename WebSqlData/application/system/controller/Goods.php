<?php
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\Goods as GoodsModel;
use app\system\model\GoodsImg;
use app\system\model\Category;
use app\system\model\SpecType;
use app\system\model\SpecValue;
class Goods extends Base
{
  /*获取 规格*/
  function get_spec(){
    $cate=input('type_id');
    $spec_type=SpecType::where('category_id',$cate)->select();
    if(!$spec_type){
      return json(['code'=>300,'msg'=>'no null']);
    }
    $ids=[];
    foreach ($spec_type as $key => $value) {
      $ids[]=$value['id'];
    }
    $spec_val=SpecValue::where('f_id','in',$ids)->select();
    if(!$spec_val){
      return json(['code'=>300,'msg'=>'no null']);
    }
    return json(['code'=>200,'type'=>$spec_type,'val'=>$spec_val]);
  }
  //获取 商品二级分类
  function get_cate2(){
    $cate=input('type_id');
    $list=Category::where('f_id',$cate)->select();
    if ($list) {
      return json(['code'=>200,'list'=>$list]);
    }else{
      return json(['code'=>300,',msg'=>'no null']);
    }
  }
  //获取 商品三级分类
  function get_cate3(){
    $cate=input('type_id');
    $list=Category::where('f_id',$cate)->select();
    if ($list) {
      return json(['code'=>200,'list'=>$list]);
    }else{
      return json(['code'=>300,',msg'=>'no null']);
    }
  }
  //添加 页面
  function add(){
    $list=Category::where('level',1)->select();
    $this->assign('list',$list);
    return view();
  }
  //入库 保存
  function addmsg(){
    if(request()->isajax()){
      $data = input('post.');
      print_r($data);die;
      /*$data['store_num']=(int)$data['store_num'];
      $data['market_price']=(float)$data['market_price'];
      $data['shop_price']=(float)$data['shop_price'];
      $data['cost_price']=(float)$data['cost_price'];*/
      $imgs=$data['imgs'];
      unset($data['imgs']);
      /*通用信息*/
      $add_res = GoodsModel::insertGetId($data);
      if (!$add_res) {return json(['code'=>404,'msg'=>'通用信息添加失败']);
      }
      /*相册*/
      foreach ($imgs as $k=>$v){
        $arr = ["goods_id"=>$add_res,"src"=>$v];
        $img_res=GoodsImg::insert($arr);
        if (!$img_res) {return json(['code'=>404,'msg'=>'相册添加失败']);
        }
      }
      /*规格*/
      /*图片*/
      die;
      if($res){
        $this->json_return('0000','添加成功');
      }else{
        $this->json_return('0001');
      }
    }
  }
  //单品 规格
  function addspec(){
    $cate_one=Category::where('level',1)->select();//一级分类
    return view('',['cate_one'=>$cate_one]);//小米手机分类ID=55
  }
  /*图片和相册*/
  function uploads(){
    $file=request()->file('goods_img');/*图片和相册使用统一的接口*/
    $info=$file->move('./uploads');
    if($info){
      $path='uploads/'.$info->getSaveName();
      return json(['code'=>200,'msg'=>'上传成功','src'=>$path]);
    }else{
      /*上传失败获取错误信息*/
      return json(['code'=>789,'msg'=>$file->getError(),'src'=>$path]);
    }
  }
}