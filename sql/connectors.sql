/*
 Navicat Premium Dump SQL

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 100421 (10.4.21-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : avib2b_app

 Target Server Type    : MySQL
 Target Server Version : 100421 (10.4.21-MariaDB)
 File Encoding         : 65001

 Date: 04/06/2025 17:13:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for connectors
-- ----------------------------
DROP TABLE IF EXISTS `connectors`;
CREATE TABLE `connectors` (
  `ConnectorsId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `Type` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `Status` enum('activo','inactivo') COLLATE utf8mb4_bin NOT NULL,
  `LastConnection` datetime DEFAULT NULL,
  PRIMARY KEY (`ConnectorsId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of connectors
-- ----------------------------
BEGIN;
INSERT INTO `connectors` (`ConnectorsId`, `Description`, `Type`, `Status`, `LastConnection`) VALUES (4, 'test4', 'Google Sheets', 'activo', NULL);
INSERT INTO `connectors` (`ConnectorsId`, `Description`, `Type`, `Status`, `LastConnection`) VALUES (5, 'test4', 'Google Sheets', 'activo', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
