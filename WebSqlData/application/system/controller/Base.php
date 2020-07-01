<?php
namespace app\system\controller;
use think\Controller;
use think\Request;
use app\system\model\User;
//公共控制器//数据库 one
class Base extends Controller
{
    /*public function initialize(){
        $action = request()->action();
        if($action != 'login'){
            if(!$this->checktoken()){
                $this->error('请重新登录',url('users/login'));
            }
        }
    }*/
	function json_return($code="0000",$msg="",$data=null){
        $data = ['code'=>$code,'msg'=>$msg,'data1'=>$data];
        echo json_encode($data);exit;
    }
    function settoken()//生成token
    {
    	$str=md5( uniqid( md5( time() ),true ) );
    	return $str;
    }
    function checktoken()//验证token
    {
    	$userinfo=session('userinfo');
    	$res=User::where('token',$userinfo['token'])->where('outtime','>',time())->find();
    	if($res){return true;}else{return false;}
    }
}