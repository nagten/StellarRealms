<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Planet Stats</title>
<style type="text/css">
<!--
body {
	background-color: #b0c4de;
}
-->
</style>
</head>

<body>
<?php
//This script creates the table we use for the planetstats page,
include("../connect_to_database.php");

//the tablename we want to create
$mysql_tablename  = "tblplanet";

$strSqlString = "CREATE TABLE $mysql_tablename (
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
  `date` VARCHAR(64) default NULL,
  PRIMARY KEY  (`RecordNumber`),
  KEY `SID1` (`SID1`),
  KEY `SID2` (`SID2`),
  KEY `PlanetName` (`PlanetName`)
) TYPE=MyISAM AUTO_INCREMENT=131";

$result = @mysql_query($strSqlString);

if (!$result)
{
	echo "<p>Error performing query: " . mysql_error() . "</p>";
	exit();
}
else
{
	echo "Table: " .$mysql_tablename. " succesfully created";
}
?>
</body>
</html>