<?php
namespace app\mi\controller;
use think\Controller;
use think\Request;
use app\mi\model\Ads;
use app\mi\model\AdsPosition;
class Ad extends Controller
{
    public function index()
    {
    }
    //获取广告的接口
    function get_ad(){
        $position=input('position');
        //获取广告位信息
        $pos=AdsPosition::get($position);
        //获取广告位下的所有广告
        $pos['ad']=Ads::where('pid',$position)
        ->where('start_time','<',time())
        ->where('end_time','>',time())
        ->where('total>=price')
        ->order('price desc')
        ->limit($pos['show_num'])
        ->select();
        if (count($pos['ad'])==0) {
            $pos['ad']=Ads::where('pid',$position)
            ->where('start_time','<',time())
            ->where('end_time','>',time())
            ->limit($pos['show_num'])
            ->select();
        }else{
            //削减金额
            foreach ($pos['ad'] as $key => $value) {
                Ads::where('id',$value['id'])->setDec('total',$value['price']);
            }
        }
        return json(['code'=>200,'msg'=>'获取成功','data'=>$pos]);
    }
    function adress(){
        $ip='111.200.116.208';
        $add=$this->get_city($ip);
        print_r($add);
    }
    function get_city($ip){
        $url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
        $ipinfo=json_decode(file_get_contents($url));
        if($ipinfo->code=='1'){
            return false;
        }
        return $ipinfo->data->city;
    }
}