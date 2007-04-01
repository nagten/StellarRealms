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
$mysql_tablename  = "tblscout";

$strSqlString = "CREATE TABLE $mysql_tablename (
				  `RecordNumber` int(11) NOT NULL auto_increment,
				  `PlanetID` int(11) NOT NULL default '0',
				  `PlanetName` varchar(60) NOT NULL default '',
				  `SourceID` int(11) NOT NULL default '0',
				  `SourceName` varchar(60) NOT NULL default '',
				  `ReportDate` date NOT NULL default '0000-00-00',
				  `ReportTime` time NOT NULL default '00:00:00',
				  `ReportDateTime` datetime NOT NULL default '0000-00-00 00:00:00',
				  `Reconnaitertype` tinyint(1) NOT NULL default '1',
				  `ADVGE` int(1) default '0',
				  `ADVIN` int(5) default '0',
				  `ADVTS` int(1) default '0',
				  `AEGMS` int(5) default '0',
				  `AIRB1` int(2) default '0',
				  `AIRB2` int(2) default '0',
				  `AMIPS` int(1) default '0',
				  `ANVBS` int(5) default '0',
				  `ASPHC` int(5) default '0',
				  `AVASC` int(5) default '0',
				  `BADLC` int(5) default '0',
				  `BARAF` int(5) default '0',
				  `BARR1` int(1) default '0',
				  `BARR2` int(1) default '0',
				  `BATSH` int(5) default '0',
				  `BERDE` int(5) default '0',
				  `BIOLO` int(1) default '0',
				  `BLABM` int(5) default '0',
				  `BROCE` int(1) default '0',
				  `COLFR` int(5) default '0',
				  `COLOS` int(3) default '0',
				  `CRUBC` int(5) default '0',
				  `CRUIS` int(5) default '0',
				  `DAGHF` int(5) default '0',
				  `DEERS` int(5) default '0',
				  `DEFTU` int(3) default '0',
				  `DESTR` int(5) default '0',
				  `DIPCO` int(1) default '0',
				  `DRAMA` int(5) default '0',
				  `DREAD` int(5) default '0',
				  `EMBAS` int(2) default '0',
				  `FANFB` int(5) default '0',
				  `FARM1` int(2) default '0',
				  `FARM2` int(2) default '0',
				  `FARM3` int(2) default '0',
				  `FIGBO` int(5) default '0',
				  `FIGIN` int(5) default '0',
				  `FIRSD` int(5) default '0',
				  `FRIGA` int(5) default '0',
				  `FUEL1` int(2) default '0',
				  `FUEL2` int(2) default '0',
				  `FOLDR` int(2) default '0',
				  `GELAB` int(2) default '0',
				  `GOLBA` int(5) default '0',
				  `GNDHI` int(1) default '0',
				  `HABI1` int(2) default '0',
				  `HABI2` int(2) default '0',
				  `HABI3` int(2) default '0',
				  `HAMGU` int(5) default '0',
				  `HVYBO` int(5) default '0',
				  `HVYCA` int(5) default '0',
				  `HVYCR` int(5) default '0',
				  `HIBCA` int(1) default '0',
				  `HOSPI` int(1) default '0',
				  `HURFC` int(5) default '0',
				  `IMPFR` int(5) default '0',
				  `INSHT` int(1) default '0',
				  `INTEL` int(1) default '0',
				  `INTFR` int(5) default '0',
				  `INTMP` int(1) default '0',
				  `INTFO` int(1) default '0',
				  `JUDDR` int(5) default '0',
				  `JUMP1` int(2) default '0',
				  `JUMP2` int(2) default '0',
				  `LEOSC` int(5) default '0',
				  `LIGCA` int(5) default '0',
				  `LISTN` int(2) default '0',
				  `MANU1` int(2) default '0',
				  `MANU2` int(2) default '0',
				  `MATS1` int(1) default '0',
				  `MATS2` int(1) default '0',
				  `MATRC` int(1) default '0',
				  `MINE1` int(2) default '0',
				  `MINE2` int(2) default '0',
				  `RADI1` int(2) default '0',
				  `RADI2` int(2) default '0',
				  `OBULK` int(3) default '0',
				  `OCON1` int(2) default '0',
				  `OCON2` int(2) default '0',
				  `ODEFM` int(3) default '0',
				  `ODEF1` int(3) default '0',
				  `ODEF2` int(3) default '0',
				  `OMIN1` int(3) default '0',
				  `OMIN2` int(3) default '0',
				  `ORCBA` int(5) default '0',
				  `OSLD1` int(3) default '0',
				  `OSLD2` int(3) default '0',
				  `PBANK` int(1) default '0',
				  `PLATE` int(1) default '0',
				  `PRIHC` int(5) default '0',
				  `RAVMC` int(5) default '0',
				  `RSENS` int(2) default '0',
				  `RLAB1` int(1) default '0',
				  `RLAB2` int(1) default '0',
				  `SATE1` int(2) default '0',
				  `SATE2` int(2) default '0',
				  `SCOUT` int(5) default '0',
				  `SBASE` int(1) default '0',
				  `STIDR` int(5) default '0',
				  `STOCK` int(1) default '0',
				  `SDEF1` int(3) default '0',
				  `SDEF2` int(3) default '0',
				  `SSLD1` int(3) default '0',
				  `SSLD2` int(3) default '0',
				  `TANDB` int(5) default '0',
				  `TERCA` int(5) default '0',
				  `TORBA` int(5) default '0',
				  `TRACK` int(2) default '0',
				  `TSCHL` int(1) default '0',
				  `UNIVE` int(1) default '0',
				  `VENHF` int(5) default '0',
				  `VESSC` int(5) default '0',
				  `VINEM` int(1) default '0',
				  `WARFA` int(1) default '0',
				  `WASFI` int(5) default '0',
				  `WAYEC` int(5) default '0',
				  `WHSE1` int(3) default '0',
				  `WHSE2` int(3) default '0',
				  `WHSE3` int(3) default '0',
				  `WEATL` int(1) default '0',
				  `ZEPFD` int(5) default '0',
				  `AirOps` int(8) default '0',
				  `Capital` int(8) default '0',
				  `Diplomacy` int(5) default '0',
				  `Fighter` int(8) default '0',
				  `Habitat` int(11) default '0',
				  `IntelOps` int(5) default '0',
				  `Materials` int(5) default '0',
				  `Reproduction` int(5) default '0',
				  `Queues` int(3) default '0',
				  `Research` int(3) default '0',
				  `Scouting` int(5) default '0',
				  `Sensors` int(3) default '0',
				  `Special` int(3) default '0',
				  `Speed` int(3) default '0',
				  `Training` int(3) default '0',
				  `Warehouse` int(5) default '0',
				  `Wealth` int(5) default '0',
				  `Rank` int(3) default '0',
				  `AirCap` int(8) default '0',
				  `HabSpace` int(11) default '0',
				  `FleetRating` int(7) default '0',
				  `OrbRating` int(7) default '0',
				  `SurRating` int(7) default '0',
				  `BuildRating` int(7) default '0',
				  `Current` char(1) default 'N',
				  `Species` varchar(40) default '',
				  `DurabilityPerc` float default '1',
				  PRIMARY KEY  (`RecordNumber`,`PlanetID`,`SourceID`),
				  KEY `PlanetID` (`PlanetID`),
				  KEY `ReportDate` (`ReportDate`),
				  KEY `ReportTime` (`ReportTime`),
				  KEY `ReportDateTime` (`ReportDateTime`),
				  KEY `Current` (`Current`)
                  ) TYPE=MyISAM";

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