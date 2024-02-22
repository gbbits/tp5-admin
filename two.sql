/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : two

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-03-09 15:10:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for xwh_auth_group_rule
-- ----------------------------
DROP TABLE IF EXISTS `xwh_auth_group_rule`;
CREATE TABLE `xwh_auth_group_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '组名/管理员名',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态1显示2隐藏',
  `rules` varchar(255) NOT NULL DEFAULT '' COMMENT '权限表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='组/管理员对应权限表';

-- ----------------------------
-- Records of xwh_auth_group_rule
-- ----------------------------
INSERT INTO `xwh_auth_group_rule` VALUES ('1', '超级管理员', '1', '11,12,13,14,15,16,17,18,19,10,20,21');
INSERT INTO `xwh_auth_group_rule` VALUES ('2', '菜单管理员', '1', '11,12,13,14,15,16');

-- ----------------------------
-- Table structure for xwh_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `xwh_auth_rule`;
CREATE TABLE `xwh_auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT NULL COMMENT '父级id',
  `name` varchar(80) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单地址',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态1显示2隐藏',
  `sort` int(11) unsigned DEFAULT NULL COMMENT '排序值 越小越靠前',
  `tips` varchar(500) NOT NULL DEFAULT '' COMMENT '页面提示语',
  `left_key` int(11) unsigned DEFAULT NULL COMMENT '左区间',
  `right_key` int(11) unsigned DEFAULT NULL COMMENT '右区间',
  `level` int(11) unsigned DEFAULT NULL COMMENT '层级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COMMENT='规则表也是菜单表';

-- ----------------------------
-- Records of xwh_auth_rule
-- ----------------------------
INSERT INTO `xwh_auth_rule` VALUES ('10', '0', '管理员管理', '#', 'menu-icon fa fa-user', '1', '4', '管理员管理', '17', '22', '1');
INSERT INTO `xwh_auth_rule` VALUES ('11', '0', '仪表板', 'Index/index', 'menu-icon fa fa-tachometer', '1', '1', '仪表板', '23', '24', '1');
INSERT INTO `xwh_auth_rule` VALUES ('12', '0', '菜单管理', '#', 'menu-icon fa fa-navicon', '1', '2', '菜单管理', '7', '16', '1');
INSERT INTO `xwh_auth_rule` VALUES ('13', '12', '后台菜单列表', 'Menu/adminList', 'menu-icon fa fa-caret-right', '1', '51', '后台菜单列表', '14', '15', '2');
INSERT INTO `xwh_auth_rule` VALUES ('14', '12', '增改后台菜单', 'Menu/adminMenu', 'menu-icon fa fa-caret-right', '1', '52', '增改后台菜单', '12', '13', '2');
INSERT INTO `xwh_auth_rule` VALUES ('15', '12', '前台菜单列表', 'Menu/homeList', 'menu-icon fa fa-caret-right', '1', '53', '前台菜单列表', '10', '11', '2');
INSERT INTO `xwh_auth_rule` VALUES ('16', '12', '增改前台菜单', 'Menu/homeMenu', 'menu-icon fa fa-caret-right', '1', '54', '增改前台菜单', '8', '9', '2');
INSERT INTO `xwh_auth_rule` VALUES ('17', '0', '组/权限管理', '#', 'menu-icon fa fa-users', '1', '3', '组/权限管理', '1', '6', '1');
INSERT INTO `xwh_auth_rule` VALUES ('18', '17', '组列表', 'Group/index', 'menu-icon fa fa-caret-right', '1', '51', '组列表', '4', '5', '2');
INSERT INTO `xwh_auth_rule` VALUES ('19', '17', '增改组/权限', 'Group/add', 'menu-icon fa fa-caret-right', '1', '52', '增改组/权限', '2', '3', '2');
INSERT INTO `xwh_auth_rule` VALUES ('20', '10', '管理员列表', 'User/index', 'menu-icon fa fa-caret-right', '1', '51', '管理员列表', '20', '21', '2');
INSERT INTO `xwh_auth_rule` VALUES ('21', '10', '增改管理员', 'User/add', 'menu-icon fa fa-caret-right', '1', '52', '增改管理员', '18', '19', '2');

-- ----------------------------
-- Table structure for xwh_auth_user_group
-- ----------------------------
DROP TABLE IF EXISTS `xwh_auth_user_group`;
CREATE TABLE `xwh_auth_user_group` (
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '后台用户id',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '组/管理员id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户对应组/管理员表';

-- ----------------------------
-- Records of xwh_auth_user_group
-- ----------------------------
INSERT INTO `xwh_auth_user_group` VALUES ('1', '1');
INSERT INTO `xwh_auth_user_group` VALUES ('2', '2');

-- ----------------------------
-- Table structure for xwh_log_admin
-- ----------------------------
DROP TABLE IF EXISTS `xwh_log_admin`;
CREATE TABLE `xwh_log_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '登录ip地址',
  `log` varchar(500) NOT NULL DEFAULT '' COMMENT '日志',
  `add_time` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='后台日志表';

-- ----------------------------
-- Records of xwh_log_admin
-- ----------------------------
INSERT INTO `xwh_log_admin` VALUES ('1', 'admin', '127.0.0.1', '自动登录：admin', '1520576592');

-- ----------------------------
-- Table structure for xwh_user_admin
-- ----------------------------
DROP TABLE IF EXISTS `xwh_user_admin`;
CREATE TABLE `xwh_user_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `salt_value` varchar(255) NOT NULL DEFAULT '' COMMENT '密码盐值',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别 0保密 1男2女',
  `photo` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `add_time` int(10) unsigned DEFAULT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='后台用户表';

-- ----------------------------
-- Records of xwh_user_admin
-- ----------------------------
INSERT INTO `xwh_user_admin` VALUES ('1', 'admin', 'fe7a8456c3ff9e5686114c9d3aca642e', 'jxtpjB', '1', '/upload/images/headImg/20180306\\f779466901ea413fa15337f2c63ca841.jpg', '15523305071', '179001243@qq.com', '1520301830');
INSERT INTO `xwh_user_admin` VALUES ('2', 'xwh', '73eddbfd9ff69b34925168c0878283e4', 'WSaHmA', '0', '/upload/images/headImg\\20180307\\2e0ddb91ed43451759a15bf27f2ee216.jpg', '15523305071', '179001243@qq.com', '1520398614');
