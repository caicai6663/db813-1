<?php
namespace app\system\model;
use think\Model;
class User extends Model
{
	//用户密码加密 自定义算法
	static function setpassword($password,$code){
		return md5(md5($password).md5($code));
	}
}