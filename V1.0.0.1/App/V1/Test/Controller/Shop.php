<?php
/*
 +----------------------------------------------------------------------
 + Title        : ApiPHP 测试的商品接口
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 14:23
 + Last-time    : 2017-06-27 17:31 + 小黄牛
 + Desc         : 这是对外访问的地址
 +----------------------------------------------------------------------
*/

# 申明命名空间
namespace App\V1\Test\Controller;
# 调用权限过滤控制器
use App\ParentClass\V1;

class Shop extends V1{

	/**
	 * 查询商品接口
	 */
	public function select(){
		# 开启验证，并获得请求数据
		$array = $this->Auth();
		# 请求数据
		$body  = $array['body']['body'];
		# 渠道数据
		$info  = $array['info'];

		/********************************* 业务逻辑处理 **********************************/
		$title = S($body['title']);
		if (empty($title)) {
			json('01','请求失败，查询的商品名称不能为空','');
		}
		# 查询出对应的商品信息
		$array = M('shop')->where("title LIKE '%{$title}%'")->select();
		# 使用渠道密钥，把数据加密返回
		$res   = dec_ryption(json_encode($array), false, $info['channel_key']);
		json('00','请求成功',$res);
	}

	/**
	 * 新增商品接口
	 */
	public function add(){
		# 开启验证，并获得请求数据
		$array = $this->Auth();
		# 请求数据
		$body  = $array['body']['body'];
		# 渠道数据
		$info  = $array['info'];

		/********************************* 业务逻辑处理 **********************************/
		# 这里一般做数据验证，我就不做了
		$title = S($body['title']);
		$num   = S($body['num']);
		$money = S($body['money']);
		$array = [
			'title' => $title,
			'num'   => $num,
			'money' => $money,
		];

		# 添加对应的商品
		$res = M('shop')->data($array)->add();
		if ($res) {
			json('00','请求成功','');
		}
		json('01','请求失败，服务器错误', '');
	}	
	
}