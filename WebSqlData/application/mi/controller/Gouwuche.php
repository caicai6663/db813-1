<?php
namespace app\mi\controller;
use think\Controller;
use think\facade\Request;
use think\facade\App;

use app\mi\model\Cart;
use app\mi\model\GoodsSpec;
use app\mi\model\Address;
class Gouwuche extends Controller
{
	//立即选购 添加 订单 页面
	function add_order(){
		//获取 用户的地址
		$get_address=Address::where('user_id',3)->select()->toArray();
		$this->assign('myaddress', $get_address);
		//商品 规格
		$banben=input('banben');
		$banben=(int)$banben;
		return view('');
	}
	//核对订单信息 页面
	function order(){
		if(Request()->isPost()){
			//获取 用户的地址
			$get_address=Address::where('user_id',3)->select()->toArray();
			$this->assign('myaddress', $get_address);
			return view();
		}
		$get_address=Address::where('user_id',3)->select()->toArray();
		$this->assign('myaddress', $get_address);
		return view();
	}
	//购物车 页面
	function index(){
		//用户 3
		$data=Cart::where('user_id',3)->select()->toArray();
		$num=Cart::count();
		$this->assign('num',$num);
		return view('',['list'=>$data]);
	}
	//加入 购物车
	function addcar(){
		$banben=input('banben');
		$banben=(int)$banben;
		//取出规格值的相关数据
		$out_data=GoodsSpec::where('id',$banben)->find()->toArray();
		$in_data=[
			'user_id'=>3,
			'goods_id'=>$out_data['goods_id'],
			'spec_value'=>$out_data['spec_value'],
			'spec_name'=>$out_data['spec_name'],
			'goods_img'=>3,
			'num'=>1,//购买的数量，默认为1
			'unit_price'=>$out_data['price'],//单价
			'total_price'=>$out_data['price'],//总价，默认数量为一件
			'selected'=>1,//默认选中，为1
			'addtime'=>time()
		];
		$res=Cart::insert($in_data);
		if($res){
			return json(['code'=>200]);//添加成功
		}
		return json(['code'=>404]);
	}
}