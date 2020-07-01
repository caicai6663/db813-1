<?php
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\User;
class Users extends Base{
	//登录页面
	function login(){
		if(request()->isajax()){
			$data=input('post.');
			$user=User::where(['username'=>$data['usename']])->find();
			if($res){
				if($res['password']==$user->setpassword($data['password'],$res['code'])){
					$token=$this>settoken();
					$arr=['token'=>$token,'outtime'=>time()+1440];
					User::where('id',$res['id'])->update($arr);
					$res=array_merge($res->getDate(),$arr);
					session('userinfo',$res);
					$this->json_return('0000','登录成功',$res);
				}else{
					$thius->json_return('0002','密码错误',$res);
				}
			}else{
				$this->json_return('0001','用户不存在',$res);
			}
		}
		return view();
	}
    //用户 列表 页面
	public function index(){
		return view();
	}
	//分页数据
	public function get_list(){
		$pagesize = input('limit');
		$res = User::paginate($pagesize);
		$this->json_return('0000','获取成功',$res);
	}
	//删除 用户
	public function del(){
		$id = input('id');
		$res = User::where('id',$id)->delete();		
		if($res){
			$this->json_return('0000','删除成功');
		}else{
			$this->json_return('0001','删除失败');
		}
	}
	//添加用户 页面 保存
	public function add(){
		if(request()->isajax()){
			$data=input('post.');
			$code=rand(1000,9999);//随机码
			$password=User::setpassword($data['password'],$code);//密码加密

			$data['password']=$password;
			$data['code']=$code;
			$data['addtime']=time();
			$res = User::insert($data);
			if($res){
				$this->json_return('0000','添加成功');
			}else{
				$this->json_return('0001','添加失败');
			}
		}
		return view();
	}
	//修改用户 页面 保存
	public function update_user(){
		$id = input('id');
		if(request()->isajax()){
			$data = input('post.');
			$res = User::where('id',$id)->update($data);
			if($res){
				$this->json_return('0000','修改成功');
			}else{
				$this->json_return('0001','修改失败');
			}	
		}
		$res = User::get($id);
		$this->assign('user',$res);//模板赋值
		return view();
	}	
}