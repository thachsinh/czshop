-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `td_driver`;
CREATE TABLE `td_driver` (
  `driver_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `birthday` date NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '1',
  `address` text NOT NULL,
  `driving_status` text NOT NULL,
  `driving_licence_number` text NOT NULL,
  `licence_valid_from` date NOT NULL,
  `licence_valid_to` date NOT NULL,
  `note` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`driver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2015-01-25 14:47:02