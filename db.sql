-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `td_vehicle`;
CREATE TABLE `td_vehicle` (
  `vehicle_id` int(11) NOT NULL AUTO_INCREMENT,
  `vin` varchar(7) NOT NULL,
  `make` varchar(128) NOT NULL,
  `model` varchar(128) NOT NULL,
  `production_date` date NOT NULL,
  `model_number` varchar(128) NOT NULL,
  `drive_type` varchar(128) NOT NULL,
  `chassis` varchar(128) NOT NULL,
  `engine` varchar(128) NOT NULL,
  `transmission` varchar(128) NOT NULL,
  `body` varchar(128) NOT NULL,
  `odometer` varchar(128) NOT NULL,
  `note` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vehicle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2015-01-26 16:02:55