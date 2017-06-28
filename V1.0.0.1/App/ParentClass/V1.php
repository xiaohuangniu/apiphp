<?php
/*
 +----------------------------------------------------------------------
 + Title        : V1 版本，接口权限控制器
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 13:41
 + Last-time    : 2017-06-27 08:15 + 小黄牛
 + Desc         : 主要用于过滤公共逻辑请求
 +----------------------------------------------------------------------
*/

# 申明命名空间
namespace App\ParentClass;

class V1{
	/**
	 * 接口公共逻辑过滤
	 * @return array 解密后的内容与渠道信息
	 */
	public function Auth() {
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

		# ⑥ Access_Token不能为空
		$access_token = $data['body']['access_token'];
		if (empty($access_token)) {
			json('40005','Access_Token，不能为空','');
		}

		# ⑦ 渠道ID是否匹配
		if ($channel_id != $data['info']['channel_id']) {
			json('40006','渠道ID错误','');
		}

		# ⑧ Access_Token是否超时 -60S，预留缓冲时间
		if (($data['info']['token_start_out']-60) <= time()) {
			json('40007','Access_Token已失效，请重新获取','');
		}

		# ⑨ Access_Token是否匹配
		if ($access_token != $data['info']['access_token']) {
			json('40008','Access_Token错误','');
		}

		# ⑩ 检测来源路径是否等于绑定的路径
		$url = 'channel_'.lcfirst(API).'_url';
		$channel_url = $data['info'][$url];
		$start_url   = $_SERVER['HTTP_REFERER'];

		# 为空不做检测(CURL是不会带有来源网址的)，这里就不做兼容了，要做兼容的朋友可以将url换成ip
		if (!empty($start_url)) {
			$array = explode($channel_url, $start_url);
			if (count($array)!=2 || !empty($array[0])) {
				json('40009','非法请求，来源地址错误','');
			}
		}

		return $data;
	}
	
	/**
	 * 验证加密数据
	 * @param string $body 加密的请求内容
	 * @return bool|array 解密后的请求内容与对应的渠道信息
	 */
	protected function KeyVif($body){
		$body = str_replace('_', '+', $body);
		$DB = M('channel');
		# ⑧ 查询对应的渠道是否存在
		$info = $DB->field('channel_id, channel_key, channel_formal_url, channel_test_url, access_token, token_start_out')->select();
		foreach ($info as $v){
			# 解密请求内容
			$json = dec_ryption($body, true, $v['channel_key']);
			$data = json_decode($json, true);
			# 解密成功
			if (is_array($data)) {
				return [
					'body' => $data,
					'info' => $v,
				];
			}
		}
		return false;
	}
}




























