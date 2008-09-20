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
-- Table structure for table `tblMetrics`
-- 

DROP TABLE IF EXISTS `tblMetrics`;
CREATE TABLE IF NOT EXISTS `tblMetrics` (
  `RecordNumber` int(11) NOT NULL auto_increment,
  `PlanetID` int(11) NOT NULL default '0',
  `Turn` int(11) NOT NULL default '0',
  `RD0` int(11) default '0',
  `RD1` int(11) default '0',
  `RD6` int(11) default '0',
  `RD12` int(11) default '0',
  `RD72` int(11) default '0',
  `RD144` int(11) default '0',
  `RD216` int(11) default '0',
  `RD288` int(11) default '0',
  `RD360` int(11) default '0',
  `RD720` int(11) default '0',
  `RD1440` int(11) default '0',
  `RDFirst` int(11) default '0',
  `PD0` int(11) default '0',
  `PD1` int(11) default '0',
  `PD6` int(11) default '0',
  `PD12` int(11) default '0',
  `PD72` int(11) default '0',
  `PD144` int(11) default '0',
  `PD216` int(11) default '0',
  `PD288` int(11) default '0',
  `PD360` int(11) default '0',
  `PD720` int(11) default '0',
  `PD1440` int(11) default '0',
  `PDNext` int(11) default '0',
  `PA6` int(11) default '0',
  `PG1` int(11) default '0',
  `PG2` int(11) default '0',
  `PG3` int(11) default '0',
  `PG4` int(11) default '0',
  `PG5` int(11) default '0',
  `PG6` int(11) default '0',
  `PG7` int(11) default '0',
  `PG8` int(11) default '0',
  `PG9` int(11) default '0',
  `PG10` int(11) default '0',
  `PG11` int(11) default '0',
  `PG12` int(11) default '0',
  `PG13` int(11) default '0',
  `PG14` int(11) default '0',
  `PG15` int(11) default '0',
  `PG16` int(11) default '0',
  `PG17` int(11) default '0',
  `PG18` int(11) default '0',
  PRIMARY KEY  (`RecordNumber`,`PlanetID`,`Turn`)
) TYPE=MyISAM;
