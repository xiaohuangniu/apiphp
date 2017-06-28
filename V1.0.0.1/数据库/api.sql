/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : api

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-06-28 10:05:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for api_channel
-- ----------------------------
DROP TABLE IF EXISTS `api_channel`;
CREATE TABLE `api_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增',
  `phone` varchar(15) NOT NULL COMMENT '手机号',
  `pwd` varchar(25) NOT NULL COMMENT '密码',
  `channel_id` varchar(70) NOT NULL COMMENT '渠道ID',
  `channel_key` varchar(120) NOT NULL COMMENT '渠道密钥',
  `channel_formal_url` varchar(60) NOT NULL COMMENT '生产环境的渠道地址',
  `channel_test_url` varchar(60) NOT NULL COMMENT '测试环境的渠道地址',
  `add_time` int(10) NOT NULL COMMENT '添加时间，盐',
  `access_token` varchar(255) NOT NULL COMMENT 'Access_Token，接口凭证',
  `token_start_time` int(10) NOT NULL COMMENT '凭证生成时间',
  `token_start_out` int(10) NOT NULL COMMENT '凭证过期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='渠道表';

-- ----------------------------
-- Records of api_channel
-- ----------------------------
INSERT INTO `api_channel` VALUES ('1', '15992431680', '', 'PHV0IEGX5DMDOB3R7X02UCE', 'R0P6KVYJLEQB8I1Q6VBBPIN3JQEX4TZCFT9BNZVG', 'http://127.0.0.1/', 'http://127.0.0.1/', '1498531483', 'nJBeAbcjeV2Xq0qullDVIvX56sNcTuWAZvggQHfgO1DJ6F90TdxtRABCBhfSlI2KY7c0ULhpQmkWcQ01ToO18mAfXjv9Kkj2wvfFaalNhxEcqNffA3LkusdOMKImBiJJSvqnwde2EY0TW3tXQ1evxJysYkeFLno6Bg4tPbmxXBRalGNatWWj6GT2OyTp8TuAFK41qV6wZpQ36xEnitA1uo6x9QCKnaH149fblTu8Z3v5UwUGGyL6dscISEI10Dg', '1498612078', '1498619278');
