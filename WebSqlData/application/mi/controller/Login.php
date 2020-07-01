<?php
namespace app\mi\controller;
use think\Controller;
use think\Request;
use app\mi\model\Ads;
use app\mi\model\AdsPosition;

use think\facade\Env;
/*5.1版本取消了所有的系统常量，原来的系统路径变量改为使用Env类获取（需要引入think\facade\Env）*/
//登录
class Login extends Controller{
	/*微博授权登录回调*/
	function weibo_login_callback(){
		session_start();
		//echo Env::get('ROOT_PATH'); //输出 D:\phpstudy_pro\WWW\www.kao.com\
		require_once Env::get('ROOT_PATH')."extend/weibo/config.php";
		require_once Env::get('ROOT_PATH')."extend/weibo/saetv2.ex.class.php";
		//echo dirname(WEB_PATH); //输出 D:\phpstudy_pro\WWW\www.kao.com
		/*include_once( dirname(WEB_PATH).'/extends/weibo/config.php' );
		include_once( dirname(WEB_PATH).'/extends/weibo/saetv2.ex.class.php' );*/
		$o = new \SaeTOAuthV2( WB_AKEY , WB_SKEY );
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $o->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}
		if ($token) {
			$_SESSION['token'] = $token;
			setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
			return $this->fetch('weibo_ok');
		}else{
			return $this->fetch('weibo_no');
		}
		/*返回值 json {"access_token": "SlAV32hkKG","remind_in": 3600,"expires_in": 3600 }*/
	}
	/*进入你的微博列表页面*/
	function weibolist(){
		session_start();
		require_once Env::get('ROOT_PATH')."extend/weibo/config.php";
		require_once Env::get('ROOT_PATH')."extend/weibo/saetv2.ex.class.php";
		$c = new \SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$ms  = $c->home_timeline(); // done
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		var_dump($user_message);die;
	}
	/*小米商城 登录 页面*/
	function index(){
		/*微博登录类库引入*/
		session_start();
		require_once Env::get('ROOT_PATH')."extend/weibo/config.php";
		require_once Env::get('ROOT_PATH')."extend/weibo/saetv2.ex.class.php";
		//include_once( dirname(__LINE__).'/extend/weibo/config.php' );
		//include_once( dirname(__LINE__).'/extend/weibo/saetv2.ex.class.php' );
		$o = new \SaeTOAuthV2( WB_AKEY , WB_SKEY );
		$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
		$this->assign('code_url', $code_url);
		/*小米商城 登录 页面*/
		return view();
	}
}