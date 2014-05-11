-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 12, 2014 at 01:59 AM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `notify`
--
CREATE DATABASE `notify` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `notify`;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tokenId` varchar(50) NOT NULL DEFAULT '',
  `eventId` int(10) unsigned NOT NULL,
  `isNew` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `eName` varchar(100) NOT NULL,
  `eData` longtext NOT NULL,
  PRIMARY KEY (`id`,`tokenId`),
  KEY `IX_recipientUid` (`tokenId`),
  KEY `IX_isNew` (`isNew`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='User notifications' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `abc` varchar(50) NOT NULL,
  `data` varchar(100) NOT NULL,
  PRIMARY KEY (`id`,`abc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `connectionId` int(10) NOT NULL AUTO_INCREMENT,
  `sessionId` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tokenId` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`connectionId`),
  UNIQUE KEY `sessionId` (`sessionId`,`tokenId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1771 ;
--
-- Database: `rudraxdb`
--
CREATE DATABASE `rudraxdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `rudraxdb`;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
