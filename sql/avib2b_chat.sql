/*
 Navicat Premium Data Transfer

 Source Server         : avib2b
 Source Server Type    : MySQL
 Source Server Version : 100529 (10.5.29-MariaDB)
 Source Host           : 65.109.88.87:3306
 Source Schema         : avib2b_chat

 Target Server Type    : MySQL
 Target Server Version : 100529 (10.5.29-MariaDB)
 File Encoding         : 65001

 Date: 19/05/2025 12:02:45
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of customers
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for customers_users
-- ----------------------------
DROP TABLE IF EXISTS `customers_users`;
CREATE TABLE `customers_users` (
  `CustomersUsersId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CustomersId` int(11) NOT NULL,
  `UsersId` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`CustomersUsersId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of customers_users
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
  `SSOId` varchar(255) NOT NULL,
  `Status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`UsersId`,`SSOId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (1, 'heroeskq3@gmail.com', 'Herbert Poveda', 'Herbert', 'Poveda', 'https://lh3.googleusercontent.com/a/ACg8ocLISurEW2Bp2Nb5BkiPhvrs3-yjo_dbc74z6kj-01BELaSFj0tGww=s96-c', 'google', '102061386792578744392', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (2, 'povedah@outlook.com', 'Herbert Poveda', 'Herbert', 'Poveda', 'https://lh3.googleusercontent.com/a/ACg8ocIjiLyMIs_9q-zJLZajy0KMoNm4DIruRwei_iCiGIaUu8Fz9B0=s96-c', 'google', '104314526341117079384', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (3, 'herbertpovedah@gmail.com', 'Herbert Poveda H.', 'Herbert', 'Poveda H.', 'https://lh3.googleusercontent.com/a/ACg8ocKSJzW_3Xh_1v3-HNyuHhoFwe_dTCpvW6zzSS6dTHZ-K9M-8G0=s96-c', 'google', '112342553220831371894', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (4, 'hpoveda@loveavi.com', 'Herbert Poveda', 'Herbert', 'Poveda', 'https://lh3.googleusercontent.com/a/ACg8ocKXh02mU4-pkdYiUZrpfyQcqryjQ4Bi8jz0cYqChUUaMLsmRg=s96-c', 'google', '109907688733986680663', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (5, 'chachan1820@gmail.com', 'chAchAn', 'chAchAn', '', 'https://lh3.googleusercontent.com/a/ACg8ocJfHHgX9-c0xID0jy8-nZa9qgEsjVtfYOjEt1kLxZW-zgaUILs=s96-c', 'google', '109131235877758359578', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (6, 'adrymj15@gmail.com', 'Adriana Mora Jiménez', 'Adriana', 'Mora Jiménez', 'https://lh3.googleusercontent.com/a/ACg8ocKJtphH4rYVm6hKLGl62neNLQ8rI4a-r4GdY_KIEwP5nRoaZsUaUQ=s96-c', 'google', '100407513945357972140', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (7, 'keylor2512@gmail.com', 'Keylor Mendez', 'Keylor', 'Mendez', 'https://lh3.googleusercontent.com/a/ACg8ocIT16rjeXbpy1ipAFzCcJaH6pS1l7Odtce3CPNzW4WqygFjLQ=s96-c', 'google', '101077806507112448195', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (8, 'gabosa0721@gmail.com', 'Johan Des', 'Johan', 'Des', 'https://lh3.googleusercontent.com/a/ACg8ocJk8SC884FzrEdVHMkOIiTE2K-n5ZAgEX3RWPEbyVV_-iYNsa5M=s96-c', 'google', '100407094150554016495', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (9, 'sistemasavi.com@gmail.com', 'Sistemas AVI', 'Sistemas', 'AVI', 'https://lh3.googleusercontent.com/a/ACg8ocINJ2_I7l-UjCalc0Sm9UwCcIDoId-NiEXfNX9YiQEI9nGOWA=s96-c', 'google', '101998724082290757392', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (10, 'pruebaswebavi@gmail.com', 'pruebaswebavi@gmail.com', '', '', 'https://lh3.googleusercontent.com/a-/ALV-UjVdDCWxpUK8HI60shLSBNYv1-kmWwwUJinTC-PgIpt6JgKv5A=s96-c', 'google', '102334625327820338963', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (11, 'crisviti4@gmail.com', 'Cristopher Romero', 'Cristopher', 'Romero', 'https://lh3.googleusercontent.com/a/ACg8ocKKUrsW7eveB_tY8BikwGR50DhYVhzLEIFbC0niXCisI8oT7XTYdQ=s96-c', 'google', '111246578862021379753', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (12, 'emmanuel.aviles.s@gmail.com', 'Emmanuel Aviles Salas', 'Emmanuel', 'Aviles Salas', 'https://lh3.googleusercontent.com/a/ACg8ocJ-H6-5bKD5BlIv0FYKMVnmAnxclA8i_g_tGSZIzX9-OL8xhOCu=s96-c', 'google', '110183030786139829611', '1');
INSERT INTO `users` (`UsersId`, `Email`, `FullName`, `FirstName`, `LastName`, `Picture`, `SSOMethod`, `SSOId`, `Status`) VALUES (13, 'josuemendezfonseca@gmail.com', 'Josué Méndez', 'Josué', 'Méndez', 'https://lh3.googleusercontent.com/a/ACg8ocLDwHN5bmVnEAZuVlHYG3QQ_lFi7_IsBr9mA69-_R9heQ277L-XAg=s96-c', 'google', '105774094227374077392', '1');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
