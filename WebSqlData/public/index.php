<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

define('APP_PATH', __DIR__.'/../application/');
define('WEB_PATH', __DIR__);
define('EXTEND_PATH', __DIR__.'/../extend/');

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();

/* WEB_PATH 是 D:\phpstudy_pro\WWW\www.kao.com\public 入口文件所在目录*/
/* APP_PATH 是 D:\phpstudy_pro\WWW\www.kao.com\public/../application/ 应用目录*/
/* EXTEND_PATH 是 D:\phpstudy_pro\WWW\www.kao.com\public/../extend/ 第三方类库目录*/
/* __DIR__ 是 D:\phpstudy_pro\WWW\www.kao.com\application\mi\controller 控制器目录*/
/* __FILE__ 是 D:\phpstudy_pro\WWW\www.kao.com\application\mi\controller\Pay.php 控制器文件*/
/* dirname(WEB_PATH) 是 返回路径中的目录部分 D:\phpstudy_pro\WWW\www.kao.com */