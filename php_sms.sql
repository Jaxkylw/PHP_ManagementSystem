/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80023
 Source Host           : localhost:3306
 Source Schema         : php_sms

 Target Server Type    : MySQL
 Target Server Version : 80023
 File Encoding         : 65001

 Date: 17/05/2023 22:00:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for administrator
-- ----------------------------
DROP TABLE IF EXISTS `administrator`;
CREATE TABLE `administrator`  (
  `id` int NOT NULL,
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `permission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of administrator
-- ----------------------------
INSERT INTO `administrator` VALUES (1, 'admin', 'admin', 'admin', '某教务老师');

-- ----------------------------
-- Table structure for gradestable
-- ----------------------------
DROP TABLE IF EXISTS `gradestable`;
CREATE TABLE `gradestable`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id',
  `teacher` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '任课老师',
  `teacheruid` int NOT NULL COMMENT '老师工号',
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '任课科目',
  `subject_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '科目代码',
  `student_uid` int NULL DEFAULT NULL COMMENT '学生uid',
  `student_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '学生姓名',
  `student_major` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '学生专业',
  `student_grade` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '学生班级',
  `student_achievement` float(255, 2) NULL DEFAULT 0.00 COMMENT '学生成绩',
  `teacher_account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '老师姓名',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of gradestable
-- ----------------------------
INSERT INTO `gradestable` VALUES (2, '冯书杰', 20001, 'Python程序设计', 'python', NULL, NULL, NULL, NULL, 0.00, 'teacher');
INSERT INTO `gradestable` VALUES (4, '冯书杰', 20001, 'PHP应用开发', 'php', NULL, NULL, NULL, NULL, 0.00, 'teacher');
INSERT INTO `gradestable` VALUES (22, '冯书杰', 20001, 'Mysql基础入门', 'mysql', NULL, NULL, NULL, NULL, 0.00, 'teacher');
INSERT INTO `gradestable` VALUES (27, '冯书杰', 20001, 'Python程序设计', 'python', 10005, 'fff', '网络工程', '22302', 99.00, 'teacher');
INSERT INTO `gradestable` VALUES (28, '冯书杰', 20001, 'Mysql基础入门', 'mysql', 10005, 'fff', '网络工程', '22302', 60.00, 'teacher');
INSERT INTO `gradestable` VALUES (29, '冯书杰', 20001, 'PHP应用开发', 'php', 10005, 'fff', '网络工程', '22302', 85.00, 'teacher');

-- ----------------------------
-- Table structure for student
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '访客' COMMENT '姓名',
  `gender` enum('男','女','未录入') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '未录入' COMMENT '性别',
  `major` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '未录入' COMMENT '专业',
  `class` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '未录入' COMMENT '班级',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `permission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'student' COMMENT '权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10007 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student
-- ----------------------------
INSERT INTO `student` VALUES (10001, 'jacky', '8ddcff3a80f4189ca1c9d4d902c3c909', '李四', '男', '网络工程', '22301', '2023-04-26 16:16:17', 'student');
INSERT INTO `student` VALUES (10002, 'admin', 'f5bb0c8de146c67b44babbf4e6584cc0', '王五', '男', '网络工程', '22302', '2023-04-26 16:43:44', 'student');
INSERT INTO `student` VALUES (10003, 'admin2', 'f5bb0c8de146c67b44babbf4e6584cc0', '访客', '男', '未录入', '未录入', '2023-04-26 16:52:52', 'student');
INSERT INTO `student` VALUES (10004, 'jacky1', 'f5bb0c8de146c67b44babbf4e6584cc0', '1', '男', '1', '1', '2023-04-26 19:25:27', 'student');
INSERT INTO `student` VALUES (10005, '123123', 'f5bb0c8de146c67b44babbf4e6584cc0', 'fff', '男', '网络工程', '22302', '2023-04-27 18:48:50', 'student');
INSERT INTO `student` VALUES (10006, 'aaaaa', 'f5bb0c8de146c67b44babbf4e6584cc0', '张三', '男', '网络工程', '22301', '2023-05-03 21:02:30', 'student');

-- ----------------------------
-- Table structure for studenttmp
-- ----------------------------
DROP TABLE IF EXISTS `studenttmp`;
CREATE TABLE `studenttmp`  (
  `prid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id` int NULL DEFAULT NULL COMMENT '学号',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `major` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '专业',
  `class` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '班级',
  `priname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原姓名',
  `primajor` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原专业',
  `priclass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原班级',
  `tag` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '012标记未对错',
  PRIMARY KEY (`prid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10007 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of studenttmp
-- ----------------------------
INSERT INTO `studenttmp` VALUES (10009, 10005, '1', '1', '1', '冯书杰', '网络工程', '22302', '1');
INSERT INTO `studenttmp` VALUES (10010, 10004, '1', '1', '1', '杰克', '网络工程', '22302', '1');
INSERT INTO `studenttmp` VALUES (10011, 10005, '冯书杰', '网络工程', '22302', '1', '1', '1', '1');
INSERT INTO `studenttmp` VALUES (10012, 10005, '冯书杰', '网络工程', '22302', '1', '1', '1', '1');
INSERT INTO `studenttmp` VALUES (10013, 10005, '2', '2', '2', '1', '1', '1', '1');
INSERT INTO `studenttmp` VALUES (10014, 10005, '冯书杰', '网络工程', '22302', '1', '1', '1', '1');
INSERT INTO `studenttmp` VALUES (10015, 10001, '李四', '网络工程', '22301', '访客', '未录入', '未录入', '1');
INSERT INTO `studenttmp` VALUES (10016, 10005, 'fff', '网络工程', '22302', '冯书杰', '网络工程', '22302', '1');
INSERT INTO `studenttmp` VALUES (10017, 10002, '王五', '网络工程', '22302', 'admin', '未录入', '未录入', '1');
INSERT INTO `studenttmp` VALUES (10018, 10002, '1', '1', '1', 'admin', '未录入', '未录入', '1');
INSERT INTO `studenttmp` VALUES (10019, 10002, '王五', '网络工程', '22302', 'admin', '未录入', '未录入', '1');

-- ----------------------------
-- Table structure for teacher
-- ----------------------------
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'uid',
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '默认教师' COMMENT '姓名',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `permission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'teacher' COMMENT '权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20015 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teacher
-- ----------------------------
INSERT INTO `teacher` VALUES (20001, 'teacher', 'f5bb0c8de146c67b44babbf4e6584cc0', '冯书杰', '2023-04-28 10:32:30', 'teacher');
INSERT INTO `teacher` VALUES (20013, 'teacher3', 'f5bb0c8de146c67b44babbf4e6584cc0', '张三', '2023-05-14 13:58:05', 'teacher');

SET FOREIGN_KEY_CHECKS = 1;
