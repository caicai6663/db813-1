<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller{
	//框架模块统一访问页面
    public function index(){
        return view();
    }
    /*百度富文本编辑器*/
    function ueditor(){
        return $this->fetch();// 不带任何参数 自动定位当前操作的模板文件
    }
    /*PHP环境信息*/
    function php_info(){
        return phpinfo();
    }
    /*以下内容是笔记*/
    /*视图渲染 的 部分笔记*/
    function test1(){
        die;
    	// 指定模板输出
		return $this->fetch('edit');//表示调用当前控制器下面的edit模板
		return $this->fetch('member/read');//表示调用Member控制器下面的read模板。
		/*跨模块渲染模板*/
		return $this->fetch('admin@member/edit');
    }
    /*视图赋值 的 部分笔记*/
    function test2(){
        die;
        // 模板变量赋值
        $this->assign('name','ThinkPHP');
        //如果使用view助手函数渲染输出的话，可以使用下面的方法进行模板变量赋值：
        return view('index', [
            'name'  => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
        //或者
        return view('index')->assign([
            'name'  => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
    }
}
