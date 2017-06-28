<?php
/*
 +--------------------------------------------------------------------------------------------------
 + Title        : Api框架 路由器核心基类
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 13:07
 + Last-time    : 2017-06-26 13:07 + 小黄牛
 + Desc         : 三大路由模式都将继承该父类
 +--------------------------------------------------------------------------------------------------
*/
namespace ApiPHP\ApiRoot\Library\Route;

class Route{
	/**
     * 开始监听路由
     */
	static public function Monitor(){
		Infopath::Go();
	}

	/**
     * 获得URL
     * @return string
     */
    static public function Path_Info() {
		# nginx环境下需要修改.conf文件才能支持
        if (isset($_SERVER['PATH_INFO'])) {
			return $_SERVER['PATH_INFO'];
		}
		
		if (isset($_SERVER['REDIRECT_PATH_INFO'])) {
			return $_SERVER['REDIRECT_PATH_INFO'];
		}
		
		if (isset($_SERVER['REDIRECT_URL'])) {
			return $_SERVER['REDIRECT_URL'];
		}
		
		return false;
    }

	/*
	 * 判断HTPPS协议
	 * 是则返回true
	*/
	static public function Route_Https(){
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return true;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		{
			return true;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return true;
		}
		return false;
	}
}