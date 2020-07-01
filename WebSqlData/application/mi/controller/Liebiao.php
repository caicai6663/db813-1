<?php
namespace app\mi\controller;
use think\Controller;
use think\facade\Request;
use think\facade\App;

class Liebiao extends Controller
{
	function index(){
		return view();
	}
	/**
	 * 商品列表页
	 */
	public function goods_list(){
		//第一步  接受参数
		$cate1_id = input('cate1_id',0);
		$cate2_id = input('cate2_id',0);
		$cate3_id = input('cate3_id',0);
		$check_spec = input('spec','');
		//第二步 查询复合条件的商品
		$where = "1=1 ";
		if($cate1_id){
			$where .= ' and cate1_id ='.$cate1_id;
		}
		if($cate2_id){
			$where .= ' and cate2_id='.$cate2_id;
		}
		if($cate3_id){
			$where .= ' and cate3_id='.$cate3_id;
		}
		if($check_spec != ''){
			$check_arr = explode(',', $check_spec);
			$or = ' and (';
			foreach ($check_arr as $v){
				$or .= 'find_in_set('.$v.',spec_value_id) or ';
			}
			$or = substr($or, 0, -3).')';
			$where .= $or;
		}
		$res = dao::where($where)->select();
		//第三步 构建查询查询条件
		//规格的查询条件
		$arr = [];
		foreach ($res as $k=>$v)
		{
			$spec_arr = explode(',', $v['spec_id']);
			foreach ($spec_arr as $k=>$vv)
			{
				$spec = Spec::get($vv)->toArray();
				$spec['list'] = [];
				$arr[$vv] = $spec;
			}
		}
		foreach ($res as $k=>$v)
		{
			$spec_value_arr = explode(',', $v['spec_value_id']);
			foreach ($spec_value_arr as $k=>$vv)
			{
				$value = SpecValues::get($vv)->toArray();
				$arr[$value['spec_id']]['list'][$vv] = $value;
			}
		}
		//价格
		$res_price = dao::where($where)->max('shop_price');
		$qj = $res_price/5;//价格区间
		$price_arr = [];
		for ($i=0;$i<5;$i++){
			$price_arr[] = [$i*$qj,($i+1)*$qj];
		}
		$price_arr[] = [$qj*5];
		//品牌
		$res_brand = dao::where($where)->column('brand_id');
		$brand = implode($res_brand,',');
		//展示数据
		$this->assign('goods',$res);
		$this->assign('spec',$arr);
		$this->assign('price',$price_arr);
		$this->assign('checked_spec',$check_spec);
		return view();
	}
}