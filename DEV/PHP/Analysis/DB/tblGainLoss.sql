-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: 10.0.11.190
-- Generation Time: Sep 02, 2007 at 05:56 AM
-- Server version: 4.0.27
-- PHP Version: 4.4.4
-- 
-- Database: `CabalDB`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `tblGainLoss`
-- 

DROP TABLE IF EXISTS `tblGainLoss`;
CREATE TABLE IF NOT EXISTS `tblGainLoss` (
  `RecordNumber` int(11) NOT NULL auto_increment,
  `Turn` int(11) NOT NULL default '0',
  `Planet` varchar(60) default '',
  `Type` varchar(15) default '',
  `Value` int(11) default '0',
  `Text` varchar(50) default '',
  PRIMARY KEY  (`RecordNumber`,`Turn`)
) TYPE=MyISAM;
