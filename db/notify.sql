-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2014 at 05:26 PM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `notify`
--
CREATE DATABASE IF NOT EXISTS `notify` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `notify`;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipientUid` int(10) unsigned NOT NULL,
  `eventId` int(10) unsigned NOT NULL,
  `isNew` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IX_recipientUid` (`recipientUid`),
  KEY `IX_isNew` (`isNew`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='User notifications' AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `sessionId` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `tokenId` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`sessionId`,`tokenId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
