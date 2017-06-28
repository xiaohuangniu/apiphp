<?php
/*
 +--------------------------------------------------------------------------------------------------
 + Title        : Api框架 API核心类
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-27 10:17
 + Last-time    : 2017-06-27 10:17 + 小黄牛
 + Desc         : 主要用于：生成access_toekn、渠道ID、渠道key
 +--------------------------------------------------------------------------------------------------
*/
namespace ApiPHP\ApiRoot\Library\Api;

class Api{
	private $txt       = 'Z1X2C3V4B5N6M7A8S9D0FGHJKLQWERTYUIOP';                           // 渠道ID与渠道KEY的数据池
	private $Randomkey = 'Z1X2C3V4B5Np6oMi7uAy8tSr9eDw0qFlGkHjJhKgLfQdWsEaRmTnYbUvIcOxPz'; // Access_Token的数据池

	/**
	 * 生成长度为255的access_token
	 * @return string
	 */
	public function set_access_token(){
		$str = '';
		for ($i = 0; $i < 255; $i++) {
			 $str .= $this->Randomkey[mt_rand(0,60)];
		}
		return $str;
	}

	/**
	 * 生成长度为23的渠道ID
	 * (唯一)
	 * @return string
	 */
	public function set_channel_id(){
		$str = '';
		for ($i = 0; $i < 23; $i++) {
			 $str .= $this->txt[mt_rand(0,35)];
		}
		return $str;
	}

	/**
	 * 生成长度为40的渠道KEY
	 * @return string
	 */
	public function set_channel_key(){
		$str = '';
		for ($i = 0; $i < 40; $i++) {
			 $str .= $this->txt[mt_rand(0,35)];
		}
		return $str;
	}

}