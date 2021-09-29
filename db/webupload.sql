-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for webupload
DROP DATABASE IF EXISTS `webupload`;
CREATE DATABASE IF NOT EXISTS `webupload` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `webupload`;

-- Dumping structure for table webupload.auth
DROP TABLE IF EXISTS `auth`;
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUrl` varchar(255) NOT NULL,
  `idUser` int(11) NOT NULL,
  `date_expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`idUrl`),
  KEY `FK_auth_users` (`idUser`),
  CONSTRAINT `FK_auth_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for event webupload.delete_auth
DROP EVENT IF EXISTS `delete_auth`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` EVENT `delete_auth` ON SCHEDULE EVERY 10 MINUTE STARTS '2021-05-01 00:00:00' ON COMPLETION PRESERVE ENABLE DO DELETE auth FROM auth INNER JOIN users ON auth.idUser = users.id where date_expires < NOW() AND users.`status` = 1//
DELIMITER ;

-- Dumping structure for event webupload.delete_files
DROP EVENT IF EXISTS `delete_files`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` EVENT `delete_files` ON SCHEDULE EVERY 1 WEEK STARTS '2021-04-29 00:00:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM files WHERE date_expires < NOW()//
DELIMITER ;

-- Dumping structure for event webupload.delete_token
DROP EVENT IF EXISTS `delete_token`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` EVENT `delete_token` ON SCHEDULE EVERY 1 DAY STARTS '2014-04-30 00:00:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM token WHERE refresh_expires < NOW()//
DELIMITER ;

-- Dumping structure for event webupload.delete_userauth
DROP EVENT IF EXISTS `delete_userauth`;
DELIMITER //
CREATE DEFINER=`root`@`localhost` EVENT `delete_userauth` ON SCHEDULE EVERY 1 DAY STARTS '2021-05-18 11:38:38' ON COMPLETION PRESERVE ENABLE DO DELETE FROM auth_user WHERE date_expires < NOW()//
DELIMITER ;

-- Dumping structure for table webupload.files
DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `_idFile` int(11) NOT NULL AUTO_INCREMENT,
  `_nameFile` varchar(255) NOT NULL,
  `_urlFile` varchar(255) NOT NULL,
  `_name_md5` varchar(255) NOT NULL,
  `_extension` varchar(50) NOT NULL,
  `date_upload` datetime NOT NULL,
  `date_expires` datetime NOT NULL,
  `storages` varchar(50) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`_idFile`),
  UNIQUE KEY `_name_md5` (`_name_md5`),
  UNIQUE KEY `_urlFile` (`_urlFile`),
  KEY `FK_files_users` (`idUser`),
  CONSTRAINT `FK_files_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table webupload.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(3) NOT NULL,
  `idUser` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  `date_donate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`,`type`),
  KEY `FK_order_users` (`idUser`),
  CONSTRAINT `FK_order_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table webupload.token
DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(1000) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `refresh_token` varchar(1000) NOT NULL DEFAULT '',
  `refresh_expires` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refresh_token` (`refresh_token`),
  KEY `FK_token_users` (`idUser`),
  CONSTRAINT `FK_token_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table webupload.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `permission` tinyint(3) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `idAuth` int(11) DEFAULT NULL,
  `blocked` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `api_key` (`api_key`),
  KEY `FK_users_auth` (`idAuth`),
  CONSTRAINT `FK_users_auth` FOREIGN KEY (`idAuth`) REFERENCES `auth` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table webupload.user_auth
DROP TABLE IF EXISTS `user_auth`;
CREATE TABLE IF NOT EXISTS `user_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` mediumint(6) NOT NULL DEFAULT '0',
  `date_expires` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `rule` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_user_auth_users` (`idUser`),
  CONSTRAINT `FK_user_auth_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for trigger webupload.after_auth_insert
DROP TRIGGER IF EXISTS `after_auth_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_auth_insert` AFTER INSERT ON `auth` FOR EACH ROW UPDATE users SET idAuth = new.id  where id = new.idUser//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger webupload.after_users_update
DROP TRIGGER IF EXISTS `after_users_update`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_users_update` AFTER UPDATE ON `users` FOR EACH ROW DELETE FROM token WHERE idUser = new.id//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
