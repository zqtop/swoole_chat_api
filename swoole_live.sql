/*
Navicat MySQL Data Transfer

Source Server         : 192.168.169.138
Source Server Version : 50730
Source Host           : 192.168.169.138:3306
Source Database       : swoole_live

Target Server Type    : MYSQL
Target Server Version : 50730
File Encoding         : 65001

Date: 2020-08-23 20:08:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sw_chat
-- ----------------------------
DROP TABLE IF EXISTS `sw_chat`;
CREATE TABLE `sw_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `my_user_id` int(11) DEFAULT NULL COMMENT '我的用户id',
  `friend_user_id` int(11) DEFAULT NULL COMMENT '朋友的用户id',
  `create_time` datetime DEFAULT NULL,
  `content` text COMMENT '聊天内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of sw_chat
-- ----------------------------

-- ----------------------------
-- Table structure for sw_user
-- ----------------------------
DROP TABLE IF EXISTS `sw_user`;
CREATE TABLE `sw_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `create_time` datetime DEFAULT NULL COMMENT '用户创建时间',
  `nickname` varchar(30) DEFAULT NULL COMMENT '用户昵称',
  `avatar` text COMMENT '用户头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Records of sw_user
-- ----------------------------
INSERT INTO `sw_user` VALUES ('1', '820780348@qq.com', '2020-08-22 20:26:23', '美丽的黑裤', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgd2lkdGg9IjY1IiBoZWlnaHQ9IjY1IiB2aWV3Qm94PSIwIDAgNSA1Ij48cmVjdCB3aWR0aD0iNSIgaGVpZ2h0PSI1IiBmaWxsPSIjRkZGIiBzdHJva2Utd2lkdGg9IjAiLz48cGF0aCBmaWxsPSIjQTAxMEQwIiBzdHJva2Utd2lkdGg9IjAiIGQ9Ik0wLDBoMXYxaC0xdi0xTTEsMGgxdjFoLTF2LTFNMiwwaDF2MWgtMXYtMU0zLDBoMXYxaC0xdi0xTTQsMGgxdjFoLTF2LTFNMCwxaDF2MWgtMXYtMU0xLDFoMXYxaC0xdi0xTTIsMWgxdjFoLTF2LTFNMywxaDF2MWgtMXYtMU00LDFoMXYxaC0xdi0xTTAsMmgxdjFoLTF2LTFNMSwyaDF2MWgtMXYtMU0zLDJoMXYxaC0xdi0xTTQsMmgxdjFoLTF2LTFNMSw0aDF2MWgtMXYtMU0zLDRoMXYxaC0xdi0xTTAsNWgxdjFoLTF2LTFNNCw1aDF2MWgtMXYtMSIvPjwvc3ZnPg==');
INSERT INTO `sw_user` VALUES ('5', 'h17779640268@126.com', '2020-08-23 09:36:25', '苹果小馒头', 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgd2lkdGg9IjY1IiBoZWlnaHQ9IjY1IiB2aWV3Qm94PSIwIDAgNSA1Ij48cmVjdCB3aWR0aD0iNSIgaGVpZ2h0PSI1IiBmaWxsPSIjRkZGIiBzdHJva2Utd2lkdGg9IjAiLz48cGF0aCBmaWxsPSIjQjAxMDAiIHN0cm9rZS13aWR0aD0iMCIgZD0iTTIsMGgxdjFoLTF2LTFNMSwxaDF2MWgtMXYtMU0yLDFoMXYxaC0xdi0xTTMsMWgxdjFoLTF2LTFNMSwyaDF2MWgtMXYtMU0yLDJoMXYxaC0xdi0xTTMsMmgxdjFoLTF2LTFNMCwzaDF2MWgtMXYtMU0xLDNoMXYxaC0xdi0xTTMsM2gxdjFoLTF2LTFNNCwzaDF2MWgtMXYtMU0wLDRoMXYxaC0xdi0xTTQsNGgxdjFoLTF2LTFNMCw1aDF2MWgtMXYtMU00LDVoMXYxaC0xdi0xIi8+PC9zdmc+');
