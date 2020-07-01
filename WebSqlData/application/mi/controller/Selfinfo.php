<?php
namespace app\mi\controller;
use think\Controller;
use think\facade\Request;
use think\facade\App;

use app\mi\model\Address;
use app\mi\model\User;
class Selfinfo extends Controller
{
	//添加 新地址
	function address_save(){
		if(Request()->isGet()){
			return view('addmyaddress');
		}
		$name=input('name');
		$phone=input('phone');
		$address=input('address');
		var_dump($address);die;
		$code=Address::insert(['name'=>$name,'phone'=>$phone,'address'=>$address]);
		if($code){
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
	}
	//修改 地址
	function address_update(){
		if(Request()->isGet()){
			$id=(int)(input('id'));
			$data=Address::where('id',$id)->find()->toArray();
			if($data){
				$this->assign('vo',$data);
			}else{
				$this->error('没有该地址');
			}
			return view('updateaddress');
		}
		$id=input('id');
		$name=input('name');
		$phone=input('phone');
		$address=input('address');
		var_dump($address);die;
		$code=Address::where('id',$id)->update(['name'=>$name,'phone'=>$phone,'address'=>$address]);
		if($code){
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}
	//个人中心 页面
	function index(){
		return view();
	}
	//收货地址 页面
	function address(){
		$data=Address::all();
		$this->assign('address', $data);
		return view('');
	}
	//删除 地址
	function address_del(){
		$id=input('id');
		$code=Address::where('id',$id)->delete();
		if($code){
			return json(['code'=>200]);
		}else{
			return json(['code'=>404]);
		}
	}
}