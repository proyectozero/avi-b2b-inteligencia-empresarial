/*
 Navicat Premium Dump SQL

 Source Server         : avib2b_new
 Source Server Type    : MySQL
 Source Server Version : 101111 (10.11.11-MariaDB)
 Source Host           : 135.181.180.109:3306
 Source Schema         : avib2b_appdev

 Target Server Type    : MySQL
 Target Server Version : 101111 (10.11.11-MariaDB)
 File Encoding         : 65001

 Date: 31/05/2025 16:18:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `CustomersId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  PRIMARY KEY (`CustomersId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of customers
-- ----------------------------
BEGIN;
INSERT INTO `customers` (`CustomersId`, `Name`, `Status`) VALUES (1, 'AVI', 1);
INSERT INTO `customers` (`CustomersId`, `Name`, `Status`) VALUES (2, 'TEST', 1);
COMMIT;

-- ----------------------------
-- Table structure for customers_users
-- ----------------------------
DROP TABLE IF EXISTS `customers_users`;
CREATE TABLE `customers_users` (
  `CustomersUsersId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CustomersId` int(11) NOT NULL,
  `UsersId` int(11) NOT NULL,
  `Default` int(11) DEFAULT NULL,
  `Status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`CustomersUsersId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of customers_users
-- ----------------------------
BEGIN;
INSERT INTO `customers_users` (`CustomersUsersId`, `CustomersId`, `UsersId`, `Default`, `Status`) VALUES (4, 2, 2, NULL, 1);
INSERT INTO `customers_users` (`CustomersUsersId`, `CustomersId`, `UsersId`, `Default`, `Status`) VALUES (7, 1, 3, NULL, 2);
INSERT INTO `customers_users` (`CustomersUsersId`, `CustomersId`, `UsersId`, `Default`, `Status`) VALUES (8, 2, 4, NULL, 1);
INSERT INTO `customers_users` (`CustomersUsersId`, `CustomersId`, `UsersId`, `Default`, `Status`) VALUES (9, 1, 8, NULL, 2);
INSERT INTO `customers_users` (`CustomersUsersId`, `CustomersId`, `UsersId`, `Default`, `Status`) VALUES (10, 2, 8, NULL, 1);
COMMIT;

-- ----------------------------
-- Table structure for myavi
-- ----------------------------
DROP TABLE IF EXISTS `myavi`;
CREATE TABLE `myavi` (
  `MyaviId` int(11) NOT NULL AUTO_INCREMENT,
  `File` varchar(255) NOT NULL,
  `MaxToken` int(11) DEFAULT 1000,
  `Temperature` decimal(5,1) DEFAULT NULL,
  `UsersId` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`MyaviId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of myavi
-- ----------------------------
BEGIN;
INSERT INTO `myavi` (`MyaviId`, `File`, `MaxToken`, `Temperature`, `UsersId`, `created_at`, `updated_at`) VALUES (2, 'chatGPTAVI.txt', 300, 0.6, 10, '2025-05-29 19:29:54', '2025-05-30 08:10:17');
INSERT INTO `myavi` (`MyaviId`, `File`, `MaxToken`, `Temperature`, `UsersId`, `created_at`, `updated_at`) VALUES (3, 'chatGPTAVI.txt', 300, 0.6, 11, '2025-05-30 08:18:50', '2025-05-30 08:42:37');
COMMIT;

-- ----------------------------
-- Table structure for records
-- ----------------------------
DROP TABLE IF EXISTS `records`;
CREATE TABLE `records` (
  `RecordsId` int(11) NOT NULL AUTO_INCREMENT,
  `File` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `UsersId` int(11) NOT NULL,
  `SessionId` varchar(25) NOT NULL,
  `DateCreate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` int(11) DEFAULT 1,
  PRIMARY KEY (`RecordsId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of records
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `UsersId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) DEFAULT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Picture` varchar(255) DEFAULT NULL,
  `SSOMethod` varchar(50) DEFAULT NULL,
  `SSOId` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `Debug` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`UsersId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`, `Debug`) VALUES (10, 'josuemendezfonseca@gmail.com', 'JosuÃ© MÃ©ndez', 'JosuÃ©', 'MÃ©ndez', 'https://lh3.googleusercontent.com/a/ACg8ocLDwHN5bmVnEAZuVlHYG3QQ_lFi7_IsBr9mA69-_R9heQ277L-XAg=s96-c', 'google', '105774094227374077392', '1', 0);
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`, `Debug`) VALUES (11, 'heroeskq3@gmail.com', 'Herbert Poveda', 'Herbert', 'Poveda', 'https://lh3.googleusercontent.com/a/ACg8ocLISurEW2Bp2Nb5BkiPhvrs3-yjo_dbc74z6kj-01BELaSFj0tGww=s96-c', 'google', '102061386792578744392', '1', 1);
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`, `Debug`) VALUES (12, 'hpoveda@loveavi.com', 'Herbert Poveda', 'Herbert', 'Poveda', 'https://lh3.googleusercontent.com/a/ACg8ocKXh02mU4-pkdYiUZrpfyQcqryjQ4Bi8jz0cYqChUUaMLsmRg=s96-c', 'google', '109907688733986680663', '1', 0);
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`, `Debug`) VALUES (13, 'leandrocg10@gmail.com', 'Leandro Camacho', 'Leandro', 'Camacho', 'https://lh3.googleusercontent.com/a/ACg8ocI85jb1l6zSSvUO6yUnADYF_v4mmfy4trlOt_f7kNGkY-YlL0-uoA=s96-c', 'google', '102384364943160259842', '1', 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
