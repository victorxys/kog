/*
 Navicat Premium Data Transfer

 Source Server         : GCP-allin
 Source Server Type    : MySQL
 Source Server Version : 50725
 Source Host           : 35.194.145.199:3306
 Source Schema         : kog

 Target Server Type    : MySQL
 Target Server Version : 50725
 File Encoding         : 65001

 Date: 12/11/2019 15:04:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kog_commentmeta
-- ----------------------------
DROP TABLE IF EXISTS `kog_commentmeta`;
CREATE TABLE `kog_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for kog_comments
-- ----------------------------
DROP TABLE IF EXISTS `kog_comments`;
CREATE TABLE `kog_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_comments
-- ----------------------------
BEGIN;
INSERT INTO `kog_comments` VALUES (1, 1, '一位WordPress评论者', 'wapuu@wordpress.example', 'https://wordpress.org/', '', '2018-11-27 17:21:43', '2018-11-27 09:21:43', '嗨，这是一条评论。\n要开始审核、编辑及删除评论，请访问仪表盘的“评论”页面。\n评论者头像来自<a href=\"https://gravatar.com\">Gravatar</a>。', 0, '1', '', '', 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for kog_kog_details
-- ----------------------------
DROP TABLE IF EXISTS `kog_kog_details`;
CREATE TABLE `kog_kog_details` (
  `id` mediumint(6) NOT NULL AUTO_INCREMENT,
  `gid` mediumint(6) NOT NULL,
  `player` varchar(45) NOT NULL,
  `uid` mediumint(6) DEFAULT NULL,
  `start_chips` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `end_chips` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `rank` int(8) DEFAULT NULL,
  `bonus` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `seat_position` varchar(45) NOT NULL,
  `count_chips` varchar(45) DEFAULT NULL,
  `reward` varchar(45) DEFAULT NULL,
  `total_should` varchar(45) DEFAULT NULL,
  `total_fact` varchar(45) DEFAULT NULL,
  `real_chips` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=376 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kog_kog_details
-- ----------------------------
BEGIN;
INSERT INTO `kog_kog_details` VALUES (1, 16, '续', 1, '1000', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (2, 16, '仉', 2, '1000', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (3, 16, '翠', 3, '1000', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (4, 16, 'Holy', 4, '1000', NULL, NULL, NULL, '4', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (5, 17, '续', 1, '1000', '2000', 2, '100', '1', '1000', '74', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (6, 17, '仉', 2, '1000', '1750', 3, '-100', '2', '250', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (7, 17, '翠', 3, '1000', '2830', 4, '-400', '3', '-2170', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (8, 17, 'Holy', 4, '1000', '1920', 1, '400', '4', '1920', '143', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (9, 18, '续', 1, '1000', '800', 3, '-100', '2', '800', '0', '-100', '-100', '-20');
INSERT INTO `kog_kog_details` VALUES (10, 18, '仉', 2, '1000', '0', 4, '-400', '1', '-1000', '-100', '-500', '-500', '-200');
INSERT INTO `kog_kog_details` VALUES (11, 18, '翠', 3, '1000', '2700', 2, '100', '3', '1700', '40', '140', '140', '70');
INSERT INTO `kog_kog_details` VALUES (12, 18, 'Holy', 4, '1000', '2500', 1, '400', '4', '2500', '60', '460', '460', '150');
INSERT INTO `kog_kog_details` VALUES (13, 19, '续', 1, '1000', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (14, 19, '仉', 2, '1000', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (15, 19, '翠', 3, '1000', NULL, NULL, NULL, '4', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (16, 19, 'Holy', 4, '1000', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (17, 20, '续', 1, '1000', NULL, NULL, NULL, '4', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (18, 20, '仉', 2, '1000', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (19, 20, '翠', 3, '1000', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (20, 20, 'Holy', 4, '1000', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (21, 21, '续', 1, '1000', '1990', 1, '400', '4', '1990', '0', '400', '400', '99');
INSERT INTO `kog_kog_details` VALUES (22, 21, '仉', 2, '1000', '1000', 4, '-400', '1', '0', '0', '-400', '-400', '-100');
INSERT INTO `kog_kog_details` VALUES (23, 21, '翠', 3, '1000', '1900', 2, '100', '3', '1900', '0', '100', '100', '90');
INSERT INTO `kog_kog_details` VALUES (24, 21, 'Holy', 4, '1000', '2140', 3, '-100', '2', '140', '0', '-100', '-100', '-86');
INSERT INTO `kog_kog_details` VALUES (25, 22, '续', 1, '1000', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (26, 22, '仉', 2, '1000', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (27, 22, '翠', 3, '1000', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (28, 22, 'Holy', 4, '1000', NULL, NULL, NULL, '4', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (29, 23, '续', 1, '1000', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (30, 23, '仉', 2, '1000', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (31, 23, '翠', 3, '1000', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (32, 23, 'Holy', 4, '1000', NULL, NULL, NULL, '4', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (33, 24, '续', 1, '1000', '1970', 1, '400', '4', '1970', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (34, 24, '仉', 2, '1000', '1000', 4, '-400', '1', '0', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (35, 24, '翠', 3, '1000', '1900', 2, '100', '3', '1900', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (36, 24, 'Holy', 4, '1000', '2190', 3, '-100', '2', '190', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (37, 25, '续', 1, '1000', '2500', 1, '400', '1', '2500', '0', '400', '400', '150');
INSERT INTO `kog_kog_details` VALUES (38, 25, '仉', 2, '1000', '350', 4, '-400', '4', '350', '0', '-400', '-400', '-65');
INSERT INTO `kog_kog_details` VALUES (39, 25, '翠', 3, '1000', '690', 2, '100', '2', '690', '0', '100', '100', '-31');
INSERT INTO `kog_kog_details` VALUES (40, 25, 'Holy', 4, '1000', '1460', 3, '-100', '3', '460', '0', '-100', '-100', '-54');
INSERT INTO `kog_kog_details` VALUES (41, 26, '续', 1, '1000', '4890', 1, '400', '4', '4890', '326', '726', '426', '389');
INSERT INTO `kog_kog_details` VALUES (42, 26, '仉', 2, '1000', '2850', 3, '-100', '3', '1850', '0', '-100', '-100', '85');
INSERT INTO `kog_kog_details` VALUES (43, 26, '翠', 3, '1000', '915', 4, '-400', '1', '-5085', '-508', '-908', '-608', '-608.5');
INSERT INTO `kog_kog_details` VALUES (44, 26, 'Holy', 4, '1000', '2720', 2, '100', '2', '2720', '182', '282', '282', '172');
INSERT INTO `kog_kog_details` VALUES (45, 27, '续', 1, '1000', '4575', 1, '200', '2', '4575', '63', '263', '253', '357.5');
INSERT INTO `kog_kog_details` VALUES (46, 27, '仉', 2, '1000', '1100', 2, '0', '1', '100', '0', '0', '0', '-90');
INSERT INTO `kog_kog_details` VALUES (47, 27, '二子', 7, '1000', '365', 3, '-200', '4', '-635', '-63', '-263', '-263', '-163.5');
INSERT INTO `kog_kog_details` VALUES (48, 30, 'Holy', 4, '1000', '1650', 3, '-100', '1', '650', '0', '-100', '-100', '-35');
INSERT INTO `kog_kog_details` VALUES (49, 30, '翠', 3, '1000', '2415', 1, '400', '2', '2415', '0', '400', '400', '141.5');
INSERT INTO `kog_kog_details` VALUES (50, 30, '仉', 2, '1000', '760', 2, '100', '3', '760', '0', '100', '100', '-24');
INSERT INTO `kog_kog_details` VALUES (51, 30, '续', 1, '1000', '1170', 4, '-400', '4', '170', '0', '-400', '-400', '-83');
INSERT INTO `kog_kog_details` VALUES (52, 31, '翠', 3, '1000', '2920', 2, '100', '1', '1920', '84', '184', '184', '92');
INSERT INTO `kog_kog_details` VALUES (53, 31, 'Holy', 4, '1000', '1980', 3, '-100', '2', '980', '0', '-100', '-100', '-2');
INSERT INTO `kog_kog_details` VALUES (54, 31, '续', 1, '1000', '2620', 4, '-400', '3', '-2380', '-238', '-638', '-638', '-338');
INSERT INTO `kog_kog_details` VALUES (55, 31, '仉', 2, '1000', '3530', 1, '400', '4', '3530', '154', '554', '554', '253');
INSERT INTO `kog_kog_details` VALUES (56, 32, '二子', 7, '1000', '1165', 3, '0', '1', '1165', '0', '0', '0', '16.5');
INSERT INTO `kog_kog_details` VALUES (57, 32, '翠', 3, '1000', '1940', 2, '100', '2', '1940', '36', '136', '136', '94');
INSERT INTO `kog_kog_details` VALUES (58, 32, '仉', 2, '1000', '2200', 5, '-400', '3', '-800', '-80', '-480', '-480', '-180');
INSERT INTO `kog_kog_details` VALUES (59, 32, '续', 1, '1000', '1405', 4, '-100', '4', '405', '0', '-100', '-100', '-59.5');
INSERT INTO `kog_kog_details` VALUES (60, 32, 'Holy', 4, '1000', '2415', 1, '400', '5', '2415', '44', '444', '444', '141.5');
INSERT INTO `kog_kog_details` VALUES (61, 33, '翠', 3, '1000', '1025', 2, '100', '1', '1025', '23', '123', '123', '2.5');
INSERT INTO `kog_kog_details` VALUES (62, 33, '仉', 2, '1000', '1475', 3, '-100', '2', '-525', '-52', '-152', '-152', '-152.5');
INSERT INTO `kog_kog_details` VALUES (63, 33, '续', 1, '1000', '2385', 4, '-400', '3', '-615', '-61', '-461', '-461', '-161.5');
INSERT INTO `kog_kog_details` VALUES (64, 33, 'Holy', 4, '1000', '4115', 1, '400', '4', '4115', '90', '490', '490', '311.5');
INSERT INTO `kog_kog_details` VALUES (65, 34, '翠', 3, '1000', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (66, 34, '续', 1, '1000', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (67, 34, '翠', 3, '1000', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (68, 35, '仉', 2, '1000', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (69, 35, '续', 1, '1000', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (70, 35, '翠', 3, '1000', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (71, 36, '仉', 2, '1000', '895', 3, '-400', '1', '-105', '-10', '-410', '-410', '-110.5');
INSERT INTO `kog_kog_details` VALUES (72, 36, '续', 1, '1000', '1935', 1, '300', '2', '1935', '6', '306', '306', '93.5');
INSERT INTO `kog_kog_details` VALUES (73, 36, '翠', 3, '1000', '1170', 2, '100', '3', '1170', '4', '104', '104', '17');
INSERT INTO `kog_kog_details` VALUES (74, 37, 'Holy', 4, '1000', '1650', 2, '100', '1', '1650', '14', '114', '114', '65');
INSERT INTO `kog_kog_details` VALUES (75, 37, '翠', 3, '1000', '1570', 3, '-100', '2', '1570', '0', '-100', '-100', '57');
INSERT INTO `kog_kog_details` VALUES (76, 37, '仉', 2, '1000', '665', 4, '-400', '3', '-335', '-33', '-433', '-433', '-133.5');
INSERT INTO `kog_kog_details` VALUES (77, 37, '续', 1, '1000', '3205', 1, '400', '4', '2205', '19', '419', '419', '120.5');
INSERT INTO `kog_kog_details` VALUES (78, 38, 'Holy', 4, '1000', '1215', 3, '-100', '1', '-785', '-78', '-178', '-178', '-178.5');
INSERT INTO `kog_kog_details` VALUES (79, 38, '仉', 2, '1000', '4380', 1, '400', '2', '4380', '187', '587', '587', '338');
INSERT INTO `kog_kog_details` VALUES (80, 38, '翠', 3, '1000', '710', 4, '-400', '3', '-2290', '-229', '-629', '-629', '-329');
INSERT INTO `kog_kog_details` VALUES (81, 38, '续', 1, '1000', '3825', 2, '100', '4', '2825', '120', '220', '220', '182.5');
INSERT INTO `kog_kog_details` VALUES (82, 39, '翠', 3, '1000', '900', 4, '-400', '1', '-1100', '-110', '-510', '-510', '-210');
INSERT INTO `kog_kog_details` VALUES (83, 39, '仉', 2, '1000', '1100', 3, '-100', '2', '100', '0', '-100', '-100', '-90');
INSERT INTO `kog_kog_details` VALUES (84, 39, '续', 1, '1000', '2870', 1, '400', '3', '2870', '62', '462', '462', '187');
INSERT INTO `kog_kog_details` VALUES (85, 39, 'Holy', 4, '1000', '2240', 2, '100', '4', '2240', '48', '148', '148', '124');
INSERT INTO `kog_kog_details` VALUES (86, 40, '翠', 3, '1000', '2745', 2, '100', '1', '1745', '13', '113', '113', '74.5');
INSERT INTO `kog_kog_details` VALUES (87, 40, '续', 1, '1000', '2180', 1, '400', '2', '2180', '17', '417', '417', '118');
INSERT INTO `kog_kog_details` VALUES (88, 40, 'Holy', 4, '1000', '1700', 4, '-400', '3', '-300', '-30', '-430', '-430', '-130');
INSERT INTO `kog_kog_details` VALUES (89, 40, '仉', 2, '1000', '2300', 3, '-100', '4', '300', '0', '-100', '-100', '-70');
INSERT INTO `kog_kog_details` VALUES (90, 41, 'Holy', 4, '1000', '3570', 2, '100', '1', '1570', '114', '214', '214', '57');
INSERT INTO `kog_kog_details` VALUES (91, 41, '翠', 3, '1000', '1600', 3, '-100', '2', '-400', '-40', '-140', '-140', '-140');
INSERT INTO `kog_kog_details` VALUES (92, 41, '续', 1, '1000', '5835', 1, '400', '3', '5835', '426', '826', '526', '483.5');
INSERT INTO `kog_kog_details` VALUES (93, 41, '仉', 2, '1000', '1000', 4, '-400', '4', '-5000', '-500', '-900', '-600', '-600');
INSERT INTO `kog_kog_details` VALUES (94, 42, '仉', 2, '1000', '140', 4, '-400', '1', '-1860', '-186', '-586', '-586', '-286');
INSERT INTO `kog_kog_details` VALUES (95, 42, '翠', 3, '1000', '4910', 1, '400', '2', '3910', '131', '531', '531', '291');
INSERT INTO `kog_kog_details` VALUES (96, 42, 'Holy', 4, '1000', '1655', 2, '100', '3', '1655', '55', '155', '155', '65.5');
INSERT INTO `kog_kog_details` VALUES (97, 42, '续', 1, '1000', '3335', 3, '-100', '4', '335', '0', '-100', '-100', '-66.5');
INSERT INTO `kog_kog_details` VALUES (98, 43, '续', 1, '1000', '725', 4, '-400', '1', '-1275', '-127', '-527', '-527', '-227.5');
INSERT INTO `kog_kog_details` VALUES (99, 43, 'Holy', 4, '1000', '5290', 1, '400', '2', '4290', '125', '525', '525', '329');
INSERT INTO `kog_kog_details` VALUES (100, 43, '仉', 2, '1000', '1625', 3, '-100', '3', '-375', '-37', '-137', '-137', '-137.5');
INSERT INTO `kog_kog_details` VALUES (101, 43, '翠', 3, '1000', '1360', 2, '100', '4', '1360', '39', '139', '139', '36');
INSERT INTO `kog_kog_details` VALUES (102, 44, '仉', 2, '1000', '2400', 2, '0', '1', '1400', '0', '0', '0', '40');
INSERT INTO `kog_kog_details` VALUES (103, 44, '续', 1, '1000', '5450', 1, '300', '2', '5450', '392', '692', '400', '445');
INSERT INTO `kog_kog_details` VALUES (104, 44, 'Holy', 4, '1000', '1080', 3, '-300', '3', '-3920', '-392', '-692', '-400', '-492');
INSERT INTO `kog_kog_details` VALUES (105, 45, 'Holy', 4, '1000', '4660', 1, '300', '1', '3660', '298', '598', '300', '266');
INSERT INTO `kog_kog_details` VALUES (106, 45, '续', 1, '1000', '1020', 3, '-300', '2', '-2980', '-298', '-598', '-300', '-398');
INSERT INTO `kog_kog_details` VALUES (107, 45, '仉', 2, '1000', '1320', 2, '0', '3', '1320', '0', '0', '0', '32');
INSERT INTO `kog_kog_details` VALUES (108, 46, '仉', 2, '1000', '1280', 4, '-100', '1', '-2720', '-672', '-772', '-380', '-372');
INSERT INTO `kog_kog_details` VALUES (109, 46, '续', 1, '1000', '1830', 3, '-400', '2', '830', '0', '-400', '-20', '-17');
INSERT INTO `kog_kog_details` VALUES (110, 46, 'Holy', 4, '1000', '4695', 1, '400', '3', '4695', '535', '935', '380', '369.5');
INSERT INTO `kog_kog_details` VALUES (111, 46, '翠', 3, '1000', '1200', 2, '100', '4', '1200', '137', '237', '20', '20');
INSERT INTO `kog_kog_details` VALUES (112, 47, '仉', 2, '1000', '2010', 2, '0', '1', '1010', '0', '0', '1', '1');
INSERT INTO `kog_kog_details` VALUES (113, 47, '续', 1, '1000', '170', 3, '-100', '2', '-2830', '-283', '-383', '-293', '-383');
INSERT INTO `kog_kog_details` VALUES (114, 47, 'Holy', 4, '1000', '4920', 1, '100', '3', '3920', '0', '383', '292', '292');
INSERT INTO `kog_kog_details` VALUES (115, 48, '续', 1, '1000', '2365', 2, '100', '1', '1365', '0', '153', '36', '36.5');
INSERT INTO `kog_kog_details` VALUES (116, 48, 'Holy', 4, '1000', '3535', 1, '100', '2', '3535', '0', '237', '253', '253.5');
INSERT INTO `kog_kog_details` VALUES (117, 48, '仉', 2, '1000', '100', 3, '-100', '3', '-1900', '-190', '-290', '-299', '-290');
INSERT INTO `kog_kog_details` VALUES (118, 49, '仉', 2, '1000', '3800', 3, '-1', '1', '-4200', '-420', '-421', '-300', '-520');
INSERT INTO `kog_kog_details` VALUES (119, 49, '续', 1, '1000', '3380', 2, '1', '2', '-2620', '-262', '1', '-200', '-362');
INSERT INTO `kog_kog_details` VALUES (120, 49, 'Holy', 4, '1000', '9820', 1, '1', '3', '9820', '0', '683', '500', '882');
INSERT INTO `kog_kog_details` VALUES (121, 50, '仉', 2, '1000', '2430', 2, '1', '1', '2430', '0', '63', '140', '143');
INSERT INTO `kog_kog_details` VALUES (122, 50, '翠', 3, '1000', '0', 4, '-1', '2', '-1000', '-100', '-101', '-200', '-200');
INSERT INTO `kog_kog_details` VALUES (123, 50, 'Holy', 4, '1000', '4640', 3, '-1', '3', '-360', '-36', '-37', '-130', '-136');
INSERT INTO `kog_kog_details` VALUES (124, 50, '续', 1, '1000', '3930', 1, '1', '4', '2930', '0', '75', '190', '193');
INSERT INTO `kog_kog_details` VALUES (125, 51, 'Holy', 4, '1000', '1800', 3, '-1', '1', '-1200', '-120', '-121', '-200', '-220');
INSERT INTO `kog_kog_details` VALUES (126, 51, '翠', 3, '1000', '4280', 2, '1', '2', '3280', '0', '191', '200', '228');
INSERT INTO `kog_kog_details` VALUES (127, 51, '仉', 2, '1000', '600', 4, '-1', '3', '-8400', '-840', '-841', '-350', '-940');
INSERT INTO `kog_kog_details` VALUES (128, 51, '续', 1, '1000', '14300', 1, '1', '4', '13300', '0', '771', '350', '1230');
INSERT INTO `kog_kog_details` VALUES (129, 52, '仉', 2, '1000', '3150', 1, '1', '1', '3150', '0', '63', '180', '215');
INSERT INTO `kog_kog_details` VALUES (130, 52, '续', 1, '1000', '1640', 2, '1', '2', '640', '0', '13', '-30', '-36');
INSERT INTO `kog_kog_details` VALUES (131, 52, 'Holy', 4, '1000', '3260', 3, '-1', '3', '-740', '-74', '-75', '-150', '-174');
INSERT INTO `kog_kog_details` VALUES (132, 53, 'Holy', 4, '1000', '1780', 4, '-1', '1', '-3220', '-322', '-323', '-300', '-422');
INSERT INTO `kog_kog_details` VALUES (133, 53, '翠', 3, '1000', '4800', 2, '1', '2', '-200', '-20', '1', '0', '-120');
INSERT INTO `kog_kog_details` VALUES (134, 53, '续', 1, '1000', '8635', 1, '1', '3', '8635', '0', '463', '400', '763.5');
INSERT INTO `kog_kog_details` VALUES (135, 53, '仉', 2, '1000', '2800', 3, '-1', '4', '-1200', '-120', '-121', '-100', '-220');
INSERT INTO `kog_kog_details` VALUES (136, 54, '仉', 2, '1000', '700', 3, '-1', '1', '-4300', '-430', '-431', '-318', '-530');
INSERT INTO `kog_kog_details` VALUES (137, 54, 'Holy', 4, '1000', '2180', 2, '1', '2', '1180', '0', '70', '18', '18');
INSERT INTO `kog_kog_details` VALUES (138, 54, '续', 1, '1000', '6130', 1, '1', '3', '6130', '0', '362', '300', '513');
INSERT INTO `kog_kog_details` VALUES (139, 55, 'Holy', 4, '1000', '870', 3, '-1', '1', '-2130', '-213', '-214', '-251', '-313');
INSERT INTO `kog_kog_details` VALUES (140, 55, '续', 1, '1000', '2310', 2, '1', '2', '2310', '0', '97', '101', '131');
INSERT INTO `kog_kog_details` VALUES (141, 55, '仉', 2, '1000', '2830', 1, '1', '3', '2830', '0', '118', '150', '183');
INSERT INTO `kog_kog_details` VALUES (142, 56, '续', 1, '1000', '3790', 1, '1', '1', '2790', '0', '60', '170', '179');
INSERT INTO `kog_kog_details` VALUES (143, 56, 'Holy', 4, '1000', '1200', 3, '-1', '2', '-800', '-80', '-81', '-170', '-180');
INSERT INTO `kog_kog_details` VALUES (144, 56, '仉', 2, '1000', '3000', 2, '1', '3', '1000', '0', '22', '0', '0');
INSERT INTO `kog_kog_details` VALUES (145, 57, 'Holy', 4, '1000', '770', 3, '-1', '1', '-1230', '-123', '-124', '-100', '-223');
INSERT INTO `kog_kog_details` VALUES (146, 57, '续', 1, '1000', '2495', 1, '1', '2', '2495', '0', '73', '100', '149.5');
INSERT INTO `kog_kog_details` VALUES (147, 57, '仉', 2, '1000', '1770', 2, '1', '3', '1770', '0', '52', '0', '77');
INSERT INTO `kog_kog_details` VALUES (148, 58, '仉', 2, '1000', '2050', 3, '0', '1', '-1950', '-195', '-195', '-200', '-295');
INSERT INTO `kog_kog_details` VALUES (149, 58, 'Holy', 4, '1000', '1040', 2, '0', '2', '-960', '-96', '-96', '-100', '-196');
INSERT INTO `kog_kog_details` VALUES (150, 58, '续', 1, '1000', '5945', 1, '0', '3', '5945', '0', '0', '300', '494.5');
INSERT INTO `kog_kog_details` VALUES (151, 59, '仉', 2, '1000', '600', 3, '0', '1', '-400', '-40', '-40', '-140', '-140');
INSERT INTO `kog_kog_details` VALUES (152, 59, '续', 1, '1000', '1495', 2, '0', '2', '1495', '0', '0', '50', '49.5');
INSERT INTO `kog_kog_details` VALUES (153, 59, 'Holy', 4, '1000', '1900', 1, '0', '3', '1900', '0', '0', '90', '90');
INSERT INTO `kog_kog_details` VALUES (154, 60, 'Holy', 4, '1000', '1300', 2, '0', '1', '1300', '0', '0', '0', '30');
INSERT INTO `kog_kog_details` VALUES (155, 60, '续', 1, '1000', '4005', 1, '0', '2', '3005', '0', '0', '200', '200.5');
INSERT INTO `kog_kog_details` VALUES (156, 60, '仉', 2, '1000', '710', 3, '0', '3', '-1290', '-129', '-129', '-200', '-229');
INSERT INTO `kog_kog_details` VALUES (157, 61, '续', 1, '1000', '1650', 1, '0', '1', '1650', '0', '0', '50', '65');
INSERT INTO `kog_kog_details` VALUES (158, 61, '仉', 2, '1000', '1300', 3, '0', '2', '300', '0', '0', '-50', '-70');
INSERT INTO `kog_kog_details` VALUES (159, 61, 'Holy', 4, '1000', '1200', 2, '0', '3', '1200', '0', '0', '0', '20');
INSERT INTO `kog_kog_details` VALUES (160, 62, 'Holy', 4, '1000', '3600', 1, '0', '1', '2600', '0', '0', '150', '160');
INSERT INTO `kog_kog_details` VALUES (161, 62, '仉', 2, '1000', '1640', 2, '0', '2', '640', '0', '0', '-30', '-36');
INSERT INTO `kog_kog_details` VALUES (162, 62, '续', 1, '1000', '760', 3, '0', '3', '-240', '-24', '-24', '-120', '-124');
INSERT INTO `kog_kog_details` VALUES (163, 63, '翠', 3, '1000', '2030', 1, '0', '1', '2030', '0', '0', '100', '103');
INSERT INTO `kog_kog_details` VALUES (164, 63, '续', 1, '1000', '1850', 2, '0', '2', '850', '0', '0', '-15', '-15');
INSERT INTO `kog_kog_details` VALUES (165, 63, '仉', 2, '1000', '1150', 3, '0', '3', '150', '0', '0', '-85', '-85');
INSERT INTO `kog_kog_details` VALUES (166, 64, '翠', 3, '1000', '2600', 1, '0', '1', '2600', '0', '0', '160', '160');
INSERT INTO `kog_kog_details` VALUES (167, 64, '续', 1, '1000', '830', 3, '0', '2', '-170', '-17', '-17', '-120', '-117');
INSERT INTO `kog_kog_details` VALUES (168, 64, '仉', 2, '1000', '2600', 2, '0', '3', '600', '0', '0', '-40', '-40');
INSERT INTO `kog_kog_details` VALUES (169, 65, '续', 1, '1000', '2985', 2, '0', '1', '2985', '0', '0', '180', '198.5');
INSERT INTO `kog_kog_details` VALUES (170, 65, '仉', 2, '1000', '9000', 1, '0', '2', '8000', '0', '0', '300', '700');
INSERT INTO `kog_kog_details` VALUES (171, 65, '翠', 3, '1000', '2200', 3, '0', '3', '1200', '0', '0', '0', '20');
INSERT INTO `kog_kog_details` VALUES (172, 65, 'Holy', 4, '1000', '2000', 4, '0', '4', '-8000', '-800', '-800', '-480', '-900');
INSERT INTO `kog_kog_details` VALUES (173, 66, '翠', 3, '1000', '4535', 3, '0', '1', '-1465', '-146', '-146', '-250', '-246.5');
INSERT INTO `kog_kog_details` VALUES (174, 66, 'Holy', 4, '1000', '970', 4, '0', '2', '-3030', '-303', '-303', '-250', '-403');
INSERT INTO `kog_kog_details` VALUES (175, 66, '续', 1, '1000', '4370', 1, '0', '3', '4370', '0', '0', '250', '337');
INSERT INTO `kog_kog_details` VALUES (176, 66, '仉', 2, '1000', '4130', 2, '0', '4', '4130', '0', '0', '250', '313');
INSERT INTO `kog_kog_details` VALUES (177, 67, '续', 1, '1000', '1345', 3, NULL, '1', '-5655', '-565', '-665.5', '-400', NULL);
INSERT INTO `kog_kog_details` VALUES (178, 67, 'Holy', 4, '1000', '11395', 1, NULL, '2', '11395', '0', '1039.5', '700', NULL);
INSERT INTO `kog_kog_details` VALUES (179, 67, '仉', 2, '1000', '3200', 2, NULL, '3', '-2800', '-280', '-380', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (180, 68, '仉', 2, '1000', '2900', 2, NULL, '1', '-100', '-10', '-110', '-110', NULL);
INSERT INTO `kog_kog_details` VALUES (181, 68, '续', 1, '1000', '4725', 1, NULL, '2', '4725', '0', '372.5', '310', NULL);
INSERT INTO `kog_kog_details` VALUES (182, 68, 'Holy', 4, '1000', '1075', 3, NULL, '3', '-925', '-92', '-192.5', '-200', NULL);
INSERT INTO `kog_kog_details` VALUES (183, 69, 'Holy', 4, '1000', '4200', 3, NULL, '1', '200', '0', '-80', '-80', NULL);
INSERT INTO `kog_kog_details` VALUES (184, 69, '续', 1, '1000', '2100', 1, NULL, '2', '2100', '0', '110', '110', NULL);
INSERT INTO `kog_kog_details` VALUES (185, 69, '仉', 2, '1000', '1700', 2, NULL, '3', '700', '0', '-30', '-30', NULL);
INSERT INTO `kog_kog_details` VALUES (186, 70, '翠', 3, '1000', '3350', 1, NULL, '1', '3350', '0', '235', '200', NULL);
INSERT INTO `kog_kog_details` VALUES (187, 70, '续', 1, '1000', '2015', 3, NULL, '2', '1015', '0', '1.5', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (188, 70, '仉', 2, '1000', '3670', 4, NULL, '3', '-2330', '-233', '-333', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (189, 70, 'Holy', 4, '1000', '3325', 2, NULL, '4', '2325', '0', '132.5', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (190, 71, 'Holy', 4, '1000', '1800', 3, NULL, '1', '-1200', '-120', '-220', '-220', NULL);
INSERT INTO `kog_kog_details` VALUES (191, 71, '仉', 2, '1000', '2600', 2, NULL, '2', '1600', '0', '60', '60', NULL);
INSERT INTO `kog_kog_details` VALUES (192, 71, '续', 1, '1000', '3605', 1, NULL, '3', '2605', '0', '160.5', '160', NULL);
INSERT INTO `kog_kog_details` VALUES (193, 72, '仉', 2, '1000', '1000', 1, NULL, '1', '1000', '0', '0', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (194, 72, 'Holy', 4, '1000', '1000', 2, NULL, '2', '1000', '0', '0', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (195, 72, '续', 1, '1000', '1000', 3, NULL, '3', '1000', '0', '0', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (196, 73, '仉', 2, '1000', '2700', 3, NULL, '1', '-2300', '-230', '-330', '-340', NULL);
INSERT INTO `kog_kog_details` VALUES (197, 73, 'Holy', 4, '1000', '2360', 2, NULL, '2', '1360', '0', '36', '40', NULL);
INSERT INTO `kog_kog_details` VALUES (198, 73, '续', 1, '1000', '9010', 1, NULL, '3', '6010', '0', '501', '300', NULL);
INSERT INTO `kog_kog_details` VALUES (199, 74, '翠', 3, '1000', '950', 2, NULL, '1', '950', '0', '-5', '-5', NULL);
INSERT INTO `kog_kog_details` VALUES (200, 74, '续', 1, '1000', '1300', 1, NULL, '2', '1300', '0', '30', '30', NULL);
INSERT INTO `kog_kog_details` VALUES (201, 74, 'Holy', 4, '1000', '1750', 3, NULL, '3', '750', '0', '-25', '-25', NULL);
INSERT INTO `kog_kog_details` VALUES (202, 75, '翠', 3, '1000', '2775', 1, NULL, '1', '2775', '0', '177.5', '190', NULL);
INSERT INTO `kog_kog_details` VALUES (203, 75, '续', 1, '1000', '2715', 2, NULL, '2', '1715', '0', '71.5', '70', NULL);
INSERT INTO `kog_kog_details` VALUES (204, 75, '仉', 2, '1000', '3400', 4, NULL, '3', '-600', '-60', '-160', '-160', NULL);
INSERT INTO `kog_kog_details` VALUES (205, 75, 'Holy', 4, '1000', '0', 3, NULL, '4', '0', '0', '-100', '-100', NULL);
INSERT INTO `kog_kog_details` VALUES (206, 76, '仉', 2, '1000', '700', 3, NULL, '1', '-1300', '-130', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (207, 76, '续', 1, '1000', '2460', 2, NULL, '2', '460', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (208, 76, 'Holy', 4, '1000', '4835', 1, NULL, '3', '3835', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (209, 77, '仉', 2, '1000', '4190', 3, NULL, '1', '190', '0', '-81', '-81', NULL);
INSERT INTO `kog_kog_details` VALUES (210, 77, '翠', 3, '1000', '4160', 2, NULL, '2', '3160', '0', '216', '216', NULL);
INSERT INTO `kog_kog_details` VALUES (211, 77, '续', 1, '1000', '885', 4, NULL, '3', '-7115', '-711', '-811.5', '-811.5', NULL);
INSERT INTO `kog_kog_details` VALUES (212, 77, 'Holy', 4, '1000', '9765', 1, NULL, '4', '7765', '0', '676.5', '676.5', NULL);
INSERT INTO `kog_kog_details` VALUES (213, 78, '仉', 2, '1000', '1750', 1, NULL, '1', '1750', '0', '75', '70', NULL);
INSERT INTO `kog_kog_details` VALUES (214, 78, '续', 1, '1000', '1630', 2, NULL, '2', '1630', '0', '63', '60', NULL);
INSERT INTO `kog_kog_details` VALUES (215, 78, 'Holy', 4, '1000', '1700', 3, NULL, '3', '-300', '-30', '-130', '-130', NULL);
INSERT INTO `kog_kog_details` VALUES (216, 79, 'Holy', 4, '1000', '2800', 1, NULL, '1', '2800', '0', '180', '180', NULL);
INSERT INTO `kog_kog_details` VALUES (217, 79, '翠', 3, '1000', '970', 4, NULL, '2', '-1030', '-103', '-203', '-203', NULL);
INSERT INTO `kog_kog_details` VALUES (218, 79, '续', 1, '1000', '4390', 2, NULL, '3', '2390', '0', '139', '139', NULL);
INSERT INTO `kog_kog_details` VALUES (219, 79, '仉', 2, '1000', '1840', 3, NULL, '4', '-160', '-16', '-116', '-116', NULL);
INSERT INTO `kog_kog_details` VALUES (220, 80, '续', 1, '1000', '2555', 1, NULL, '1', '2555', '0', '155.5', '155.5', NULL);
INSERT INTO `kog_kog_details` VALUES (221, 80, 'Holy', 4, '1000', '2000', 3, NULL, '2', '-1000', '-100', '-200', '-200', NULL);
INSERT INTO `kog_kog_details` VALUES (222, 80, '仉', 2, '1000', '3445', 2, NULL, '3', '1445', '0', '44.5', '44.5', NULL);
INSERT INTO `kog_kog_details` VALUES (223, 81, 'Holy', 4, '1000', '4645', 2, NULL, '1', '2645', '0', '164.5', '150', NULL);
INSERT INTO `kog_kog_details` VALUES (224, 81, '续', 1, '1000', '6310', 1, NULL, '2', '6310', '0', '531', '400', NULL);
INSERT INTO `kog_kog_details` VALUES (225, 81, '仉', 2, '1000', '1150', 3, NULL, '3', '-4850', '-485', '-585', '-550', NULL);
INSERT INTO `kog_kog_details` VALUES (226, 82, '续', 1, '1000', '1', 1, NULL, '1', '1', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (227, 82, '仉', 2, '1000', '1', 3, NULL, '2', '-999', '-100', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (228, 82, '翠', 3, '1000', '1', 2, NULL, '3', '1', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (229, 83, '仉', 2, '1000', '1', 3, NULL, '1', '1', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (230, 83, '翠', 3, '1000', '2', 2, NULL, '2', '2', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (231, 83, '二子', 7, '1000', '3', 1, NULL, '3', '3', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (232, 84, '续', 1, '1000', '7315', 1, NULL, '1', '6315', '0', '531.5', '400', NULL);
INSERT INTO `kog_kog_details` VALUES (233, 84, 'Holy', 4, '1000', '4540', 2, NULL, '2', '2540', '0', '154', '150', NULL);
INSERT INTO `kog_kog_details` VALUES (234, 84, '仉', 2, '1000', '1130', 3, NULL, '3', '-4870', '-487', '-587', '-550', NULL);
INSERT INTO `kog_kog_details` VALUES (235, 85, '续', 1, '1000', '14190', 1, NULL, '1', '14190', '0', '1319', '800', NULL);
INSERT INTO `kog_kog_details` VALUES (236, 85, '仉', 2, '1000', '5500', 3, NULL, '2', '-5500', '-550', '-650', '-450', NULL);
INSERT INTO `kog_kog_details` VALUES (237, 85, 'Holy', 4, '1000', '8570', 2, NULL, '3', '3570', '0', '257', '250', NULL);
INSERT INTO `kog_kog_details` VALUES (238, 85, '翠', 3, '1000', '2790', 4, NULL, '4', '-8210', '-821', '-921', '-600', NULL);
INSERT INTO `kog_kog_details` VALUES (239, 86, '续', 1, '1000', '7775', 1, NULL, '1', '6775', '0', '577.5', '400', NULL);
INSERT INTO `kog_kog_details` VALUES (240, 86, 'Holy', 4, '1000', '2425', 2, NULL, '2', '1425', '0', '42.5', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (241, 86, '仉', 2, '1000', '0', 3, NULL, '3', '-5000', '-500', '-600', '-400', NULL);
INSERT INTO `kog_kog_details` VALUES (242, 87, 'Holy', 4, '1000', '860', 3, NULL, '1', '-2140', '-214', '-314', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (243, 87, '续', 1, '1000', '3255', 1, NULL, '2', '3255', '0', '225.5', '200', NULL);
INSERT INTO `kog_kog_details` VALUES (244, 87, '仉', 2, '1000', '2700', 2, NULL, '3', '1700', '0', '70', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (245, 88, '仉', 2, '1000', '1800', 3, NULL, '1', '-1200', '-120', '-220', '-200', NULL);
INSERT INTO `kog_kog_details` VALUES (246, 88, '续', 1, '1000', '3120', 2, NULL, '2', '1120', '0', '12', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (247, 88, '翠', 3, '1000', '3040', 1, NULL, '3', '3040', '0', '204', '200', NULL);
INSERT INTO `kog_kog_details` VALUES (248, 89, '翠', 3, '1000', '6045', 2, NULL, '1', '3045', '0', '204.5', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (249, 89, '仉', 2, '1000', '0', 3, NULL, '2', '-11000', '-1100', '-1200', '-500', NULL);
INSERT INTO `kog_kog_details` VALUES (250, 89, '续', 1, '1000', '10955', 1, NULL, '3', '10955', '0', '995.5', '400', NULL);
INSERT INTO `kog_kog_details` VALUES (251, 90, '仉', 2, '1000', '950', 2, NULL, '1', '950', '0', '-5', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (252, 90, 'Holy', 4, '1000', '1575', 3, NULL, '2', '-2425', '-242', '-342.5', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (253, 90, '续', 1, '1000', '5595', 1, NULL, '3', '4595', '0', '359.5', '300', NULL);
INSERT INTO `kog_kog_details` VALUES (254, 91, 'Holy', 4, '1000', '7550', 1, NULL, '1', '7550', '0', '655', '500', NULL);
INSERT INTO `kog_kog_details` VALUES (255, 91, '续', 1, '1000', '0', 3, NULL, '2', '-3000', '-300', '-400', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (256, 91, '仉', 2, '1000', '2450', 2, NULL, '3', '-1550', '-155', '-255', '-200', NULL);
INSERT INTO `kog_kog_details` VALUES (257, 92, 'Holy', 4, '1000', '6390', 1, NULL, '1', '6390', '0', '539', '420', NULL);
INSERT INTO `kog_kog_details` VALUES (258, 92, '续', 1, '1000', '2310', 2, NULL, '2', '-690', '-69', '-169', '-120', NULL);
INSERT INTO `kog_kog_details` VALUES (259, 92, '仉', 2, '1000', '2300', 3, NULL, '3', '-1700', '-170', '-270', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (260, 93, '翠', 3, '1000', '1000', 3, NULL, '1', '1000', '0', '0', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (261, 93, 'Holy', 4, '1000', '4555', 1, NULL, '2', '4555', '0', '355.5', '201', NULL);
INSERT INTO `kog_kog_details` VALUES (262, 93, '续', 1, '1000', '4445', 2, NULL, '3', '4445', '0', '344.5', '199', NULL);
INSERT INTO `kog_kog_details` VALUES (263, 93, '仉', 2, '1000', '0', 4, NULL, '4', '-6000', '-600', '-700', '-400', NULL);
INSERT INTO `kog_kog_details` VALUES (264, 94, '仉', 2, '1000', '700', 3, NULL, '1', '-2300', '-230', '-330', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (265, 94, 'Holy', 4, '1000', '1475', 4, NULL, '2', '-2525', '-252', '-352.5', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (266, 94, '续', 1, '1000', '7565', 1, NULL, '3', '6565', '0', '556.5', '450', NULL);
INSERT INTO `kog_kog_details` VALUES (267, 94, '翠', 3, '1000', '7500', 2, NULL, '4', '2500', '0', '150', '150', NULL);
INSERT INTO `kog_kog_details` VALUES (268, 95, '仉', 2, '1000', '2200', 3, NULL, '1', '200', '0', '-80', '-80', NULL);
INSERT INTO `kog_kog_details` VALUES (269, 95, '翠', 3, '1000', '3900', 1, NULL, '2', '1900', '0', '90', '80', NULL);
INSERT INTO `kog_kog_details` VALUES (270, 95, '续', 1, '1000', '4900', 2, NULL, '3', '900', '0', '-10', '-0', NULL);
INSERT INTO `kog_kog_details` VALUES (271, 96, 'Holy', 4, '1000', '2850', 1, NULL, '1', '2850', '0', '185', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (272, 96, '续', 1, '1000', '1150', 2, NULL, '2', '1150', '0', '15', '10', NULL);
INSERT INTO `kog_kog_details` VALUES (273, 96, '仉', 2, '1000', '0', 3, NULL, '3', '-1000', '-100', '-200', '-110', NULL);
INSERT INTO `kog_kog_details` VALUES (274, 97, 'Holy', 4, '1000', '3270', 2, NULL, '1', '270', '0', '-73', '-50', NULL);
INSERT INTO `kog_kog_details` VALUES (275, 97, '续', 1, '1000', '5785', 3, NULL, '2', '-2215', '-221', '-321.5', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (276, 97, '仉', 2, '1000', '5000', 1, NULL, '3', '5000', '0', '400', '350', NULL);
INSERT INTO `kog_kog_details` VALUES (277, 98, 'Holy', 4, '1000', '6820', 1, NULL, '1', '6820', '0', '582', '350', NULL);
INSERT INTO `kog_kog_details` VALUES (278, 98, '续', 1, '1000', '4330', 3, NULL, '2', '-3670', '-367', '-467', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (279, 98, '仉', 2, '1000', '2950', 2, NULL, '3', '-50', '-5', '-105', '-50', NULL);
INSERT INTO `kog_kog_details` VALUES (280, 99, 'Holy', 4, '1000', '1490', 3, NULL, '1', '-1510', '-151', '-251', '-244', NULL);
INSERT INTO `kog_kog_details` VALUES (281, 99, '续', 1, '1000', '2990', 1, NULL, '2', '2990', '0', '199', '199', NULL);
INSERT INTO `kog_kog_details` VALUES (282, 99, '仉', 2, '1000', '1540', 2, NULL, '3', '1540', '0', '54', '45', NULL);
INSERT INTO `kog_kog_details` VALUES (283, 100, '续', 1, '1000', '1150', 2, NULL, '1', '1150', '0', '15', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (284, 100, '仉', 2, '1000', '1880', 1, NULL, '2', '1880', '0', '88', '50', NULL);
INSERT INTO `kog_kog_details` VALUES (285, 100, '翠', 3, '1000', '3030', 3, NULL, '3', '30', '0', '-97', '-50', NULL);
INSERT INTO `kog_kog_details` VALUES (286, 101, '翠', 3, '1000', '2535', 4, NULL, '1', '-465', '-46', '-146.5', '-140', NULL);
INSERT INTO `kog_kog_details` VALUES (287, 101, 'Holy', 4, '1000', '8825', 2, NULL, '2', '1825', '0', '82.5', '82', NULL);
INSERT INTO `kog_kog_details` VALUES (288, 101, '续', 1, '1000', '3870', 3, NULL, '3', '-130', '-13', '-113', '-112', NULL);
INSERT INTO `kog_kog_details` VALUES (289, 101, '仉', 2, '1000', '2760', 1, NULL, '4', '2760', '0', '176', '170', NULL);
INSERT INTO `kog_kog_details` VALUES (290, 102, 'Holy', 4, '1000', '3945', 1, NULL, '1', '3945', '0', '294.5', '295', NULL);
INSERT INTO `kog_kog_details` VALUES (291, 102, '续', 1, '1000', '5750', 2, NULL, '2', '750', '0', '-25', '-25', NULL);
INSERT INTO `kog_kog_details` VALUES (292, 102, '仉', 2, '1000', '1300', 3, NULL, '3', '-1700', '-170', '-270', '-270', NULL);
INSERT INTO `kog_kog_details` VALUES (293, 103, 'Holy', 4, '1000', '3200', 3, NULL, '1', '-2800', '-280', '-380', '-350', NULL);
INSERT INTO `kog_kog_details` VALUES (294, 103, '续', 1, '1000', '4480', 1, NULL, '2', '4480', '0', '348', '350', NULL);
INSERT INTO `kog_kog_details` VALUES (295, 103, '仉', 2, '1000', '2400', 2, NULL, '3', '1400', '0', '40', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (296, 104, '续', 1, '1000', '2125', 1, NULL, '1', '2125', '0', '112.5', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (297, 104, '仉', 2, '1000', '400', 3, NULL, '2', '400', '0', '-60', '-60', NULL);
INSERT INTO `kog_kog_details` VALUES (298, 104, '二子', 7, '1000', '1546', 2, NULL, '3', '546', '0', '-45.4', '-40', NULL);
INSERT INTO `kog_kog_details` VALUES (299, 105, '仉', 2, '1000', '1050', 2, NULL, '1', '-950', '-95', '-195', '-180', NULL);
INSERT INTO `kog_kog_details` VALUES (300, 105, 'Holy', 4, '1000', '6974', 1, NULL, '2', '6974', '0', '597.4', '480', NULL);
INSERT INTO `kog_kog_details` VALUES (301, 105, '续', 1, '1000', '3980', 3, NULL, '3', '-3020', '-302', '-402', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (302, 106, 'Holy', 4, '1000', '2915', 1, NULL, '1', '2915', '0', '191.5', '190', NULL);
INSERT INTO `kog_kog_details` VALUES (303, 106, '续', 1, '1000', '1870', 2, NULL, '2', '1870', '0', '87', '80', NULL);
INSERT INTO `kog_kog_details` VALUES (304, 106, '仉', 2, '1000', '2215', 3, NULL, '3', '-1785', '-178', '-278.5', '-270', NULL);
INSERT INTO `kog_kog_details` VALUES (305, 107, 'Holy', 4, '1000', '455', 3, NULL, '1', '455', '0', '-54.5', '-50', NULL);
INSERT INTO `kog_kog_details` VALUES (306, 107, '续', 1, '1000', '1770', 1, NULL, '2', '1770', '0', '77', '70', NULL);
INSERT INTO `kog_kog_details` VALUES (307, 107, '仉', 2, '1000', '800', 2, NULL, '3', '800', '0', '-20', '-20', NULL);
INSERT INTO `kog_kog_details` VALUES (308, 108, '续', 1, '200', '408', 1, NULL, '1', '408', '0', '104', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (309, 108, '仉', 2, '200', '315', 3, NULL, '2', '-85', '-42', '-142.5', '-135', NULL);
INSERT INTO `kog_kog_details` VALUES (310, 108, '翠', 3, '200', '277', 2, NULL, '3', '277', '0', '38.5', '35', NULL);
INSERT INTO `kog_kog_details` VALUES (311, 109, '续', 1, '200', '416', 1, NULL, '1', '416', '0', '108', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (312, 109, 'Holy', 4, '200', '100', 4, NULL, '2', '-200', '-100', '-200', '-100', NULL);
INSERT INTO `kog_kog_details` VALUES (313, 109, '仉', 2, '200', '150', 3, NULL, '3', '150', '0', '-25', '-15', NULL);
INSERT INTO `kog_kog_details` VALUES (314, 109, '翠', 3, '200', '234', 2, NULL, '4', '234', '0', '17', '15', NULL);
INSERT INTO `kog_kog_details` VALUES (315, 110, '续', 1, '200', '216', 2, NULL, '1', '216', '0', '8', '5', NULL);
INSERT INTO `kog_kog_details` VALUES (316, 110, '仉', 2, '200', '490', 1, NULL, '2', '490', '0', '145', '145', NULL);
INSERT INTO `kog_kog_details` VALUES (317, 110, 'Holy', 4, '200', '294', 3, NULL, '3', '-106', '-53', '-153', '-150', NULL);
INSERT INTO `kog_kog_details` VALUES (318, 111, '续', 1, '200', '164', 2, NULL, '1', '164', '0', '-18', '-18', NULL);
INSERT INTO `kog_kog_details` VALUES (319, 111, '翠', 3, '200', '402', 1, NULL, '2', '402', '0', '101', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (320, 111, '仉', 2, '200', '236', 3, NULL, '3', '36', '0', '-82', '-82', NULL);
INSERT INTO `kog_kog_details` VALUES (321, 112, '翠', 3, '1000', '23', 2, NULL, '1', '23', '0', '-97.7', '-97', NULL);
INSERT INTO `kog_kog_details` VALUES (322, 112, '续', 1, '1000', '0', 3, NULL, '2', '0', '0', '-100', '-100', NULL);
INSERT INTO `kog_kog_details` VALUES (323, 112, '仉', 2, '1000', '567', 1, NULL, '3', '567', '0', '-43.3', '197', NULL);
INSERT INTO `kog_kog_details` VALUES (324, 113, 'Holy', 4, '1000', '230', 2, NULL, '1', '230', '0', '-77', '15', NULL);
INSERT INTO `kog_kog_details` VALUES (325, 113, '续', 1, '1000', '370', 1, NULL, '2', '370', '0', '-63', '85', NULL);
INSERT INTO `kog_kog_details` VALUES (326, 113, '仉', 2, '1000', '184', 3, NULL, '3', '184', '0', '-81.6', '-8', NULL);
INSERT INTO `kog_kog_details` VALUES (327, 113, '翠', 3, '1000', '415', 4, NULL, '4', '15', '0', '-98.5', '-92', NULL);
INSERT INTO `kog_kog_details` VALUES (328, 114, 'Holy', 4, '200', '638', 3, NULL, '1', '138', '0', '-31', '-80', NULL);
INSERT INTO `kog_kog_details` VALUES (329, 114, '续', 1, '200', '513', 1, NULL, '2', '513', '0', '156.5', '155', NULL);
INSERT INTO `kog_kog_details` VALUES (330, 114, '仉', 2, '200', '263', 2, NULL, '3', '263', '0', '31.5', '30', NULL);
INSERT INTO `kog_kog_details` VALUES (331, 114, '翠', 3, '200', '686', 4, NULL, '4', '-114', '-57', '-157', '-105', NULL);
INSERT INTO `kog_kog_details` VALUES (332, 115, '仉', 2, '200', '320', 1, NULL, '1', '320', '0', '60', '60', NULL);
INSERT INTO `kog_kog_details` VALUES (333, 115, '续', 1, '200', '380', 3, NULL, '2', '80', '0', '-60', '-60', NULL);
INSERT INTO `kog_kog_details` VALUES (334, 115, '翠', 3, '200', '200', 2, NULL, '3', '200', '0', '0', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (335, 116, '仉', 2, '200', NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (336, 116, '续', 1, '200', NULL, NULL, NULL, '2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (337, 116, '翠', 3, '200', NULL, NULL, NULL, '3', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (338, 117, '续', 1, '1000', '2365', 2, NULL, '1', '2365', '0', '136.5', '120', NULL);
INSERT INTO `kog_kog_details` VALUES (339, 117, 'Holy', 4, '1000', '4515', 1, NULL, '2', '3015', '0', '201.5', '200', NULL);
INSERT INTO `kog_kog_details` VALUES (340, 117, '仉', 2, '1000', '950', 4, NULL, '3', '-2050', '-205', '-305', '-300', NULL);
INSERT INTO `kog_kog_details` VALUES (341, 117, '翠', 3, '1000', '3760', 3, NULL, '4', '760', '0', '-24', '-20', NULL);
INSERT INTO `kog_kog_details` VALUES (342, 118, 'Holy', 4, '1000', '3510', 1, NULL, '1', '3510', '0', '251', '250', NULL);
INSERT INTO `kog_kog_details` VALUES (343, 118, '续', 1, '1000', '1325', 2, NULL, '2', '325', '0', '-67.5', '-70', NULL);
INSERT INTO `kog_kog_details` VALUES (344, 118, '仉', 2, '1000', '2160', 3, NULL, '3', '-840', '-84', '-184', '-180', NULL);
INSERT INTO `kog_kog_details` VALUES (345, 119, '仉', 2, '1000', '5165', 2, NULL, '1', '1165', '0', '16.5', '0', NULL);
INSERT INTO `kog_kog_details` VALUES (346, 119, 'Holy', 4, '1000', '2630', 1, NULL, '2', '2630', '0', '163', '160', NULL);
INSERT INTO `kog_kog_details` VALUES (347, 119, '续', 1, '1000', '1735', 3, NULL, '3', '-765', '-76', '-176.5', '-160', NULL);
INSERT INTO `kog_kog_details` VALUES (348, 120, 'Holy', 4, '1000', '3520', 1, NULL, '1', '3520', '0', '252', '250', NULL);
INSERT INTO `kog_kog_details` VALUES (349, 120, '仉', 2, '1000', '1880', 2, NULL, '2', '1880', '0', '88', '80', NULL);
INSERT INTO `kog_kog_details` VALUES (350, 120, '续', 1, '1000', '2615', 3, NULL, '3', '815', '0', '-18.5', '-10', NULL);
INSERT INTO `kog_kog_details` VALUES (351, 120, '翠', 3, '1000', '2800', 4, NULL, '4', '-2200', '-220', '-320', '-320', NULL);
INSERT INTO `kog_kog_details` VALUES (352, 121, 'Holy', 4, '1000', '2475', 3, NULL, '1', '275', '0', '-72.5', '-70', NULL);
INSERT INTO `kog_kog_details` VALUES (353, 121, '仉', 2, '1000', '400', 2, NULL, '2', '400', '0', '-60', '-60', NULL);
INSERT INTO `kog_kog_details` VALUES (354, 121, '续', 1, '1000', '2320', 1, NULL, '3', '2320', '0', '132', '130', NULL);
INSERT INTO `kog_kog_details` VALUES (355, 122, '仉', 2, '1000', '1000', 1, NULL, '1', '1000', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (356, 122, '田', 8, '1000', '2000', 2, NULL, '2', '1000', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (357, 122, '续', 1, '1000', '1000', 3, NULL, '3', '1000', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (358, 122, 'Holy', 4, '1000', '1000', 4, NULL, '4', '1000', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (359, 123, '仉', 2, '1000', '2050', 3, NULL, '1', '-2450', '-245', '-345', '-350', NULL);
INSERT INTO `kog_kog_details` VALUES (360, 123, 'Holy', 4, '1000', '2290', 2, NULL, '2', '2290', '0', '129', '130', NULL);
INSERT INTO `kog_kog_details` VALUES (361, 123, '续', 1, '1000', '3190', 1, NULL, '3', '3190', '0', '219', '220', NULL);
INSERT INTO `kog_kog_details` VALUES (362, 124, '续', 1, '1000', '3760', 2, NULL, '1', '2260', '0', '126', '100', NULL);
INSERT INTO `kog_kog_details` VALUES (363, 124, '仉', 2, '1000', '3750', 1, NULL, '2', '3750', '0', '275', '200', NULL);
INSERT INTO `kog_kog_details` VALUES (364, 124, '翠', 3, '1000', '2220', 4, NULL, '3', '-1280', '-128', '-228', '-200', NULL);
INSERT INTO `kog_kog_details` VALUES (365, 124, 'Holy', 4, '1000', '1790', 3, NULL, '4', '-710', '-71', '-171', '-100', NULL);
INSERT INTO `kog_kog_details` VALUES (366, 125, '仉', 2, '1000', '0', 4, NULL, '1', '-2500', '-250', '-350', '-340', NULL);
INSERT INTO `kog_kog_details` VALUES (367, 125, 'Holy', 4, '1000', '5355', 3, NULL, '2', '-945', '-94', '-194.5', '-190', NULL);
INSERT INTO `kog_kog_details` VALUES (368, 125, '翠', 3, '1000', '7530', 2, NULL, '3', '3530', '0', '253', '250', NULL);
INSERT INTO `kog_kog_details` VALUES (369, 125, '续', 1, '1000', '7355', 1, NULL, '4', '3855', '0', '285.5', '280', NULL);
INSERT INTO `kog_kog_details` VALUES (370, 126, '仉', 2, '1000', '3300', 1, NULL, '1', '3300', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (371, 126, 'Holy', 4, '1000', '3050', 2, NULL, '2', '50', '0', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (372, 126, '续', 1, '1000', '1535', 3, NULL, '3', '-365', '-36', NULL, NULL, NULL);
INSERT INTO `kog_kog_details` VALUES (373, 127, '仉', 2, '1000', '0', 3, NULL, '1', '0', '0', '-100', '-100', NULL);
INSERT INTO `kog_kog_details` VALUES (374, 127, 'Holy', 4, '1000', '2900', 2, NULL, '2', '1400', '0', '40', '40', NULL);
INSERT INTO `kog_kog_details` VALUES (375, 127, '续', 1, '1000', '1600', 1, NULL, '3', '1600', '0', '60', '60', NULL);
COMMIT;

-- ----------------------------
-- Table structure for kog_kog_gamemeta
-- ----------------------------
DROP TABLE IF EXISTS `kog_kog_gamemeta`;
CREATE TABLE `kog_kog_gamemeta` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` mediumint(8) unsigned NOT NULL,
  `meta_key` varchar(256) NOT NULL,
  `meta_value` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kog_kog_gamemeta
-- ----------------------------
BEGIN;
INSERT INTO `kog_kog_gamemeta` VALUES (9, 18, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (10, 18, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (11, 21, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (12, 21, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (13, 24, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (14, 24, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (15, 25, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (16, 26, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (17, 26, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (18, 27, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (19, 27, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (20, 30, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (21, 31, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (22, 31, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (23, 32, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (24, 32, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (25, 33, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (26, 33, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (27, 36, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (28, 36, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (29, 37, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (30, 38, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (31, 39, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (32, 40, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (33, 41, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (34, 42, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (35, 43, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (36, 43, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (37, 44, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (38, 44, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (39, 45, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (40, 46, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (41, 46, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (42, 47, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (43, 48, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (44, 48, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (45, 49, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (46, 49, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (47, 50, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (48, 50, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (49, 51, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (50, 52, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (51, 53, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (52, 53, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (53, 54, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (54, 55, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (55, 55, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (56, 56, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (57, 57, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (58, 58, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (59, 58, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (60, 59, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (61, 60, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (62, 61, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (63, 61, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (64, 62, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (65, 62, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (66, 63, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (67, 64, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (68, 64, 'mvp', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (69, 65, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (70, 65, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (71, 66, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (72, 67, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (73, 67, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (74, 68, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (75, 68, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (76, 69, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (77, 69, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (78, 70, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (79, 70, 'mvp', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (80, 71, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (81, 73, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (82, 74, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (83, 75, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (84, 76, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (85, 76, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (86, 77, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (87, 77, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (88, 78, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (89, 78, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (90, 79, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (91, 79, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (92, 80, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (93, 81, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (94, 82, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (95, 84, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (96, 84, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (97, 85, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (98, 85, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (99, 86, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (100, 86, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (101, 87, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (102, 88, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (103, 88, 'mvp', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (104, 89, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (105, 89, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (106, 90, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (107, 91, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (108, 92, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (109, 92, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (110, 93, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (111, 93, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (112, 94, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (113, 95, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (114, 95, 'mvp', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (115, 96, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (116, 96, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (117, 97, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (118, 97, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (119, 98, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (120, 98, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (121, 99, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (122, 99, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (123, 100, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (124, 100, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (125, 101, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (126, 101, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (127, 102, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (128, 102, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (129, 103, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (130, 103, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (131, 104, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (132, 104, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (133, 105, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (134, 105, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (135, 106, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (136, 106, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (137, 108, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (138, 108, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (139, 109, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (140, 110, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (141, 110, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (142, 111, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (143, 111, 'mvp', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (144, 113, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (145, 114, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (146, 114, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (147, 115, 'best_killer', '3');
INSERT INTO `kog_kog_gamemeta` VALUES (148, 117, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (149, 117, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (150, 118, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (151, 118, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (152, 119, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (153, 119, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (154, 120, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (155, 120, 'mvp', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (156, 121, 'best_killer', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (157, 121, 'mvp', '1');
INSERT INTO `kog_kog_gamemeta` VALUES (158, 122, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (159, 123, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (160, 124, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (161, 124, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (162, 125, 'best_killer', '4');
INSERT INTO `kog_kog_gamemeta` VALUES (163, 126, 'best_killer', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (164, 126, 'mvp', '2');
INSERT INTO `kog_kog_gamemeta` VALUES (165, 127, 'best_killer', '2');
COMMIT;

-- ----------------------------
-- Table structure for kog_kog_games
-- ----------------------------
DROP TABLE IF EXISTS `kog_kog_games`;
CREATE TABLE `kog_kog_games` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(11) unsigned NOT NULL,
  `location` varchar(45) DEFAULT NULL,
  `start_time` varchar(45) DEFAULT NULL,
  `end_time` varchar(45) DEFAULT NULL,
  `chips_level` int(8) unsigned NOT NULL,
  `rebuy_rate` int(8) unsigned DEFAULT NULL,
  `bonus` varchar(256) NOT NULL,
  `memo` varchar(256) DEFAULT NULL,
  `status` int(8) unsigned NOT NULL DEFAULT '0',
  `created_by` mediumint(6) unsigned DEFAULT NULL,
  `game_type` varchar(45) DEFAULT 'SNG',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kog_kog_games
-- ----------------------------
BEGIN;
INSERT INTO `kog_kog_games` VALUES (1, 20180106, NULL, '1515220764', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:3:\"100\";i:4;s:3:\"400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (2, 20180106, NULL, '1515221382', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (3, 20180106, NULL, '1515221904', NULL, 1000, 100, 'a:4:{i:1;s:3:\"500\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-500\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (4, 20180106, NULL, '1515221920', NULL, 1000, 100, 'a:4:{i:1;s:3:\"500\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-500\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (5, 20180106, NULL, '1515221972', NULL, 1000, 100, 'a:4:{i:1;s:3:\"500\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-500\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (6, 20180106, NULL, '1515232956', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (7, 20180106, NULL, '1515233069', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (8, 20180106, NULL, '1515233567', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (9, 20180106, NULL, '1515234134', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (10, 20180106, NULL, '1515234203', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (11, 20180106, NULL, '1515234751', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (12, 20180106, NULL, '1515234772', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (13, 20180106, NULL, '1515234851', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (14, 20180106, NULL, '1515235337', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (15, 20180106, NULL, '1515235364', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"300\";i:3;s:5:\"-1001\";i:4;s:4:\"-100\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (16, 20180106, NULL, '1515235462', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (17, 20180106, NULL, '1515235774', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (18, 20180106, NULL, '1515246769', '1515262200', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (19, 20180107, NULL, '1515335061', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, NULL, 'SNG');
INSERT INTO `kog_kog_games` VALUES (20, 20180113, NULL, '1515850343', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-300\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (21, 20180113, NULL, '1515850403', '1515864000', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (22, 20180114, NULL, '1515897384', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (23, 20180114, NULL, '1515897443', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (24, 20180114, NULL, '1515898862', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (25, 20180119, NULL, '1516364456', '1516383288', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (26, 20180126, NULL, '1516970124', '1516987819', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (27, 20180209, NULL, '1518177490', '1519005600', 1000, 100, 'a:4:{i:1;s:3:\"200\";i:2;s:1:\"0\";i:3;s:4:\"-200\";i:4;s:1:\"0\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (28, 20180219, NULL, '1519044829', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (29, 20180219, NULL, '1519045161', NULL, 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (30, 20180219, NULL, '1519045272', '1519065905', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (31, 20180221, NULL, '1519197357', '1519215822', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (32, 20180224, NULL, '1519484896', '1519557877', 1000, 100, 'a:5:{i:1;i:400;i:2;i:100;i:3;i:0;i:4;i:-100;i:5;i:-400;}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (33, 20180302, NULL, '1519995303', '1520010899', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (34, 20180316, NULL, '1521202241', NULL, 1000, 100, 'a:3:{i:1;s:3:\"400\";i:2;s:1:\"0\";i:3;s:4:\"-400\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (35, 20180316, NULL, '1521202269', NULL, 1000, 100, 'a:3:{i:1;s:3:\"400\";i:2;s:1:\"0\";i:3;s:4:\"-400\";}', NULL, 0, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (36, 20180316, NULL, '1521204268', '1521220963', 1000, 100, 'a:3:{i:1;s:3:\"300\";i:2;s:3:\"100\";i:3;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (37, 20180323, NULL, '1521815601', '1521825689', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (38, 20180421, NULL, '1524312906', '1524332067', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (39, 20180428, NULL, '1524921259', '1524932696', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (40, 20180518, NULL, '1526653473', '1526666293', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (41, 20180602, NULL, '1527944205', '1527962173', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (42, 20180713, NULL, '1531486164', '1531503355', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (43, 20180720, NULL, '1532090354', '1532103461', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-100\";i:4;s:4:\"-400\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (44, 20180730, NULL, '1532951084', '1532960056', 1000, 100, 'a:3:{i:1;s:3:\"300\";i:2;s:1:\"0\";i:3;s:4:\"-300\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (45, 20180802, NULL, '1533214728', '1534150586', 1000, 100, 'a:3:{i:1;s:3:\"300\";i:2;s:1:\"0\";i:3;s:4:\"-300\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (46, 20180811, NULL, '1533999201', '1534150859', 1000, 100, 'a:4:{i:1;s:3:\"400\";i:2;s:3:\"100\";i:3;s:4:\"-400\";i:4;s:4:\"-100\";}', NULL, 2, 1, 'SNG');
INSERT INTO `kog_kog_games` VALUES (47, 20180824, NULL, '1535117378', '1535133156', 1000, 100, 'a:3:{i:1;s:3:\"100\";i:2;s:1:\"0\";i:3;s:4:\"-100\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (48, 20180827, NULL, '1535371941', '1535382969', 1000, 100, 'a:3:{i:1;s:3:\"100\";i:2;s:3:\"100\";i:3;s:4:\"-100\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (49, 20180901, NULL, '1535803042', '1535823324', 1000, 100, 'a:3:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (50, 20180907, NULL, '1536321569', '1536340179', 1000, 100, 'a:4:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";i:4;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (51, 20180908, NULL, '1536386921', '1536401258', 1000, 100, 'a:4:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";i:4;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (52, 20180910, NULL, '1536579517', '1536592421', 1000, 100, 'a:3:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (53, 20180923, NULL, '1537688409', '1545733520', 1000, 100, 'a:4:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";i:4;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (54, 20180924, NULL, '1537768553', '1545733589', 1000, 100, 'a:3:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (55, 20180930, NULL, '1538301465', '1545732680', 1000, 100, 'a:3:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (56, 20181007, NULL, '1538898637', '1545733741', 1000, 100, 'a:3:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (57, 20181009, NULL, '1539086040', '1545733201', 1000, 100, 'a:3:{i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:2:\"-1\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (58, 20181019, NULL, '1539948064', '1539961804', 1000, 100, 'a:3:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (59, 20181020, NULL, '1540036157', '1540048020', 1000, 100, 'a:3:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (60, 20181107, NULL, '1541592877', '1541605178', 1000, 100, 'a:3:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (61, 20181110, NULL, '1541829606', '1541841894', 1000, 100, 'a:3:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (62, 20181129, NULL, '1543490531', '1543504199', 1000, 100, 'a:3:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (63, 20181201, NULL, '1543675145', '1543686116', 1000, 100, 'a:3:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (64, 20181208, NULL, '1544274691', '1544288940', 1000, 100, 'a:3:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (65, 20181215, NULL, '1544876346', '1545127053', 1000, 100, 'a:4:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";i:4;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (66, 20181221, NULL, '1545392977', '1545410696', 1000, 100, 'a:4:{i:1;s:1:\"0\";i:2;s:1:\"0\";i:3;s:1:\"0\";i:4;s:1:\"0\";}', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (67, 20190115, NULL, '1547549230', '1547566946', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (68, 20190118, NULL, '1547822226', '1547836697', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (69, 20190119, NULL, '1547878286', '1547900511', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (70, 20190126, NULL, '1548483203', '1548525053', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (71, 20190131, NULL, '1548934081', '1548950809', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (72, 20190202, NULL, '1549114613', '1549119819', 1000, 100, 'N;', NULL, 0, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (73, 20190202, NULL, '1549114741', '1549133521', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (74, 20190211, NULL, '1549860327', '1549874024', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (75, 20190309, NULL, '1552133553', '1552155156', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (76, 20190314, NULL, '1552565394', NULL, 1000, 100, 'N;', NULL, 1, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (77, 20190316, NULL, '1552740023', '1552762252', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (78, 20190321, NULL, '1553176100', '1553184748', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (79, 20190323, NULL, '1553340084', '1553359128', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (80, 20190327, NULL, '1553685485', '1553735665', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (81, 20190329, NULL, '1553863796', '1553875893', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (82, 20190404, NULL, '1554384498', NULL, 1000, 100, 'N;', NULL, 0, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (83, 20190404, NULL, '1554384666', NULL, 1000, 100, 'N;', NULL, 1, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (84, 20190406, NULL, '1554539302', '1554565547', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (85, 20190413, NULL, '1555138784', '1555213938', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (86, 20190417, NULL, '1555502807', '1555516757', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (87, 20190419, NULL, '1555677551', '1555694780', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (88, 20190426, NULL, '1556284589', '1556301045', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (89, 20190430, NULL, '1556624253', '1556643501', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (90, 20190509, NULL, '1557403530', '1557425538', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (91, 20190510, NULL, '1557495822', '1557518900', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (92, 20190517, NULL, '1558100938', '1558120622', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (93, 20190518, NULL, '1558158735', '1558170951', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (94, 20190518, NULL, '1558178084', '1558207697', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (95, 20190525, NULL, '1558793336', '1558814919', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (96, 20190527, NULL, '1558958354', '1558962284', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (97, 20190531, NULL, '1559300962', '1559319096', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (98, 20190601, NULL, '1559391949', '1559405549', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (99, 20190609, NULL, '1560068862', '1560074863', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (100, 20190616, NULL, '1560672811', '1560690305', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (101, 20190622, NULL, '1561208019', '1561223836', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (102, 20190623, NULL, '1561268735', '1561283537', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (103, 20190626, NULL, '1561548454', '1561558969', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (104, 20190712, NULL, '1562939535', '1562948001', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (105, 20190717, NULL, '1563362223', '1563383149', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (106, 20190719, NULL, '1563536574', '1563554580', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (107, 20190721, NULL, '1563713824', '1563725289', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (108, 20190724, NULL, '1563977137', '1563978453', 200, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (109, 20190725, NULL, '1564063890', '1564065776', 200, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (110, 20190725, NULL, '1564065948', '1564069567', 200, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (111, 20190726, NULL, '1564150833', '1564152525', 200, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (112, 20190726, NULL, '1564152837', '1564236445', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (113, 20190727, NULL, '1564236486', '1564240291', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (114, 20190727, NULL, '1564240319', '1564240518', 200, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (115, 20190729, NULL, '1564407335', '1564411076', 200, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (116, 20190729, NULL, '1564411119', NULL, 200, 100, 'N;', NULL, 1, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (117, 20190730, NULL, '1564476779', '1564494213', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (118, 20190802, NULL, '1564752106', '1564765678', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (119, 20190816, NULL, '1565957031', '1565969100', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (120, 20190817, NULL, '1566020773', '1566033246', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (121, 20190920, NULL, '1568987576', '1568994311', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (122, 20190922, NULL, '1569130973', NULL, 1000, 100, 'N;', NULL, 1, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (123, 20191017, NULL, '1571309601', '1571326719', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (124, 20191020, NULL, '1571551753', '1571562571', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (125, 20191102, NULL, '1572692501', '1572710897', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (126, 20191106, NULL, '1573041318', NULL, 1000, 100, 'N;', NULL, 1, 1, 'CASH');
INSERT INTO `kog_kog_games` VALUES (127, 20191109, NULL, '1573280577', '1573288622', 1000, 100, 'N;', NULL, 2, 1, 'CASH');
COMMIT;

-- ----------------------------
-- Table structure for kog_kog_rebuy
-- ----------------------------
DROP TABLE IF EXISTS `kog_kog_rebuy`;
CREATE TABLE `kog_kog_rebuy` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(6) NOT NULL,
  `gid` mediumint(6) NOT NULL,
  `rebuy` int(8) NOT NULL,
  `paied` int(8) DEFAULT NULL,
  `killed_by` mediumint(6) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=509 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kog_kog_rebuy
-- ----------------------------
BEGIN;
INSERT INTO `kog_kog_rebuy` VALUES (1, 3, 17, 1000, 100, 2, 1515243584);
INSERT INTO `kog_kog_rebuy` VALUES (2, 2, 17, 500, 50, 1, 1515243723);
INSERT INTO `kog_kog_rebuy` VALUES (3, 3, 18, 1000, 100, 4, 1515250714);
INSERT INTO `kog_kog_rebuy` VALUES (4, 2, 18, 1000, 100, 1, 1515251578);
INSERT INTO `kog_kog_rebuy` VALUES (5, 2, 17, 1000, 100, 3, 1515250714);
INSERT INTO `kog_kog_rebuy` VALUES (6, 3, 17, 3000, 300, 1, 1515251578);
INSERT INTO `kog_kog_rebuy` VALUES (7, 1, 17, 1000, 100, 2, 1515321467);
INSERT INTO `kog_kog_rebuy` VALUES (8, 2, 18, 0, 0, 3, 1515329154);
INSERT INTO `kog_kog_rebuy` VALUES (9, 3, 17, 500, 50, 1, 1515339829);
INSERT INTO `kog_kog_rebuy` VALUES (10, 3, 17, 500, 50, 1, 1515339888);
INSERT INTO `kog_kog_rebuy` VALUES (11, 2, 21, 1000, 100, 1, 1515852119);
INSERT INTO `kog_kog_rebuy` VALUES (13, 4, 21, 1000, 100, 2, 1515854745);
INSERT INTO `kog_kog_rebuy` VALUES (15, 4, 21, 1000, 100, 3, 1515855968);
INSERT INTO `kog_kog_rebuy` VALUES (16, 1, 22, 1000, 100, 2, 1515897410);
INSERT INTO `kog_kog_rebuy` VALUES (17, 2, 22, 1000, 100, 3, 1515897448);
INSERT INTO `kog_kog_rebuy` VALUES (18, 1, 22, 1000, 100, 2, 1515897470);
INSERT INTO `kog_kog_rebuy` VALUES (19, 1, 22, 1000, 100, 3, 1515897477);
INSERT INTO `kog_kog_rebuy` VALUES (20, 1, 22, 1000, 100, 3, 1515897536);
INSERT INTO `kog_kog_rebuy` VALUES (21, 3, 22, 1000, 100, 1, 1515897587);
INSERT INTO `kog_kog_rebuy` VALUES (22, 3, 22, 1000, 100, 1, 1515897618);
INSERT INTO `kog_kog_rebuy` VALUES (23, 3, 22, 1000, 100, 2, 1515898479);
INSERT INTO `kog_kog_rebuy` VALUES (24, 4, 22, 1000, 100, 1, 1515898501);
INSERT INTO `kog_kog_rebuy` VALUES (25, 4, 22, 1000, 100, 2, 1515898619);
INSERT INTO `kog_kog_rebuy` VALUES (26, 4, 22, 1000, 100, 1, 1515898671);
INSERT INTO `kog_kog_rebuy` VALUES (27, 4, 22, 1000, 100, 1, 1515898692);
INSERT INTO `kog_kog_rebuy` VALUES (28, 2, 22, 1000, 100, 1, 1515898715);
INSERT INTO `kog_kog_rebuy` VALUES (29, 1, 23, 1000, 100, 2, 1515898767);
INSERT INTO `kog_kog_rebuy` VALUES (30, 2, 23, 1000, 100, 3, 1515898791);
INSERT INTO `kog_kog_rebuy` VALUES (31, 3, 23, 1000, 100, 4, 1515898809);
INSERT INTO `kog_kog_rebuy` VALUES (32, 2, 24, 1000, 100, 1, 1515898898);
INSERT INTO `kog_kog_rebuy` VALUES (33, 4, 24, 1000, 100, 2, 1515898921);
INSERT INTO `kog_kog_rebuy` VALUES (34, 4, 24, 1000, 100, 3, 1515898939);
INSERT INTO `kog_kog_rebuy` VALUES (35, 4, 25, 1000, 100, 3, 1516365559);
INSERT INTO `kog_kog_rebuy` VALUES (36, 3, 26, 1000, 100, 4, 1516975131);
INSERT INTO `kog_kog_rebuy` VALUES (37, 2, 26, 1000, 100, 4, 1516975150);
INSERT INTO `kog_kog_rebuy` VALUES (38, 3, 26, 1000, 100, 1, 1516975846);
INSERT INTO `kog_kog_rebuy` VALUES (39, 3, 26, 1000, 100, 4, 1516978392);
INSERT INTO `kog_kog_rebuy` VALUES (40, 3, 26, 1000, 100, 1, 1516981702);
INSERT INTO `kog_kog_rebuy` VALUES (41, 3, 26, 1000, 100, 1, 1516982031);
INSERT INTO `kog_kog_rebuy` VALUES (42, 3, 26, 1000, 100, 2, 1516983995);
INSERT INTO `kog_kog_rebuy` VALUES (43, 7, 27, 1000, 100, 1, 1518182138);
INSERT INTO `kog_kog_rebuy` VALUES (44, 2, 27, 1000, 100, 1, 1518183996);
INSERT INTO `kog_kog_rebuy` VALUES (45, 1, 30, 1000, 100, 2, 1519050043);
INSERT INTO `kog_kog_rebuy` VALUES (46, 4, 30, 1000, 100, 2, 1519063856);
INSERT INTO `kog_kog_rebuy` VALUES (47, 1, 31, 1000, 100, 2, 1519199607);
INSERT INTO `kog_kog_rebuy` VALUES (48, 1, 31, 1000, 100, 3, 1519201577);
INSERT INTO `kog_kog_rebuy` VALUES (49, 4, 31, 1000, 100, 1, 1519203287);
INSERT INTO `kog_kog_rebuy` VALUES (50, 3, 31, 1000, 100, 2, 1519205705);
INSERT INTO `kog_kog_rebuy` VALUES (51, 1, 31, 1000, 100, 4, 1519209399);
INSERT INTO `kog_kog_rebuy` VALUES (52, 1, 31, 1000, 100, 2, 1519210010);
INSERT INTO `kog_kog_rebuy` VALUES (53, 1, 31, 1000, 100, 3, 1519214567);
INSERT INTO `kog_kog_rebuy` VALUES (54, 2, 32, 1000, 100, 4, 1519486647);
INSERT INTO `kog_kog_rebuy` VALUES (55, 2, 32, 1000, 100, 3, 1519489752);
INSERT INTO `kog_kog_rebuy` VALUES (56, 2, 32, 1000, 100, 7, 1519491574);
INSERT INTO `kog_kog_rebuy` VALUES (57, 1, 32, 1000, 100, 4, 1519494289);
INSERT INTO `kog_kog_rebuy` VALUES (58, 1, 33, 1000, 100, 4, 1519996261);
INSERT INTO `kog_kog_rebuy` VALUES (59, 1, 33, 1000, 100, 4, 1519996764);
INSERT INTO `kog_kog_rebuy` VALUES (60, 2, 33, 1000, 100, 4, 1520002608);
INSERT INTO `kog_kog_rebuy` VALUES (61, 1, 33, 1000, 100, 4, 1520003036);
INSERT INTO `kog_kog_rebuy` VALUES (62, 2, 33, 1000, 100, 3, 1520006040);
INSERT INTO `kog_kog_rebuy` VALUES (63, 2, 36, 1000, 100, 1, 1521217465);
INSERT INTO `kog_kog_rebuy` VALUES (64, 1, 37, 1000, 100, 4, 1521817074);
INSERT INTO `kog_kog_rebuy` VALUES (65, 2, 37, 1000, 100, 4, 1521817519);
INSERT INTO `kog_kog_rebuy` VALUES (66, 4, 38, 1000, 100, 1, 1524316208);
INSERT INTO `kog_kog_rebuy` VALUES (67, 3, 38, 1000, 100, 4, 1524316226);
INSERT INTO `kog_kog_rebuy` VALUES (68, 3, 38, 1000, 100, 4, 1524317797);
INSERT INTO `kog_kog_rebuy` VALUES (69, 3, 38, 1000, 100, 4, 1524319870);
INSERT INTO `kog_kog_rebuy` VALUES (70, 1, 38, 1000, 100, 2, 1524323767);
INSERT INTO `kog_kog_rebuy` VALUES (71, 4, 38, 1000, 100, 2, 1524327213);
INSERT INTO `kog_kog_rebuy` VALUES (72, 2, 39, 1000, 100, 4, 1524921486);
INSERT INTO `kog_kog_rebuy` VALUES (73, 3, 39, 1000, 100, 2, 1524921897);
INSERT INTO `kog_kog_rebuy` VALUES (74, 3, 39, 1000, 100, 4, 1524926150);
INSERT INTO `kog_kog_rebuy` VALUES (75, 3, 40, 1000, 100, 4, 1526655843);
INSERT INTO `kog_kog_rebuy` VALUES (76, 4, 40, 1000, 100, 3, 1526656587);
INSERT INTO `kog_kog_rebuy` VALUES (77, 4, 40, 1000, 100, 1, 1526658147);
INSERT INTO `kog_kog_rebuy` VALUES (78, 2, 40, 1000, 100, 3, 1526659814);
INSERT INTO `kog_kog_rebuy` VALUES (79, 2, 40, 1000, 100, 4, 1526660087);
INSERT INTO `kog_kog_rebuy` VALUES (80, 2, 41, 1000, 100, 4, 1527947027);
INSERT INTO `kog_kog_rebuy` VALUES (81, 2, 41, 1000, 100, 3, 1527947707);
INSERT INTO `kog_kog_rebuy` VALUES (82, 4, 41, 1000, 100, 3, 1527951035);
INSERT INTO `kog_kog_rebuy` VALUES (83, 2, 41, 1000, 100, 1, 1527952209);
INSERT INTO `kog_kog_rebuy` VALUES (84, 2, 41, 1000, 100, 1, 1527954080);
INSERT INTO `kog_kog_rebuy` VALUES (85, 4, 41, 1000, 100, 2, 1527955624);
INSERT INTO `kog_kog_rebuy` VALUES (86, 2, 41, 1000, 100, 4, 1527959159);
INSERT INTO `kog_kog_rebuy` VALUES (87, 3, 41, 1000, 100, 4, 1527959175);
INSERT INTO `kog_kog_rebuy` VALUES (88, 3, 41, 1000, 100, 4, 1527959954);
INSERT INTO `kog_kog_rebuy` VALUES (89, 2, 41, 1000, 100, 3, 1527960733);
INSERT INTO `kog_kog_rebuy` VALUES (90, 3, 42, 1000, 100, 4, 1531486760);
INSERT INTO `kog_kog_rebuy` VALUES (91, 2, 42, 1000, 100, 4, 1531490917);
INSERT INTO `kog_kog_rebuy` VALUES (92, 1, 42, 1000, 100, 2, 1531494522);
INSERT INTO `kog_kog_rebuy` VALUES (93, 2, 42, 1000, 100, 4, 1531495979);
INSERT INTO `kog_kog_rebuy` VALUES (94, 1, 42, 1000, 100, 3, 1531501246);
INSERT INTO `kog_kog_rebuy` VALUES (95, 1, 42, 1000, 100, 4, 1531501556);
INSERT INTO `kog_kog_rebuy` VALUES (96, 4, 43, 1000, 100, 3, 1532093021);
INSERT INTO `kog_kog_rebuy` VALUES (97, 1, 43, 1000, 100, 4, 1532093806);
INSERT INTO `kog_kog_rebuy` VALUES (98, 2, 43, 1000, 100, 1, 1532095557);
INSERT INTO `kog_kog_rebuy` VALUES (99, 2, 43, 1000, 100, 4, 1532096959);
INSERT INTO `kog_kog_rebuy` VALUES (100, 1, 43, 1000, 100, 2, 1532100124);
INSERT INTO `kog_kog_rebuy` VALUES (101, 4, 44, 1000, 100, 2, 1532951103);
INSERT INTO `kog_kog_rebuy` VALUES (102, 4, 44, 1000, 100, 1, 1532951733);
INSERT INTO `kog_kog_rebuy` VALUES (103, 4, 44, 1000, 100, 1, 1532953383);
INSERT INTO `kog_kog_rebuy` VALUES (104, 2, 44, 1000, 100, 1, 1532953891);
INSERT INTO `kog_kog_rebuy` VALUES (105, 4, 44, 1000, 100, 1, 1532958919);
INSERT INTO `kog_kog_rebuy` VALUES (106, 4, 44, 1000, 100, 2, 1532959613);
INSERT INTO `kog_kog_rebuy` VALUES (107, 1, 45, 1000, 100, 2, 1533219462);
INSERT INTO `kog_kog_rebuy` VALUES (108, 4, 45, 1000, 100, 2, 1533219476);
INSERT INTO `kog_kog_rebuy` VALUES (109, 1, 45, 1000, 100, 4, 1533220605);
INSERT INTO `kog_kog_rebuy` VALUES (110, 1, 45, 1000, 100, 4, 1533222841);
INSERT INTO `kog_kog_rebuy` VALUES (111, 1, 45, 1000, 100, 2, 1533223936);
INSERT INTO `kog_kog_rebuy` VALUES (112, 2, 46, 1000, 200, 3, 1534001546);
INSERT INTO `kog_kog_rebuy` VALUES (113, 2, 46, 1000, 200, 4, 1534003432);
INSERT INTO `kog_kog_rebuy` VALUES (114, 1, 46, 1000, 200, 2, 1534004115);
INSERT INTO `kog_kog_rebuy` VALUES (115, 2, 46, 1000, 200, 4, 1534006277);
INSERT INTO `kog_kog_rebuy` VALUES (116, 2, 46, 1000, 200, 1, 1534007127);
INSERT INTO `kog_kog_rebuy` VALUES (117, 2, 47, 1000, 100, 1, 1535123713);
INSERT INTO `kog_kog_rebuy` VALUES (118, 4, 47, 1000, 100, 2, 1535126273);
INSERT INTO `kog_kog_rebuy` VALUES (119, 1, 47, 1000, 100, 4, 1535130098);
INSERT INTO `kog_kog_rebuy` VALUES (120, 1, 47, 1000, 100, 2, 1535130120);
INSERT INTO `kog_kog_rebuy` VALUES (121, 1, 47, 1000, 100, 2, 1535132366);
INSERT INTO `kog_kog_rebuy` VALUES (122, 1, 48, 1000, 100, 4, 1535375364);
INSERT INTO `kog_kog_rebuy` VALUES (123, 2, 48, 1000, 100, 1, 1535375379);
INSERT INTO `kog_kog_rebuy` VALUES (124, 2, 48, 1000, 100, 4, 1535381096);
INSERT INTO `kog_kog_rebuy` VALUES (125, 1, 49, 1000, 100, 4, 1535804021);
INSERT INTO `kog_kog_rebuy` VALUES (126, 1, 49, 1000, 100, 4, 1535808248);
INSERT INTO `kog_kog_rebuy` VALUES (127, 1, 49, 1000, 100, 4, 1535810010);
INSERT INTO `kog_kog_rebuy` VALUES (128, 2, 49, 1000, 100, 4, 1535811646);
INSERT INTO `kog_kog_rebuy` VALUES (129, 1, 49, 1000, 100, 2, 1535811890);
INSERT INTO `kog_kog_rebuy` VALUES (130, 1, 49, 1000, 100, 2, 1535813783);
INSERT INTO `kog_kog_rebuy` VALUES (131, 1, 49, 1000, 100, 4, 1535815134);
INSERT INTO `kog_kog_rebuy` VALUES (132, 2, 49, 1000, 100, 4, 1535816559);
INSERT INTO `kog_kog_rebuy` VALUES (133, 2, 49, 1000, 100, 4, 1535817795);
INSERT INTO `kog_kog_rebuy` VALUES (134, 2, 49, 1000, 100, 4, 1535819217);
INSERT INTO `kog_kog_rebuy` VALUES (135, 2, 49, 1000, 100, 4, 1535819493);
INSERT INTO `kog_kog_rebuy` VALUES (136, 2, 49, 1000, 100, 4, 1535820705);
INSERT INTO `kog_kog_rebuy` VALUES (137, 2, 49, 1000, 100, 1, 1535821556);
INSERT INTO `kog_kog_rebuy` VALUES (138, 2, 49, 1000, 100, 4, 1535821680);
INSERT INTO `kog_kog_rebuy` VALUES (139, 4, 50, 1000, 100, 3, 1536322059);
INSERT INTO `kog_kog_rebuy` VALUES (140, 1, 50, 1000, 100, 3, 1536324967);
INSERT INTO `kog_kog_rebuy` VALUES (141, 4, 50, 1000, 100, 2, 1536326692);
INSERT INTO `kog_kog_rebuy` VALUES (142, 4, 50, 1000, 100, 2, 1536328012);
INSERT INTO `kog_kog_rebuy` VALUES (143, 4, 50, 1000, 100, 1, 1536328157);
INSERT INTO `kog_kog_rebuy` VALUES (144, 4, 50, 1000, 100, 1, 1536330608);
INSERT INTO `kog_kog_rebuy` VALUES (145, 3, 50, 1000, 100, 4, 1536334843);
INSERT INTO `kog_kog_rebuy` VALUES (146, 1, 51, 1000, 100, 2, 1536389154);
INSERT INTO `kog_kog_rebuy` VALUES (147, 3, 51, 1000, 100, 4, 1536391095);
INSERT INTO `kog_kog_rebuy` VALUES (148, 4, 51, 1000, 100, 3, 1536392917);
INSERT INTO `kog_kog_rebuy` VALUES (149, 4, 51, 1000, 100, 1, 1536393132);
INSERT INTO `kog_kog_rebuy` VALUES (150, 2, 51, 1000, 100, 4, 1536393477);
INSERT INTO `kog_kog_rebuy` VALUES (151, 2, 51, 1000, 100, 4, 1536395497);
INSERT INTO `kog_kog_rebuy` VALUES (152, 2, 51, 1000, 100, 4, 1536396382);
INSERT INTO `kog_kog_rebuy` VALUES (153, 2, 51, 1000, 100, 4, 1536396385);
INSERT INTO `kog_kog_rebuy` VALUES (154, 2, 51, 1000, 100, 3, 1536396487);
INSERT INTO `kog_kog_rebuy` VALUES (155, 2, 51, 1000, 100, 4, 1536397644);
INSERT INTO `kog_kog_rebuy` VALUES (156, 2, 51, 1000, 100, 3, 1536399049);
INSERT INTO `kog_kog_rebuy` VALUES (157, 2, 51, 1000, 100, 1, 1536400314);
INSERT INTO `kog_kog_rebuy` VALUES (158, 4, 51, 1000, 100, 1, 1536400392);
INSERT INTO `kog_kog_rebuy` VALUES (159, 2, 51, 1000, 100, 4, 1536400901);
INSERT INTO `kog_kog_rebuy` VALUES (160, 4, 52, 1000, 100, 2, 1536580576);
INSERT INTO `kog_kog_rebuy` VALUES (161, 1, 52, 1000, 100, 2, 1536582234);
INSERT INTO `kog_kog_rebuy` VALUES (162, 4, 52, 1000, 100, 1, 1536583836);
INSERT INTO `kog_kog_rebuy` VALUES (163, 4, 52, 1000, 100, 1, 1536584534);
INSERT INTO `kog_kog_rebuy` VALUES (164, 4, 52, 1000, 100, 1, 1536585021);
INSERT INTO `kog_kog_rebuy` VALUES (165, 2, 53, 1000, 100, 4, 1537688429);
INSERT INTO `kog_kog_rebuy` VALUES (166, 3, 53, 1000, 100, 1, 1537689512);
INSERT INTO `kog_kog_rebuy` VALUES (167, 3, 53, 1000, 100, 1, 1537689995);
INSERT INTO `kog_kog_rebuy` VALUES (168, 2, 53, 1000, 100, 1, 1537690499);
INSERT INTO `kog_kog_rebuy` VALUES (169, 3, 53, 1000, 100, 1, 1537691636);
INSERT INTO `kog_kog_rebuy` VALUES (170, 2, 53, 1000, 100, 1, 1537691667);
INSERT INTO `kog_kog_rebuy` VALUES (171, 3, 53, 1000, 100, 4, 1537692132);
INSERT INTO `kog_kog_rebuy` VALUES (172, 4, 53, 1000, 100, 2, 1537693160);
INSERT INTO `kog_kog_rebuy` VALUES (173, 3, 53, 1000, 100, 4, 1537693414);
INSERT INTO `kog_kog_rebuy` VALUES (174, 2, 53, 1000, 100, 1, 1537694535);
INSERT INTO `kog_kog_rebuy` VALUES (175, 4, 53, 1000, 100, 3, 1537695492);
INSERT INTO `kog_kog_rebuy` VALUES (176, 4, 53, 1000, 100, 1, 1537695735);
INSERT INTO `kog_kog_rebuy` VALUES (177, 4, 53, 1000, 100, 2, 1537696122);
INSERT INTO `kog_kog_rebuy` VALUES (178, 4, 53, 1000, 100, 3, 1537696612);
INSERT INTO `kog_kog_rebuy` VALUES (179, 4, 54, 1000, 100, 2, 1537771609);
INSERT INTO `kog_kog_rebuy` VALUES (180, 2, 54, 1000, 100, 1, 1537772479);
INSERT INTO `kog_kog_rebuy` VALUES (181, 2, 54, 1000, 100, 4, 1537773135);
INSERT INTO `kog_kog_rebuy` VALUES (182, 2, 54, 1000, 100, 4, 1537773638);
INSERT INTO `kog_kog_rebuy` VALUES (183, 2, 54, 1000, 100, 1, 1537778990);
INSERT INTO `kog_kog_rebuy` VALUES (184, 2, 54, 1000, 100, 4, 1537781677);
INSERT INTO `kog_kog_rebuy` VALUES (185, 4, 55, 1000, 100, 2, 1538302295);
INSERT INTO `kog_kog_rebuy` VALUES (186, 4, 55, 1000, 100, 1, 1538303881);
INSERT INTO `kog_kog_rebuy` VALUES (187, 4, 55, 1000, 100, 2, 1538307626);
INSERT INTO `kog_kog_rebuy` VALUES (188, 4, 56, 1000, 100, 1, 1538902973);
INSERT INTO `kog_kog_rebuy` VALUES (189, 1, 56, 1000, 100, 4, 1538903389);
INSERT INTO `kog_kog_rebuy` VALUES (190, 2, 56, 1000, 100, 4, 1538905714);
INSERT INTO `kog_kog_rebuy` VALUES (191, 2, 56, 1000, 100, 4, 1538907877);
INSERT INTO `kog_kog_rebuy` VALUES (192, 4, 56, 1000, 100, 1, 1538911775);
INSERT INTO `kog_kog_rebuy` VALUES (193, 4, 57, 1000, 100, 2, 1539086142);
INSERT INTO `kog_kog_rebuy` VALUES (194, 4, 57, 1000, 100, 2, 1539086531);
INSERT INTO `kog_kog_rebuy` VALUES (195, 2, 58, 1000, 100, 4, 1539951783);
INSERT INTO `kog_kog_rebuy` VALUES (196, 4, 58, 1000, 100, 1, 1539953010);
INSERT INTO `kog_kog_rebuy` VALUES (197, 4, 58, 1000, 100, 1, 1539953247);
INSERT INTO `kog_kog_rebuy` VALUES (198, 2, 58, 1000, 100, 4, 1539955812);
INSERT INTO `kog_kog_rebuy` VALUES (199, 2, 58, 1000, 100, 1, 1539959895);
INSERT INTO `kog_kog_rebuy` VALUES (200, 2, 58, 1000, 100, 1, 1539960555);
INSERT INTO `kog_kog_rebuy` VALUES (201, 2, 59, 1000, 100, 1, 1540038062);
INSERT INTO `kog_kog_rebuy` VALUES (202, 1, 60, 1000, 100, 4, 1541594078);
INSERT INTO `kog_kog_rebuy` VALUES (203, 2, 60, 1000, 100, 1, 1541598230);
INSERT INTO `kog_kog_rebuy` VALUES (204, 2, 60, 1000, 100, 4, 1541602164);
INSERT INTO `kog_kog_rebuy` VALUES (205, 2, 61, 1000, 100, 1, 1541837488);
INSERT INTO `kog_kog_rebuy` VALUES (206, 4, 62, 1000, 100, 1, 1543492879);
INSERT INTO `kog_kog_rebuy` VALUES (207, 2, 62, 1000, 100, 4, 1543500932);
INSERT INTO `kog_kog_rebuy` VALUES (208, 1, 62, 1000, 100, 4, 1543500961);
INSERT INTO `kog_kog_rebuy` VALUES (209, 2, 63, 1000, 100, 1, 1543676574);
INSERT INTO `kog_kog_rebuy` VALUES (210, 1, 63, 1000, 100, 2, 1543682403);
INSERT INTO `kog_kog_rebuy` VALUES (211, 2, 64, 1000, 100, 1, 1544279958);
INSERT INTO `kog_kog_rebuy` VALUES (212, 2, 64, 1000, 100, 3, 1544279969);
INSERT INTO `kog_kog_rebuy` VALUES (213, 1, 64, 1000, 100, 2, 1544284976);
INSERT INTO `kog_kog_rebuy` VALUES (214, 4, 65, 1000, 100, 1, 1544878967);
INSERT INTO `kog_kog_rebuy` VALUES (215, 3, 65, 1000, 100, 2, 1544878985);
INSERT INTO `kog_kog_rebuy` VALUES (216, 4, 65, 1000, 100, 1, 1544879254);
INSERT INTO `kog_kog_rebuy` VALUES (217, 4, 65, 1000, 100, 2, 1544880797);
INSERT INTO `kog_kog_rebuy` VALUES (218, 2, 65, 1000, 100, 3, 1544887567);
INSERT INTO `kog_kog_rebuy` VALUES (219, 4, 65, 1000, 100, 1, 1544888794);
INSERT INTO `kog_kog_rebuy` VALUES (220, 4, 65, 1000, 100, 3, 1544891677);
INSERT INTO `kog_kog_rebuy` VALUES (221, 4, 65, 1000, 100, 2, 1544892776);
INSERT INTO `kog_kog_rebuy` VALUES (222, 4, 65, 1000, 100, 1, 1544893469);
INSERT INTO `kog_kog_rebuy` VALUES (223, 4, 65, 1000, 100, 3, 1544896590);
INSERT INTO `kog_kog_rebuy` VALUES (224, 4, 65, 1000, 100, 2, 1544897450);
INSERT INTO `kog_kog_rebuy` VALUES (225, 4, 65, 1000, 100, 2, 1544898171);
INSERT INTO `kog_kog_rebuy` VALUES (226, 3, 66, 1000, 100, 1, 1545394906);
INSERT INTO `kog_kog_rebuy` VALUES (227, 4, 66, 1000, 100, 2, 1545399026);
INSERT INTO `kog_kog_rebuy` VALUES (228, 3, 66, 1000, 100, 4, 1545402222);
INSERT INTO `kog_kog_rebuy` VALUES (229, 3, 66, 1000, 100, 2, 1545403012);
INSERT INTO `kog_kog_rebuy` VALUES (230, 3, 66, 1000, 100, 2, 1545408353);
INSERT INTO `kog_kog_rebuy` VALUES (231, 3, 66, 1000, 100, 1, 1545408567);
INSERT INTO `kog_kog_rebuy` VALUES (232, 3, 66, 1000, 100, 2, 1545408728);
INSERT INTO `kog_kog_rebuy` VALUES (233, 4, 66, 1000, 100, 3, 1545409120);
INSERT INTO `kog_kog_rebuy` VALUES (234, 4, 66, 1000, 100, 3, 1545410069);
INSERT INTO `kog_kog_rebuy` VALUES (235, 4, 66, 1000, 100, 3, 1545410200);
INSERT INTO `kog_kog_rebuy` VALUES (236, 2, 67, 1000, 100, 4, 1547550596);
INSERT INTO `kog_kog_rebuy` VALUES (237, 2, 67, 1000, 100, 4, 1547552990);
INSERT INTO `kog_kog_rebuy` VALUES (238, 1, 67, 1000, 100, 2, 1547554440);
INSERT INTO `kog_kog_rebuy` VALUES (239, 1, 67, 1000, 100, 4, 1547556159);
INSERT INTO `kog_kog_rebuy` VALUES (240, 2, 67, 1000, 100, 4, 1547556423);
INSERT INTO `kog_kog_rebuy` VALUES (241, 1, 67, 1000, 100, 4, 1547556573);
INSERT INTO `kog_kog_rebuy` VALUES (242, 1, 67, 1000, 100, 4, 1547558901);
INSERT INTO `kog_kog_rebuy` VALUES (243, 2, 67, 1000, 100, 4, 1547560363);
INSERT INTO `kog_kog_rebuy` VALUES (244, 2, 67, 1000, 100, 4, 1547561492);
INSERT INTO `kog_kog_rebuy` VALUES (245, 2, 67, 1000, 100, 4, 1547561830);
INSERT INTO `kog_kog_rebuy` VALUES (246, 1, 67, 1000, 100, 2, 1547563151);
INSERT INTO `kog_kog_rebuy` VALUES (247, 1, 67, 1000, 100, 2, 1547564719);
INSERT INTO `kog_kog_rebuy` VALUES (248, 1, 67, 1000, 100, 2, 1547565081);
INSERT INTO `kog_kog_rebuy` VALUES (249, 2, 68, 1000, 100, 1, 1547822606);
INSERT INTO `kog_kog_rebuy` VALUES (250, 4, 68, 1000, 100, 2, 1547823564);
INSERT INTO `kog_kog_rebuy` VALUES (251, 4, 68, 1000, 100, 1, 1547824722);
INSERT INTO `kog_kog_rebuy` VALUES (252, 2, 68, 1000, 100, 1, 1547829800);
INSERT INTO `kog_kog_rebuy` VALUES (253, 2, 68, 1000, 100, 1, 1547829998);
INSERT INTO `kog_kog_rebuy` VALUES (254, 4, 69, 1000, 100, 1, 1547880569);
INSERT INTO `kog_kog_rebuy` VALUES (255, 4, 69, 1000, 100, 1, 1547882914);
INSERT INTO `kog_kog_rebuy` VALUES (256, 4, 69, 1000, 100, 1, 1547885444);
INSERT INTO `kog_kog_rebuy` VALUES (257, 4, 69, 1000, 100, 2, 1547886339);
INSERT INTO `kog_kog_rebuy` VALUES (258, 2, 69, 1000, 100, 4, 1547889805);
INSERT INTO `kog_kog_rebuy` VALUES (259, 4, 70, 1000, 100, 2, 1548487316);
INSERT INTO `kog_kog_rebuy` VALUES (260, 2, 70, 1000, 100, 4, 1548493823);
INSERT INTO `kog_kog_rebuy` VALUES (261, 1, 70, 1000, 100, 3, 1548494572);
INSERT INTO `kog_kog_rebuy` VALUES (262, 2, 70, 1000, 100, 3, 1548495753);
INSERT INTO `kog_kog_rebuy` VALUES (263, 2, 70, 1000, 100, 3, 1548504031);
INSERT INTO `kog_kog_rebuy` VALUES (264, 2, 70, 1000, 100, 4, 1548507993);
INSERT INTO `kog_kog_rebuy` VALUES (265, 2, 70, 1000, 100, 4, 1548520527);
INSERT INTO `kog_kog_rebuy` VALUES (266, 2, 70, 1000, 100, 3, 1548522261);
INSERT INTO `kog_kog_rebuy` VALUES (267, 1, 71, 1000, 100, 4, 1548935381);
INSERT INTO `kog_kog_rebuy` VALUES (268, 2, 71, 1000, 100, 4, 1548939703);
INSERT INTO `kog_kog_rebuy` VALUES (269, 4, 71, 1000, 100, 2, 1548941301);
INSERT INTO `kog_kog_rebuy` VALUES (270, 4, 71, 1000, 100, 1, 1548945570);
INSERT INTO `kog_kog_rebuy` VALUES (271, 4, 71, 1000, 100, 2, 1548949704);
INSERT INTO `kog_kog_rebuy` VALUES (272, 4, 73, 1000, 100, 1, 1549115183);
INSERT INTO `kog_kog_rebuy` VALUES (273, 1, 73, 1000, 100, 4, 1549116035);
INSERT INTO `kog_kog_rebuy` VALUES (274, 1, 73, 1000, 100, 4, 1549119763);
INSERT INTO `kog_kog_rebuy` VALUES (275, 1, 73, 1000, 100, 4, 1549120256);
INSERT INTO `kog_kog_rebuy` VALUES (276, 2, 73, 1000, 100, 4, 1549122052);
INSERT INTO `kog_kog_rebuy` VALUES (277, 2, 73, 1000, 100, 4, 1549127928);
INSERT INTO `kog_kog_rebuy` VALUES (278, 2, 73, 1000, 100, 1, 1549129378);
INSERT INTO `kog_kog_rebuy` VALUES (279, 2, 73, 1000, 100, 4, 1549131551);
INSERT INTO `kog_kog_rebuy` VALUES (280, 2, 73, 1000, 100, 1, 1549132906);
INSERT INTO `kog_kog_rebuy` VALUES (281, 4, 74, 1000, 100, 3, 1549861200);
INSERT INTO `kog_kog_rebuy` VALUES (282, 1, 75, 1000, 100, 4, 1552133975);
INSERT INTO `kog_kog_rebuy` VALUES (283, 2, 75, 1000, 100, 1, 1552135743);
INSERT INTO `kog_kog_rebuy` VALUES (284, 2, 75, 1000, 100, 4, 1552141303);
INSERT INTO `kog_kog_rebuy` VALUES (285, 2, 75, 1000, 100, 1, 1552141901);
INSERT INTO `kog_kog_rebuy` VALUES (286, 2, 75, 1000, 100, 3, 1552143284);
INSERT INTO `kog_kog_rebuy` VALUES (287, 4, 76, 1000, 100, 2, 1552567997);
INSERT INTO `kog_kog_rebuy` VALUES (288, 1, 76, 1000, 100, 4, 1552568048);
INSERT INTO `kog_kog_rebuy` VALUES (289, 1, 76, 1000, 100, 4, 1552568055);
INSERT INTO `kog_kog_rebuy` VALUES (290, 2, 76, 1000, 100, 4, 1552572879);
INSERT INTO `kog_kog_rebuy` VALUES (291, 2, 76, 1000, 100, 4, 1552576633);
INSERT INTO `kog_kog_rebuy` VALUES (292, 4, 77, 1000, 100, 2, 1552740745);
INSERT INTO `kog_kog_rebuy` VALUES (293, 4, 77, 1000, 100, 2, 1552740914);
INSERT INTO `kog_kog_rebuy` VALUES (294, 1, 77, 1000, 100, 4, 1552744942);
INSERT INTO `kog_kog_rebuy` VALUES (295, 2, 77, 1000, 100, 4, 1552745598);
INSERT INTO `kog_kog_rebuy` VALUES (296, 2, 77, 1000, 100, 4, 1552745959);
INSERT INTO `kog_kog_rebuy` VALUES (297, 2, 77, 1000, 100, 4, 1552746220);
INSERT INTO `kog_kog_rebuy` VALUES (298, 1, 77, 1000, 100, 4, 1552747578);
INSERT INTO `kog_kog_rebuy` VALUES (299, 2, 77, 1000, 100, 4, 1552747652);
INSERT INTO `kog_kog_rebuy` VALUES (300, 1, 77, 1000, 100, 3, 1552749367);
INSERT INTO `kog_kog_rebuy` VALUES (301, 1, 77, 1000, 100, 2, 1552749819);
INSERT INTO `kog_kog_rebuy` VALUES (302, 3, 77, 1000, 100, 4, 1552751098);
INSERT INTO `kog_kog_rebuy` VALUES (303, 1, 77, 1000, 100, 4, 1552759434);
INSERT INTO `kog_kog_rebuy` VALUES (304, 1, 77, 1000, 100, 2, 1552759836);
INSERT INTO `kog_kog_rebuy` VALUES (305, 1, 77, 1000, 100, 3, 1552760857);
INSERT INTO `kog_kog_rebuy` VALUES (306, 1, 77, 1000, 100, 3, 1552761104);
INSERT INTO `kog_kog_rebuy` VALUES (307, 4, 78, 1000, 100, 2, 1553182246);
INSERT INTO `kog_kog_rebuy` VALUES (308, 4, 78, 1000, 100, 2, 1553182676);
INSERT INTO `kog_kog_rebuy` VALUES (309, 2, 79, 1000, 100, 4, 1553342608);
INSERT INTO `kog_kog_rebuy` VALUES (310, 1, 79, 1000, 100, 2, 1553345363);
INSERT INTO `kog_kog_rebuy` VALUES (311, 1, 79, 1000, 100, 4, 1553347061);
INSERT INTO `kog_kog_rebuy` VALUES (312, 3, 79, 1000, 100, 2, 1553347294);
INSERT INTO `kog_kog_rebuy` VALUES (313, 3, 79, 1000, 100, 1, 1553351887);
INSERT INTO `kog_kog_rebuy` VALUES (314, 2, 79, 1000, 100, 4, 1553353911);
INSERT INTO `kog_kog_rebuy` VALUES (315, 2, 80, 1000, 100, 4, 1553687640);
INSERT INTO `kog_kog_rebuy` VALUES (316, 2, 80, 1000, 100, 4, 1553687647);
INSERT INTO `kog_kog_rebuy` VALUES (317, 4, 80, 1000, 100, 1, 1553687685);
INSERT INTO `kog_kog_rebuy` VALUES (318, 4, 80, 1000, 100, 2, 1553695035);
INSERT INTO `kog_kog_rebuy` VALUES (319, 4, 80, 1000, 100, 2, 1553696136);
INSERT INTO `kog_kog_rebuy` VALUES (320, 2, 81, 1000, 100, 1, 1553867862);
INSERT INTO `kog_kog_rebuy` VALUES (321, 4, 81, 1000, 100, 2, 1553867869);
INSERT INTO `kog_kog_rebuy` VALUES (322, 2, 81, 1000, 100, 4, 1553869435);
INSERT INTO `kog_kog_rebuy` VALUES (323, 4, 81, 1000, 100, 1, 1553871266);
INSERT INTO `kog_kog_rebuy` VALUES (324, 2, 81, 1000, 100, 4, 1553871868);
INSERT INTO `kog_kog_rebuy` VALUES (325, 2, 81, 1000, 100, 4, 1553873284);
INSERT INTO `kog_kog_rebuy` VALUES (326, 2, 81, 1000, 100, 4, 1553874519);
INSERT INTO `kog_kog_rebuy` VALUES (327, 2, 81, 1000, 100, 4, 1553875215);
INSERT INTO `kog_kog_rebuy` VALUES (328, 2, 82, 1000, 100, 3, 1554384509);
INSERT INTO `kog_kog_rebuy` VALUES (329, 4, 84, 1000, 100, 2, 1554541638);
INSERT INTO `kog_kog_rebuy` VALUES (330, 4, 84, 1000, 100, 1, 1554542186);
INSERT INTO `kog_kog_rebuy` VALUES (331, 1, 84, 1000, 100, 4, 1554545467);
INSERT INTO `kog_kog_rebuy` VALUES (332, 2, 84, 1000, 100, 1, 1554547709);
INSERT INTO `kog_kog_rebuy` VALUES (333, 2, 84, 1000, 100, 4, 1554549599);
INSERT INTO `kog_kog_rebuy` VALUES (334, 2, 84, 1000, 100, 1, 1554550000);
INSERT INTO `kog_kog_rebuy` VALUES (335, 2, 84, 1000, 100, 4, 1554561573);
INSERT INTO `kog_kog_rebuy` VALUES (336, 2, 84, 1000, 100, 4, 1554561774);
INSERT INTO `kog_kog_rebuy` VALUES (337, 2, 84, 1000, 100, 1, 1554563622);
INSERT INTO `kog_kog_rebuy` VALUES (338, 4, 85, 1000, 100, 1, 1555141037);
INSERT INTO `kog_kog_rebuy` VALUES (339, 2, 85, 1000, 100, 4, 1555144042);
INSERT INTO `kog_kog_rebuy` VALUES (340, 2, 85, 1000, 100, 4, 1555144285);
INSERT INTO `kog_kog_rebuy` VALUES (341, 4, 85, 1000, 100, 2, 1555148460);
INSERT INTO `kog_kog_rebuy` VALUES (342, 4, 85, 1000, 100, 1, 1555148828);
INSERT INTO `kog_kog_rebuy` VALUES (343, 4, 85, 1000, 100, 1, 1555152978);
INSERT INTO `kog_kog_rebuy` VALUES (344, 4, 85, 1000, 100, 1, 1555155749);
INSERT INTO `kog_kog_rebuy` VALUES (345, 2, 85, 1000, 100, 1, 1555169382);
INSERT INTO `kog_kog_rebuy` VALUES (346, 2, 85, 1000, 100, 4, 1555171111);
INSERT INTO `kog_kog_rebuy` VALUES (347, 2, 85, 1000, 100, 1, 1555173029);
INSERT INTO `kog_kog_rebuy` VALUES (348, 2, 85, 1000, 100, 1, 1555173038);
INSERT INTO `kog_kog_rebuy` VALUES (349, 2, 85, 1000, 100, 1, 1555173199);
INSERT INTO `kog_kog_rebuy` VALUES (350, 2, 85, 1000, 100, 1, 1555173515);
INSERT INTO `kog_kog_rebuy` VALUES (351, 2, 85, 1000, 100, 1, 1555174965);
INSERT INTO `kog_kog_rebuy` VALUES (352, 2, 85, 1000, 100, 4, 1555175828);
INSERT INTO `kog_kog_rebuy` VALUES (353, 2, 85, 1000, 100, 1, 1555178008);
INSERT INTO `kog_kog_rebuy` VALUES (354, 3, 85, 1000, 100, 1, 1555144042);
INSERT INTO `kog_kog_rebuy` VALUES (355, 3, 85, 1000, 100, 2, 1555144285);
INSERT INTO `kog_kog_rebuy` VALUES (356, 3, 85, 1000, 100, 4, 1555169382);
INSERT INTO `kog_kog_rebuy` VALUES (357, 3, 85, 1000, 100, 1, 1555171111);
INSERT INTO `kog_kog_rebuy` VALUES (358, 3, 85, 1000, 100, 2, 1555173029);
INSERT INTO `kog_kog_rebuy` VALUES (359, 3, 85, 1000, 100, 4, 1555173038);
INSERT INTO `kog_kog_rebuy` VALUES (360, 3, 85, 1000, 100, 1, 1555173199);
INSERT INTO `kog_kog_rebuy` VALUES (361, 3, 85, 1000, 100, 2, 1555173515);
INSERT INTO `kog_kog_rebuy` VALUES (362, 3, 85, 1000, 100, 4, 1555174965);
INSERT INTO `kog_kog_rebuy` VALUES (363, 3, 85, 1000, 100, 1, 1555175828);
INSERT INTO `kog_kog_rebuy` VALUES (364, 3, 85, 1000, 100, 2, 1555178008);
INSERT INTO `kog_kog_rebuy` VALUES (365, 2, 86, 1000, 100, 1, 1555503772);
INSERT INTO `kog_kog_rebuy` VALUES (366, 2, 86, 1000, 100, 4, 1555504665);
INSERT INTO `kog_kog_rebuy` VALUES (367, 1, 86, 1000, 100, 4, 1555506344);
INSERT INTO `kog_kog_rebuy` VALUES (368, 2, 86, 1000, 100, 1, 1555508862);
INSERT INTO `kog_kog_rebuy` VALUES (369, 2, 86, 1000, 100, 1, 1555511640);
INSERT INTO `kog_kog_rebuy` VALUES (370, 4, 86, 1000, 100, 1, 1555513034);
INSERT INTO `kog_kog_rebuy` VALUES (371, 2, 86, 1000, 100, 4, 1555513887);
INSERT INTO `kog_kog_rebuy` VALUES (372, 2, 87, 1000, 100, 1, 1555685752);
INSERT INTO `kog_kog_rebuy` VALUES (373, 4, 87, 1000, 100, 2, 1555689995);
INSERT INTO `kog_kog_rebuy` VALUES (374, 4, 87, 1000, 100, 2, 1555691331);
INSERT INTO `kog_kog_rebuy` VALUES (375, 4, 87, 1000, 100, 2, 1555693946);
INSERT INTO `kog_kog_rebuy` VALUES (376, 1, 88, 1000, 100, 3, 1556288042);
INSERT INTO `kog_kog_rebuy` VALUES (377, 2, 88, 1000, 100, 1, 1556288476);
INSERT INTO `kog_kog_rebuy` VALUES (378, 1, 88, 1000, 100, 3, 1556288770);
INSERT INTO `kog_kog_rebuy` VALUES (379, 2, 88, 1000, 100, 3, 1556292865);
INSERT INTO `kog_kog_rebuy` VALUES (380, 2, 88, 1000, 100, 3, 1556296793);
INSERT INTO `kog_kog_rebuy` VALUES (381, 2, 89, 1000, 100, 1, 1556627505);
INSERT INTO `kog_kog_rebuy` VALUES (382, 3, 89, 1000, 100, 1, 1556629252);
INSERT INTO `kog_kog_rebuy` VALUES (383, 2, 89, 1000, 100, 1, 1556629929);
INSERT INTO `kog_kog_rebuy` VALUES (384, 2, 89, 1000, 100, 3, 1556630519);
INSERT INTO `kog_kog_rebuy` VALUES (385, 3, 89, 1000, 100, 2, 1556632610);
INSERT INTO `kog_kog_rebuy` VALUES (386, 2, 89, 1000, 100, 1, 1556633227);
INSERT INTO `kog_kog_rebuy` VALUES (387, 2, 89, 1000, 100, 1, 1556634616);
INSERT INTO `kog_kog_rebuy` VALUES (388, 2, 89, 1000, 100, 1, 1556634824);
INSERT INTO `kog_kog_rebuy` VALUES (389, 2, 89, 1000, 100, 3, 1556635825);
INSERT INTO `kog_kog_rebuy` VALUES (390, 3, 89, 1000, 100, 1, 1556636747);
INSERT INTO `kog_kog_rebuy` VALUES (391, 2, 89, 1000, 100, 3, 1556642644);
INSERT INTO `kog_kog_rebuy` VALUES (392, 2, 89, 1000, 100, 3, 1556642655);
INSERT INTO `kog_kog_rebuy` VALUES (393, 2, 89, 1000, 100, 3, 1556643009);
INSERT INTO `kog_kog_rebuy` VALUES (394, 2, 89, 1000, 100, 3, 1556643027);
INSERT INTO `kog_kog_rebuy` VALUES (395, 4, 90, 1000, 100, 2, 1557405104);
INSERT INTO `kog_kog_rebuy` VALUES (396, 1, 90, 1000, 100, 2, 1557406511);
INSERT INTO `kog_kog_rebuy` VALUES (397, 4, 90, 1000, 100, 2, 1557417304);
INSERT INTO `kog_kog_rebuy` VALUES (398, 4, 90, 1000, 100, 1, 1557417606);
INSERT INTO `kog_kog_rebuy` VALUES (399, 4, 90, 1000, 100, 2, 1557423875);
INSERT INTO `kog_kog_rebuy` VALUES (400, 2, 91, 1000, 100, 1, 1557496141);
INSERT INTO `kog_kog_rebuy` VALUES (401, 2, 91, 1000, 100, 1, 1557497663);
INSERT INTO `kog_kog_rebuy` VALUES (402, 2, 91, 1000, 100, 1, 1557499524);
INSERT INTO `kog_kog_rebuy` VALUES (403, 2, 91, 1000, 100, 4, 1557500362);
INSERT INTO `kog_kog_rebuy` VALUES (404, 1, 91, 1000, 100, 2, 1557514607);
INSERT INTO `kog_kog_rebuy` VALUES (405, 1, 91, 1000, 100, 2, 1557515575);
INSERT INTO `kog_kog_rebuy` VALUES (406, 1, 91, 1000, 100, 2, 1557517078);
INSERT INTO `kog_kog_rebuy` VALUES (407, 2, 92, 1000, 100, 1, 1558102319);
INSERT INTO `kog_kog_rebuy` VALUES (408, 2, 92, 1000, 100, 4, 1558107390);
INSERT INTO `kog_kog_rebuy` VALUES (409, 1, 92, 1000, 100, 2, 1558107603);
INSERT INTO `kog_kog_rebuy` VALUES (410, 2, 92, 1000, 100, 4, 1558109917);
INSERT INTO `kog_kog_rebuy` VALUES (411, 1, 92, 1000, 100, 2, 1558114894);
INSERT INTO `kog_kog_rebuy` VALUES (412, 1, 92, 1000, 100, 2, 1558115672);
INSERT INTO `kog_kog_rebuy` VALUES (413, 2, 92, 1000, 100, 4, 1558117448);
INSERT INTO `kog_kog_rebuy` VALUES (414, 2, 93, 1000, 100, 4, 1558159516);
INSERT INTO `kog_kog_rebuy` VALUES (415, 2, 93, 1000, 100, 4, 1558160249);
INSERT INTO `kog_kog_rebuy` VALUES (416, 2, 93, 1000, 100, 4, 1558162342);
INSERT INTO `kog_kog_rebuy` VALUES (417, 2, 93, 1000, 100, 4, 1558164665);
INSERT INTO `kog_kog_rebuy` VALUES (418, 2, 93, 1000, 100, 4, 1558166832);
INSERT INTO `kog_kog_rebuy` VALUES (419, 2, 93, 1000, 100, 1, 1558170144);
INSERT INTO `kog_kog_rebuy` VALUES (420, 3, 94, 1000, 100, 1, 1558181240);
INSERT INTO `kog_kog_rebuy` VALUES (421, 3, 94, 1000, 100, 4, 1558188117);
INSERT INTO `kog_kog_rebuy` VALUES (422, 3, 94, 1000, 100, 4, 1558188126);
INSERT INTO `kog_kog_rebuy` VALUES (423, 1, 94, 1000, 100, 4, 1558190316);
INSERT INTO `kog_kog_rebuy` VALUES (424, 2, 94, 1000, 100, 4, 1558195777);
INSERT INTO `kog_kog_rebuy` VALUES (425, 3, 94, 1000, 100, 2, 1558196628);
INSERT INTO `kog_kog_rebuy` VALUES (426, 3, 94, 1000, 100, 2, 1558197291);
INSERT INTO `kog_kog_rebuy` VALUES (427, 4, 94, 1000, 100, 3, 1558197869);
INSERT INTO `kog_kog_rebuy` VALUES (428, 4, 94, 1000, 100, 2, 1558198677);
INSERT INTO `kog_kog_rebuy` VALUES (429, 4, 94, 1000, 100, 3, 1558200583);
INSERT INTO `kog_kog_rebuy` VALUES (430, 4, 94, 1000, 100, 2, 1558202391);
INSERT INTO `kog_kog_rebuy` VALUES (431, 2, 94, 1000, 100, 4, 1558205127);
INSERT INTO `kog_kog_rebuy` VALUES (432, 2, 94, 1000, 100, 4, 1558206099);
INSERT INTO `kog_kog_rebuy` VALUES (433, 3, 95, 1000, 100, 1, 1558795814);
INSERT INTO `kog_kog_rebuy` VALUES (434, 3, 95, 1000, 100, 2, 1558796632);
INSERT INTO `kog_kog_rebuy` VALUES (435, 1, 95, 1000, 100, 2, 1558799019);
INSERT INTO `kog_kog_rebuy` VALUES (436, 1, 95, 1000, 100, 3, 1558799981);
INSERT INTO `kog_kog_rebuy` VALUES (437, 1, 95, 2000, 200, 3, 1558801144);
INSERT INTO `kog_kog_rebuy` VALUES (438, 2, 95, 2000, 200, 1, 1558809362);
INSERT INTO `kog_kog_rebuy` VALUES (439, 2, 96, 1000, 100, 4, 1558962210);
INSERT INTO `kog_kog_rebuy` VALUES (440, 1, 97, 1000, 100, 2, 1559302527);
INSERT INTO `kog_kog_rebuy` VALUES (441, 4, 97, 1000, 100, 2, 1559302843);
INSERT INTO `kog_kog_rebuy` VALUES (442, 4, 97, 1000, 100, 2, 1559305163);
INSERT INTO `kog_kog_rebuy` VALUES (443, 4, 97, 1000, 100, 2, 1559305197);
INSERT INTO `kog_kog_rebuy` VALUES (444, 1, 97, 3000, 300, 4, 1559307410);
INSERT INTO `kog_kog_rebuy` VALUES (445, 1, 97, 4000, 400, 2, 1559317281);
INSERT INTO `kog_kog_rebuy` VALUES (446, 1, 98, 2000, 200, 2, 1559394046);
INSERT INTO `kog_kog_rebuy` VALUES (447, 1, 98, 2000, 200, 4, 1559394699);
INSERT INTO `kog_kog_rebuy` VALUES (448, 1, 98, 4000, 400, 4, 1559396160);
INSERT INTO `kog_kog_rebuy` VALUES (449, 2, 98, 3000, 300, 4, 1559403773);
INSERT INTO `kog_kog_rebuy` VALUES (450, 4, 99, 1000, 100, 2, 1560069446);
INSERT INTO `kog_kog_rebuy` VALUES (451, 4, 99, 2000, 200, 1, 1560071753);
INSERT INTO `kog_kog_rebuy` VALUES (452, 3, 100, 1000, 100, 2, 1560673691);
INSERT INTO `kog_kog_rebuy` VALUES (453, 3, 100, 2000, 200, 1, 1560682566);
INSERT INTO `kog_kog_rebuy` VALUES (454, 4, 101, 1000, 100, 2, 1561208423);
INSERT INTO `kog_kog_rebuy` VALUES (455, 4, 101, 2000, 200, 2, 1561209186);
INSERT INTO `kog_kog_rebuy` VALUES (456, 4, 101, 4000, 400, 2, 1561211010);
INSERT INTO `kog_kog_rebuy` VALUES (457, 3, 101, 3000, 300, 2, 1561213573);
INSERT INTO `kog_kog_rebuy` VALUES (458, 1, 101, 4000, 400, 4, 1561218740);
INSERT INTO `kog_kog_rebuy` VALUES (459, 1, 102, 1000, 100, 4, 1561272074);
INSERT INTO `kog_kog_rebuy` VALUES (460, 2, 102, 1000, 100, 4, 1561272953);
INSERT INTO `kog_kog_rebuy` VALUES (461, 2, 102, 2000, 200, 4, 1561274041);
INSERT INTO `kog_kog_rebuy` VALUES (462, 1, 102, 4000, 400, 4, 1561274722);
INSERT INTO `kog_kog_rebuy` VALUES (463, 4, 103, 2000, 200, 2, 1561553535);
INSERT INTO `kog_kog_rebuy` VALUES (464, 4, 103, 4000, 400, 1, 1561558250);
INSERT INTO `kog_kog_rebuy` VALUES (465, 2, 103, 1000, 100, 4, 1561558928);
INSERT INTO `kog_kog_rebuy` VALUES (466, 7, 104, 1000, 100, 1, 1562947750);
INSERT INTO `kog_kog_rebuy` VALUES (467, 1, 105, 1000, 100, 4, 1563366844);
INSERT INTO `kog_kog_rebuy` VALUES (468, 1, 105, 2000, 200, 4, 1563368383);
INSERT INTO `kog_kog_rebuy` VALUES (469, 2, 105, 2000, 200, 4, 1563369222);
INSERT INTO `kog_kog_rebuy` VALUES (470, 1, 105, 4000, 400, 4, 1563370765);
INSERT INTO `kog_kog_rebuy` VALUES (471, 2, 106, 2000, 200, 4, 1563547589);
INSERT INTO `kog_kog_rebuy` VALUES (472, 2, 106, 2000, 200, 4, 1563551662);
INSERT INTO `kog_kog_rebuy` VALUES (473, 2, 108, 400, 200, 1, 1563977181);
INSERT INTO `kog_kog_rebuy` VALUES (474, 4, 109, 300, 150, 2, 1564065386);
INSERT INTO `kog_kog_rebuy` VALUES (475, 4, 110, 400, 200, 2, 1564066841);
INSERT INTO `kog_kog_rebuy` VALUES (476, 2, 111, 200, 100, 3, 1564151846);
INSERT INTO `kog_kog_rebuy` VALUES (477, 3, 113, 400, 200, 4, 1564237797);
INSERT INTO `kog_kog_rebuy` VALUES (478, 4, 114, 500, 250, 2, 1564240351);
INSERT INTO `kog_kog_rebuy` VALUES (479, 3, 114, 200, 100, 1, 1564240363);
INSERT INTO `kog_kog_rebuy` VALUES (480, 3, 114, 600, 300, 4, 1564240386);
INSERT INTO `kog_kog_rebuy` VALUES (481, 1, 115, 300, 150, 3, 1564407921);
INSERT INTO `kog_kog_rebuy` VALUES (482, 1, 116, 300, 150, 3, 1564411921);
INSERT INTO `kog_kog_rebuy` VALUES (483, 4, 117, 1500, 150, 3, 1564479902);
INSERT INTO `kog_kog_rebuy` VALUES (484, 3, 117, 3000, 300, 1, 1564480443);
INSERT INTO `kog_kog_rebuy` VALUES (485, 2, 117, 3000, 300, 4, 1564480859);
INSERT INTO `kog_kog_rebuy` VALUES (486, 1, 118, 1000, 100, 4, 1564756235);
INSERT INTO `kog_kog_rebuy` VALUES (487, 2, 118, 3000, 300, 4, 1564756582);
INSERT INTO `kog_kog_rebuy` VALUES (488, 2, 119, 2000, 200, 4, 1565960946);
INSERT INTO `kog_kog_rebuy` VALUES (489, 2, 119, 2000, 200, 4, 1565960946);
INSERT INTO `kog_kog_rebuy` VALUES (490, 1, 119, 2500, 250, 2, 1565965457);
INSERT INTO `kog_kog_rebuy` VALUES (491, 1, 120, 1800, 180, 2, 1566022420);
INSERT INTO `kog_kog_rebuy` VALUES (492, 3, 120, 2000, 200, 1, 1566023225);
INSERT INTO `kog_kog_rebuy` VALUES (493, 3, 120, 3000, 300, 4, 1566032196);
INSERT INTO `kog_kog_rebuy` VALUES (494, 4, 121, 2200, 220, 1, 1568987594);
INSERT INTO `kog_kog_rebuy` VALUES (495, 8, 122, 1000, 100, 4, 1569132356);
INSERT INTO `kog_kog_rebuy` VALUES (496, 2, 123, 2000, 200, 4, 1571314324);
INSERT INTO `kog_kog_rebuy` VALUES (497, 2, 123, 2500, 250, 4, 1571323883);
INSERT INTO `kog_kog_rebuy` VALUES (498, 1, 124, 1500, 150, 3, 1571554110);
INSERT INTO `kog_kog_rebuy` VALUES (499, 4, 124, 2500, 250, 2, 1571556560);
INSERT INTO `kog_kog_rebuy` VALUES (500, 3, 124, 3500, 350, 4, 1571558968);
INSERT INTO `kog_kog_rebuy` VALUES (501, 4, 125, 1300, 130, 2, 1572694470);
INSERT INTO `kog_kog_rebuy` VALUES (502, 2, 125, 2500, 250, 4, 1572695314);
INSERT INTO `kog_kog_rebuy` VALUES (503, 1, 125, 3500, 350, 4, 1572696193);
INSERT INTO `kog_kog_rebuy` VALUES (504, 3, 125, 4000, 400, 4, 1572699319);
INSERT INTO `kog_kog_rebuy` VALUES (505, 4, 125, 5000, 500, 2, 1572699855);
INSERT INTO `kog_kog_rebuy` VALUES (506, 1, 126, 1900, 190, 4, 1573044447);
INSERT INTO `kog_kog_rebuy` VALUES (507, 4, 126, 3000, 300, 2, 1573048724);
INSERT INTO `kog_kog_rebuy` VALUES (508, 4, 127, 1500, 150, 2, 1573285971);
COMMIT;

-- ----------------------------
-- Table structure for kog_kog_uermeta
-- ----------------------------
DROP TABLE IF EXISTS `kog_kog_uermeta`;
CREATE TABLE `kog_kog_uermeta` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL,
  `meta_key` varchar(256) NOT NULL,
  `meta_value` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for kog_kog_user
-- ----------------------------
DROP TABLE IF EXISTS `kog_kog_user`;
CREATE TABLE `kog_kog_user` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `nickname` varchar(50) NOT NULL,
  `headimg_url` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kog_kog_user
-- ----------------------------
BEGIN;
INSERT INTO `kog_kog_user` VALUES (1, NULL, '续', NULL, NULL);
INSERT INTO `kog_kog_user` VALUES (2, NULL, '仉', NULL, NULL);
INSERT INTO `kog_kog_user` VALUES (3, NULL, '翠', NULL, NULL);
INSERT INTO `kog_kog_user` VALUES (4, NULL, 'Holy', NULL, NULL);
INSERT INTO `kog_kog_user` VALUES (7, NULL, '二子', NULL, NULL);
INSERT INTO `kog_kog_user` VALUES (8, NULL, '田', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for kog_links
-- ----------------------------
DROP TABLE IF EXISTS `kog_links`;
CREATE TABLE `kog_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for kog_options
-- ----------------------------
DROP TABLE IF EXISTS `kog_options`;
CREATE TABLE `kog_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8381 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_options
-- ----------------------------
BEGIN;
INSERT INTO `kog_options` VALUES (1, 'siteurl', 'http://www.victorxys.com', 'yes');
INSERT INTO `kog_options` VALUES (2, 'home', 'http://www.victorxys.com', 'yes');
INSERT INTO `kog_options` VALUES (3, 'blogname', '萦璀斯汀', 'yes');
INSERT INTO `kog_options` VALUES (4, 'blogdescription', 'All for fun', 'yes');
INSERT INTO `kog_options` VALUES (5, 'users_can_register', '0', 'yes');
INSERT INTO `kog_options` VALUES (6, 'admin_email', 'victor.xys@gmail.com', 'yes');
INSERT INTO `kog_options` VALUES (7, 'start_of_week', '1', 'yes');
INSERT INTO `kog_options` VALUES (8, 'use_balanceTags', '0', 'yes');
INSERT INTO `kog_options` VALUES (9, 'use_smilies', '1', 'yes');
INSERT INTO `kog_options` VALUES (10, 'require_name_email', '1', 'yes');
INSERT INTO `kog_options` VALUES (11, 'comments_notify', '1', 'yes');
INSERT INTO `kog_options` VALUES (12, 'posts_per_rss', '10', 'yes');
INSERT INTO `kog_options` VALUES (13, 'rss_use_excerpt', '0', 'yes');
INSERT INTO `kog_options` VALUES (14, 'mailserver_url', 'mail.example.com', 'yes');
INSERT INTO `kog_options` VALUES (15, 'mailserver_login', 'login@example.com', 'yes');
INSERT INTO `kog_options` VALUES (16, 'mailserver_pass', 'password', 'yes');
INSERT INTO `kog_options` VALUES (17, 'mailserver_port', '110', 'yes');
INSERT INTO `kog_options` VALUES (18, 'default_category', '1', 'yes');
INSERT INTO `kog_options` VALUES (19, 'default_comment_status', 'open', 'yes');
INSERT INTO `kog_options` VALUES (20, 'default_ping_status', 'open', 'yes');
INSERT INTO `kog_options` VALUES (21, 'default_pingback_flag', '0', 'yes');
INSERT INTO `kog_options` VALUES (22, 'posts_per_page', '10', 'yes');
INSERT INTO `kog_options` VALUES (23, 'date_format', 'Y年n月j日', 'yes');
INSERT INTO `kog_options` VALUES (24, 'time_format', 'ag:i', 'yes');
INSERT INTO `kog_options` VALUES (25, 'links_updated_date_format', 'Y年n月j日ag:i', 'yes');
INSERT INTO `kog_options` VALUES (26, 'comment_moderation', '0', 'yes');
INSERT INTO `kog_options` VALUES (27, 'moderation_notify', '1', 'yes');
INSERT INTO `kog_options` VALUES (28, 'permalink_structure', '', 'yes');
INSERT INTO `kog_options` VALUES (29, 'rewrite_rules', '', 'yes');
INSERT INTO `kog_options` VALUES (30, 'hack_file', '0', 'yes');
INSERT INTO `kog_options` VALUES (31, 'blog_charset', 'UTF-8', 'yes');
INSERT INTO `kog_options` VALUES (32, 'moderation_keys', '', 'no');
INSERT INTO `kog_options` VALUES (33, 'active_plugins', 'a:1:{i:1;s:27:\"updraftplus/updraftplus.php\";}', 'yes');
INSERT INTO `kog_options` VALUES (34, 'category_base', '', 'yes');
INSERT INTO `kog_options` VALUES (35, 'ping_sites', 'http://rpc.pingomatic.com/', 'yes');
INSERT INTO `kog_options` VALUES (36, 'comment_max_links', '2', 'yes');
INSERT INTO `kog_options` VALUES (37, 'gmt_offset', '', 'yes');
INSERT INTO `kog_options` VALUES (38, 'default_email_category', '1', 'yes');
INSERT INTO `kog_options` VALUES (39, 'recently_edited', 'a:2:{i:0;s:58:\"/data/www/default/kog/wp-content/themes/Kratos-2/style.css\";i:2;s:0:\"\";}', 'no');
INSERT INTO `kog_options` VALUES (40, 'template', 'twentynineteen', 'yes');
INSERT INTO `kog_options` VALUES (41, 'stylesheet', 'twentynineteen', 'yes');
INSERT INTO `kog_options` VALUES (42, 'comment_whitelist', '1', 'yes');
INSERT INTO `kog_options` VALUES (43, 'blacklist_keys', '', 'no');
INSERT INTO `kog_options` VALUES (44, 'comment_registration', '0', 'yes');
INSERT INTO `kog_options` VALUES (45, 'html_type', 'text/html', 'yes');
INSERT INTO `kog_options` VALUES (46, 'use_trackback', '0', 'yes');
INSERT INTO `kog_options` VALUES (47, 'default_role', 'subscriber', 'yes');
INSERT INTO `kog_options` VALUES (48, 'db_version', '44719', 'yes');
INSERT INTO `kog_options` VALUES (49, 'uploads_use_yearmonth_folders', '1', 'yes');
INSERT INTO `kog_options` VALUES (50, 'upload_path', '', 'yes');
INSERT INTO `kog_options` VALUES (51, 'blog_public', '1', 'yes');
INSERT INTO `kog_options` VALUES (52, 'default_link_category', '2', 'yes');
INSERT INTO `kog_options` VALUES (53, 'show_on_front', 'posts', 'yes');
INSERT INTO `kog_options` VALUES (54, 'tag_base', '', 'yes');
INSERT INTO `kog_options` VALUES (55, 'show_avatars', '1', 'yes');
INSERT INTO `kog_options` VALUES (56, 'avatar_rating', 'G', 'yes');
INSERT INTO `kog_options` VALUES (57, 'upload_url_path', '', 'yes');
INSERT INTO `kog_options` VALUES (58, 'thumbnail_size_w', '150', 'yes');
INSERT INTO `kog_options` VALUES (59, 'thumbnail_size_h', '150', 'yes');
INSERT INTO `kog_options` VALUES (60, 'thumbnail_crop', '1', 'yes');
INSERT INTO `kog_options` VALUES (61, 'medium_size_w', '300', 'yes');
INSERT INTO `kog_options` VALUES (62, 'medium_size_h', '300', 'yes');
INSERT INTO `kog_options` VALUES (63, 'avatar_default', 'mystery', 'yes');
INSERT INTO `kog_options` VALUES (64, 'large_size_w', '1024', 'yes');
INSERT INTO `kog_options` VALUES (65, 'large_size_h', '1024', 'yes');
INSERT INTO `kog_options` VALUES (66, 'image_default_link_type', 'none', 'yes');
INSERT INTO `kog_options` VALUES (67, 'image_default_size', '', 'yes');
INSERT INTO `kog_options` VALUES (68, 'image_default_align', '', 'yes');
INSERT INTO `kog_options` VALUES (69, 'close_comments_for_old_posts', '0', 'yes');
INSERT INTO `kog_options` VALUES (70, 'close_comments_days_old', '14', 'yes');
INSERT INTO `kog_options` VALUES (71, 'thread_comments', '1', 'yes');
INSERT INTO `kog_options` VALUES (72, 'thread_comments_depth', '5', 'yes');
INSERT INTO `kog_options` VALUES (73, 'page_comments', '0', 'yes');
INSERT INTO `kog_options` VALUES (74, 'comments_per_page', '50', 'yes');
INSERT INTO `kog_options` VALUES (75, 'default_comments_page', 'newest', 'yes');
INSERT INTO `kog_options` VALUES (76, 'comment_order', 'asc', 'yes');
INSERT INTO `kog_options` VALUES (77, 'sticky_posts', 'a:0:{}', 'yes');
INSERT INTO `kog_options` VALUES (78, 'widget_categories', 'a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (79, 'widget_text', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (80, 'widget_rss', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (81, 'uninstall_plugins', 'a:2:{s:49:\"restricted-site-access/restricted_site_access.php\";s:32:\"restricted_site_access_uninstall\";s:52:\"restricted-site-access-BK/restricted_site_access.php\";s:32:\"restricted_site_access_uninstall\";}', 'no');
INSERT INTO `kog_options` VALUES (82, 'timezone_string', 'Asia/Shanghai', 'yes');
INSERT INTO `kog_options` VALUES (83, 'page_for_posts', '0', 'yes');
INSERT INTO `kog_options` VALUES (84, 'page_on_front', '0', 'yes');
INSERT INTO `kog_options` VALUES (85, 'default_post_format', '0', 'yes');
INSERT INTO `kog_options` VALUES (86, 'link_manager_enabled', '0', 'yes');
INSERT INTO `kog_options` VALUES (87, 'finished_splitting_shared_terms', '1', 'yes');
INSERT INTO `kog_options` VALUES (88, 'site_icon', '0', 'yes');
INSERT INTO `kog_options` VALUES (89, 'medium_large_size_w', '768', 'yes');
INSERT INTO `kog_options` VALUES (90, 'medium_large_size_h', '0', 'yes');
INSERT INTO `kog_options` VALUES (91, 'wp_page_for_privacy_policy', '3', 'yes');
INSERT INTO `kog_options` VALUES (92, 'show_comments_cookies_opt_in', '0', 'yes');
INSERT INTO `kog_options` VALUES (93, 'initial_db_version', '38590', 'yes');
INSERT INTO `kog_options` VALUES (94, 'kog_user_roles', 'a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:61:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}', 'yes');
INSERT INTO `kog_options` VALUES (95, 'fresh_site', '0', 'yes');
INSERT INTO `kog_options` VALUES (96, 'WPLANG', 'zh_CN', 'yes');
INSERT INTO `kog_options` VALUES (97, 'widget_search', 'a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (98, 'widget_recent-posts', 'a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (99, 'widget_recent-comments', 'a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (100, 'widget_archives', 'a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (101, 'widget_meta', 'a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (102, 'sidebars_widgets', 'a:3:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:13:\"array_version\";i:3;}', 'yes');
INSERT INTO `kog_options` VALUES (103, 'widget_pages', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (104, 'widget_calendar', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (105, 'widget_media_audio', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (106, 'widget_media_image', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (107, 'widget_media_gallery', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (108, 'widget_media_video', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (109, 'widget_tag_cloud', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (110, 'widget_nav_menu', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (111, 'widget_custom_html', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (112, 'cron', 'a:6:{i:1573543303;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1573550503;a:3:{s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1573550514;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1573573154;a:1:{s:35:\"puc_cron_check_updates_theme-Kratos\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1573618015;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}s:7:\"version\";i:2;}', 'yes');
INSERT INTO `kog_options` VALUES (113, 'theme_mods_twentyseventeen', 'a:3:{s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1555138751;s:4:\"data\";a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}}}s:18:\"nav_menu_locations\";a:0:{}}', 'yes');
INSERT INTO `kog_options` VALUES (190, 'auto_core_update_notified', 'a:4:{s:4:\"type\";s:7:\"success\";s:5:\"email\";s:20:\"victor.xys@gmail.com\";s:7:\"version\";s:5:\"5.1.3\";s:9:\"timestamp\";i:1571131798;}', 'no');
INSERT INTO `kog_options` VALUES (405, 'recently_activated', 'a:0:{}', 'yes');
INSERT INTO `kog_options` VALUES (413, 'new_admin_email', 'victor.xys@gmail.com', 'yes');
INSERT INTO `kog_options` VALUES (436, 'widget_akismet_widget', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (552, 'updraftplus_version', '1.16.10', 'yes');
INSERT INTO `kog_options` VALUES (553, 'updraft_updraftvault', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-037c940ed8db27aa681e29548e46cba1\";a:3:{s:5:\"token\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"quota\";i:-1;}}}', 'yes');
INSERT INTO `kog_options` VALUES (554, 'updraft_dropbox', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-eea103ffcb9370033f377c490235769d\";a:4:{s:6:\"appkey\";s:0:\"\";s:6:\"secret\";s:0:\"\";s:6:\"folder\";s:0:\"\";s:15:\"tk_access_token\";s:0:\"\";}}}', 'yes');
INSERT INTO `kog_options` VALUES (555, 'updraft_s3', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-f02522d31e2258dd957d840f6de636bd\";a:5:{s:9:\"accesskey\";s:0:\"\";s:9:\"secretkey\";s:0:\"\";s:4:\"path\";s:0:\"\";s:3:\"rrs\";s:0:\"\";s:22:\"server_side_encryption\";s:0:\"\";}}}', 'yes');
INSERT INTO `kog_options` VALUES (556, 'updraft_cloudfiles', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-6aeffc00b660b19043b0240e80b298db\";a:5:{s:4:\"user\";s:0:\"\";s:7:\"authurl\";s:35:\"https://auth.api.rackspacecloud.com\";s:6:\"apikey\";s:0:\"\";s:4:\"path\";s:0:\"\";s:6:\"region\";N;}}}', 'yes');
INSERT INTO `kog_options` VALUES (557, 'updraft_googledrive', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-2de195d9dccac1705883ba97b5ed0ee4\";a:3:{s:8:\"clientid\";s:0:\"\";s:6:\"secret\";s:0:\"\";s:5:\"token\";s:0:\"\";}}}', 'yes');
INSERT INTO `kog_options` VALUES (558, 'updraft_onedrive', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-e26325e63f49037ceda7bf6d1a3a9266\";a:0:{}}}', 'yes');
INSERT INTO `kog_options` VALUES (559, 'updraft_ftp', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-89225d1ae2849153e7c746723f328af3\";a:5:{s:4:\"host\";s:0:\"\";s:4:\"user\";s:0:\"\";s:4:\"pass\";s:0:\"\";s:4:\"path\";s:0:\"\";s:7:\"passive\";i:1;}}}', 'yes');
INSERT INTO `kog_options` VALUES (560, 'updraft_azure', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-9287ff86bded358eafd82025216f4382\";a:0:{}}}', 'yes');
INSERT INTO `kog_options` VALUES (561, 'updraft_sftp', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-388a223d57810b397eeb874f926969d8\";a:0:{}}}', 'yes');
INSERT INTO `kog_options` VALUES (562, 'updraft_googlecloud', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-a9c45ce2e5ab4a0d22d4b691959f52f6\";a:0:{}}}', 'yes');
INSERT INTO `kog_options` VALUES (563, 'updraft_backblaze', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-2956507b2a2a50dd63db09c56023f774\";a:0:{}}}', 'yes');
INSERT INTO `kog_options` VALUES (564, 'updraft_webdav', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-77b5d4426e86a14cd383207529c6baad\";a:0:{}}}', 'yes');
INSERT INTO `kog_options` VALUES (565, 'updraft_s3generic', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-a8354ae8bc626003e6de69fa1ff28cea\";a:4:{s:9:\"accesskey\";s:0:\"\";s:9:\"secretkey\";s:0:\"\";s:4:\"path\";s:0:\"\";s:8:\"endpoint\";s:0:\"\";}}}', 'yes');
INSERT INTO `kog_options` VALUES (566, 'updraft_openstack', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-467885c9007c07380eedca90fc55aa58\";a:6:{s:4:\"user\";s:0:\"\";s:7:\"authurl\";s:0:\"\";s:8:\"password\";s:0:\"\";s:6:\"tenant\";s:0:\"\";s:4:\"path\";s:0:\"\";s:6:\"region\";s:0:\"\";}}}', 'yes');
INSERT INTO `kog_options` VALUES (567, 'updraft_dreamobjects', 'a:2:{s:7:\"version\";i:1;s:8:\"settings\";a:1:{s:34:\"s-c004dfb6d345ebac59f02b752fe53c75\";a:3:{s:9:\"accesskey\";s:0:\"\";s:9:\"secretkey\";s:0:\"\";s:4:\"path\";s:0:\"\";}}}', 'yes');
INSERT INTO `kog_options` VALUES (568, 'updraftplus-addons_siteid', '73d6588251fcebe222d5f73e363a0ea6', 'no');
INSERT INTO `kog_options` VALUES (569, 'updraft_lastmessage', '备份成功完成 (4月 05 11:36:41)', 'yes');
INSERT INTO `kog_options` VALUES (570, 'updraftplus_unlocked_fd', '1', 'no');
INSERT INTO `kog_options` VALUES (571, 'updraftplus_last_lock_time_fd', '2019-04-05 03:36:26', 'no');
INSERT INTO `kog_options` VALUES (572, 'updraftplus_semaphore_fd', '0', 'no');
INSERT INTO `kog_options` VALUES (573, 'updraft_last_scheduled_fd', '1554435386', 'yes');
INSERT INTO `kog_options` VALUES (575, 'updraft_backup_history', 'a:1:{i:1554435386;a:19:{s:7:\"plugins\";a:1:{i:0;s:59:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-plugins.zip\";}s:12:\"plugins-size\";i:13865070;s:6:\"themes\";a:1:{i:0;s:58:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-themes.zip\";}s:11:\"themes-size\";i:2331192;s:7:\"uploads\";a:1:{i:0;s:59:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-uploads.zip\";}s:12:\"uploads-size\";i:766;s:6:\"others\";a:1:{i:0;s:58:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-others.zip\";}s:11:\"others-size\";i:799548;s:2:\"db\";s:53:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-db.gz\";s:7:\"db-size\";i:189513;s:9:\"checksums\";a:2:{s:4:\"sha1\";a:5:{s:8:\"plugins0\";s:40:\"f70ec74c1ff5dac0f6ab446b5105e28b63f520b4\";s:7:\"themes0\";s:40:\"1e956bb412c49f7af9373a83fcafa1f7c629bada\";s:8:\"uploads0\";s:40:\"3cd2df15bec8747bb7a98c8f13c8733ee769b312\";s:7:\"others0\";s:40:\"5c1807c2dcf91f7b7e68fb4b903ecb4ea5a3d862\";s:3:\"db0\";s:40:\"14c0983af7ab9a0b39f72da574120d191eae3e43\";}s:6:\"sha256\";a:5:{s:8:\"plugins0\";s:64:\"6c1081cbe3f9fd8c8624d9ad54675a05ff202519e0853e8ce7ce238e9fffac85\";s:7:\"themes0\";s:64:\"d8afb8f6be238b32e004a55ab567c3048a6f0b873e648e13b30a0d68b897bfa7\";s:8:\"uploads0\";s:64:\"6214edfd681ff2fcd57d1c6bef36b5ef399cde3db4fd37206ad5f0f7248c44c5\";s:7:\"others0\";s:64:\"18ad352291c8c7e7148f8f84fc5c9de2aa5bb223fe7ad75edc14e227177fec6a\";s:3:\"db0\";s:64:\"764dde33b1c3f470982f83ef58c569ff0a41c02a250c43fecd9667ca18840e3c\";}}s:5:\"nonce\";s:12:\"abe6d6cbc16b\";s:7:\"service\";a:1:{i:0;s:4:\"none\";}s:20:\"service_instance_ids\";a:0:{}s:11:\"always_keep\";b:0;s:19:\"files_enumerated_at\";a:4:{s:7:\"plugins\";i:1554435387;s:6:\"themes\";i:1554435397;s:7:\"uploads\";i:1554435397;s:6:\"others\";i:1554435397;}s:18:\"created_by_version\";s:7:\"1.16.10\";s:21:\"last_saved_by_version\";s:7:\"1.16.10\";s:12:\"is_multisite\";b:0;}}', 'no');
INSERT INTO `kog_options` VALUES (576, 'updraft_last_backup', 'a:5:{s:11:\"backup_time\";i:1554435386;s:12:\"backup_array\";a:11:{s:7:\"plugins\";a:1:{i:0;s:59:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-plugins.zip\";}s:12:\"plugins-size\";i:13865070;s:6:\"themes\";a:1:{i:0;s:58:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-themes.zip\";}s:11:\"themes-size\";i:2331192;s:7:\"uploads\";a:1:{i:0;s:59:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-uploads.zip\";}s:12:\"uploads-size\";i:766;s:6:\"others\";a:1:{i:0;s:58:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-others.zip\";}s:11:\"others-size\";i:799548;s:2:\"db\";s:53:\"backup_2019-04-05-1136_All_For_Fun_abe6d6cbc16b-db.gz\";s:7:\"db-size\";i:189513;s:9:\"checksums\";a:2:{s:4:\"sha1\";a:5:{s:8:\"plugins0\";s:40:\"f70ec74c1ff5dac0f6ab446b5105e28b63f520b4\";s:7:\"themes0\";s:40:\"1e956bb412c49f7af9373a83fcafa1f7c629bada\";s:8:\"uploads0\";s:40:\"3cd2df15bec8747bb7a98c8f13c8733ee769b312\";s:7:\"others0\";s:40:\"5c1807c2dcf91f7b7e68fb4b903ecb4ea5a3d862\";s:3:\"db0\";s:40:\"14c0983af7ab9a0b39f72da574120d191eae3e43\";}s:6:\"sha256\";a:5:{s:8:\"plugins0\";s:64:\"6c1081cbe3f9fd8c8624d9ad54675a05ff202519e0853e8ce7ce238e9fffac85\";s:7:\"themes0\";s:64:\"d8afb8f6be238b32e004a55ab567c3048a6f0b873e648e13b30a0d68b897bfa7\";s:8:\"uploads0\";s:64:\"6214edfd681ff2fcd57d1c6bef36b5ef399cde3db4fd37206ad5f0f7248c44c5\";s:7:\"others0\";s:64:\"18ad352291c8c7e7148f8f84fc5c9de2aa5bb223fe7ad75edc14e227177fec6a\";s:3:\"db0\";s:64:\"764dde33b1c3f470982f83ef58c569ff0a41c02a250c43fecd9667ca18840e3c\";}}}s:7:\"success\";i:1;s:6:\"errors\";a:0:{}s:12:\"backup_nonce\";s:12:\"abe6d6cbc16b\";}', 'yes');
INSERT INTO `kog_options` VALUES (577, 'updraftplus_tour_cancelled_on', 'settings_timing', 'yes');
INSERT INTO `kog_options` VALUES (578, 'updraftplus_dismissedautobackup', '1561693046', 'yes');
INSERT INTO `kog_options` VALUES (581, 'widget_links', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (582, 'widget_kratos_ad', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (583, 'widget_kratos_about', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (584, 'widget_kratos_tags', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (585, 'widget_kratos_search', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (586, 'widget_kratos_posts', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes');
INSERT INTO `kog_options` VALUES (587, 'puc_external_updates_theme-Kratos', 'O:8:\"stdClass\":5:{s:9:\"lastCheck\";i:1554714736;s:14:\"checkedVersion\";s:5:\"2.8.0\";s:6:\"update\";O:8:\"stdClass\":5:{s:4:\"slug\";s:6:\"Kratos\";s:7:\"version\";s:5:\"2.8.0\";s:12:\"download_url\";s:57:\"https://api.github.com/repos/xb2016/Kratos/zipball/v2.8.0\";s:12:\"translations\";a:0:{}s:11:\"details_url\";s:32:\"https://github.com/Vtrois/Kratos\";}s:11:\"updateClass\";s:21:\"Puc_v4p5_Theme_Update\";s:15:\"updateBaseClass\";s:12:\"Theme_Update\";}', 'no');
INSERT INTO `kog_options` VALUES (590, 'theme_mods_Kratos-2', 'a:2:{s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1554714778;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:0:{}s:12:\"sidebar_tool\";a:0:{}s:9:\"sidebar-1\";a:4:{i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";}}}}', 'yes');
INSERT INTO `kog_options` VALUES (591, 'current_theme', 'Twenty Nineteen', 'yes');
INSERT INTO `kog_options` VALUES (592, 'theme_switched', '', 'yes');
INSERT INTO `kog_options` VALUES (593, 'theme_switched_via_customizer', '', 'yes');
INSERT INTO `kog_options` VALUES (594, 'customize_stashed_theme_mods', 'a:0:{}', 'no');
INSERT INTO `kog_options` VALUES (600, 'category_children', 'a:0:{}', 'yes');
INSERT INTO `kog_options` VALUES (608, 'db_upgraded', '', 'yes');
INSERT INTO `kog_options` VALUES (610, 'can_compress_scripts', '0', 'no');
INSERT INTO `kog_options` VALUES (623, 'kratos_banners', '', 'yes');
INSERT INTO `kog_options` VALUES (624, 'kratos_2', 'a:86:{s:9:\"site_logo\";s:0:\"\";s:22:\"background_index_color\";s:7:\"#f5f5f5\";s:11:\"list_layout\";s:10:\"new_layout\";s:13:\"use_gutenberg\";s:1:\"1\";s:8:\"mail_reg\";b:0;s:7:\"site_sa\";b:0;s:13:\"show_head_cat\";b:0;s:13:\"show_head_tag\";b:0;s:7:\"site_bw\";b:0;s:12:\"donate_links\";s:0:\"\";s:9:\"post_trim\";s:3:\"110\";s:13:\"default_image\";s:72:\"http://34.80.179.83/wp-content/uploads/2019/04/Pexels-Videos-1110140.mp4\";s:9:\"page_html\";b:0;s:8:\"wy_music\";b:0;s:5:\"cd_gb\";b:0;s:15:\"guestbook_links\";s:0:\"\";s:9:\"cd_weixin\";b:0;s:12:\"weixin_image\";s:64:\"http://34.80.179.83/wp-content/themes/Kratos-2/images/weixin.png\";s:13:\"site_keywords\";s:0:\"\";s:16:\"site_description\";s:0:\"\";s:11:\"site_tongji\";s:0:\"\";s:15:\"background_mode\";s:5:\"image\";s:16:\"background_image\";s:78:\"http://34.80.179.83/wp-content/uploads/2019/04/IMG_20180623_182105-EFFECTS.jpg\";s:22:\"background_image_text1\";s:0:\"\";s:22:\"background_image_text2\";s:0:\"\";s:16:\"background_color\";s:7:\"#222831\";s:8:\"side_bar\";s:10:\"right_side\";s:7:\"post_cc\";b:0;s:10:\"post_share\";b:0;s:16:\"post_like_donate\";b:0;s:13:\"page_side_bar\";s:10:\"right_side\";s:7:\"page_cc\";b:0;s:10:\"page_share\";b:0;s:16:\"page_like_donate\";b:0;s:13:\"krsort_hm_img\";s:68:\"http://34.80.179.83/wp-content/themes/Kratos-2/images/background.jpg\";s:13:\"krsort_hm_tx1\";s:6:\"Kratos\";s:13:\"krsort_hm_tx2\";s:33:\"A responsible theme for WordPress\";s:13:\"krsort_hm_tx3\";s:12:\"标题内容\";s:13:\"krsort_hm_tx4\";s:36:\"这里是关于版块的简介内容\";s:13:\"krsort_hm_bk1\";s:2:\"15\";s:13:\"krsort_hm_tx5\";s:0:\"\";s:13:\"krsort_hm_tx6\";s:12:\"标题内容\";s:13:\"krsort_hm_tx7\";s:36:\"这里是关于版块的简介内容\";s:13:\"krsort_hm_bk2\";s:2:\"15\";s:13:\"krsort_hm_tx8\";s:0:\"\";s:13:\"krsort_hm_tx9\";s:12:\"标题内容\";s:14:\"krsort_hm_tx10\";s:36:\"这里是关于版块的简介内容\";s:13:\"krsort_hm_bk3\";s:2:\"15\";s:14:\"krsort_hm_tx11\";s:0:\"\";s:11:\"error_text1\";s:45:\"这里已经是废墟，什么东西都没有\";s:11:\"error_text2\";s:26:\"That page can not be found\";s:11:\"error_image\";s:61:\"http://34.80.179.83/wp-content/themes/Kratos-2/images/404.jpg\";s:13:\"kratos_banner\";s:1:\"0\";s:14:\"kratos_banner1\";s:0:\"\";s:18:\"kratos_banner_url1\";s:0:\"\";s:14:\"kratos_banner2\";s:0:\"\";s:18:\"kratos_banner_url2\";s:0:\"\";s:14:\"kratos_banner3\";s:0:\"\";s:18:\"kratos_banner_url3\";s:0:\"\";s:14:\"kratos_banner4\";s:0:\"\";s:18:\"kratos_banner_url4\";s:0:\"\";s:14:\"kratos_banner5\";s:0:\"\";s:18:\"kratos_banner_url5\";s:0:\"\";s:10:\"mail_smtps\";b:0;s:9:\"mail_name\";s:6:\"Kratos\";s:9:\"mail_host\";s:15:\"smtp.vtrois.com\";s:9:\"mail_port\";s:3:\"994\";s:13:\"mail_username\";s:19:\"no_reply@vtrois.com\";s:11:\"mail_passwd\";s:9:\"123456789\";s:13:\"mail_smtpauth\";s:1:\"1\";s:15:\"mail_smtpsecure\";s:3:\"ssl\";s:7:\"icp_num\";s:0:\"\";s:7:\"gov_num\";s:0:\"\";s:8:\"gov_link\";s:0:\"\";s:12:\"social_weibo\";s:0:\"\";s:13:\"social_tweibo\";s:0:\"\";s:14:\"social_twitter\";s:0:\"\";s:15:\"social_facebook\";s:0:\"\";s:15:\"social_linkedin\";s:0:\"\";s:13:\"social_github\";s:0:\"\";s:9:\"ad_show_1\";b:0;s:8:\"ad_img_1\";s:60:\"http://34.80.179.83/wp-content/themes/Kratos-2/images/ad.png\";s:9:\"ad_link_1\";s:0:\"\";s:9:\"ad_show_2\";b:0;s:8:\"ad_img_2\";s:60:\"http://34.80.179.83/wp-content/themes/Kratos-2/images/ad.png\";s:9:\"ad_link_2\";s:0:\"\";}', 'yes');
INSERT INTO `kog_options` VALUES (760, 'theme_mods_llorix-one-lite', 'a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1555138707;s:4:\"data\";a:7:{s:19:\"wp_inactive_widgets\";a:6:{i:0;s:6:\"meta-2\";i:1;s:8:\"search-2\";i:2;s:14:\"recent-posts-2\";i:3;s:17:\"recent-comments-2\";i:4;s:10:\"archives-2\";i:5;s:12:\"categories-2\";}s:9:\"sidebar-1\";a:0:{}s:11:\"footer-area\";a:0:{}s:13:\"footer-area-2\";a:0:{}s:13:\"footer-area-3\";a:0:{}s:13:\"footer-area-4\";a:0:{}s:31:\"llorix-one-sidebar-shop-archive\";a:0:{}}}s:18:\"custom_css_post_id\";i:-1;}', 'yes');
INSERT INTO `kog_options` VALUES (762, 'theme_mods_twentynineteen', 'a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1554714859;s:4:\"data\";a:2:{s:19:\"wp_inactive_widgets\";a:6:{i:0;s:6:\"meta-2\";i:1;s:8:\"search-2\";i:2;s:14:\"recent-posts-2\";i:3;s:17:\"recent-comments-2\";i:4;s:10:\"archives-2\";i:5;s:12:\"categories-2\";}s:9:\"sidebar-1\";a:0:{}}}}', 'yes');
INSERT INTO `kog_options` VALUES (924, 'theme_mods_twentyfifteen', 'a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1555138794;s:4:\"data\";a:2:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}}}}', 'yes');
INSERT INTO `kog_options` VALUES (926, 'theme_mods_twentysixteen', 'a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:18:\"custom_css_post_id\";i:-1;s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1555138857;s:4:\"data\";a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}}}}', 'yes');
INSERT INTO `kog_options` VALUES (7293, '_site_transient_update_core', 'O:8:\"stdClass\":4:{s:7:\"updates\";a:3:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:7:\"upgrade\";s:8:\"download\";s:65:\"https://downloads.wordpress.org/release/zh_CN/wordpress-5.2.4.zip\";s:6:\"locale\";s:5:\"zh_CN\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:65:\"https://downloads.wordpress.org/release/zh_CN/wordpress-5.2.4.zip\";s:10:\"no_content\";b:0;s:11:\"new_bundled\";b:0;s:7:\"partial\";b:0;s:8:\"rollback\";b:0;}s:7:\"current\";s:5:\"5.2.4\";s:7:\"version\";s:5:\"5.2.4\";s:11:\"php_version\";s:6:\"5.6.20\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"5.0\";s:15:\"partial_version\";s:0:\"\";}i:1;O:8:\"stdClass\":10:{s:8:\"response\";s:7:\"upgrade\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-5.2.4.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-5.2.4.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-5.2.4-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-5.2.4-new-bundled.zip\";s:7:\"partial\";b:0;s:8:\"rollback\";b:0;}s:7:\"current\";s:5:\"5.2.4\";s:7:\"version\";s:5:\"5.2.4\";s:11:\"php_version\";s:6:\"5.6.20\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"5.0\";s:15:\"partial_version\";s:0:\"\";}i:2;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-5.2.4.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-5.2.4.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-5.2.4-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-5.2.4-new-bundled.zip\";s:7:\"partial\";b:0;s:8:\"rollback\";b:0;}s:7:\"current\";s:5:\"5.2.4\";s:7:\"version\";s:5:\"5.2.4\";s:11:\"php_version\";s:6:\"5.6.20\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"5.0\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}}s:12:\"last_checked\";i:1573507354;s:15:\"version_checked\";s:5:\"5.1.3\";s:12:\"translations\";a:0:{}}', 'no');
INSERT INTO `kog_options` VALUES (8219, '_site_transient_timeout_browser_d62a1a3bb908ffa3361920bc8dc2be5a', '1573806661', 'no');
INSERT INTO `kog_options` VALUES (8220, '_site_transient_browser_d62a1a3bb908ffa3361920bc8dc2be5a', 'a:10:{s:4:\"name\";s:7:\"Firefox\";s:7:\"version\";s:4:\"62.0\";s:8:\"platform\";s:5:\"Linux\";s:10:\"update_url\";s:24:\"https://www.firefox.com/\";s:7:\"img_src\";s:44:\"http://s.w.org/images/browsers/firefox.png?1\";s:11:\"img_src_ssl\";s:45:\"https://s.w.org/images/browsers/firefox.png?1\";s:15:\"current_version\";s:2:\"56\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;s:6:\"mobile\";b:0;}', 'no');
INSERT INTO `kog_options` VALUES (8221, '_site_transient_timeout_php_check_2ac01933d3fb8486315bb6ee1941062f', '1573806662', 'no');
INSERT INTO `kog_options` VALUES (8222, '_site_transient_php_check_2ac01933d3fb8486315bb6ee1941062f', 'a:5:{s:19:\"recommended_version\";s:3:\"7.3\";s:15:\"minimum_version\";s:6:\"5.6.20\";s:12:\"is_supported\";b:1;s:9:\"is_secure\";b:1;s:13:\"is_acceptable\";b:1;}', 'no');
INSERT INTO `kog_options` VALUES (8347, '_site_transient_timeout_browser_227febf5dcde37ea43a5af5170c388cf', '1574074436', 'no');
INSERT INTO `kog_options` VALUES (8348, '_site_transient_browser_227febf5dcde37ea43a5af5170c388cf', 'a:10:{s:4:\"name\";s:6:\"Chrome\";s:7:\"version\";s:11:\"41.0.2228.0\";s:8:\"platform\";s:7:\"Windows\";s:10:\"update_url\";s:29:\"https://www.google.com/chrome\";s:7:\"img_src\";s:43:\"http://s.w.org/images/browsers/chrome.png?1\";s:11:\"img_src_ssl\";s:44:\"https://s.w.org/images/browsers/chrome.png?1\";s:15:\"current_version\";s:2:\"18\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;s:6:\"mobile\";b:0;}', 'no');
INSERT INTO `kog_options` VALUES (8349, '_site_transient_timeout_browser_91532f0a84878d909e2deed33e9932cf', '1574074448', 'no');
INSERT INTO `kog_options` VALUES (8350, '_site_transient_browser_91532f0a84878d909e2deed33e9932cf', 'a:10:{s:4:\"name\";s:6:\"Chrome\";s:7:\"version\";s:11:\"37.0.2049.0\";s:8:\"platform\";s:7:\"Windows\";s:10:\"update_url\";s:29:\"https://www.google.com/chrome\";s:7:\"img_src\";s:43:\"http://s.w.org/images/browsers/chrome.png?1\";s:11:\"img_src_ssl\";s:44:\"https://s.w.org/images/browsers/chrome.png?1\";s:15:\"current_version\";s:2:\"18\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;s:6:\"mobile\";b:0;}', 'no');
INSERT INTO `kog_options` VALUES (8363, '_site_transient_timeout_theme_roots', '1573509152', 'no');
INSERT INTO `kog_options` VALUES (8364, '_site_transient_theme_roots', 'a:6:{s:8:\"Kratos-2\";s:7:\"/themes\";s:15:\"llorix-one-lite\";s:7:\"/themes\";s:13:\"twentyfifteen\";s:7:\"/themes\";s:14:\"twentynineteen\";s:7:\"/themes\";s:15:\"twentyseventeen\";s:7:\"/themes\";s:13:\"twentysixteen\";s:7:\"/themes\";}', 'no');
INSERT INTO `kog_options` VALUES (8366, '_site_transient_update_themes', 'O:8:\"stdClass\":4:{s:12:\"last_checked\";i:1573507356;s:7:\"checked\";a:6:{s:8:\"Kratos-2\";s:5:\"2.8.0\";s:15:\"llorix-one-lite\";s:6:\"0.2.17\";s:13:\"twentyfifteen\";s:3:\"2.4\";s:14:\"twentynineteen\";s:3:\"1.3\";s:15:\"twentyseventeen\";s:3:\"2.1\";s:13:\"twentysixteen\";s:3:\"1.9\";}s:8:\"response\";a:4:{s:13:\"twentyfifteen\";a:6:{s:5:\"theme\";s:13:\"twentyfifteen\";s:11:\"new_version\";s:3:\"2.5\";s:3:\"url\";s:43:\"https://wordpress.org/themes/twentyfifteen/\";s:7:\"package\";s:59:\"https://downloads.wordpress.org/theme/twentyfifteen.2.5.zip\";s:8:\"requires\";s:3:\"4.1\";s:12:\"requires_php\";s:5:\"5.2.4\";}s:14:\"twentynineteen\";a:6:{s:5:\"theme\";s:14:\"twentynineteen\";s:11:\"new_version\";s:3:\"1.4\";s:3:\"url\";s:44:\"https://wordpress.org/themes/twentynineteen/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/theme/twentynineteen.1.4.zip\";s:8:\"requires\";s:5:\"4.9.6\";s:12:\"requires_php\";s:5:\"5.2.4\";}s:15:\"twentyseventeen\";a:6:{s:5:\"theme\";s:15:\"twentyseventeen\";s:11:\"new_version\";s:3:\"2.2\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentyseventeen/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentyseventeen.2.2.zip\";s:8:\"requires\";s:3:\"4.7\";s:12:\"requires_php\";s:5:\"5.2.4\";}s:13:\"twentysixteen\";a:6:{s:5:\"theme\";s:13:\"twentysixteen\";s:11:\"new_version\";s:3:\"2.0\";s:3:\"url\";s:43:\"https://wordpress.org/themes/twentysixteen/\";s:7:\"package\";s:59:\"https://downloads.wordpress.org/theme/twentysixteen.2.0.zip\";s:8:\"requires\";s:3:\"4.4\";s:12:\"requires_php\";s:5:\"5.2.4\";}}s:12:\"translations\";a:0:{}}', 'no');
INSERT INTO `kog_options` VALUES (8367, '_site_transient_update_plugins', 'O:8:\"stdClass\":4:{s:12:\"last_checked\";i:1573507356;s:8:\"response\";a:3:{s:19:\"akismet/akismet.php\";O:8:\"stdClass\":12:{s:2:\"id\";s:21:\"w.org/plugins/akismet\";s:4:\"slug\";s:7:\"akismet\";s:6:\"plugin\";s:19:\"akismet/akismet.php\";s:11:\"new_version\";s:5:\"4.1.3\";s:3:\"url\";s:38:\"https://wordpress.org/plugins/akismet/\";s:7:\"package\";s:56:\"https://downloads.wordpress.org/plugin/akismet.4.1.3.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:59:\"https://ps.w.org/akismet/assets/icon-256x256.png?rev=969272\";s:2:\"1x\";s:59:\"https://ps.w.org/akismet/assets/icon-128x128.png?rev=969272\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:61:\"https://ps.w.org/akismet/assets/banner-772x250.jpg?rev=479904\";}s:11:\"banners_rtl\";a:0:{}s:6:\"tested\";s:5:\"5.2.4\";s:12:\"requires_php\";b:0;s:13:\"compatibility\";O:8:\"stdClass\":0:{}}s:19:\"jetpack/jetpack.php\";O:8:\"stdClass\":12:{s:2:\"id\";s:21:\"w.org/plugins/jetpack\";s:4:\"slug\";s:7:\"jetpack\";s:6:\"plugin\";s:19:\"jetpack/jetpack.php\";s:11:\"new_version\";s:3:\"7.9\";s:3:\"url\";s:38:\"https://wordpress.org/plugins/jetpack/\";s:7:\"package\";s:54:\"https://downloads.wordpress.org/plugin/jetpack.7.9.zip\";s:5:\"icons\";a:3:{s:2:\"2x\";s:60:\"https://ps.w.org/jetpack/assets/icon-256x256.png?rev=1791404\";s:2:\"1x\";s:52:\"https://ps.w.org/jetpack/assets/icon.svg?rev=1791404\";s:3:\"svg\";s:52:\"https://ps.w.org/jetpack/assets/icon.svg?rev=1791404\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:63:\"https://ps.w.org/jetpack/assets/banner-1544x500.png?rev=1791404\";s:2:\"1x\";s:62:\"https://ps.w.org/jetpack/assets/banner-772x250.png?rev=1791404\";}s:11:\"banners_rtl\";a:0:{}s:6:\"tested\";s:3:\"5.3\";s:12:\"requires_php\";s:3:\"5.6\";s:13:\"compatibility\";O:8:\"stdClass\":0:{}}s:27:\"updraftplus/updraftplus.php\";O:8:\"stdClass\":12:{s:2:\"id\";s:25:\"w.org/plugins/updraftplus\";s:4:\"slug\";s:11:\"updraftplus\";s:6:\"plugin\";s:27:\"updraftplus/updraftplus.php\";s:11:\"new_version\";s:7:\"1.16.20\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/updraftplus/\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/plugin/updraftplus.1.16.20.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/updraftplus/assets/icon-256x256.jpg?rev=1686200\";s:2:\"1x\";s:64:\"https://ps.w.org/updraftplus/assets/icon-128x128.jpg?rev=1686200\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/updraftplus/assets/banner-1544x500.png?rev=1686200\";s:2:\"1x\";s:66:\"https://ps.w.org/updraftplus/assets/banner-772x250.png?rev=1686200\";}s:11:\"banners_rtl\";a:0:{}s:6:\"tested\";s:3:\"5.3\";s:12:\"requires_php\";b:0;s:13:\"compatibility\";O:8:\"stdClass\":0:{}}}s:12:\"translations\";a:0:{}s:9:\"no_update\";a:2:{s:21:\"hello-dolly/hello.php\";O:8:\"stdClass\":9:{s:2:\"id\";s:25:\"w.org/plugins/hello-dolly\";s:4:\"slug\";s:11:\"hello-dolly\";s:6:\"plugin\";s:21:\"hello-dolly/hello.php\";s:11:\"new_version\";s:5:\"1.7.2\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/hello-dolly/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/hello-dolly.1.7.2.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-256x256.jpg?rev=2052855\";s:2:\"1x\";s:64:\"https://ps.w.org/hello-dolly/assets/icon-128x128.jpg?rev=2052855\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:66:\"https://ps.w.org/hello-dolly/assets/banner-772x250.jpg?rev=2052855\";}s:11:\"banners_rtl\";a:0:{}}s:45:\"llorix-one-companion/llorix-one-companion.php\";O:8:\"stdClass\":9:{s:2:\"id\";s:34:\"w.org/plugins/llorix-one-companion\";s:4:\"slug\";s:20:\"llorix-one-companion\";s:6:\"plugin\";s:45:\"llorix-one-companion/llorix-one-companion.php\";s:11:\"new_version\";s:5:\"1.1.4\";s:3:\"url\";s:51:\"https://wordpress.org/plugins/llorix-one-companion/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/plugin/llorix-one-companion.zip\";s:5:\"icons\";a:1:{s:2:\"1x\";s:73:\"https://ps.w.org/llorix-one-companion/assets/icon-128x128.png?rev=1366644\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:75:\"https://ps.w.org/llorix-one-companion/assets/banner-772x250.png?rev=1366644\";}s:11:\"banners_rtl\";a:0:{}}}}', 'no');
INSERT INTO `kog_options` VALUES (8376, '_site_transient_timeout_available_translations', '1573542603', 'no');
INSERT INTO `kog_options` VALUES (8377, '_site_transient_available_translations', 'a:118:{s:2:\"af\";a:8:{s:8:\"language\";s:2:\"af\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-31 16:35:52\";s:12:\"english_name\";s:9:\"Afrikaans\";s:11:\"native_name\";s:9:\"Afrikaans\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/af.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"af\";i:2;s:3:\"afr\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"Gaan voort\";}}s:2:\"ar\";a:8:{s:8:\"language\";s:2:\"ar\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-22 22:33:15\";s:12:\"english_name\";s:6:\"Arabic\";s:11:\"native_name\";s:14:\"العربية\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/ar.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ar\";i:2;s:3:\"ara\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:16:\"المتابعة\";}}s:3:\"ary\";a:8:{s:8:\"language\";s:3:\"ary\";s:7:\"version\";s:5:\"4.7.7\";s:7:\"updated\";s:19:\"2017-01-26 15:42:35\";s:12:\"english_name\";s:15:\"Moroccan Arabic\";s:11:\"native_name\";s:31:\"العربية المغربية\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.7/ary.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ar\";i:3;s:3:\"ary\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:16:\"المتابعة\";}}s:2:\"as\";a:8:{s:8:\"language\";s:2:\"as\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-11-22 18:59:07\";s:12:\"english_name\";s:8:\"Assamese\";s:11:\"native_name\";s:21:\"অসমীয়া\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/as.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"as\";i:2;s:3:\"asm\";i:3;s:3:\"asm\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:0:\"\";}}s:3:\"azb\";a:8:{s:8:\"language\";s:3:\"azb\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-09-12 20:34:31\";s:12:\"english_name\";s:17:\"South Azerbaijani\";s:11:\"native_name\";s:29:\"گؤنئی آذربایجان\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.2/azb.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"az\";i:3;s:3:\"azb\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continue\";}}s:2:\"az\";a:8:{s:8:\"language\";s:2:\"az\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-11-06 00:09:27\";s:12:\"english_name\";s:11:\"Azerbaijani\";s:11:\"native_name\";s:16:\"Azərbaycan dili\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/az.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"az\";i:2;s:3:\"aze\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:5:\"Davam\";}}s:3:\"bel\";a:8:{s:8:\"language\";s:3:\"bel\";s:7:\"version\";s:6:\"4.9.12\";s:7:\"updated\";s:19:\"2019-10-29 07:54:22\";s:12:\"english_name\";s:10:\"Belarusian\";s:11:\"native_name\";s:29:\"Беларуская мова\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/translation/core/4.9.12/bel.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"be\";i:2;s:3:\"bel\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:20:\"Працягнуць\";}}s:5:\"bg_BG\";a:8:{s:8:\"language\";s:5:\"bg_BG\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-09 08:44:51\";s:12:\"english_name\";s:9:\"Bulgarian\";s:11:\"native_name\";s:18:\"Български\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/bg_BG.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"bg\";i:2;s:3:\"bul\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:22:\"Продължение\";}}s:5:\"bn_BD\";a:8:{s:8:\"language\";s:5:\"bn_BD\";s:7:\"version\";s:5:\"4.8.6\";s:7:\"updated\";s:19:\"2017-10-01 12:57:10\";s:12:\"english_name\";s:20:\"Bengali (Bangladesh)\";s:11:\"native_name\";s:15:\"বাংলা\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.8.6/bn_BD.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"bn\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:23:\"এগিয়ে চল.\";}}s:2:\"bo\";a:8:{s:8:\"language\";s:2:\"bo\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-21 07:54:13\";s:12:\"english_name\";s:7:\"Tibetan\";s:11:\"native_name\";s:21:\"བོད་ཡིག\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/bo.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"bo\";i:2;s:3:\"tib\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:24:\"མུ་མཐུད།\";}}s:5:\"bs_BA\";a:8:{s:8:\"language\";s:5:\"bs_BA\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-25 20:23:46\";s:12:\"english_name\";s:7:\"Bosnian\";s:11:\"native_name\";s:8:\"Bosanski\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/bs_BA.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"bs\";i:2;s:3:\"bos\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:7:\"Nastavi\";}}s:2:\"ca\";a:8:{s:8:\"language\";s:2:\"ca\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-20 06:19:14\";s:12:\"english_name\";s:7:\"Catalan\";s:11:\"native_name\";s:7:\"Català\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/ca.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ca\";i:2;s:3:\"cat\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continua\";}}s:3:\"ceb\";a:8:{s:8:\"language\";s:3:\"ceb\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-03-02 17:25:51\";s:12:\"english_name\";s:7:\"Cebuano\";s:11:\"native_name\";s:7:\"Cebuano\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.2/ceb.zip\";s:3:\"iso\";a:2:{i:2;s:3:\"ceb\";i:3;s:3:\"ceb\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:7:\"Padayun\";}}s:5:\"cs_CZ\";a:8:{s:8:\"language\";s:5:\"cs_CZ\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-28 19:06:20\";s:12:\"english_name\";s:5:\"Czech\";s:11:\"native_name\";s:9:\"Čeština\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/cs_CZ.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"cs\";i:2;s:3:\"ces\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:11:\"Pokračovat\";}}s:2:\"cy\";a:8:{s:8:\"language\";s:2:\"cy\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-22 10:38:19\";s:12:\"english_name\";s:5:\"Welsh\";s:11:\"native_name\";s:7:\"Cymraeg\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/cy.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"cy\";i:2;s:3:\"cym\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"Parhau\";}}s:5:\"da_DK\";a:8:{s:8:\"language\";s:5:\"da_DK\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-09-19 12:19:29\";s:12:\"english_name\";s:6:\"Danish\";s:11:\"native_name\";s:5:\"Dansk\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/da_DK.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"da\";i:2;s:3:\"dan\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:12:\"Forts&#230;t\";}}s:14:\"de_CH_informal\";a:8:{s:8:\"language\";s:14:\"de_CH_informal\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-29 19:06:37\";s:12:\"english_name\";s:30:\"German (Switzerland, Informal)\";s:11:\"native_name\";s:21:\"Deutsch (Schweiz, Du)\";s:7:\"package\";s:73:\"https://downloads.wordpress.org/translation/core/5.1.3/de_CH_informal.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"de\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"Weiter\";}}s:5:\"de_DE\";a:8:{s:8:\"language\";s:5:\"de_DE\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 15:10:26\";s:12:\"english_name\";s:6:\"German\";s:11:\"native_name\";s:7:\"Deutsch\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/de_DE.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"de\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"Fortfahren\";}}s:12:\"de_DE_formal\";a:8:{s:8:\"language\";s:12:\"de_DE_formal\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 15:10:39\";s:12:\"english_name\";s:15:\"German (Formal)\";s:11:\"native_name\";s:13:\"Deutsch (Sie)\";s:7:\"package\";s:71:\"https://downloads.wordpress.org/translation/core/5.1.3/de_DE_formal.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"de\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"Fortfahren\";}}s:5:\"de_CH\";a:8:{s:8:\"language\";s:5:\"de_CH\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-29 19:06:10\";s:12:\"english_name\";s:20:\"German (Switzerland)\";s:11:\"native_name\";s:17:\"Deutsch (Schweiz)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/de_CH.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"de\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"Fortfahren\";}}s:5:\"de_AT\";a:8:{s:8:\"language\";s:5:\"de_AT\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-05 22:28:50\";s:12:\"english_name\";s:16:\"German (Austria)\";s:11:\"native_name\";s:21:\"Deutsch (Österreich)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/de_AT.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"de\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"Weiter\";}}s:3:\"dzo\";a:8:{s:8:\"language\";s:3:\"dzo\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-06-29 08:59:03\";s:12:\"english_name\";s:8:\"Dzongkha\";s:11:\"native_name\";s:18:\"རྫོང་ཁ\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.2/dzo.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"dz\";i:2;s:3:\"dzo\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:0:\"\";}}s:2:\"el\";a:8:{s:8:\"language\";s:2:\"el\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-08 12:38:18\";s:12:\"english_name\";s:5:\"Greek\";s:11:\"native_name\";s:16:\"Ελληνικά\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/el.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"el\";i:2;s:3:\"ell\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:16:\"Συνέχεια\";}}s:5:\"en_ZA\";a:8:{s:8:\"language\";s:5:\"en_ZA\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-06-06 15:48:01\";s:12:\"english_name\";s:22:\"English (South Africa)\";s:11:\"native_name\";s:22:\"English (South Africa)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/en_ZA.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"en\";i:2;s:3:\"eng\";i:3;s:3:\"eng\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continue\";}}s:5:\"en_NZ\";a:8:{s:8:\"language\";s:5:\"en_NZ\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-20 22:33:27\";s:12:\"english_name\";s:21:\"English (New Zealand)\";s:11:\"native_name\";s:21:\"English (New Zealand)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/en_NZ.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"en\";i:2;s:3:\"eng\";i:3;s:3:\"eng\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continue\";}}s:5:\"en_GB\";a:8:{s:8:\"language\";s:5:\"en_GB\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 14:35:54\";s:12:\"english_name\";s:12:\"English (UK)\";s:11:\"native_name\";s:12:\"English (UK)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/en_GB.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"en\";i:2;s:3:\"eng\";i:3;s:3:\"eng\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continue\";}}s:5:\"en_AU\";a:8:{s:8:\"language\";s:5:\"en_AU\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-20 00:25:54\";s:12:\"english_name\";s:19:\"English (Australia)\";s:11:\"native_name\";s:19:\"English (Australia)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/en_AU.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"en\";i:2;s:3:\"eng\";i:3;s:3:\"eng\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continue\";}}s:5:\"en_CA\";a:8:{s:8:\"language\";s:5:\"en_CA\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-20 22:33:38\";s:12:\"english_name\";s:16:\"English (Canada)\";s:11:\"native_name\";s:16:\"English (Canada)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/en_CA.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"en\";i:2;s:3:\"eng\";i:3;s:3:\"eng\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continue\";}}s:2:\"eo\";a:8:{s:8:\"language\";s:2:\"eo\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-21 14:53:06\";s:12:\"english_name\";s:9:\"Esperanto\";s:11:\"native_name\";s:9:\"Esperanto\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/eo.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"eo\";i:2;s:3:\"epo\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Daŭrigi\";}}s:5:\"es_AR\";a:8:{s:8:\"language\";s:5:\"es_AR\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-23 01:54:34\";s:12:\"english_name\";s:19:\"Spanish (Argentina)\";s:11:\"native_name\";s:21:\"Español de Argentina\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/es_AR.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_VE\";a:8:{s:8:\"language\";s:5:\"es_VE\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 23:14:42\";s:12:\"english_name\";s:19:\"Spanish (Venezuela)\";s:11:\"native_name\";s:21:\"Español de Venezuela\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/es_VE.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_GT\";a:8:{s:8:\"language\";s:5:\"es_GT\";s:7:\"version\";s:3:\"5.1\";s:7:\"updated\";s:19:\"2019-03-02 06:35:01\";s:12:\"english_name\";s:19:\"Spanish (Guatemala)\";s:11:\"native_name\";s:21:\"Español de Guatemala\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/5.1/es_GT.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_MX\";a:8:{s:8:\"language\";s:5:\"es_MX\";s:7:\"version\";s:5:\"5.0.7\";s:7:\"updated\";s:19:\"2018-12-07 18:38:30\";s:12:\"english_name\";s:16:\"Spanish (Mexico)\";s:11:\"native_name\";s:19:\"Español de México\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.0.7/es_MX.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_CR\";a:8:{s:8:\"language\";s:5:\"es_CR\";s:7:\"version\";s:3:\"5.0\";s:7:\"updated\";s:19:\"2018-12-06 21:26:01\";s:12:\"english_name\";s:20:\"Spanish (Costa Rica)\";s:11:\"native_name\";s:22:\"Español de Costa Rica\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/5.0/es_CR.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_CO\";a:8:{s:8:\"language\";s:5:\"es_CO\";s:7:\"version\";s:6:\"4.9.12\";s:7:\"updated\";s:19:\"2019-05-23 02:23:28\";s:12:\"english_name\";s:18:\"Spanish (Colombia)\";s:11:\"native_name\";s:20:\"Español de Colombia\";s:7:\"package\";s:65:\"https://downloads.wordpress.org/translation/core/4.9.12/es_CO.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_PE\";a:8:{s:8:\"language\";s:5:\"es_PE\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-09-09 09:36:22\";s:12:\"english_name\";s:14:\"Spanish (Peru)\";s:11:\"native_name\";s:17:\"Español de Perú\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.7.2/es_PE.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_ES\";a:8:{s:8:\"language\";s:5:\"es_ES\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-10 22:47:26\";s:12:\"english_name\";s:15:\"Spanish (Spain)\";s:11:\"native_name\";s:8:\"Español\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/es_ES.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"es_CL\";a:8:{s:8:\"language\";s:5:\"es_CL\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-05-07 16:34:55\";s:12:\"english_name\";s:15:\"Spanish (Chile)\";s:11:\"native_name\";s:17:\"Español de Chile\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/es_CL.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"es\";i:2;s:3:\"spa\";i:3;s:3:\"spa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:2:\"et\";a:8:{s:8:\"language\";s:2:\"et\";s:7:\"version\";s:9:\"5.0-beta3\";s:7:\"updated\";s:19:\"2018-11-28 16:04:33\";s:12:\"english_name\";s:8:\"Estonian\";s:11:\"native_name\";s:5:\"Eesti\";s:7:\"package\";s:65:\"https://downloads.wordpress.org/translation/core/5.0-beta3/et.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"et\";i:2;s:3:\"est\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"Jätka\";}}s:2:\"eu\";a:8:{s:8:\"language\";s:2:\"eu\";s:7:\"version\";s:5:\"4.9.2\";s:7:\"updated\";s:19:\"2017-12-09 21:12:23\";s:12:\"english_name\";s:6:\"Basque\";s:11:\"native_name\";s:7:\"Euskara\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.9.2/eu.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"eu\";i:2;s:3:\"eus\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Jarraitu\";}}s:5:\"fa_IR\";a:8:{s:8:\"language\";s:5:\"fa_IR\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-26 11:05:16\";s:12:\"english_name\";s:7:\"Persian\";s:11:\"native_name\";s:10:\"فارسی\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/fa_IR.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"fa\";i:2;s:3:\"fas\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"ادامه\";}}s:2:\"fi\";a:8:{s:8:\"language\";s:2:\"fi\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-22 14:08:00\";s:12:\"english_name\";s:7:\"Finnish\";s:11:\"native_name\";s:5:\"Suomi\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/fi.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"fi\";i:2;s:3:\"fin\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:5:\"Jatka\";}}s:5:\"fr_CA\";a:8:{s:8:\"language\";s:5:\"fr_CA\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-20 19:34:23\";s:12:\"english_name\";s:15:\"French (Canada)\";s:11:\"native_name\";s:19:\"Français du Canada\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/fr_CA.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"fr\";i:2;s:3:\"fra\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuer\";}}s:5:\"fr_FR\";a:8:{s:8:\"language\";s:5:\"fr_FR\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 15:01:27\";s:12:\"english_name\";s:15:\"French (France)\";s:11:\"native_name\";s:9:\"Français\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/fr_FR.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"fr\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuer\";}}s:5:\"fr_BE\";a:8:{s:8:\"language\";s:5:\"fr_BE\";s:7:\"version\";s:5:\"4.9.5\";s:7:\"updated\";s:19:\"2018-01-31 11:16:06\";s:12:\"english_name\";s:16:\"French (Belgium)\";s:11:\"native_name\";s:21:\"Français de Belgique\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.5/fr_BE.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"fr\";i:2;s:3:\"fra\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuer\";}}s:3:\"fur\";a:8:{s:8:\"language\";s:3:\"fur\";s:7:\"version\";s:5:\"4.8.6\";s:7:\"updated\";s:19:\"2018-01-29 17:32:35\";s:12:\"english_name\";s:8:\"Friulian\";s:11:\"native_name\";s:8:\"Friulian\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.8.6/fur.zip\";s:3:\"iso\";a:2:{i:2;s:3:\"fur\";i:3;s:3:\"fur\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continue\";}}s:2:\"gd\";a:8:{s:8:\"language\";s:2:\"gd\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-08-23 17:41:37\";s:12:\"english_name\";s:15:\"Scottish Gaelic\";s:11:\"native_name\";s:9:\"Gàidhlig\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/gd.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"gd\";i:2;s:3:\"gla\";i:3;s:3:\"gla\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:15:\"Lean air adhart\";}}s:5:\"gl_ES\";a:8:{s:8:\"language\";s:5:\"gl_ES\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 22:08:26\";s:12:\"english_name\";s:8:\"Galician\";s:11:\"native_name\";s:6:\"Galego\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/gl_ES.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"gl\";i:2;s:3:\"glg\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:2:\"gu\";a:8:{s:8:\"language\";s:2:\"gu\";s:7:\"version\";s:5:\"4.9.8\";s:7:\"updated\";s:19:\"2018-09-14 12:33:48\";s:12:\"english_name\";s:8:\"Gujarati\";s:11:\"native_name\";s:21:\"ગુજરાતી\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.9.8/gu.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"gu\";i:2;s:3:\"guj\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:31:\"ચાલુ રાખવું\";}}s:3:\"haz\";a:8:{s:8:\"language\";s:3:\"haz\";s:7:\"version\";s:5:\"4.4.2\";s:7:\"updated\";s:19:\"2015-12-05 00:59:09\";s:12:\"english_name\";s:8:\"Hazaragi\";s:11:\"native_name\";s:15:\"هزاره گی\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.4.2/haz.zip\";s:3:\"iso\";a:1:{i:3;s:3:\"haz\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"ادامه\";}}s:5:\"he_IL\";a:8:{s:8:\"language\";s:5:\"he_IL\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-05 14:01:00\";s:12:\"english_name\";s:6:\"Hebrew\";s:11:\"native_name\";s:16:\"עִבְרִית\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/he_IL.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"he\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:12:\"להמשיך\";}}s:5:\"hi_IN\";a:8:{s:8:\"language\";s:5:\"hi_IN\";s:7:\"version\";s:5:\"4.9.7\";s:7:\"updated\";s:19:\"2018-06-17 09:33:44\";s:12:\"english_name\";s:5:\"Hindi\";s:11:\"native_name\";s:18:\"हिन्दी\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.7/hi_IN.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"hi\";i:2;s:3:\"hin\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:12:\"जारी\";}}s:2:\"hr\";a:8:{s:8:\"language\";s:2:\"hr\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-28 08:31:21\";s:12:\"english_name\";s:8:\"Croatian\";s:11:\"native_name\";s:8:\"Hrvatski\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/hr.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"hr\";i:2;s:3:\"hrv\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:7:\"Nastavi\";}}s:5:\"hu_HU\";a:8:{s:8:\"language\";s:5:\"hu_HU\";s:7:\"version\";s:5:\"5.1.1\";s:7:\"updated\";s:19:\"2019-03-19 14:36:40\";s:12:\"english_name\";s:9:\"Hungarian\";s:11:\"native_name\";s:6:\"Magyar\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.1/hu_HU.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"hu\";i:2;s:3:\"hun\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:7:\"Tovább\";}}s:2:\"hy\";a:8:{s:8:\"language\";s:2:\"hy\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-12-03 16:21:10\";s:12:\"english_name\";s:8:\"Armenian\";s:11:\"native_name\";s:14:\"Հայերեն\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/hy.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"hy\";i:2;s:3:\"hye\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:20:\"Շարունակել\";}}s:5:\"id_ID\";a:8:{s:8:\"language\";s:5:\"id_ID\";s:7:\"version\";s:5:\"4.9.8\";s:7:\"updated\";s:19:\"2018-07-28 13:16:13\";s:12:\"english_name\";s:10:\"Indonesian\";s:11:\"native_name\";s:16:\"Bahasa Indonesia\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.8/id_ID.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"id\";i:2;s:3:\"ind\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Lanjutkan\";}}s:5:\"is_IS\";a:8:{s:8:\"language\";s:5:\"is_IS\";s:7:\"version\";s:6:\"4.7.11\";s:7:\"updated\";s:19:\"2018-09-20 11:13:37\";s:12:\"english_name\";s:9:\"Icelandic\";s:11:\"native_name\";s:9:\"Íslenska\";s:7:\"package\";s:65:\"https://downloads.wordpress.org/translation/core/4.7.11/is_IS.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"is\";i:2;s:3:\"isl\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"Áfram\";}}s:5:\"it_IT\";a:8:{s:8:\"language\";s:5:\"it_IT\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 16:39:02\";s:12:\"english_name\";s:7:\"Italian\";s:11:\"native_name\";s:8:\"Italiano\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/it_IT.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"it\";i:2;s:3:\"ita\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Continua\";}}s:2:\"ja\";a:8:{s:8:\"language\";s:2:\"ja\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-05-11 20:53:19\";s:12:\"english_name\";s:8:\"Japanese\";s:11:\"native_name\";s:9:\"日本語\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/ja.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"ja\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"続ける\";}}s:5:\"jv_ID\";a:8:{s:8:\"language\";s:5:\"jv_ID\";s:7:\"version\";s:5:\"4.9.5\";s:7:\"updated\";s:19:\"2018-03-24 13:53:29\";s:12:\"english_name\";s:8:\"Javanese\";s:11:\"native_name\";s:9:\"Basa Jawa\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.5/jv_ID.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"jv\";i:2;s:3:\"jav\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:7:\"Nutugne\";}}s:5:\"ka_GE\";a:8:{s:8:\"language\";s:5:\"ka_GE\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-04 08:57:25\";s:12:\"english_name\";s:8:\"Georgian\";s:11:\"native_name\";s:21:\"ქართული\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/ka_GE.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ka\";i:2;s:3:\"kat\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:30:\"გაგრძელება\";}}s:3:\"kab\";a:8:{s:8:\"language\";s:3:\"kab\";s:7:\"version\";s:5:\"4.9.8\";s:7:\"updated\";s:19:\"2018-09-21 14:15:57\";s:12:\"english_name\";s:6:\"Kabyle\";s:11:\"native_name\";s:9:\"Taqbaylit\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.9.8/kab.zip\";s:3:\"iso\";a:2:{i:2;s:3:\"kab\";i:3;s:3:\"kab\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuer\";}}s:2:\"kk\";a:8:{s:8:\"language\";s:2:\"kk\";s:7:\"version\";s:5:\"4.9.5\";s:7:\"updated\";s:19:\"2018-03-12 08:08:32\";s:12:\"english_name\";s:6:\"Kazakh\";s:11:\"native_name\";s:19:\"Қазақ тілі\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.9.5/kk.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"kk\";i:2;s:3:\"kaz\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:20:\"Жалғастыру\";}}s:2:\"km\";a:8:{s:8:\"language\";s:2:\"km\";s:7:\"version\";s:5:\"5.0.3\";s:7:\"updated\";s:19:\"2019-01-09 07:34:10\";s:12:\"english_name\";s:5:\"Khmer\";s:11:\"native_name\";s:27:\"ភាសាខ្មែរ\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.0.3/km.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"km\";i:2;s:3:\"khm\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:12:\"បន្ត\";}}s:2:\"kn\";a:8:{s:8:\"language\";s:2:\"kn\";s:7:\"version\";s:6:\"4.9.12\";s:7:\"updated\";s:19:\"2019-05-08 04:00:57\";s:12:\"english_name\";s:7:\"Kannada\";s:11:\"native_name\";s:15:\"ಕನ್ನಡ\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.9.12/kn.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"kn\";i:2;s:3:\"kan\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:30:\"ಮುಂದುವರೆಸಿ\";}}s:5:\"ko_KR\";a:8:{s:8:\"language\";s:5:\"ko_KR\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-05 01:55:15\";s:12:\"english_name\";s:6:\"Korean\";s:11:\"native_name\";s:9:\"한국어\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/ko_KR.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ko\";i:2;s:3:\"kor\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"계속\";}}s:3:\"ckb\";a:8:{s:8:\"language\";s:3:\"ckb\";s:7:\"version\";s:5:\"4.9.9\";s:7:\"updated\";s:19:\"2018-12-18 14:32:44\";s:12:\"english_name\";s:16:\"Kurdish (Sorani)\";s:11:\"native_name\";s:13:\"كوردی‎\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.9.9/ckb.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ku\";i:3;s:3:\"ckb\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:30:\"به‌رده‌وام به‌\";}}s:2:\"lo\";a:8:{s:8:\"language\";s:2:\"lo\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-11-12 09:59:23\";s:12:\"english_name\";s:3:\"Lao\";s:11:\"native_name\";s:21:\"ພາສາລາວ\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/lo.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"lo\";i:2;s:3:\"lao\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"ຕໍ່\";}}s:5:\"lt_LT\";a:8:{s:8:\"language\";s:5:\"lt_LT\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 19:22:55\";s:12:\"english_name\";s:10:\"Lithuanian\";s:11:\"native_name\";s:15:\"Lietuvių kalba\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/lt_LT.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"lt\";i:2;s:3:\"lit\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"Tęsti\";}}s:2:\"lv\";a:8:{s:8:\"language\";s:2:\"lv\";s:7:\"version\";s:6:\"4.7.15\";s:7:\"updated\";s:19:\"2019-05-10 10:24:08\";s:12:\"english_name\";s:7:\"Latvian\";s:11:\"native_name\";s:16:\"Latviešu valoda\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.15/lv.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"lv\";i:2;s:3:\"lav\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Turpināt\";}}s:5:\"mk_MK\";a:8:{s:8:\"language\";s:5:\"mk_MK\";s:7:\"version\";s:5:\"4.7.7\";s:7:\"updated\";s:19:\"2017-01-26 15:54:41\";s:12:\"english_name\";s:10:\"Macedonian\";s:11:\"native_name\";s:31:\"Македонски јазик\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.7.7/mk_MK.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"mk\";i:2;s:3:\"mkd\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:16:\"Продолжи\";}}s:5:\"ml_IN\";a:8:{s:8:\"language\";s:5:\"ml_IN\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2017-01-27 03:43:32\";s:12:\"english_name\";s:9:\"Malayalam\";s:11:\"native_name\";s:18:\"മലയാളം\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.7.2/ml_IN.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ml\";i:2;s:3:\"mal\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:18:\"തുടരുക\";}}s:2:\"mn\";a:8:{s:8:\"language\";s:2:\"mn\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2017-01-12 07:29:35\";s:12:\"english_name\";s:9:\"Mongolian\";s:11:\"native_name\";s:12:\"Монгол\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/mn.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"mn\";i:2;s:3:\"mon\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:24:\"Үргэлжлүүлэх\";}}s:2:\"mr\";a:8:{s:8:\"language\";s:2:\"mr\";s:7:\"version\";s:6:\"4.8.11\";s:7:\"updated\";s:19:\"2018-02-13 07:38:55\";s:12:\"english_name\";s:7:\"Marathi\";s:11:\"native_name\";s:15:\"मराठी\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.8.11/mr.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"mr\";i:2;s:3:\"mar\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:25:\"सुरु ठेवा\";}}s:5:\"ms_MY\";a:8:{s:8:\"language\";s:5:\"ms_MY\";s:7:\"version\";s:5:\"4.9.8\";s:7:\"updated\";s:19:\"2018-08-30 20:27:25\";s:12:\"english_name\";s:5:\"Malay\";s:11:\"native_name\";s:13:\"Bahasa Melayu\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.8/ms_MY.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ms\";i:2;s:3:\"msa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Teruskan\";}}s:5:\"my_MM\";a:8:{s:8:\"language\";s:5:\"my_MM\";s:7:\"version\";s:6:\"4.1.20\";s:7:\"updated\";s:19:\"2015-03-26 15:57:42\";s:12:\"english_name\";s:17:\"Myanmar (Burmese)\";s:11:\"native_name\";s:15:\"ဗမာစာ\";s:7:\"package\";s:65:\"https://downloads.wordpress.org/translation/core/4.1.20/my_MM.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"my\";i:2;s:3:\"mya\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:54:\"ဆက်လက်လုပ်ေဆာင်ပါ။\";}}s:5:\"nb_NO\";a:8:{s:8:\"language\";s:5:\"nb_NO\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-07-29 18:32:50\";s:12:\"english_name\";s:19:\"Norwegian (Bokmål)\";s:11:\"native_name\";s:13:\"Norsk bokmål\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/nb_NO.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"nb\";i:2;s:3:\"nob\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Fortsett\";}}s:5:\"ne_NP\";a:8:{s:8:\"language\";s:5:\"ne_NP\";s:7:\"version\";s:5:\"4.9.5\";s:7:\"updated\";s:19:\"2018-03-27 10:30:26\";s:12:\"english_name\";s:6:\"Nepali\";s:11:\"native_name\";s:18:\"नेपाली\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.5/ne_NP.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ne\";i:2;s:3:\"nep\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:31:\"जारीराख्नु \";}}s:12:\"nl_NL_formal\";a:8:{s:8:\"language\";s:12:\"nl_NL_formal\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-23 09:40:21\";s:12:\"english_name\";s:14:\"Dutch (Formal)\";s:11:\"native_name\";s:20:\"Nederlands (Formeel)\";s:7:\"package\";s:71:\"https://downloads.wordpress.org/translation/core/5.1.3/nl_NL_formal.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"nl\";i:2;s:3:\"nld\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Doorgaan\";}}s:5:\"nl_NL\";a:8:{s:8:\"language\";s:5:\"nl_NL\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-25 09:24:13\";s:12:\"english_name\";s:5:\"Dutch\";s:11:\"native_name\";s:10:\"Nederlands\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/nl_NL.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"nl\";i:2;s:3:\"nld\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Doorgaan\";}}s:5:\"nl_BE\";a:8:{s:8:\"language\";s:5:\"nl_BE\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-20 07:54:08\";s:12:\"english_name\";s:15:\"Dutch (Belgium)\";s:11:\"native_name\";s:20:\"Nederlands (België)\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/nl_BE.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"nl\";i:2;s:3:\"nld\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"Doorgaan\";}}s:5:\"nn_NO\";a:8:{s:8:\"language\";s:5:\"nn_NO\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-24 08:39:08\";s:12:\"english_name\";s:19:\"Norwegian (Nynorsk)\";s:11:\"native_name\";s:13:\"Norsk nynorsk\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/nn_NO.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"nn\";i:2;s:3:\"nno\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Hald fram\";}}s:3:\"oci\";a:8:{s:8:\"language\";s:3:\"oci\";s:7:\"version\";s:5:\"4.8.3\";s:7:\"updated\";s:19:\"2017-08-25 10:03:08\";s:12:\"english_name\";s:7:\"Occitan\";s:11:\"native_name\";s:7:\"Occitan\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.8.3/oci.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"oc\";i:2;s:3:\"oci\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Contunhar\";}}s:5:\"pa_IN\";a:8:{s:8:\"language\";s:5:\"pa_IN\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2017-01-16 05:19:43\";s:12:\"english_name\";s:7:\"Punjabi\";s:11:\"native_name\";s:18:\"ਪੰਜਾਬੀ\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.7.2/pa_IN.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"pa\";i:2;s:3:\"pan\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:25:\"ਜਾਰੀ ਰੱਖੋ\";}}s:5:\"pl_PL\";a:8:{s:8:\"language\";s:5:\"pl_PL\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-23 19:49:18\";s:12:\"english_name\";s:6:\"Polish\";s:11:\"native_name\";s:6:\"Polski\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/pl_PL.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"pl\";i:2;s:3:\"pol\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Kontynuuj\";}}s:2:\"ps\";a:8:{s:8:\"language\";s:2:\"ps\";s:7:\"version\";s:6:\"4.1.20\";s:7:\"updated\";s:19:\"2015-03-29 22:19:48\";s:12:\"english_name\";s:6:\"Pashto\";s:11:\"native_name\";s:8:\"پښتو\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.1.20/ps.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ps\";i:2;s:3:\"pus\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:8:\"دوام\";}}s:5:\"pt_AO\";a:8:{s:8:\"language\";s:5:\"pt_AO\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-23 10:26:20\";s:12:\"english_name\";s:19:\"Portuguese (Angola)\";s:11:\"native_name\";s:20:\"Português de Angola\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/pt_AO.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"pt\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"pt_PT\";a:8:{s:8:\"language\";s:5:\"pt_PT\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-16 11:33:04\";s:12:\"english_name\";s:21:\"Portuguese (Portugal)\";s:11:\"native_name\";s:10:\"Português\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/pt_PT.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"pt\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:5:\"pt_BR\";a:8:{s:8:\"language\";s:5:\"pt_BR\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 15:12:49\";s:12:\"english_name\";s:19:\"Portuguese (Brazil)\";s:11:\"native_name\";s:20:\"Português do Brasil\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/pt_BR.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"pt\";i:2;s:3:\"por\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:10:\"pt_PT_ao90\";a:8:{s:8:\"language\";s:10:\"pt_PT_ao90\";s:7:\"version\";s:3:\"5.1\";s:7:\"updated\";s:19:\"2019-02-22 12:37:09\";s:12:\"english_name\";s:27:\"Portuguese (Portugal, AO90)\";s:11:\"native_name\";s:17:\"Português (AO90)\";s:7:\"package\";s:67:\"https://downloads.wordpress.org/translation/core/5.1/pt_PT_ao90.zip\";s:3:\"iso\";a:1:{i:1;s:2:\"pt\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuar\";}}s:3:\"rhg\";a:8:{s:8:\"language\";s:3:\"rhg\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-03-16 13:03:18\";s:12:\"english_name\";s:8:\"Rohingya\";s:11:\"native_name\";s:8:\"Ruáinga\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.2/rhg.zip\";s:3:\"iso\";a:1:{i:3;s:3:\"rhg\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:0:\"\";}}s:5:\"ro_RO\";a:8:{s:8:\"language\";s:5:\"ro_RO\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-21 12:45:52\";s:12:\"english_name\";s:8:\"Romanian\";s:11:\"native_name\";s:8:\"Română\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/ro_RO.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ro\";i:2;s:3:\"ron\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Continuă\";}}s:5:\"ru_RU\";a:8:{s:8:\"language\";s:5:\"ru_RU\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 14:39:25\";s:12:\"english_name\";s:7:\"Russian\";s:11:\"native_name\";s:14:\"Русский\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/ru_RU.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ru\";i:2;s:3:\"rus\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:20:\"Продолжить\";}}s:3:\"sah\";a:8:{s:8:\"language\";s:3:\"sah\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2017-01-21 02:06:41\";s:12:\"english_name\";s:5:\"Sakha\";s:11:\"native_name\";s:14:\"Сахалыы\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.2/sah.zip\";s:3:\"iso\";a:2:{i:2;s:3:\"sah\";i:3;s:3:\"sah\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:12:\"Салҕаа\";}}s:5:\"si_LK\";a:8:{s:8:\"language\";s:5:\"si_LK\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-11-12 06:00:52\";s:12:\"english_name\";s:7:\"Sinhala\";s:11:\"native_name\";s:15:\"සිංහල\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.7.2/si_LK.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"si\";i:2;s:3:\"sin\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:44:\"දිගටම කරගෙන යන්න\";}}s:5:\"sk_SK\";a:8:{s:8:\"language\";s:5:\"sk_SK\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-08 15:30:32\";s:12:\"english_name\";s:6:\"Slovak\";s:11:\"native_name\";s:11:\"Slovenčina\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/sk_SK.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"sk\";i:2;s:3:\"slk\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:12:\"Pokračovať\";}}s:3:\"skr\";a:8:{s:8:\"language\";s:3:\"skr\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-09 14:38:41\";s:12:\"english_name\";s:7:\"Saraiki\";s:11:\"native_name\";s:14:\"سرائیکی\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/5.1.3/skr.zip\";s:3:\"iso\";a:1:{i:3;s:3:\"skr\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:17:\"جاری رکھو\";}}s:5:\"sl_SI\";a:8:{s:8:\"language\";s:5:\"sl_SI\";s:7:\"version\";s:5:\"4.9.2\";s:7:\"updated\";s:19:\"2018-01-04 13:33:13\";s:12:\"english_name\";s:9:\"Slovenian\";s:11:\"native_name\";s:13:\"Slovenščina\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.2/sl_SI.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"sl\";i:2;s:3:\"slv\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"Nadaljujte\";}}s:2:\"sq\";a:8:{s:8:\"language\";s:2:\"sq\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-20 12:34:47\";s:12:\"english_name\";s:8:\"Albanian\";s:11:\"native_name\";s:5:\"Shqip\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/sq.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"sq\";i:2;s:3:\"sqi\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"Vazhdo\";}}s:5:\"sr_RS\";a:8:{s:8:\"language\";s:5:\"sr_RS\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-13 12:26:58\";s:12:\"english_name\";s:7:\"Serbian\";s:11:\"native_name\";s:23:\"Српски језик\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/sr_RS.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"sr\";i:2;s:3:\"srp\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:14:\"Настави\";}}s:5:\"sv_SE\";a:8:{s:8:\"language\";s:5:\"sv_SE\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-19 13:42:39\";s:12:\"english_name\";s:7:\"Swedish\";s:11:\"native_name\";s:7:\"Svenska\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/sv_SE.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"sv\";i:2;s:3:\"swe\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:9:\"Fortsätt\";}}s:2:\"sw\";a:8:{s:8:\"language\";s:2:\"sw\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-29 03:20:38\";s:12:\"english_name\";s:7:\"Swahili\";s:11:\"native_name\";s:9:\"Kiswahili\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/sw.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"sw\";i:2;s:3:\"swa\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:7:\"Endelea\";}}s:3:\"szl\";a:8:{s:8:\"language\";s:3:\"szl\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-09-24 19:58:14\";s:12:\"english_name\";s:8:\"Silesian\";s:11:\"native_name\";s:17:\"Ślōnskŏ gŏdka\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.2/szl.zip\";s:3:\"iso\";a:1:{i:3;s:3:\"szl\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:13:\"Kōntynuować\";}}s:5:\"ta_IN\";a:8:{s:8:\"language\";s:5:\"ta_IN\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2017-01-27 03:22:47\";s:12:\"english_name\";s:5:\"Tamil\";s:11:\"native_name\";s:15:\"தமிழ்\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.7.2/ta_IN.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ta\";i:2;s:3:\"tam\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:24:\"தொடரவும்\";}}s:2:\"te\";a:8:{s:8:\"language\";s:2:\"te\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2017-01-26 15:47:39\";s:12:\"english_name\";s:6:\"Telugu\";s:11:\"native_name\";s:18:\"తెలుగు\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/te.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"te\";i:2;s:3:\"tel\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:30:\"కొనసాగించు\";}}s:2:\"th\";a:8:{s:8:\"language\";s:2:\"th\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-27 04:33:46\";s:12:\"english_name\";s:4:\"Thai\";s:11:\"native_name\";s:9:\"ไทย\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/th.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"th\";i:2;s:3:\"tha\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:15:\"ต่อไป\";}}s:2:\"tl\";a:8:{s:8:\"language\";s:2:\"tl\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-12-30 02:38:08\";s:12:\"english_name\";s:7:\"Tagalog\";s:11:\"native_name\";s:7:\"Tagalog\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/4.7.2/tl.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"tl\";i:2;s:3:\"tgl\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:10:\"Magpatuloy\";}}s:5:\"tr_TR\";a:8:{s:8:\"language\";s:5:\"tr_TR\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-30 18:44:41\";s:12:\"english_name\";s:7:\"Turkish\";s:11:\"native_name\";s:8:\"Türkçe\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/tr_TR.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"tr\";i:2;s:3:\"tur\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:5:\"Devam\";}}s:5:\"tt_RU\";a:8:{s:8:\"language\";s:5:\"tt_RU\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-11-20 20:20:50\";s:12:\"english_name\";s:5:\"Tatar\";s:11:\"native_name\";s:19:\"Татар теле\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.7.2/tt_RU.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"tt\";i:2;s:3:\"tat\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:17:\"дәвам итү\";}}s:3:\"tah\";a:8:{s:8:\"language\";s:3:\"tah\";s:7:\"version\";s:5:\"4.7.2\";s:7:\"updated\";s:19:\"2016-03-06 18:39:39\";s:12:\"english_name\";s:8:\"Tahitian\";s:11:\"native_name\";s:10:\"Reo Tahiti\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/translation/core/4.7.2/tah.zip\";s:3:\"iso\";a:3:{i:1;s:2:\"ty\";i:2;s:3:\"tah\";i:3;s:3:\"tah\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:0:\"\";}}s:5:\"ug_CN\";a:8:{s:8:\"language\";s:5:\"ug_CN\";s:7:\"version\";s:5:\"4.9.5\";s:7:\"updated\";s:19:\"2018-04-12 12:31:53\";s:12:\"english_name\";s:6:\"Uighur\";s:11:\"native_name\";s:16:\"ئۇيغۇرچە\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/4.9.5/ug_CN.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ug\";i:2;s:3:\"uig\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:26:\"داۋاملاشتۇرۇش\";}}s:2:\"uk\";a:8:{s:8:\"language\";s:2:\"uk\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-10-03 17:08:44\";s:12:\"english_name\";s:9:\"Ukrainian\";s:11:\"native_name\";s:20:\"Українська\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/uk.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"uk\";i:2;s:3:\"ukr\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:20:\"Продовжити\";}}s:2:\"ur\";a:8:{s:8:\"language\";s:2:\"ur\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-03-31 10:39:40\";s:12:\"english_name\";s:4:\"Urdu\";s:11:\"native_name\";s:8:\"اردو\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/ur.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"ur\";i:2;s:3:\"urd\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:19:\"جاری رکھیں\";}}s:5:\"uz_UZ\";a:8:{s:8:\"language\";s:5:\"uz_UZ\";s:7:\"version\";s:5:\"5.0.3\";s:7:\"updated\";s:19:\"2019-01-23 12:32:40\";s:12:\"english_name\";s:5:\"Uzbek\";s:11:\"native_name\";s:11:\"O‘zbekcha\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.0.3/uz_UZ.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"uz\";i:2;s:3:\"uzb\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:20:\"Продолжить\";}}s:2:\"vi\";a:8:{s:8:\"language\";s:2:\"vi\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-08-24 12:06:15\";s:12:\"english_name\";s:10:\"Vietnamese\";s:11:\"native_name\";s:14:\"Tiếng Việt\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/translation/core/5.1.3/vi.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"vi\";i:2;s:3:\"vie\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:12:\"Tiếp tục\";}}s:5:\"zh_HK\";a:8:{s:8:\"language\";s:5:\"zh_HK\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-05-09 17:10:44\";s:12:\"english_name\";s:19:\"Chinese (Hong Kong)\";s:11:\"native_name\";s:16:\"香港中文版	\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/zh_HK.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"zh\";i:2;s:3:\"zho\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"繼續\";}}s:5:\"zh_CN\";a:8:{s:8:\"language\";s:5:\"zh_CN\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-07-29 00:19:11\";s:12:\"english_name\";s:15:\"Chinese (China)\";s:11:\"native_name\";s:12:\"简体中文\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/zh_CN.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"zh\";i:2;s:3:\"zho\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"继续\";}}s:5:\"zh_TW\";a:8:{s:8:\"language\";s:5:\"zh_TW\";s:7:\"version\";s:5:\"5.1.3\";s:7:\"updated\";s:19:\"2019-11-12 03:13:55\";s:12:\"english_name\";s:16:\"Chinese (Taiwan)\";s:11:\"native_name\";s:12:\"繁體中文\";s:7:\"package\";s:64:\"https://downloads.wordpress.org/translation/core/5.1.3/zh_TW.zip\";s:3:\"iso\";a:2:{i:1;s:2:\"zh\";i:2;s:3:\"zho\";}s:7:\"strings\";a:1:{s:8:\"continue\";s:6:\"繼續\";}}}', 'no');
COMMIT;

-- ----------------------------
-- Table structure for kog_postmeta
-- ----------------------------
DROP TABLE IF EXISTS `kog_postmeta`;
CREATE TABLE `kog_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_postmeta
-- ----------------------------
BEGIN;
INSERT INTO `kog_postmeta` VALUES (1, 2, '_wp_page_template', 'default');
INSERT INTO `kog_postmeta` VALUES (2, 3, '_wp_page_template', 'default');
INSERT INTO `kog_postmeta` VALUES (7, 1, '_edit_lock', '1554437452:1');
INSERT INTO `kog_postmeta` VALUES (12, 14, '_wp_attached_file', '2019/04/Pexels-Videos-1110140.mp4');
INSERT INTO `kog_postmeta` VALUES (13, 14, '_wp_attachment_metadata', 'a:10:{s:7:\"bitrate\";i:21597034;s:8:\"filesize\";i:17010991;s:9:\"mime_type\";s:15:\"video/quicktime\";s:6:\"length\";i:6;s:16:\"length_formatted\";s:4:\"0:06\";s:5:\"width\";i:3840;s:6:\"height\";i:2160;s:10:\"fileformat\";s:3:\"mp4\";s:10:\"dataformat\";s:9:\"quicktime\";s:17:\"created_timestamp\";i:1527094137;}');
INSERT INTO `kog_postmeta` VALUES (14, 15, '_wp_attached_file', '2019/04/IMG_20180623_182105-EFFECTS.jpg');
INSERT INTO `kog_postmeta` VALUES (15, 15, '_wp_attachment_metadata', 'a:5:{s:5:\"width\";i:3840;s:6:\"height\";i:2160;s:4:\"file\";s:39:\"2019/04/IMG_20180623_182105-EFFECTS.jpg\";s:5:\"sizes\";a:5:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:39:\"IMG_20180623_182105-EFFECTS-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:39:\"IMG_20180623_182105-EFFECTS-300x169.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:169;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:12:\"medium_large\";a:4:{s:4:\"file\";s:39:\"IMG_20180623_182105-EFFECTS-768x432.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:432;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:5:\"large\";a:4:{s:4:\"file\";s:40:\"IMG_20180623_182105-EFFECTS-1024x576.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:576;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:12:\"kratos-thumb\";a:4:{s:4:\"file\";s:39:\"IMG_20180623_182105-EFFECTS-750x422.jpg\";s:5:\"width\";i:750;s:6:\"height\";i:422;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:12:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:8:\"Pixel XL\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:10:\"1529778065\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:15:\"4.6700000762939\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}}}');
INSERT INTO `kog_postmeta` VALUES (18, 17, '_edit_lock', '1554441590:1');
INSERT INTO `kog_postmeta` VALUES (19, 17, '_edit_last', '1');
INSERT INTO `kog_postmeta` VALUES (21, 17, 'enclosure', 'http://34.80.179.83/wp-content/uploads/2019/04/Pexels-Videos-1110140.mp4\n0\nvideo/mp4\n');
INSERT INTO `kog_postmeta` VALUES (22, 17, 'views', '13');
INSERT INTO `kog_postmeta` VALUES (23, 17, 'love', '1');
INSERT INTO `kog_postmeta` VALUES (24, 18, '_edit_lock', '1554441937:1');
INSERT INTO `kog_postmeta` VALUES (25, 18, '_edit_last', '1');
INSERT INTO `kog_postmeta` VALUES (27, 18, 'views', '3');
INSERT INTO `kog_postmeta` VALUES (28, 18, 'love', '1');
INSERT INTO `kog_postmeta` VALUES (29, 1, 'views', '2');
INSERT INTO `kog_postmeta` VALUES (30, 2, 'views', '1');
INSERT INTO `kog_postmeta` VALUES (31, 19, '_edit_lock', '1554705366:1');
INSERT INTO `kog_postmeta` VALUES (32, 19, '_edit_last', '1');
INSERT INTO `kog_postmeta` VALUES (33, 20, '_wp_attached_file', '2019/04/PEAK-POWER-INCREASE-TEST-Is-it-really-FASTER.mp4');
INSERT INTO `kog_postmeta` VALUES (34, 20, '_wp_attachment_metadata', 'a:10:{s:8:\"filesize\";i:21090652;s:9:\"mime_type\";s:9:\"video/mp4\";s:6:\"length\";i:665;s:16:\"length_formatted\";s:5:\"11:05\";s:5:\"width\";i:426;s:6:\"height\";i:240;s:10:\"fileformat\";s:3:\"mp4\";s:10:\"dataformat\";s:9:\"quicktime\";s:5:\"audio\";a:7:{s:10:\"dataformat\";s:3:\"mp4\";s:5:\"codec\";s:19:\"ISO/IEC 14496-3 AAC\";s:11:\"sample_rate\";d:44100;s:8:\"channels\";i:2;s:15:\"bits_per_sample\";i:16;s:8:\"lossless\";b:0;s:11:\"channelmode\";s:6:\"stereo\";}s:17:\"created_timestamp\";i:-2082844800;}');
INSERT INTO `kog_postmeta` VALUES (36, 19, 'enclosure', 'http://www.victorxys.com/wp-content/uploads/2019/04/PEAK-POWER-INCREASE-TEST-Is-it-really-FASTER.mp4\r\n0\r\nvideo/mp4\r\n');
INSERT INTO `kog_postmeta` VALUES (37, 19, 'views', '5');
INSERT INTO `kog_postmeta` VALUES (39, 22, '_edit_lock', '1554708805:1');
INSERT INTO `kog_postmeta` VALUES (40, 22, '_oembed_77478d3f5055cbdc48cbcf915235aab9', '<iframe width=\"916\" height=\"515\" src=\"https://www.youtube.com/embed/5viFu57qUgs?start=231&feature=oembed\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>');
INSERT INTO `kog_postmeta` VALUES (41, 22, '_oembed_time_77478d3f5055cbdc48cbcf915235aab9', '1554708507');
INSERT INTO `kog_postmeta` VALUES (42, 22, '_edit_last', '1');
INSERT INTO `kog_postmeta` VALUES (43, 23, '_wp_attached_file', '2019/04/Tesla-sentry-mode.mp4');
INSERT INTO `kog_postmeta` VALUES (44, 23, '_wp_attachment_metadata', 'a:10:{s:8:\"filesize\";i:23274074;s:9:\"mime_type\";s:9:\"video/mp4\";s:6:\"length\";i:432;s:16:\"length_formatted\";s:4:\"7:12\";s:5:\"width\";i:640;s:6:\"height\";i:360;s:10:\"fileformat\";s:3:\"mp4\";s:10:\"dataformat\";s:9:\"quicktime\";s:5:\"audio\";a:7:{s:10:\"dataformat\";s:3:\"mp4\";s:5:\"codec\";s:19:\"ISO/IEC 14496-3 AAC\";s:11:\"sample_rate\";d:44100;s:8:\"channels\";i:2;s:15:\"bits_per_sample\";i:16;s:8:\"lossless\";b:0;s:11:\"channelmode\";s:6:\"stereo\";}s:17:\"created_timestamp\";i:1554550606;}');
INSERT INTO `kog_postmeta` VALUES (45, 22, '_wp_page_template', 'default');
INSERT INTO `kog_postmeta` VALUES (46, 22, 'views', '1');
INSERT INTO `kog_postmeta` VALUES (47, 28, '_wp_attached_file', '2019/04/耳光乐队十八系列《马主任十八禁》字幕版.mp4');
INSERT INTO `kog_postmeta` VALUES (48, 28, '_wp_attachment_metadata', 'a:10:{s:8:\"filesize\";i:20849288;s:9:\"mime_type\";s:9:\"video/mp4\";s:6:\"length\";i:703;s:16:\"length_formatted\";s:5:\"11:43\";s:5:\"width\";i:256;s:6:\"height\";i:144;s:10:\"fileformat\";s:3:\"mp4\";s:10:\"dataformat\";s:9:\"quicktime\";s:5:\"audio\";a:7:{s:10:\"dataformat\";s:3:\"mp4\";s:5:\"codec\";s:19:\"ISO/IEC 14496-3 AAC\";s:11:\"sample_rate\";d:44100;s:8:\"channels\";i:2;s:15:\"bits_per_sample\";i:16;s:8:\"lossless\";b:0;s:11:\"channelmode\";s:6:\"stereo\";}s:17:\"created_timestamp\";i:-2082844800;}');
INSERT INTO `kog_postmeta` VALUES (49, 28, '_edit_lock', '1556596122:1');
INSERT INTO `kog_postmeta` VALUES (50, 29, '_edit_lock', '1556596616:1');
INSERT INTO `kog_postmeta` VALUES (52, 32, '_wp_attached_file', '2019/04/耳光乐队十八系列《马主任十八禁》字幕版-1.mp4');
INSERT INTO `kog_postmeta` VALUES (53, 32, '_wp_attachment_metadata', 'a:10:{s:8:\"filesize\";i:35871869;s:9:\"mime_type\";s:9:\"video/mp4\";s:6:\"length\";i:703;s:16:\"length_formatted\";s:5:\"11:43\";s:5:\"width\";i:640;s:6:\"height\";i:360;s:10:\"fileformat\";s:3:\"mp4\";s:10:\"dataformat\";s:9:\"quicktime\";s:5:\"audio\";a:7:{s:10:\"dataformat\";s:3:\"mp4\";s:5:\"codec\";s:19:\"ISO/IEC 14496-3 AAC\";s:11:\"sample_rate\";d:44100;s:8:\"channels\";i:2;s:15:\"bits_per_sample\";i:16;s:8:\"lossless\";b:0;s:11:\"channelmode\";s:6:\"stereo\";}s:17:\"created_timestamp\";i:1428644787;}');
INSERT INTO `kog_postmeta` VALUES (55, 29, 'enclosure', 'http://www.victorxys.com/wp-content/uploads/2019/04/耳光乐队十八系列《马主任十八禁》字幕版-1.mp4\n0\nvideo/mp4\n');
INSERT INTO `kog_postmeta` VALUES (59, 37, '_wp_attached_file', '2019/05/无形资产流程梳理-续永胜-20190423.pdf');
INSERT INTO `kog_postmeta` VALUES (62, 40, '_wp_attached_file', '2019/05/IP分配功能逻辑.pdf');
INSERT INTO `kog_postmeta` VALUES (63, 42, '_wp_attached_file', '2019/06/无形资产流程梳理-续永胜-20190619.pdf');
INSERT INTO `kog_postmeta` VALUES (64, 43, '_wp_attached_file', '2019/06/采购验收及上架流程.pdf');
INSERT INTO `kog_postmeta` VALUES (65, 45, '_wp_attached_file', '2019/06/无形资产流程梳理-续永胜-20190627.pdf');
INSERT INTO `kog_postmeta` VALUES (66, 46, '_wp_attached_file', '2019/06/项目预算.pdf');
INSERT INTO `kog_postmeta` VALUES (69, 49, '_wp_attached_file', '2019/07/项目预算-20190705.pdf');
INSERT INTO `kog_postmeta` VALUES (70, 49, '_edit_lock', '1562567855:1');
INSERT INTO `kog_postmeta` VALUES (71, 50, '_wp_attached_file', '2019/07/无形资产流程梳理-续永胜-20190708.pdf');
INSERT INTO `kog_postmeta` VALUES (72, 52, '_wp_attached_file', '2019/07/无形资产流程梳理-续永胜-20190710.pdf');
INSERT INTO `kog_postmeta` VALUES (76, 55, '_wp_attached_file', '2019/07/采购验收及上架流程-20190718.pdf');
INSERT INTO `kog_postmeta` VALUES (77, 56, '_wp_attached_file', '2019/07/采购验收及上架流程-20190718-2.pdf');
INSERT INTO `kog_postmeta` VALUES (79, 59, '_wp_attached_file', '2019/07/采购验收及上架流程-20190724-1.pdf');
INSERT INTO `kog_postmeta` VALUES (81, 61, '_wp_attached_file', '2019/07/采购验收及上架流程-20190726.pdf');
INSERT INTO `kog_postmeta` VALUES (82, 62, '_wp_attached_file', '2019/07/采购验收及上架流程-20190726-1.pdf');
INSERT INTO `kog_postmeta` VALUES (83, 63, '_wp_attached_file', '2019/07/项目关联预算流程-20190729.pdf');
COMMIT;

-- ----------------------------
-- Table structure for kog_posts
-- ----------------------------
DROP TABLE IF EXISTS `kog_posts`;
CREATE TABLE `kog_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_posts
-- ----------------------------
BEGIN;
INSERT INTO `kog_posts` VALUES (1, 1, '2018-11-27 17:21:43', '2018-11-27 09:21:43', '欢迎使用WordPress。这是您的第一篇文章。编辑或删除它，然后开始写作吧！', '世界，您好！', '', 'publish', 'open', 'open', '', 'hello-world', '', '', '2018-11-27 17:21:43', '2018-11-27 09:21:43', '', 0, 'http://65.49.219.230/kog/?p=1', 0, 'post', '', 1);
INSERT INTO `kog_posts` VALUES (2, 1, '2018-11-27 17:21:43', '2018-11-27 09:21:43', '这是示范页面。页面和博客文章不同，它的位置是固定的，通常会在站点导航栏显示。很多用户都创建一个“关于”页面，向访客介绍自己。例如：\n\n<blockquote>欢迎！我白天是个邮递员，晚上就是个有抱负的演员。这是我的博客。我住在天朝的帝都，有条叫做Jack的狗。</blockquote>\n\n……或这个：\n\n<blockquote>XYZ Doohickey公司成立于1971年，自从建立以来，我们一直向社会贡献着优秀doohickies。我们的公司总部位于天朝魔都，有着超过两千名员工，对魔都政府税收有着巨大贡献。</blockquote>\n\n而您，作为一个WordPress用户，我们建议您访问<a href=\"http://65.49.219.230/kog/wp-admin/\">控制板</a>，删除本页面，然后添加您自己的页面。祝您使用愉快！', '示例页面', '', 'publish', 'closed', 'open', '', 'sample-page', '', '', '2018-11-27 17:21:43', '2018-11-27 09:21:43', '', 0, 'http://65.49.219.230/kog/?page_id=2', 0, 'page', '', 0);
INSERT INTO `kog_posts` VALUES (3, 1, '2018-11-27 17:21:43', '2018-11-27 09:21:43', '<h2>Who we are</h2><p>Our website address is: http://65.49.219.230/kog.</p><h2>What personal data we collect and why we collect it</h2><h3>评论</h3><p>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p><p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p><h3>媒体</h3><p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p><h3>Contact forms</h3><h3>Cookies</h3><p>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p><p>If you have an account and you log in to this site, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p><p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p><p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p><h3>Embedded content from other websites</h3><p>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p><p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p><h3>Analytics</h3><h2>Who we share your data with</h2><h2>How long we retain your data</h2><p>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p><p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p><h2>What rights you have over your data</h2><p>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p><h2>Where we send your data</h2><p>Visitor comments may be checked through an automated spam detection service.</p><h2>Your contact information</h2><h2>Additional information</h2><h3>How we protect your data</h3><h3>What data breach procedures we have in place</h3><h3>What third parties we receive data from</h3><h3>What automated decision making and/or profiling we do with user data</h3><h3>Industry regulatory disclosure requirements</h3>', 'Privacy Policy', '', 'draft', 'closed', 'open', '', 'privacy-policy', '', '', '2018-11-27 17:21:43', '2018-11-27 09:21:43', '', 0, 'http://65.49.219.230/kog/?page_id=3', 0, 'page', '', 0);
INSERT INTO `kog_posts` VALUES (14, 1, '2019-04-05 12:30:56', '2019-04-05 04:30:56', '', 'Pexels Videos 1110140', '', 'inherit', 'open', 'closed', '', 'pexels-videos-1110140', '', '', '2019-04-05 13:16:39', '2019-04-05 05:16:39', '', 17, 'http://34.80.179.83/wp-content/uploads/2019/04/Pexels-Videos-1110140.mp4', 0, 'attachment', 'video/mp4', 0);
INSERT INTO `kog_posts` VALUES (15, 1, '2019-04-05 13:03:19', '2019-04-05 05:03:19', '', 'IMG_20180623_182105-EFFECTS', '', 'inherit', 'open', 'closed', '', 'img_20180623_182105-effects', '', '', '2019-04-05 13:24:47', '2019-04-05 05:24:47', '', 18, 'http://34.80.179.83/wp-content/uploads/2019/04/IMG_20180623_182105-EFFECTS.jpg', 0, 'attachment', 'image/jpeg', 0);
INSERT INTO `kog_posts` VALUES (17, 1, '2019-04-05 13:17:27', '2019-04-05 05:17:27', '看看这个视频\r\n\r\n[video width=\"3840\" height=\"2160\" mp4=\"http://34.80.179.83/wp-content/uploads/2019/04/Pexels-Videos-1110140.mp4\"][/video]\r\n\r\n到底怎么样', '', '', 'publish', 'open', 'open', '', '17', '', '', '2019-04-05 13:17:27', '2019-04-05 05:17:27', '', 0, 'http://34.80.179.83/?p=17', 0, 'post', '', 0);
INSERT INTO `kog_posts` VALUES (18, 1, '2019-04-05 13:24:57', '2019-04-05 05:24:57', '123123123\r\n\r\n<img class=\"alignnone size-medium wp-image-15\" src=\"http://www.victorxys.com/wp-content/uploads/2019/04/IMG_20180623_182105-EFFECTS-300x169.jpg\" alt=\"\" width=\"300\" height=\"169\" />\r\n\r\ndsadfadf\r\n\r\n<audio style=\"display: none;\" controls=\"controls\"></audio>', 'test', '', 'publish', 'open', 'open', '', 'test', '', '', '2019-04-05 13:24:57', '2019-04-05 05:24:57', '', 0, 'http://www.victorxys.com/?p=18', 0, 'post', '', 0);
INSERT INTO `kog_posts` VALUES (19, 1, '2019-04-08 11:38:46', '2019-04-08 03:38:46', '最近一次大的升级，增加了Performace版本5%的峰值输出。对于后驱版的，增加了5%的续航。\r\n\r\n同样是Performance，升级前后对比。\r\n\r\n先说结论：加速阶段，前半程升级后的反而慢了一些，但后半程，完全碾压升级前的。\r\n\r\n视频来源与国外玩家：https://www.youtube.com/watch?v=0OcrD4qttDg&amp;t=2s\r\n\r\n如有侵权，请留言我删除。谢谢\r\n\r\n[video width=\"426\" height=\"240\" mp4=\"http://www.victorxys.com/wp-content/uploads/2019/04/PEAK-POWER-INCREASE-TEST-Is-it-really-FASTER.mp4\"][/video]\r\n\r\n<audio style=\"display: none;\" controls=\"controls\"></audio>\r\n\r\n<audio style=\"display: none;\" controls=\"controls\"></audio>', 'Performance 升级了5%的峰值输出，前后差距对比', '', 'publish', 'open', 'open', '', 'performance-%e5%8d%87%e7%ba%a7%e4%ba%865%e7%9a%84%e5%b3%b0%e5%80%bc%e8%be%93%e5%87%ba%ef%bc%8c%e5%89%8d%e5%90%8e%e5%b7%ae%e8%b7%9d%e5%af%b9%e6%af%94', '', '', '2019-04-08 12:10:03', '2019-04-08 04:10:03', '', 0, 'http://www.victorxys.com/?p=19', 0, 'post', '', 0);
INSERT INTO `kog_posts` VALUES (20, 1, '2019-04-08 11:38:11', '2019-04-08 03:38:11', '', 'PEAK POWER INCREASE TEST - Is it really FASTER?', '', 'inherit', 'open', 'closed', '', 'peak-power-increase-test-is-it-really-faster', '', '', '2019-04-08 11:38:26', '2019-04-08 03:38:26', '', 19, 'http://www.victorxys.com/wp-content/uploads/2019/04/PEAK-POWER-INCREASE-TEST-Is-it-really-FASTER.mp4', 0, 'attachment', 'video/mp4', 0);
INSERT INTO `kog_posts` VALUES (21, 1, '2019-04-08 12:09:46', '2019-04-08 04:09:46', '最近一次大的升级，增加了Performace版本5%的峰值输出。对于后驱版的，增加了5%的续航。\r\n\r\n同样是Performance，升级前后对比。\r\n\r\n先说结论：加速阶段，前半程升级后的反而慢了一些，但后半程，完全碾压升级前的。\r\n\r\n视频来源与国外玩家：https://www.youtube.com/watch?v=0OcrD4qttDg&amp;t=2s\r\n\r\n如有侵权，请留言我删除。谢谢\r\n\r\n[video width=\"426\" height=\"240\" mp4=\"http://www.victorxys.com/wp-content/uploads/2019/04/PEAK-POWER-INCREASE-TEST-Is-it-really-FASTER.mp4\"][/video]\r\n\r\n<audio style=\"display: none;\" controls=\"controls\"></audio>\r\n\r\n<audio style=\"display: none;\" controls=\"controls\"></audio>', 'Performance 升级了5%的峰值输出，前后差距对比', '', 'inherit', 'closed', 'closed', '', '19-autosave-v1', '', '', '2019-04-08 12:09:46', '2019-04-08 04:09:46', '', 19, 'http://www.victorxys.com/?p=21', 0, 'revision', '', 0);
INSERT INTO `kog_posts` VALUES (22, 1, '2019-04-08 15:35:13', '2019-04-08 07:35:13', '先说结论：\r\n\r\n经国外玩家测试，有一下几种情况会<strong>触发“哨兵模式”</strong>\r\n\r\n1、有人挨着停在车旁边时——开始记录\r\n\r\n2、近距离在车周围绕行时——开始记录\r\n\r\n3、拍打车辆时——开始记录\r\n\r\n4、如果车门已锁，车窗打开，从里面开车门时——鸣笛警报\r\n\r\n原视频地址：https://www.youtube.com/watch?v=5viFu57qUgs&amp;t=231s\r\n\r\n[video width=\"640\" height=\"360\" mp4=\"http://www.victorxys.com/wp-content/uploads/2019/04/Tesla-sentry-mode.mp4\"][/video]\r\n\r\n<audio style=\"display: none;\" controls=\"controls\"></audio>', '哨兵模式什么时候会被触发？', '', 'publish', 'closed', 'closed', '', '%e5%93%a8%e5%85%b5%e6%a8%a1%e5%bc%8f%e4%bb%80%e4%b9%88%e6%97%b6%e5%80%99%e4%bc%9a%e8%a2%ab%e8%a7%a6%e5%8f%91%ef%bc%9f', '', '', '2019-04-08 15:35:13', '2019-04-08 07:35:13', '', 0, 'http://www.victorxys.com/?page_id=22', 0, 'page', '', 0);
INSERT INTO `kog_posts` VALUES (23, 1, '2019-04-08 15:35:01', '2019-04-08 07:35:01', '', 'Tesla sentry mode', '', 'inherit', 'open', 'closed', '', 'tesla-sentry-mode', '', '', '2019-04-08 15:35:01', '2019-04-08 07:35:01', '', 22, 'http://www.victorxys.com/wp-content/uploads/2019/04/Tesla-sentry-mode.mp4', 0, 'attachment', 'video/mp4', 0);
INSERT INTO `kog_posts` VALUES (28, 1, '2019-04-30 11:49:35', '2019-04-30 03:49:35', '', '耳光乐队十八系列《马主任十八禁》字幕版', '', 'inherit', 'open', 'closed', '', '%e8%80%b3%e5%85%89%e4%b9%90%e9%98%9f%e5%8d%81%e5%85%ab%e7%b3%bb%e5%88%97%e3%80%8a%e9%a9%ac%e4%b8%bb%e4%bb%bb%e5%8d%81%e5%85%ab%e7%a6%81%e3%80%8b%e5%ad%97%e5%b9%95%e7%89%88', '', '', '2019-04-30 11:49:35', '2019-04-30 03:49:35', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/04/耳光乐队十八系列《马主任十八禁》字幕版.mp4', 0, 'attachment', 'video/mp4', 0);
INSERT INTO `kog_posts` VALUES (29, 1, '2019-04-30 11:57:59', '2019-04-30 03:57:59', '<!-- wp:video {\"id\":32} -->\n<figure class=\"wp-block-video\"><video controls src=\"http://www.victorxys.com/wp-content/uploads/2019/04/耳光乐队十八系列《马主任十八禁》字幕版-1.mp4\"></video></figure>\n<!-- /wp:video -->', '耳光乐队十八系列《马主任十八禁》字幕版', '', 'publish', 'open', 'open', '', '%e8%80%b3%e5%85%89%e4%b9%90%e9%98%9f%e5%8d%81%e5%85%ab%e7%b3%bb%e5%88%97%e3%80%8a%e9%a9%ac%e4%b8%bb%e4%bb%bb%e5%8d%81%e5%85%ab%e7%a6%81%e3%80%8b%e5%ad%97%e5%b9%95%e7%89%88', '', '', '2019-04-30 11:59:03', '2019-04-30 03:59:03', '', 0, 'http://www.victorxys.com/?p=29', 0, 'post', '', 0);
INSERT INTO `kog_posts` VALUES (30, 1, '2019-04-30 11:52:32', '2019-04-30 03:52:32', '<!-- wp:video {\"id\":28} -->\n<figure class=\"wp-block-video\"><video controls src=\"http://www.victorxys.com/wp-content/uploads/2019/04/耳光乐队十八系列《马主任十八禁》字幕版.mp4\"></video></figure>\n<!-- /wp:video -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->', '耳光乐队十八系列《马主任十八禁》字幕版', '', 'inherit', 'closed', 'closed', '', '29-revision-v1', '', '', '2019-04-30 11:52:32', '2019-04-30 03:52:32', '', 29, 'http://www.victorxys.com/?p=30', 0, 'revision', '', 0);
INSERT INTO `kog_posts` VALUES (31, 1, '2019-04-30 11:57:59', '2019-04-30 03:57:59', '<!-- wp:video -->\n<figure class=\"wp-block-video\"><video controls src=\"blob:http://www.victorxys.com/65800bd2-f6a2-4fc8-991f-eca55544e41e\"></video></figure>\n<!-- /wp:video -->', '耳光乐队十八系列《马主任十八禁》字幕版', '', 'inherit', 'closed', 'closed', '', '29-revision-v1', '', '', '2019-04-30 11:57:59', '2019-04-30 03:57:59', '', 29, 'http://www.victorxys.com/?p=31', 0, 'revision', '', 0);
INSERT INTO `kog_posts` VALUES (32, 1, '2019-04-30 11:58:11', '2019-04-30 03:58:11', '', '耳光乐队十八系列《马主任十八禁》字幕版', '', 'inherit', 'open', 'closed', '', '%e8%80%b3%e5%85%89%e4%b9%90%e9%98%9f%e5%8d%81%e5%85%ab%e7%b3%bb%e5%88%97%e3%80%8a%e9%a9%ac%e4%b8%bb%e4%bb%bb%e5%8d%81%e5%85%ab%e7%a6%81%e3%80%8b%e5%ad%97%e5%b9%95%e7%89%88-2', '', '', '2019-04-30 11:58:11', '2019-04-30 03:58:11', '', 29, 'http://www.victorxys.com/wp-content/uploads/2019/04/耳光乐队十八系列《马主任十八禁》字幕版-1.mp4', 0, 'attachment', 'video/mp4', 0);
INSERT INTO `kog_posts` VALUES (33, 1, '2019-04-30 11:59:03', '2019-04-30 03:59:03', '<!-- wp:video {\"id\":32} -->\n<figure class=\"wp-block-video\"><video controls src=\"http://www.victorxys.com/wp-content/uploads/2019/04/耳光乐队十八系列《马主任十八禁》字幕版-1.mp4\"></video></figure>\n<!-- /wp:video -->', '耳光乐队十八系列《马主任十八禁》字幕版', '', 'inherit', 'closed', 'closed', '', '29-revision-v1', '', '', '2019-04-30 11:59:03', '2019-04-30 03:59:03', '', 29, 'http://www.victorxys.com/?p=33', 0, 'revision', '', 0);
INSERT INTO `kog_posts` VALUES (37, 1, '2019-05-23 11:10:52', '2019-05-23 03:10:52', '', '无形资产流程梳理-续永胜-20190423', '', 'inherit', 'open', 'closed', '', '%e6%97%a0%e5%bd%a2%e8%b5%84%e4%ba%a7%e6%b5%81%e7%a8%8b%e6%a2%b3%e7%90%86-%e7%bb%ad%e6%b0%b8%e8%83%9c-20190423', '', '', '2019-05-23 11:10:52', '2019-05-23 03:10:52', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/05/无形资产流程梳理-续永胜-20190423.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (40, 1, '2019-05-31 16:50:22', '2019-05-31 08:50:22', '', 'IP分配功能逻辑', '', 'inherit', 'open', 'closed', '', 'ip%e5%88%86%e9%85%8d%e5%8a%9f%e8%83%bd%e9%80%bb%e8%be%91', '', '', '2019-05-31 16:50:22', '2019-05-31 08:50:22', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/05/IP分配功能逻辑.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (42, 1, '2019-06-19 16:14:14', '2019-06-19 08:14:14', '', '无形资产流程梳理-续永胜-20190619', '', 'inherit', 'open', 'closed', '', '%e6%97%a0%e5%bd%a2%e8%b5%84%e4%ba%a7%e6%b5%81%e7%a8%8b%e6%a2%b3%e7%90%86-%e7%bb%ad%e6%b0%b8%e8%83%9c-20190619', '', '', '2019-06-19 16:14:14', '2019-06-19 08:14:14', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/06/无形资产流程梳理-续永胜-20190619.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (43, 1, '2019-06-25 17:35:26', '2019-06-25 09:35:26', '', '采购验收及上架流程', '', 'inherit', 'open', 'closed', '', '%e9%87%87%e8%b4%ad%e9%aa%8c%e6%94%b6%e5%8f%8a%e4%b8%8a%e6%9e%b6%e6%b5%81%e7%a8%8b', '', '', '2019-06-25 17:35:26', '2019-06-25 09:35:26', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/06/采购验收及上架流程.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (45, 1, '2019-06-27 18:15:40', '2019-06-27 10:15:40', '', '无形资产流程梳理-续永胜-20190627', '', 'inherit', 'open', 'closed', '', '%e6%97%a0%e5%bd%a2%e8%b5%84%e4%ba%a7%e6%b5%81%e7%a8%8b%e6%a2%b3%e7%90%86-%e7%bb%ad%e6%b0%b8%e8%83%9c-20190627', '', '', '2019-06-27 18:15:40', '2019-06-27 10:15:40', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/06/无形资产流程梳理-续永胜-20190627.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (46, 1, '2019-06-27 18:15:40', '2019-06-27 10:15:40', '', '项目预算', '', 'inherit', 'open', 'closed', '', '%e9%a1%b9%e7%9b%ae%e9%a2%84%e7%ae%97', '', '', '2019-06-27 18:15:40', '2019-06-27 10:15:40', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/06/项目预算.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (49, 1, '2019-07-08 11:33:52', '2019-07-08 03:33:52', '', '项目预算-20190705', '', 'inherit', 'open', 'closed', '', '%e9%a1%b9%e7%9b%ae%e9%a2%84%e7%ae%97-20190705', '', '', '2019-07-08 11:33:52', '2019-07-08 03:33:52', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/项目预算-20190705.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (50, 1, '2019-07-10 14:42:30', '2019-07-10 06:42:30', '', '无形资产流程梳理-续永胜-20190708', '', 'inherit', 'open', 'closed', '', '%e6%97%a0%e5%bd%a2%e8%b5%84%e4%ba%a7%e6%b5%81%e7%a8%8b%e6%a2%b3%e7%90%86-%e7%bb%ad%e6%b0%b8%e8%83%9c-20190708', '', '', '2019-07-10 14:42:30', '2019-07-10 06:42:30', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/无形资产流程梳理-续永胜-20190708.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (52, 1, '2019-07-16 09:28:18', '2019-07-16 01:28:18', '', '无形资产流程梳理-续永胜-20190710', '', 'inherit', 'open', 'closed', '', '%e6%97%a0%e5%bd%a2%e8%b5%84%e4%ba%a7%e6%b5%81%e7%a8%8b%e6%a2%b3%e7%90%86-%e7%bb%ad%e6%b0%b8%e8%83%9c-20190710', '', '', '2019-07-16 09:28:18', '2019-07-16 01:28:18', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/无形资产流程梳理-续永胜-20190710.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (55, 1, '2019-07-18 14:53:49', '2019-07-18 06:53:49', '', '采购验收及上架流程-20190718', '', 'inherit', 'open', 'closed', '', '%e9%87%87%e8%b4%ad%e9%aa%8c%e6%94%b6%e5%8f%8a%e4%b8%8a%e6%9e%b6%e6%b5%81%e7%a8%8b-20190718', '', '', '2019-07-18 14:53:49', '2019-07-18 06:53:49', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/采购验收及上架流程-20190718.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (56, 1, '2019-07-18 14:54:06', '2019-07-18 06:54:06', '', '采购验收及上架流程-20190718-2', '', 'inherit', 'open', 'closed', '', '%e9%87%87%e8%b4%ad%e9%aa%8c%e6%94%b6%e5%8f%8a%e4%b8%8a%e6%9e%b6%e6%b5%81%e7%a8%8b-20190718-2', '', '', '2019-07-18 14:54:06', '2019-07-18 06:54:06', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/采购验收及上架流程-20190718-2.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (59, 1, '2019-07-25 09:30:14', '2019-07-25 01:30:14', '', '采购验收及上架流程-20190724', '', 'inherit', 'open', 'closed', '', '%e9%87%87%e8%b4%ad%e9%aa%8c%e6%94%b6%e5%8f%8a%e4%b8%8a%e6%9e%b6%e6%b5%81%e7%a8%8b-20190724-2', '', '', '2019-07-25 09:30:14', '2019-07-25 01:30:14', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/采购验收及上架流程-20190724-1.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (61, 1, '2019-07-26 11:34:37', '2019-07-26 03:34:37', '', '采购验收及上架流程-20190726', '', 'inherit', 'open', 'closed', '', '%e9%87%87%e8%b4%ad%e9%aa%8c%e6%94%b6%e5%8f%8a%e4%b8%8a%e6%9e%b6%e6%b5%81%e7%a8%8b-20190726', '', '', '2019-07-26 11:34:37', '2019-07-26 03:34:37', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/采购验收及上架流程-20190726.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (62, 1, '2019-07-26 11:35:48', '2019-07-26 03:35:48', '', '采购验收及上架流程-20190726', '', 'inherit', 'open', 'closed', '', '%e9%87%87%e8%b4%ad%e9%aa%8c%e6%94%b6%e5%8f%8a%e4%b8%8a%e6%9e%b6%e6%b5%81%e7%a8%8b-20190726-2', '', '', '2019-07-26 11:35:48', '2019-07-26 03:35:48', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/采购验收及上架流程-20190726-1.pdf', 0, 'attachment', 'application/pdf', 0);
INSERT INTO `kog_posts` VALUES (63, 1, '2019-07-29 10:36:21', '2019-07-29 02:36:21', '', '项目关联预算流程-20190729', '', 'inherit', 'open', 'closed', '', '%e9%a1%b9%e7%9b%ae%e5%85%b3%e8%81%94%e9%a2%84%e7%ae%97%e6%b5%81%e7%a8%8b-20190729', '', '', '2019-07-29 10:36:21', '2019-07-29 02:36:21', '', 0, 'http://www.victorxys.com/wp-content/uploads/2019/07/项目关联预算流程-20190729.pdf', 0, 'attachment', 'application/pdf', 0);
COMMIT;

-- ----------------------------
-- Table structure for kog_term_relationships
-- ----------------------------
DROP TABLE IF EXISTS `kog_term_relationships`;
CREATE TABLE `kog_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_term_relationships
-- ----------------------------
BEGIN;
INSERT INTO `kog_term_relationships` VALUES (1, 1, 0);
INSERT INTO `kog_term_relationships` VALUES (17, 1, 0);
INSERT INTO `kog_term_relationships` VALUES (18, 1, 0);
INSERT INTO `kog_term_relationships` VALUES (19, 1, 0);
INSERT INTO `kog_term_relationships` VALUES (19, 5, 0);
INSERT INTO `kog_term_relationships` VALUES (19, 6, 0);
INSERT INTO `kog_term_relationships` VALUES (29, 1, 0);
COMMIT;

-- ----------------------------
-- Table structure for kog_term_taxonomy
-- ----------------------------
DROP TABLE IF EXISTS `kog_term_taxonomy`;
CREATE TABLE `kog_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_term_taxonomy
-- ----------------------------
BEGIN;
INSERT INTO `kog_term_taxonomy` VALUES (1, 1, 'category', '', 0, 5);
INSERT INTO `kog_term_taxonomy` VALUES (2, 2, 'category', '', 0, 0);
INSERT INTO `kog_term_taxonomy` VALUES (3, 3, 'category', '', 0, 0);
INSERT INTO `kog_term_taxonomy` VALUES (4, 4, 'category', '', 0, 0);
INSERT INTO `kog_term_taxonomy` VALUES (5, 5, 'category', '', 0, 1);
INSERT INTO `kog_term_taxonomy` VALUES (6, 6, 'post_format', '', 0, 1);
COMMIT;

-- ----------------------------
-- Table structure for kog_termmeta
-- ----------------------------
DROP TABLE IF EXISTS `kog_termmeta`;
CREATE TABLE `kog_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for kog_terms
-- ----------------------------
DROP TABLE IF EXISTS `kog_terms`;
CREATE TABLE `kog_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_terms
-- ----------------------------
BEGIN;
INSERT INTO `kog_terms` VALUES (1, '未分类', 'uncategorized', 0);
INSERT INTO `kog_terms` VALUES (2, '正经事', '%e6%ad%a3%e7%bb%8f%e4%ba%8b', 0);
INSERT INTO `kog_terms` VALUES (3, '搞笑', '%e6%90%9e%e7%ac%91', 0);
INSERT INTO `kog_terms` VALUES (4, '科技', '%e7%a7%91%e6%8a%80', 0);
INSERT INTO `kog_terms` VALUES (5, 'Tesla', 'tesla', 0);
INSERT INTO `kog_terms` VALUES (6, 'post-format-video', 'post-format-video', 0);
COMMIT;

-- ----------------------------
-- Table structure for kog_usermeta
-- ----------------------------
DROP TABLE IF EXISTS `kog_usermeta`;
CREATE TABLE `kog_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_usermeta
-- ----------------------------
BEGIN;
INSERT INTO `kog_usermeta` VALUES (1, 1, 'nickname', 'victor');
INSERT INTO `kog_usermeta` VALUES (2, 1, 'first_name', '');
INSERT INTO `kog_usermeta` VALUES (3, 1, 'last_name', '');
INSERT INTO `kog_usermeta` VALUES (4, 1, 'description', '');
INSERT INTO `kog_usermeta` VALUES (5, 1, 'rich_editing', 'true');
INSERT INTO `kog_usermeta` VALUES (6, 1, 'syntax_highlighting', 'true');
INSERT INTO `kog_usermeta` VALUES (7, 1, 'comment_shortcuts', 'false');
INSERT INTO `kog_usermeta` VALUES (8, 1, 'admin_color', 'fresh');
INSERT INTO `kog_usermeta` VALUES (9, 1, 'use_ssl', '0');
INSERT INTO `kog_usermeta` VALUES (10, 1, 'show_admin_bar_front', 'true');
INSERT INTO `kog_usermeta` VALUES (11, 1, 'locale', '');
INSERT INTO `kog_usermeta` VALUES (12, 1, 'kog_capabilities', 'a:1:{s:13:\"administrator\";b:1;}');
INSERT INTO `kog_usermeta` VALUES (13, 1, 'kog_user_level', '10');
INSERT INTO `kog_usermeta` VALUES (14, 1, 'dismissed_wp_pointers', 'wp496_privacy,theme_editor_notice');
INSERT INTO `kog_usermeta` VALUES (15, 1, 'show_welcome_panel', '1');
INSERT INTO `kog_usermeta` VALUES (16, 1, 'session_tokens', 'a:1:{s:64:\"f742a1da6ec8c1ac3c4d1446e1a08c9630beeeaf0a8ae6541b650dff4d6cd235\";a:4:{s:10:\"expiration\";i:1565764212;s:2:\"ip\";s:14:\"162.158.118.61\";s:2:\"ua\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.142 Safari/537.36\";s:5:\"login\";i:1565591412;}}');
INSERT INTO `kog_usermeta` VALUES (17, 1, 'kog_dashboard_quick_press_last_post_id', '64');
INSERT INTO `kog_usermeta` VALUES (18, 1, 'community-events-location', 'a:1:{s:2:\"ip\";s:11:\"164.52.12.0\";}');
INSERT INTO `kog_usermeta` VALUES (19, 2, 'nickname', 'allin');
INSERT INTO `kog_usermeta` VALUES (20, 2, 'first_name', 'allin');
INSERT INTO `kog_usermeta` VALUES (21, 2, 'last_name', '');
INSERT INTO `kog_usermeta` VALUES (22, 2, 'description', '');
INSERT INTO `kog_usermeta` VALUES (23, 2, 'rich_editing', 'true');
INSERT INTO `kog_usermeta` VALUES (24, 2, 'syntax_highlighting', 'true');
INSERT INTO `kog_usermeta` VALUES (25, 2, 'comment_shortcuts', 'false');
INSERT INTO `kog_usermeta` VALUES (26, 2, 'admin_color', 'fresh');
INSERT INTO `kog_usermeta` VALUES (27, 2, 'use_ssl', '0');
INSERT INTO `kog_usermeta` VALUES (28, 2, 'show_admin_bar_front', 'true');
INSERT INTO `kog_usermeta` VALUES (29, 2, 'locale', '');
INSERT INTO `kog_usermeta` VALUES (30, 2, 'kog_capabilities', 'a:1:{s:10:\"subscriber\";b:1;}');
INSERT INTO `kog_usermeta` VALUES (31, 2, 'kog_user_level', '0');
INSERT INTO `kog_usermeta` VALUES (32, 2, 'dismissed_wp_pointers', 'wp496_privacy');
INSERT INTO `kog_usermeta` VALUES (33, 2, 'session_tokens', 'a:22:{s:64:\"51bf3f9508c1071df7c7ea1d866497aa1ddbf582839c6703b487740843d9a015\";a:4:{s:10:\"expiration\";i:1573539618;s:2:\"ip\";s:13:\"162.158.159.8\";s:2:\"ua\";s:124:\"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3198.0 Safari/537.36 OPR/49.0.2711.0\";s:5:\"login\";i:1572330018;}s:64:\"d4fc13084a37f6a3f24a802ca58577b15f57ee6cc444fb7f134aaac9e6241890\";a:4:{s:10:\"expiration\";i:1573818572;s:2:\"ip\";s:14:\"141.101.76.110\";s:2:\"ua\";s:124:\"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3198.0 Safari/537.36 OPR/49.0.2711.0\";s:5:\"login\";i:1572608972;}s:64:\"bb756e572ebf32a4d8aef94a9c113de21b25ec4c8fa644e45d1ed0d5f3586aab\";a:4:{s:10:\"expiration\";i:1574054521;s:2:\"ip\";s:14:\"172.68.238.230\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572844921;}s:64:\"111582d6954b900f1d4f67f310ffe29aeb9900129b805807c5dd2d81cd311758\";a:4:{s:10:\"expiration\";i:1574055687;s:2:\"ip\";s:14:\"162.158.63.104\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572846087;}s:64:\"772e6c07de7fded77a809d484b3f0670b58f4cc2240fe98563b34a478932bcf2\";a:4:{s:10:\"expiration\";i:1574055687;s:2:\"ip\";s:14:\"173.245.52.167\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572846087;}s:64:\"4489b552f87e2359b595be2d6c2f28210c3c15a802b338bf0069d2e8fe3e9741\";a:4:{s:10:\"expiration\";i:1574056972;s:2:\"ip\";s:14:\"173.245.52.165\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572847372;}s:64:\"8a8566150b15df5941def9701eb6378503ed73192787bf6b474832a4c77d0493\";a:4:{s:10:\"expiration\";i:1574058739;s:2:\"ip\";s:14:\"141.101.98.212\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572849139;}s:64:\"760a938fecdc32145742a9fa9d2695c7b22ca165a6ed07eefac623772a3e9483\";a:4:{s:10:\"expiration\";i:1574076226;s:2:\"ip\";s:14:\"141.101.99.249\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572866626;}s:64:\"e9c928ccbc57e5bd587c75668d640c158ed21ccdb1b376e0c593e2db80e63ea5\";a:4:{s:10:\"expiration\";i:1574109323;s:2:\"ip\";s:15:\"162.158.154.221\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572899723;}s:64:\"13b9593d00e8b5287dd576a64e4755697007cbd6a5e717c18e8fc322cafd1339\";a:4:{s:10:\"expiration\";i:1574112744;s:2:\"ip\";s:14:\"162.158.158.95\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572903144;}s:64:\"00e4e9f42ce44bb30b8af6b28c1154d87ef4521168ed36e4effa104d92cae453\";a:4:{s:10:\"expiration\";i:1574160631;s:2:\"ip\";s:12:\"172.69.55.98\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1572951031;}s:64:\"c383872162bd3fec1ef1225e08254e96271d9b8c31866c34ca17976abd26eb76\";a:4:{s:10:\"expiration\";i:1574253623;s:2:\"ip\";s:15:\"162.158.158.139\";s:2:\"ua\";s:57:\"Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14\";s:5:\"login\";i:1573044023;}s:64:\"d9aaec29246621777dc8b84a8a26e477fa4ff07fb0b0d84f0877a1ed6c33f3bd\";a:4:{s:10:\"expiration\";i:1574307938;s:2:\"ip\";s:12:\"172.69.55.98\";s:2:\"ua\";s:60:\"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KKman2.0)\";s:5:\"login\";i:1573098338;}s:64:\"621b084867f4cca17210c8cb0c496baa18e654c83679e07c3a02b877716c830b\";a:4:{s:10:\"expiration\";i:1574307941;s:2:\"ip\";s:13:\"141.101.77.66\";s:2:\"ua\";s:60:\"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KKman2.0)\";s:5:\"login\";i:1573098341;}s:64:\"0eaeb828285195535235c78e840b539a4c4c0e6f8daaafd1646dceff8adf8ea3\";a:4:{s:10:\"expiration\";i:1574492535;s:2:\"ip\";s:12:\"172.69.54.19\";s:2:\"ua\";s:124:\"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3198.0 Safari/537.36 OPR/49.0.2711.0\";s:5:\"login\";i:1573282935;}s:64:\"5580229d68104e35b85d1efb0d751b003351d6695050105f3eb9750825880103\";a:4:{s:10:\"expiration\";i:1573634297;s:2:\"ip\";s:15:\"162.158.142.110\";s:2:\"ua\";s:76:\"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0\";s:5:\"login\";i:1573461497;}s:64:\"5bb848d9fa751efeec043530b0a8dd46cd2f57233cd35c6c34500e22ef7810ce\";a:4:{s:10:\"expiration\";i:1573634298;s:2:\"ip\";s:14:\"162.158.142.80\";s:2:\"ua\";s:76:\"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0\";s:5:\"login\";i:1573461498;}s:64:\"6308f122c8b83bc950f3c5fdf4e6f287650234f73f004688d791999115149ec0\";a:4:{s:10:\"expiration\";i:1573642435;s:2:\"ip\";s:14:\"162.158.238.32\";s:2:\"ua\";s:100:\"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36\";s:5:\"login\";i:1573469635;}s:64:\"bf0ab6f6094b0bda67e937a1cb791c447a995e41c91676373cbe9a661aaaf532\";a:4:{s:10:\"expiration\";i:1573642447;s:2:\"ip\";s:15:\"162.158.154.191\";s:2:\"ua\";s:112:\"Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36\";s:5:\"login\";i:1573469647;}s:64:\"2f21b4fea429b4312015536d9c79cfa40e1cd198cd30833c20b41d503860373d\";a:4:{s:10:\"expiration\";i:1574741401;s:2:\"ip\";s:14:\"162.158.155.36\";s:2:\"ua\";s:34:\"Opera/9.01 (Windows NT 5.1; U; en)\";s:5:\"login\";i:1573531801;}s:64:\"cc4c68c1d4abfa8e502f087f7d4b30e94ba471821a9d4feb4a16f39e815468c2\";a:4:{s:10:\"expiration\";i:1574743812;s:2:\"ip\";s:14:\"162.158.159.18\";s:2:\"ua\";s:124:\"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3198.0 Safari/537.36 OPR/49.0.2711.0\";s:5:\"login\";i:1573534212;}s:64:\"cdc00c7eca44c60614fce86645d45b8b3fd426eb277f0f8b9a1e7b88de746cb1\";a:4:{s:10:\"expiration\";i:1574747456;s:2:\"ip\";s:15:\"162.158.158.249\";s:2:\"ua\";s:124:\"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3198.0 Safari/537.36 OPR/49.0.2711.0\";s:5:\"login\";i:1573537856;}}');
INSERT INTO `kog_usermeta` VALUES (34, 2, 'community-events-location', 'a:1:{s:2:\"ip\";s:13:\"185.117.215.0\";}');
INSERT INTO `kog_usermeta` VALUES (35, 1, 'kog_user-settings', 'post_dfw=on&libraryContent=browse');
INSERT INTO `kog_usermeta` VALUES (36, 1, 'kog_user-settings-time', '1554708909');
INSERT INTO `kog_usermeta` VALUES (37, 2, 'kog_user-settings', 'deleted');
INSERT INTO `kog_usermeta` VALUES (38, 2, 'kog_user-settings-time', '1560123254');
COMMIT;

-- ----------------------------
-- Table structure for kog_users
-- ----------------------------
DROP TABLE IF EXISTS `kog_users`;
CREATE TABLE `kog_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of kog_users
-- ----------------------------
BEGIN;
INSERT INTO `kog_users` VALUES (1, 'victor', '$P$B6fC0Wsdg12YkVSfFn/29UBozby7/V0', 'victor', 'victor.xys@gmail.com', '', '2018-11-27 09:21:43', '', 0, 'victor');
INSERT INTO `kog_users` VALUES (2, 'allin', '$P$Bi16ri1iGR/hJVicWO1oZNgpmpm5dv0', 'allin', 'allin@victorxys.com', '', '2019-03-19 08:23:52', '1552983832:$P$BGc7ZTTJYCkTLSQBaCFxZbZvj7nbjZ/', 0, '站点默认');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
