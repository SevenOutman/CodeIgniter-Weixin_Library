/*
 Navicat Premium Data Transfer

 Source Server         : Pk
 Source Server Type    : MySQL
 Source Server Version : 50077
 Source Host           : 121.199.7.139
 Source Database       : pk_database

 Target Server Type    : MySQL
 Target Server Version : 50077
 File Encoding         : utf-8

 Date: 05/16/2016 13:14:25 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `wx_user`
-- ----------------------------
DROP TABLE IF EXISTS `wx_user`;
CREATE TABLE `wx_user` (
  `wx_user_id` int(11) NOT NULL auto_increment,
  `openid` varchar(32) NOT NULL,
  `nickname` varchar(32) default NULL,
  `sex` int(2) default NULL,
  `province` varchar(16) default NULL,
  `city` varchar(16) default NULL,
  `country` varchar(16) default NULL,
  `headimgurl` varchar(255) default NULL,
  `unionid` varchar(32) default NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY  (`wx_user_id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
