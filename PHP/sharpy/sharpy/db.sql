-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 29, 2010 at 05:38 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `sharpy`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(6) unsigned NOT NULL,
  `title` varchar(48) NOT NULL,
  `content` text NOT NULL,
  `unixtime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unixtime` (`unixtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `users_id`, `title`, `content`, `unixtime`) VALUES
(8, 1, '2312', 'fsdfsdf', 1293329944),
(9, 2, 'Shapry Post', 'testing sharpy account', 1293337733);

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(6) unsigned NOT NULL,
  `unixtime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`id`, `users_id`, `unixtime`) VALUES
(1, 1, 1292664968),
(2, 1, 1292729349),
(3, 1, 1293341195);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `peaches` char(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `peaches`) VALUES
(1, 'travis', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'),
(2, 'sharpy', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');
