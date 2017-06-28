<?php
/*
 +----------------------------------------------------------------------
 + Title        : ApiPHP框架 核心加载入口文件
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 11:57
 + Last-time    : 2017-06-26 11:57 + 小黄牛
 + Desc         : 主要的框架内容都将在这里被挑起应用,再被上一级ApiConstant.php文件引用
 +----------------------------------------------------------------------
*/

# 加载框架共用函数
require COMMON_PATH.'Function'.EXT;
# 加载错误与日志监听

require LIB_PATH.'Log/Log'.EXT;
Log::start();


# 使用命名空间, 自动加载class文件
spl_autoload_register(function ($class) { 
	spl_autoload_extensions(EXT);
	spl_autoload($class);
});

# 加载路由监听
ApiPHP\ApiRoot\Library\Route\Route::Monitor();