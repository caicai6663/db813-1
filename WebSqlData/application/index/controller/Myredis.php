<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
		include_once EXTEND_PATH.'/redis/BaseRedis.php';
class Myredis extends Controller
{
    /* redis 最基础的数据类型 string */
	function redis_string(){
		$id=input('id',0);
        if (!$id) {echo "空";die;}if(!is_numeric($id)){echo "不是数字";die;}if ($id==' ') {echo "空格";die;}
        /*定义键名*/
		$key='test_'.$id;
        /*实例化redis*/
		$redis=\BaseRedis::getInstance();
		$click=0;
		$redis->setnx($key,$click);/*设置key，如果key存在,不做任何操作*/
		$redis->incr($key);/* 累加1 */
		$res=$redis->get($key);/*获取*/
		echo $key.' 是 '.$res;die;/*每次刷新页面，就自增一次*/
	}
    /*有序集合 排行榜*/
    function redis_zset(){
        /*接收 文章ID*/
        $article_id=input('article_id',0);
        $key='article_id_'.$article_id;/*定义*/

        /*从数据库中获取 文章信息*/
        $article_data=['article_id'=>$article_id,'title'=>'New'.$article_id];
        $article_json=json_encode($article_data);

        /*创建redis对象*/
        $redis=\BaseRedis::getInstance();
        /*文章信息 放置到哈希表中*/
        $redis->hSetNx('article',$article_id,$article_json);

        /*设置 记录点击量 的字符串*/
        $click=0;
        $redis->setnx($key,$click);
        $redis->incr($key);
        $c=$redis->get($key);
        /*点击量 放置到有序集合中*/
        $redis->zadd('clicknum',$c,$article_id);

        /*从有序集中 获取 点击量前五的数据*/
        $list=$redis->zRevRange('clicknum',0,5);
        /*从哈希表中 获取 文章详情*/
        $article_list=$redis->hMget('article',implode(',', $list));

        print_r($article_list);
    }
    /*字符串列表list 消息队列 加*/
    function add_redis_list(){
        $user_id=input('user_id');
        /*转换字符串*/
        $value=implode(',', ['user_id'=>$user_id,'action'=>'chujia','time'=>time()]);
        $redis=\BaseRedis::getInstance();
        $redis->rPush('test1',$value);/*添加队列 右*/
    }
    /*字符串列表list 消息队列 取*/
    function get_redis_list(){
        $redis=\BaseRedis::getInstance();
        $action=$redis->lPop('test1'); /*删除并返回队列中的头元素。 左*/
        /*$action=$redis->lRange('test1');*/ /*返回队列指定区间的元素。 左*/
        print_r($action);
    }
    /*发布*/
    function publish_to(){
        $user_id=input('user_id');
        /*转换字符串*/
        $value=implode(',', ['user_id'=>$user_id,'action'=>'chujia','time'=>time()]);
        $redis=\BaseRedis::getInstance();
        $redis->rPush('test1',$value);/*添加队列*/
        /*发布消息 通知 执行队列*/
        $redis->publish('redistest1','redistest1');
    }
    /*订阅*/
    function subscribe_to(){
        $redis=\BaseRedis::getInstance();
        $len=$redis->lLen('test1');
        for($i=0;$i<$len;$++){
            /*从test1中去除要处理的数据*/
            $action=$redis->lPop('test1');
        }
        print_r($action);
    }
}