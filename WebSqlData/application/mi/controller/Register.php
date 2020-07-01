<?php
namespace app\mi\controller;
use think\Controller;
use think\Request;
use think\captcha\Captcha;
use app\mi\model\Users;
//注册
class Register extends Controller
{
    //生成 邀请码
    function set_invite_code(){
        //当前用户ID
        $user_id=10;
        //生成 邀请码
        $invite_link='http://www.kao.com/mi/login/index?user_id='.$user_id;
        return $invite_link;
    }
    //注册 页面
	function index(){
        $user_id=input('user_id');
        if ($user_id) {
        }
		return view();
	}
    //验证码 类
    public function verify()
    {
        $captcha = new Captcha();
        return $captcha->entry();
        var_dump($res);die;
    }
    //保存
    public function save(){
        //验证 验证码
        $captcha = new Captcha();
        $value=input('captcha');
        if(!$captcha->check($value)){
            $this->error('验证码 错误');
        }
        //用户名 验证
        $name=input('name');
        if ($name) {}else{$this->error('用户名 空');}
        $name_res=Users::where('name',$name)->find();
        if($name_res){$this->error('用户名 重名');}
        //密码 验证
        $password=input('password1');
        if ($name) {}else{$this->error('密码 空');}
        //信息入库
        $res=Users::insert(['name'=>$name,'password'=>$password]);
        if($res){
            $this->success('注册 成功');
        }else{
            $this->error('注册 失败');
        }
    }
}
///第三方登录 微信 手机号