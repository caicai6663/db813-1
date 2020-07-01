<?php 
namespace app\system\controller;
use app\system\controller\Base;
use app\system\model\Ads;
use app\system\model\AdsPosition;
class Ad extends Base{
	//广告 图片上传
	function upload(){
	    $file = request()->file('ad_img_upload_sign');// 获取表单上传文件
	    $info = $file->move('./uploads');// 移动到框架应用根目录/uploads/ 目录下
	    if($info){
	        // 成功上传后 获取上传信息
	        $path='\uploads\\'.$info->getSavename();
	        return json(['code'=>200,'msg'=>'上传成功','path'=>$path]);
	    }else{
	        echo $file->getError();// 上传失败获取错误信息
	    }
	}
	//广告 添加
	function ad_add(){
		if (Request()->isGet()) {
			$list=AdsPosition::all();
			$this->assign('list', $list);
			return view();//添加 页面
		}
		if (Request()->isAjax()) {
			$data=input('post.');
			//判断 接收的数据 是否为空
			if (!$data) {
				return json(['code'=>404,'msg'=>'ajax 有空值']);
			}
			//删除 空值
			unset($data['ad_img_upload_sign']);

			//去除 文件路径 尾部的错误字符
			$data['ad_path']=rtrim($data['ad_path'],'/');
			//纠正 路径格式，防止入口丢失
			$data['ad_path']=str_replace('\\', '/', $data['ad_path']);

			//日期范围 分隔
			$data_range=explode(' ~ ', $data['data_range']);
			$data['start_time']=strtotime($data_range[0]);
			$data['end_time']=strtotime($data_range[1]);
			//日期范围 字符串删除
			unset($data['data_range']);

			//判断 广告链接 格式是否规范 http
			if($data['ad_link']){}else{}

			//入库//自动纠正字段类型
			$add_res=Ads::insert($data);
			if ($add_res) {
				return json(['code'=>200]);
			}else{
				return json(['code'=>404,'msg'=>'ajax 添加 失败']);
			}
		}
	}
	//广告 列表 页面
	function ad_list(){
		return view();
	}
	//广告位 列表 分页数据
	function get_ad_page_data(){
		$limit=input('limit');//每页数据量
		$list_res = Ads::paginate($limit);
		if($list_res){
			return json(['code'=>0,'msg'=>'获取 数据 成功','data'=>$list_res]);
		}
		return json(['code'=>666,'msg'=>'获取 数据 失败']);
	}
	//广告位 列表 页面
	function ad_position_list(){
		if (Request()->isGet()) {
			return view();
		}else{echo "错误";}
	}
	//广告位 列表 分页数据
	function get_ad_position_page_data(){
		$limit=input('limit');//每页数据量
		$list_res = AdsPosition::paginate($limit);
		if($list_res){
			return json(['code'=>0,'msg'=>'获取 数据 成功','data'=>$list_res]);
		}
		return json(['code'=>666,'msg'=>'获取 数据 失败']);
	}
	//广告位 添加
	function ad_position_add(){
		if (Request()->isGet()) {
			return view();//添加 页面
		}
		if (Request()->isAjax()) {
			//return json(['code'=>404,'msg'=>'ajax']);
			$s=input('post.');
			if (!$s) {
				return json(['code'=>404,'msg'=>'ajax 空值']);
			}
			/*var_dump($s);die;
			$is_open=input('is_open');
			$position_desc=input('position_desc');
			$ad_height=input('ad_height');
			$ad_width=input('ad_width');
			$position_name=input('position_name');
			$data=[
				'position_name'=>$position_name,
				'ad_width'=>$ad_width,
				'ad_height'=>$ad_height,
				'position_desc'=>$position_desc,
				'is_open'=>$is_open
			];
			var_dump($data);die;*/
			$add_res=AdsPosition::insert($s);//自动纠正字段类型
			if ($add_res) {
				return json(['code'=>200]);
			}else{
				return json(['code'=>404,'msg'=>'ajax 添加 失败']);
			}
		}
		//无 ajax 提交post表单 不重要，仅用于测试
		/*if (Request()->isPost()) {
			return json(['code'=>200,'msg'=>'post']);
			$is_open=input('is_open');
			$position_desc=input('position_desc');
			$ad_height=input('ad_height');
			$ad_width=input('ad_width');
			$position_name=input('position_name');
			$data=[
				'position_name'=>$position_name,
				'ad_width'=>$ad_width,
				'ad_height'=>$ad_height,
				'position_desc'=>$position_desc,
				'is_open'=>$is_open
			];
			echo "post";
			var_dump($data);die;
			$add_res=AdsPosition::insert($data);
			if ($add_res) {
				return json(['code'=>200]);
			}else{
				return json(['code'=>404]);
			}
		}*/
	}
} 