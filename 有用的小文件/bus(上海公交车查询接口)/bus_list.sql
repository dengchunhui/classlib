/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : bus

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2016-01-10 23:06:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bus_list
-- ----------------------------
DROP TABLE IF EXISTS `bus_list`;
CREATE TABLE `bus_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `create_time` int(11) NOT NULL,
  `lineid` varchar(20) NOT NULL COMMENT '车次',
  `terminal` varchar(20) NOT NULL COMMENT '公交车牌号',
  `stopdis` varchar(20) NOT NULL COMMENT '还剩几站到指定站点',
  `time` int(11) NOT NULL COMMENT '还剩多少时间到指定站点',
  `refer_site` varchar(20) NOT NULL COMMENT '查询的站点的名称',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bus_list
-- ----------------------------
INSERT INTO `bus_list` VALUES ('24', '1452265932', '015800', '沪B-71841', '3', '190', '43');
INSERT INTO `bus_list` VALUES ('25', '1452265932', '015800', '11', '22', '444', '43');
