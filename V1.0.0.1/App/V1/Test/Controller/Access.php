<?php
# 申明命名空间
namespace App\V1\Test\Controller;
# 引入API核心类
use ApiPHP\ApiRoot\Library\Api\Api;
# 引入权限过滤控制器
use App\ParentClass\V1;


class Access extends V1{

	/**
	 * 更新access_token
	 */
	public function token(){
		# ① 获得请求参数
		$body = !empty($_POST) ? array_keys($_POST) : ($_GET);
		$body = $body[0];
		# ② 请求内容为空过滤
		if (count($body) == 0) {
			json('40002','请求内容，不能为空','');
		}
		# ③ 解密请求内容
		$data = $this->KeyVif($body);
		
		# ④ 解密后的数据类型过滤
		if (!$data) {
			json('40003','渠道ID或Key错误，或数据类型错误','');
		}

		# ⑤ 渠道ID不能为空
		$channel_id = $data['body']['channel_id'];
		if (empty($channel_id)) {
			json('40004','渠道ID，不能为空','');
		}

		# ⑥ 渠道ID是否匹配
		if ($channel_id != $data['info']['channel_id']) {
			json('40006','渠道ID错误','');
		}
		
		# ⑦ 生成新的access_token
		$obj = new Api;
		$access_token = $obj->set_access_token();
		$out_time     = time()+7200;
		
		# ⑧ 更新渠道信息
		$array = [
			'access_token'     => $access_token,
			'token_start_time' => time(),
			'token_start_out'  => $out_time,
		];
		$info = M('channel')->data($array)->upd("channel_id = '{$channel_id}'");

		if ($info) {
			$array = [
				'access_token' => $access_token,
				'out_time'     => $out_time,
			];
			# 使用渠道密钥，把数据加密返回
			$str = dec_ryption(json_encode($array), false, $data['info']['channel_key']);
			json('00','请求成功', $str);
		}

		json('01','请求失败，服务器错误', '');
	}
}