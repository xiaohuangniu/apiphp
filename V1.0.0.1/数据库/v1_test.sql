/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : v1_test

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-06-28 10:05:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for api_shop
-- ----------------------------
DROP TABLE IF EXISTS `api_shop`;
CREATE TABLE `api_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增',
  `title` varchar(50) NOT NULL COMMENT '商品名称',
  `num` int(11) NOT NULL COMMENT '库存数量',
  `money` varchar(12) NOT NULL COMMENT '金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of api_shop
-- ----------------------------
INSERT INTO `api_shop` VALUES ('1', '特步神鞋', '30', '500.1');
INSERT INTO `api_shop` VALUES ('2', '神牛派飞鞋', '1', '9999');
INSERT INTO `api_shop` VALUES ('3', '幼稚城派幼稚鞋', '100', '40.1');
INSERT INTO `api_shop` VALUES ('4', '幼稚城派幼稚鞋', '100', '40.1');
INSERT INTO `api_shop` VALUES ('5', '幼稚城派幼稚鞋', '100', '40.1');
