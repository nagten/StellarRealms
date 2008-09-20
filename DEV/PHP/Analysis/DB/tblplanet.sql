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
-- Table structure for table `tblplanet`
-- 

DROP TABLE IF EXISTS `tblplanet`;
CREATE TABLE IF NOT EXISTS `tblplanet` (
  `RecordNumber` int(11) NOT NULL auto_increment,
  `PlanetName` varchar(60) default '',
  `Leader` varchar(60) default '',
  `Species` varchar(40) default '',
  `Rank` int(11) default '0',
  `OnLine` char(1) default '',
  `OnLineCount` int(11) default '0',
  `TurnCount` int(11) default '0',
  `FirstTurn` int(11) default NULL,
  `FirstRank` int(11) default NULL,
  `StyleLevel` char(3) default NULL,
  `ThreatLevel` char(3) default NULL,
  `OffenseLevel` char(3) default NULL,
  `DefenseLevel` char(3) default NULL,
  `IntelLevel` char(3) default NULL,
  `ExperienceLevel` char(3) default NULL,
  `AlertLevel` char(3) default NULL,
  `StanceLevel` char(3) default NULL,
  `Cabal` char(1) default 'N',
  `GroupID` varchar(15) default NULL,
  `GroupName` varchar(15) default NULL,
  `SID1` int(11) default NULL,
  `SID2` int(11) default NULL,
  `date` varchar(64) default NULL,
  PRIMARY KEY  (`RecordNumber`),
  KEY `SID1` (`SID1`),
  KEY `SID2` (`SID2`),
  KEY `PlanetName` (`PlanetName`)
) TYPE=MyISAM;
