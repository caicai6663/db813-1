<?php
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\User;//数据库 one
class Index extends Base
{
    //
     function index()
    {
        return view();
    }
    //
    function lists()
    {
    	return view();
    }
    //获取用户表数据
     function getusers()
    {
    	$pagesize = input('limit');
    	$res = User::paginate($pagesize);
    	$this->json_return('0000','获取成功',$res);
    }
}