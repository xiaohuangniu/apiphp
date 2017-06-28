基于PHP5.5 编写对外API接口的交互流程与后续版本维护
===============================================
小黄牛
-----------------------------------------------

### 1731223728@qq.com 

+ 当前最新版本 - V1.0.0.1

+ 作者 - 小黄牛

+ 邮箱 - 1731223728@qq.com     


## 初衷说明

+ 写这个DEMO的初衷是为了让自己更好的熟悉接口开发流程，以及与朋友们的日常交流学习，期望在技术层面有所进步。


### 模式介绍

+ 1、本DEMO是一个基于原生PHP开发的项目，主要面向对外开发的API用户群，后面我们统称为“渠道”

+ 2、渠道商通过注册，获得channel_id(身份识别码码)与channel_key(数据加解密凭证)，请求access_token生成接口，获得接口使用凭证，进行后续所有接口的访问。

+ 3、渠道商还需绑定test与formal环境的来源URL（真正项目建议换成IP），否则将会被限制访问

+ 4、本DEMO定制API访问路由为 index.php/版本/环境/类型/方法，例如：index.php/v1/test/shop/select，即为，V1版本test环境下的shop商品模块的数据查询请求。

+ 5、为方便后期版本维护，本DEMO的数据库架构模式为蜂窝型，除了一个公共数据库外，其余每一个版本将对应两个数据存储的数据库，详情参考下发数据配置文件。


### 数据库配置一览

```
return [
    /******************************************* 公共数据表名，用于自动切换版本数据库 ************************************/
    'DB_PUBLIC'        => ['channel'],

    /******************************************* 公共数据库配置  *******************************************************/
	"DB_TYPE"          =>  "mysql",     		  	   // 数据库类型
	"DB_HOST"          =>  "localhost", 		   	   // 服务器地址
	"DB_NAME"          =>  "api",      		  	       // 数据库名
	"DB_USER"          =>  "root",   			  	   // 用户名
	"DB_PWD"           =>  "root",       			   // 密码
	"DB_PORT"          =>  "3306",       		  	   // 端口
	"DB_PREFIX"        =>  "api_",    				   // 数据库表前缀
	"DB_CHARSET"       =>  "utf8",   			  	   // 数据库编码



    /******************************************* 版本数据库配置 - V1  *************************************************/
    "V1"               => [
       "Test" => [ // 测试环境
            "DB_TYPE"          =>  "mysql",     		  	   // 数据库类型
            "DB_HOST"          =>  "localhost", 		   	   // 服务器地址
            "DB_NAME"          =>  "v1_test",      		  	   // 数据库名
            "DB_USER"          =>  "root",   			  	   // 用户名
            "DB_PWD"           =>  "root",       			   // 密码
            "DB_PORT"          =>  "3306",       		  	   // 端口
            "DB_PREFIX"        =>  "api_",    				   // 数据库表前缀
            "DB_CHARSET"       =>  "utf8",   			  	   // 数据库编码
       ],
       "Formal" => [ // 生产环境
            "DB_TYPE"          =>  "mysql",     		  	   // 数据库类型
            "DB_HOST"          =>  "localhost", 		   	   // 服务器地址
            "DB_NAME"          =>  "v1_formal",      		   // 数据库名
            "DB_USER"          =>  "root",   			  	   // 用户名
            "DB_PWD"           =>  "root",       			   // 密码
            "DB_PORT"          =>  "3306",       		  	   // 端口
            "DB_PREFIX"        =>  "api_",    				   // 数据库表前缀
            "DB_CHARSET"       =>  "utf8",   			  	   // 数据库编码
       ],
    ],


];
````

### 状态码一览

00        请求成功

01        请求失败

40001     接口地址不规范                               

40002     请求内容，不能为空                            

40003     渠道ID或Key错误，或数据类型错误              

40004     渠道ID，不能为空                              

40005     Access_Token，不能为空                        

40006     渠道不存在                                    

40007     Access_Token已失效，请重新获取                

40008     Access_Token错误                              

40009     非法请求，来源地址错误                        

400010    渠道KEY，不能为空                            

50000     服务器错误 


### 目录结构一览

WEB部署目录（或者子目录）
├─index.php       单一入口文件
├─index.html      使用AJAX请求接口的DEMO
│
├─App             应用目录
│  ├─Demo         使用CURL请求接口的DEMO
│  │   └─Test      
│  │      └─Controller
│  │          └─Index.php DEMO控制器
│  │
│  │
│  ├─ParentClass  接口版本控制管理
│  │   └─V1.php   V1版本接口的父类控制器
│  │
│  └─V1 V1版本接口管理
│     └─Test
│        └─Controller
│           ├─Access.php 生成access_token的接口
│           └─Shop.php   商品信息相关的接口
│
│
└─ApiPHP 框架目录
   ├─ApiConstant.php    框架核心加载入口文件
   └─ApiRoot            框架核心文件目录                      
        ├─Api.Root.php  框架所有驱动统一加载
        └─Common            框架公共配置与函数目录
         │  ├─Function.php  框架公共函数文件
         │  ├─Database.php  接口数据库配置文件
         │  └─Config.php    框架公共配置文件
         │  
         └─Library          框架核心应用目录
               ├─Log        错误压制处理
               │   ├─Log.php          错误重置类 
               │   └─Tpl              开启DeBug，默认错误信息打印页面
               │        └─Error.php   错误信息打印模板         
               │
               │  
               ├─Api            Api核心类
               │  └─Api.php     主要用于生成token，渠道ID，渠道KEY，加解密也可以迁移至这里
               │
               ├─Model          数据库Model
               │  └─Model.php   PDO链式封装
               │
               ├─Route API路由核心     
               │  ├─Route.php     路由基类
               │  └─Infopath.php  路由处理类        


### 模拟API调用的DEMO地址

1、/index.php/demo/test/index/index    查询商品

2、/index.php/demo/test/index/test     新增商品

3、/index.php/demo/test/index/token    获取access_token