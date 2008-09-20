-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: 10.0.11.190
-- Generation Time: Sep 02, 2007 at 05:55 AM
-- Server version: 4.0.27
-- PHP Version: 4.4.4
-- 
-- Database: `CabalDB`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `tblControl`
-- 

DROP TABLE IF EXISTS `tblControl`;
CREATE TABLE IF NOT EXISTS `tblControl` (
  `RoundNumber` int(11) NOT NULL default '0',
  `RoundName` varchar(100) default '',
  `Turn` int(11) default '0',
  `UpdateDate` date default '0000-00-00',
  `UpdateTime` time default '00:00:00',
  `RoundLength` smallint(6) NOT NULL default '0',
  `Active` char(1) default '',
  PRIMARY KEY  (`RoundNumber`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `tblControl`
-- 

INSERT INTO `tblControl` VALUES (20, 'Between a Rock and a Hard Place', 5, '2007-09-02', '05:38:49', 250, 'Y');
