目录结构
===============================================
小黄牛
-----------------------------------------------


WEB部署目录（或者子目录）

├─index.php       单一入口文件

├─index.html      使用AJAX请求接口的DEMO

├─App             应用目录

│  ├─Demo 使用CURL请求接口的DEMO

│  │   └─Test      

│  │      └─Controller

│  │          └─Index.php DEMO控制器

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
        
          ├─Common            框架公共配置与函数目录
         
          │   ├─Function.php  框架公共函数文件
         
          │   ├─Database.php  接口数据库配置文件
         
          │   └─Config.php    框架公共配置文件
  
          │

          └─Library          框架核心应用目录
               
              ├─Log        错误压制处理
                  
              │   ├─Log.php          错误重置类 
               
              │   └─Tpl              开启DeBug，默认错误信息打印页面
               
              │        └─Error.php   错误信息打印模板         
               
              │
               
              │

              ├─Api            Api核心类
               
              │  └─Api.php    主要用于生成token，渠道ID，渠道KEY，加解密也可以迁移至这里
               
              │
               
              ├─Model          数据库Model
  
              │  └─Model.php   PDO链式封装
               
              │

              ├─Route API路由核心
              
              │

              ├─Route.php     路由基类
               
              │  └─Infopath.php  路由处理类        