-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: 10.0.11.190
-- Generation Time: Sep 02, 2007 at 05:57 AM
-- Server version: 4.0.27
-- PHP Version: 4.4.4
-- 
-- Database: `CabalDB`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `tblStanding`
-- 

DROP TABLE IF EXISTS `tblStanding`;
CREATE TABLE IF NOT EXISTS `tblStanding` (
  `RecordNumber` int(11) NOT NULL auto_increment,
  `PlanetID` int(11) NOT NULL default '0',
  `Turn` int(11) NOT NULL default '0',
  `Rank` int(11) NOT NULL default '0',
  `Prestige` int(11) NOT NULL default '0',
  `OnLine` char(1) default '',
  `UpdateDate` date default '0000-00-00',
  `UpdateTime` time default '00:00:00',
  PRIMARY KEY  (`RecordNumber`,`PlanetID`,`Turn`)
) TYPE=MyISAM;
