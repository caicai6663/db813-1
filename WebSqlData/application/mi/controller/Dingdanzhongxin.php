<?php
namespace app\mi\controller;
use think\Controller;
use think\facade\Request;
use think\facade\App;

use app\mi\model\Order;
use app\mi\model\OrderGood;
class Dingdanzhongxin extends Controller
{
	//订单列表 页面
	function index(){
		$order_data=Order::where('user_id',3)->select()->toArray();
		$this->assign('order_data', $order_data);
		return view();
	}
	//订单详情 页面
	function show(){
		$id=input('id');
		$order_data=Order::where('id',$id)->find()->toArray();
		$this->assign('order_data', $order_data);
		$order_good_data=OrderGood::where('order_id',$order_data['id'])->select()->toArray();
		$this->assign('order_good_data', $order_good_data);
		return view();
	}
}