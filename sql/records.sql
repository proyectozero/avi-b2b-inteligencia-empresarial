/*
 Navicat Premium Dump SQL

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 100421 (10.4.21-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : avib2b_chat

 Target Server Type    : MySQL
 Target Server Version : 100421 (10.4.21-MariaDB)
 File Encoding         : 65001

 Date: 31/05/2025 16:17:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for records
-- ----------------------------
DROP TABLE IF EXISTS `records`;
CREATE TABLE `records` (
  `RecordsId` int(11) NOT NULL AUTO_INCREMENT,
  `File` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `Description` text COLLATE utf8mb4_bin NOT NULL,
  `UsersId` int(11) NOT NULL,
  `SessionId` varchar(25) COLLATE utf8mb4_bin NOT NULL,
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` int(11) DEFAULT 1,
  PRIMARY KEY (`RecordsId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of records
-- ----------------------------
BEGIN;
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
