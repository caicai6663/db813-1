<?php
namespace app\mi\controller;//规格: 规格值 价格 库存
use think\Controller;
use think\facade\Request;
use think\facade\App;

use app\mi\model\Goods;
use app\mi\model\SpecValues;
use app\mi\model\GoodsSpec;//商品的单品规格表
use app\mi\model\Cart;
use app\mi\model\Address;
use app\mi\model\Order;
use app\mi\model\OrderGood;
class Xiangqing extends Controller
{
	//立即选购 下单 入库
	function order_save(){
		$user_note=input('user_note');
		$address_id=input('address_id');$address_id=(int)$address_id;
		$good_price=input('good_price');
		$spec_value=input('spec_value');
		$good_name=input('good_name');
		$good_id=input('good_id');
		//入 订单
		$address_info=Address::where('id',$address_id)->find();//查询地址
		$order_data=[
			'user_id'=>3,
			'order_sn'=>time()."3",//3是用户ID
			'user_note'=>$user_note,
			'order_status'=>0,//待发货
			'pay_status'=>0,//未支付
			'shipping_status'=>0,//待发货
			'add_time'=>time(),
			'order_amount'=>$good_price,
			'shipping_price'=>8.00,
			'good_total_price'=>$good_price,
			//收货地址信息
			'consignee'=>$address_info['name'],
			'country'=>$address_info['country'],
			'province'=>$address_info['province'],
			'city'=>$address_info['city'],
			'area'=>$address_info['area'],
			'town'=>$address_info['town'],
			'place'=>$address_info['place'],
			'address'=>$address_info['address'],
			'phone'=>$address_info['phone']
		];
		$order_id=Order::insertGetId($order_data);
		if(!$order_id){
			return json(['code'=>404]);
		}
		//入 商品
		$good_data=[
			//单品信息
			'order_id'=>$order_id,
			'good_id'=>$good_id,
			'good_name'=>$good_name,
			'spec_value'=>$spec_value,
			'total_price'=>$good_price,
			'good_num'=>1,
			'is_comment'=>0
		];
		$order_res=OrderGood::insert($good_data);
		if(!$order_res){
			return json(['code'=>404]);
		}
		return json(['code'=>200]);
	}
	//立即选购 核对订单信息 页面
	function order(){
		//单品 规格id
		$banben=input('banben');
		if(!$banben){
			echo "版本为空";die;
		}
		$banben=(int)$banben;
		//获取 用户的地址
		$get_address=Address::where('user_id',3)->select()->toArray();
		$this->assign('myaddress', $get_address);
		//获取 商品的 单品信息
		$info=GoodsSpec::where('id',$banben)->find()->toArray();
		$this->assign('good_info', $info);
		//获取 商品的 通用信息
		$common=Goods::where('id',$info['goods_id'])->find()->toArray();
		$this->assign('common', $common);
		return view();
	}
	//商品详情 页面 订单首页
	function index(){
		$id = '3';//默认商品ID 小米手机
		//取出 单品规格价格库存
		$a_good = GoodsSpec::where('goods_id',$id)->select()->toarray();
		$this->assign('a_good',$a_good);
		return view();
	}
	//获取规格对应的商品信息
	public function get_spec(){
		$spec_value = input('spec_value');
		$spec_value = substr($spec_value, 0, strlen($spec_value)-1);
		$goods_spec = GoodsSpec::where('spec_value',$spec_value)->find();
		$arr = [
			'code' => '0000',
			'msg' => '获取成功',
			'data' => $goods_spec
		];
		echo json_encode($arr);exit;
	}
}