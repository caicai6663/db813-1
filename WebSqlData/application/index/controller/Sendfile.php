<?php
namespace app\index\controller;
use think\Controller;
/*PHP大文件分片上传断点续传*/
class Sendfile extends Controller
{
    /*测试地址 http://www.kao.com/index/sendfile/getddxc*/
    public function getddxc(){
    	include_once dirname(WEB_PATH).'/extend/httpdownload/httpdownload.php';
    	$download = new \httpdownload();
    	$download->set_byfile('D:\Program Files\sublimetext3\HTML-CSS-JS Prettify.rar');
    	$download->download();
    }
    public function getfilelength(){
    	$size = filesize('D:\Program Files\sublimetext3\HTML-CSS-JS Prettify.rar');
    	echo $size;exit;
    }
    public function getddxc3(){
    	$size = juhecurl('http://www.kao.com/index/sendfile/getfilelength');
    	$size *= 1; //文件长度转数子
    	$str = ''; //文件记录
    	$len = 100000;//每次获取长度
    	for ($i=0;;$i++){
    		$b = $i*$len;//开始位置
    		$e = ($i+1)*$len;//结束位置
            //判断是否超过结束位置
    		if($e > $size){
    			$e = $size;
    		}
    		echo $b.'<br>';
    		echo $e.'<br>';
    		//调用curl方法获取某一段数据
    		$str .= $this->getddxc2($b,$e-1);
    		echo '请求'.$i.'<br>';
    		//判断是否获取完毕
    		if($e == $size){
    			break;
    		}
    	}
    	file_put_contents(dirname(WEB_PATH).'/public/uploads/'.time().'.zip', $str);
    }
    public function getddxc2($b,$e){
    	$curl = curl_init();
    	curl_setopt_array($curl, array(
    	CURLOPT_URL => "http://www.kao.com/index/sendfile/getddxc",
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => "",
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 30,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => "GET",
    	CURLOPT_POSTFIELDS => "",
    	CURLOPT_HTTPHEADER => array(
			    	"Range: bytes=$b-$e",
			    	"cache-control: no-cache"
    			),
    	));
    	$response = curl_exec($curl);
    	$err = curl_error($curl);
    	curl_close($curl);
    	if ($err) {
    		echo "cURL Error #:" . $err;
    	} else {
    		return $response;
    	}
    }
}