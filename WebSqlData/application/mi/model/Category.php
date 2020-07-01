<?php
namespace app\mi\model;
use think\Model;
class Category extends Model
{
    public static $_treelist=[];
    public static function tree($list,$pid=0)
    {
        foreach ($list as $k=>$v)
        {
            if($v['pid'] == $pid){
                self::$_treelist[] = $v;
                //递归获取子数据
                self::tree($list,$v['id']);
            }
        }
    }
}
