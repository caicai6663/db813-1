<?php
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\Order as OrderModel;
use app\system\model\OrderGood;
class Order extends Base{
	//订单列表 页面
	function index(){
		$order_data=OrderModel::where('user_id',3)->select()->toArray();
		$this->assign('order_data', $order_data);
		return view();
	}
	//订单详情 页面
	function show(){
		$id=input('id');
		$order_data=OrderModel::where('id',$id)->find()->toArray();
		$this->assign('order_data', $order_data);
		$order_good_data=OrderGood::where('order_id',$order_data['id'])->select()->toArray();
		$this->assign('order_good_data', $order_good_data);
		return view();
	}
}