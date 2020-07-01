<?php
namespace app\mi\controller;
use think\Controller;
use think\facade\Request;
use think\facade\App;

use app\mi\model\Order;
use app\mi\model\OrderGood;
/*支付方式*/
class Pay extends Controller
{
	/*选择支付方式 页面*/
	function index(){
		return view();
	}
	/*支付操作*/
	function payment(){
		/*接受 支付类型*/
		$type = input('type','alipay');
		/*接收 订单号*/
		$order_id = input('order_id',time());/*测试阶段推荐使用时间戳*/
		/*引入 支付类库*/
		include_once dirname(WEB_PATH).'/extend/pay/'.$type.'/index.php';
		$classname = '\\'.$type;
		$p = new $classname();
		$p->get_code($order_id);
	}
	/*同步跳转*/
	function return_url(){
		/*接受 支付类型*/
		$type = input('type');
		/*引入 支付类库*/
		include_once dirname(WEB_PATH).'/extend/pay/'.$type.'/index.php';
		$classname = '\\'.$type;
		$p = new $classname();
		$res = $p->return_url();
		/*判断 结果*/
		if($res){
			$this->assign('oreder_sn',$res);
			return view();
		}else{
			$this->error('交易失败',url('pay/index'));
		}
	}
	/*异步通知*/
	function notify_url(){
		/*接受 支付类型*/
		$type = input('type','alipay');
		/*接收 订单号*/
		$order_id = input('order_id',time());/*测试阶段使用时间戳*/
		/*引入 支付类库*/
		include_once dirname(WEB_PATH).'/extend/pay/'.$type.'/index.php';
		$classname = '\\'.$type;
		$p = new $classname();
		$p->notify_url($order_id);
	}
}