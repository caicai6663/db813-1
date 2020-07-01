<?php
namespace app\mi\model;
use think\Model;
class User extends Model
{
	static function setpassword($password,$code)
	{
		return md5(md5($password).md5($code));
	}
}