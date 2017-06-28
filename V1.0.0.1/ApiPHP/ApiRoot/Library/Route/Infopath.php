<?php
/*
 +--------------------------------------------------------------------------------------------------
 + Title        : Api框架 路由核心文件
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 13:05
 + Last-time    : 2017-06-26 13:05 + 小黄牛
 + Desc         : 路由核心文件
 +--------------------------------------------------------------------------------------------------
*/

namespace ApiPHP\ApiRoot\Library\Route;
# 引入路由父类
use ApiPHP\ApiRoot\Library\Route\Route;

class Infopath extends Route{
	/**
     * 解析路由
	 * @return array 4个主要全局参数
     */
	static public function Go(){
		# 获得当前URL
		$path = self::Path_Info();
		
		# 是否包含后缀名，是则删除
		if (stripos($path, config('URL_SUFFIX')) !== false ) {
			$path = str_replace(config('URL_SUFFIX'), '', $path);
		}

		$path    = ltrim($path, config('URL_SPLIT'));    // 删除开头分隔符
		$paths   = explode(config('URL_SPLIT'), $path);  // 将URL分割成一维数组
		
		if (count($paths) != 4) {
			json('40001', '接口地址不规范');
		}
		
		# 获得版本 - 环境 - 控制器 - 操作方法
		$MODULE     = ucfirst( array_shift($paths) ); // 分组(首字母大写)
		$API        = ucfirst( array_shift($paths) ); // 环境(首字母大写)
		$CONTROLLER = ucfirst( array_shift($paths) ); // 控制器(首字母大写)
		$ACTION     = array_shift($paths);            // 方法
		
		# 初始化部分常量
		defined('MODULE')          or define('MODULE',        $MODULE);      // 当前版本
		defined('API')             or define('API',           $API);         // 当前环境
		defined('CONTROLLER')      or define('CONTROLLER',    $CONTROLLER);  // 当前控制名
		defined('ACTION')          or define('ACTION',        $ACTION);      // 当前操作方法名
		defined('IS_HTTPS')        or define('IS_HTTPS',      self::Route_Https());// 是否为HTTPS
		defined('__SELF__')        or define('__SELF__',      $_SERVER['REQUEST_URI']);// 当前URL地址  
		defined('__ROOT__')        or define('__ROOT__',      dirname($_SERVER['SCRIPT_NAME']).'/');// 网站根目录地址
		defined('__APP__')         or define('__APP__',       $_SERVER['SCRIPT_NAME']);// 网站根目录地址
		
		# 开启伪静态则不带文件名
		$top_url = (config('URL_STATIC')===true) ? __ROOT__ : __APP__;
		
		defined('__MODULE__')      or define('__MODULE__',    $top_url.MODULE);// 当前分组路径
		defined('__CONTROLLER__')  or define('__CONTROLLER__',__MODULE__.'/'.CONTROLLER);// 当前控制器路径
		defined('__ACTION__')      or define('__ACTION__',    __CONTROLLER__.'/'.ACTION);// 当前操作方法路径


		# 使用命名空间,加载控制器
		$url = "\App\\$MODULE\\$API\Controller\\$CONTROLLER";
		$obj = new $url;
		$obj->$ACTION();
	}
	
	
}