<?php
/*
 +--------------------------------------------------------------------------------------------------
 + Title        : ApiPHP框架 核心文件的公共入口文件
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 11:54
 + Last-time    : 2017-06-26 11:54 + 小黄牛
 + Desc         : 框架的所有核心文件,都必须在这里加载才能被index.php文件引用 - 主要是初始化某部分常量参数与判断
 +--------------------------------------------------------------------------------------------------
*/

# 判断PHP版本是否达到框架要求
if (version_compare('5.5', PHP_VERSION, '>')) {
     die('PHP版本小于 5.5!!!');
}

# 部分初始化常量定义 - 用于统一核心文件的引入路径
const EXT =   '.php';  // 类文件的统一后缀名
defined('APP_PATH')     or define('APP_PATH',       dirname($_SERVER['SCRIPT_FILENAME']).'/'); // 框架根目录,用于文件载入
defined('APP_MICRO')    or define('APP_MICRO',      APP_PATH.'ApiPHP/');   // 框架核心根目录
defined('API_PATH')     or define('API_PATH',       APP_MICRO.'ApiRoot/'); // 框架核心文件根目录
defined('COMMON_PATH')  or define('COMMON_PATH',    API_PATH.'Common/');   // 框架公共初始函数目录
defined('LIB_PATH')     or define('LIB_PATH',       API_PATH.'Library/');  // 框架核心应用目录
defined('GO_PATH')      or define('GO_PATH',        APP_PATH.'App/');      // 项目开发应用目录

# 是否为AJAX访问
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'); 
# 是否为POST访问
define("IS_POST", strtolower($_SERVER['REQUEST_METHOD']) == 'post');
# 是否为GET访问
define("IS_GET",  strtolower($_SERVER['REQUEST_METHOD']) == 'get');

defined('APP_DEBUG')    or define('APP_DEBUG',      false); // 是否调试模式

# 加载框架核心类
require API_PATH.'Api.Root'.EXT;