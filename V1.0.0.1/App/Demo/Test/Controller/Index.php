<?php
/*
 +----------------------------------------------------------------------
 + Title        : Api框架 所有测试接口Demo
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 17:03
 + Last-time    : 2017-06-28 08:55 + 小黄牛
 + Desc         : 完善DEMO实例
 +----------------------------------------------------------------------
*/

# 申明命名空间
namespace App\Demo\Test\Controller;

class Index{

	/**
	 * DEMO 查询数据
	 */
	public function index(){
		# 组合请求参数
		$array = [
			'channel_id'   => 'PHV0IEGX5DMDOB3R7X02UCE',
			'access_token' => 'U8lr7JhLXLpSeEuo5C0Uasq92mZC4kNnronFVaYhiv3Be2EYgUYQOC2Zum92NCTl9f07JndORrRSIObWMR8AJ3hqSfnLExnNAaKvhDopgJuseZvwYjx5nse2aPlibL1v6N6B1h6QaQmU25BeXpMPPCgdPGY2hltW1dV9HYiWbuYAWcDPFIpb5YRjPDaUJkAvIowsxdvHGQHKZiLLq3Q59m60L8SZL6Cqmg3U6C63OCW6AdGMSPb82HPbQQO09BS',
			'body'         => [
				'title' => '鞋'
			],
		];
		# 加密参数
		$str = dec_ryption(json_encode($array), false, 'R0P6KVYJLEQB8I1Q6VBBPIN3JQEX4TZCFT9BNZVG');
		# 请求
		$res = $this->https_request('http://127.0.0.1/3/index.php/v1/test/shop/select', $str);
		echo $res;
	}

	/**
	 * DEMO 新增数据
	 */
	public function test(){
		# 组合请求参数
		$array = [
			'channel_id'   => 'PHV0IEGX5DMDOB3R7X02UCE',
			'access_token' => 'U8lr7JhLXLpSeEuo5C0Uasq92mZC4kNnronFVaYhiv3Be2EYgUYQOC2Zum92NCTl9f07JndORrRSIObWMR8AJ3hqSfnLExnNAaKvhDopgJuseZvwYjx5nse2aPlibL1v6N6B1h6QaQmU25BeXpMPPCgdPGY2hltW1dV9HYiWbuYAWcDPFIpb5YRjPDaUJkAvIowsxdvHGQHKZiLLq3Q59m60L8SZL6Cqmg3U6C63OCW6AdGMSPb82HPbQQO09BS',
			'body'         => [
				'title' => '幼稚城派幼稚鞋',
				'num'   => '100',
				'money' => '40.1',
			],
		];
		# 加密参数
		$str = dec_ryption(json_encode($array), false, 'R0P6KVYJLEQB8I1Q6VBBPIN3JQEX4TZCFT9BNZVG');
		# 请求
		$res = $this->https_request('http://127.0.0.1/3/index.php/v1/test/shop/add', $str);
		echo $res;
	}

	/**
	 * DEMO 获取新的access_token
	 */
	public function token(){
		# 组合请求参数
		$array = [
			'channel_id'   => 'PHV0IEGX5DMDOB3R7X02UCE',
		];
		# 加密参数
		$str = dec_ryption(json_encode($array), false, 'R0P6KVYJLEQB8I1Q6VBBPIN3JQEX4TZCFT9BNZVG');
		# 请求
		$res = $this->https_request('http://127.0.0.1/3/index.php/v1/test/access/token', $str);
		echo $res;
	}


	/**
	 * 发送curl请求，兼容POST||GET请求类型
	 * @param string $url   接口请求路径
	 * @param $mixed $data  请求内容
	 *@return $mixed 回调内容 
	 */
	public function https_request($url, $data = null){
		# 初始化一个cURL会话
		$curl = curl_init();  
		
		# 设置请求选项, 包括具体的url
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  // 禁用后cURL将终止从服务端进行验证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);            // 设置为post请求类型
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  // 设置具体的post数据
		}
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);		
		$response = curl_exec($curl);                       // 执行一个cURL会话并且获取相关回复
		curl_close($curl);                                  // 释放cURL句柄,关闭一个cURL会话
		
		return $response;
	}
}