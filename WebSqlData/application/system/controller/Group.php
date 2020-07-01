<?php
namespace app\system\controller;
use think\Controller;
use think\Request;
use app\system\model\Goods;
class Group extends Controller
{
    /*团购活动 列表展示参加团购活动的商品*/
    function listshow(){}
    /*团购活动 添加商品 页面*/
    function add(){
        /*http://www.kao.com/system/group/add*/
        if (Request()->isGet()) {
            return view();
        }
    }
    /*搜索 商品*/
    function search(){
        $good_name=input('good_name');
        $limit=input('limit');//每页数据量
        $list_res = Goods::where('name','like','%'.$good_name.'%')->paginate($limit);
        if($list_res){
            return json(['code'=>666,'msg'=>'获取 数据 成功','data'=>$list_res]);
        }else{
            return json(['code'=>404,'msg'=>'获取 数据 失败']);
        }
    }
}
/*B2C电子商务的付款方式是货到付款与网上支付相结合，而大多数企业的配送选择物流外包方式以节约运营成本。*/
/*商品的图片分为 商品列表展示大图,相册(关联规格),商品介绍*/