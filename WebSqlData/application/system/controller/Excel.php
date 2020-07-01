<?php
namespace app\system\controller;
use think\Controller;
use think\Request;
use app\system\model\User;
/*phpexecl 实现数据的导入导出*/
/*弹出框将会引导你选择文件下载后的存放地址*/
class Excel extends Controller
{
    /*导出 存入到excel*/ /*出库*/
    function out(){
        $data=User::all()->toArray();
        /*使用字段定义表格的表头*/
        $head=[];
        foreach ($data[0] as $key => $value) {
            $head[]=$key;
        }
        /*引入第三方类库*/
        include_once dirname(WEB_PATH).'/extend/phpexcel/excel.php';
        /*实例化*/
        $o=new \excel();
        $o->output($head,$data);
    }
    /*导入 获取excel的数据*/ /*入库*/
    function read(){
        /*引入第三方类库*/
        include_once dirname(WEB_PATH).'/extend/phpexcel/excel.php';
        /*实例化*/
        $o=new \excel();
        $read_res=$o->getput('D:\Downloads/1573871168.xls');
        print_r($read_res);
    }
/*出现网页无法访问
打开 第三方类库\extend\phpexcel\Classes\PHPExcel\Shared\OLE.php 文件
第288行代码的 continue 改成 break 即可。

导入的方法只适合于列数少的的，列数大的会出问题
*/
}