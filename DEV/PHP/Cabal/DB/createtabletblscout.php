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

  //Set global variables to connect to MySQL DB
$mysql_server = "localhost";
$mysql_user = "root";
$mysql_password = "R0it";
$mysql_db = "sr";

  //the tablename we want to create
  $mysql_tablename  = "tblscout";

  $dbcnx = @mysql_connect($mysql_server, $mysql_user, $mysql_password);

  if (!$dbcnx)
  {
    echo "<p>Unable to connect to the database server.</p>";
    exit();
  }
  else
  {
    if (!@mysql_select_db($mysql_db))
    {
        echo "<p>Unable to locate the " . $mysql_db . " database.</p>";
        exit();
    }
    else
    {
      $strSqlString = "CREATE TABLE $mysql_tablename (
				  `RecordNumber` int(11) NOT NULL auto_increment,
				  `PlanetID` int(11) NOT NULL default '0',
				  `PlanetName` varchar(60) NOT NULL default '',
				  `SourceID` int(11) NOT NULL default '0',
				  `SourceName` varchar(60) NOT NULL default '',
				  `ReportDate` date NOT NULL default '0000-00-00',
				  `ReportTime` time NOT NULL default '00:00:00',
				  `ADVIN` int(11) default '0',
				  `ADVGE` int(11) default '0',
				  `ADVTS` int(11) default '0',
				  `AEGMS` int(11) default '0',
				  `AIRB1` int(11) default '0',
				  `AIRB2` int(11) default '0',
				  `ANVBS` int(11) default '0',
				  `ASPHC` int(11) default '0',
				  `AVASC` int(11) default '0',
				  `BADLC` int(11) default '0',
				  `BARAF` int(11) default '0',
				  `BARR1` int(11) default '0',
				  `BARR2` int(11) default '0',
				  `BATSH` int(11) default '0',
				  `BERDE` int(11) default '0',
				  `BIOLO` int(11) default '0',
				  `BLABM` int(11) default '0',
				  `COLFR` int(11) default '0',
				  `COLOS` int(11) default '0',
				  `CRUBC` int(11) default '0',
				  `CRUIS` int(11) default '0',
				  `DAGHF` int(11) default '0',
				  `DEERS` int(11) default '0',
				  `DEFTU` int(11) default '0',
				  `DESTR` int(11) default '0',
				  `DIPCO` int(11) default '0',
				  `DRAMA` int(11) default '0',
				  `DREAD` int(11) default '0',
				  `EMBAS` int(11) default '0',
				  `FANFB` int(11) default '0',
				  `FARM1` int(11) default '0',
				  `FARM2` int(11) default '0',
				  `FARM3` int(11) default '0',
				  `FIGBO` int(11) default '0',
				  `FIGIN` int(11) default '0',
				  `FIRSD` int(11) default '0',
				  `FRIGA` int(11) default '0',
				  `GELAB` int(11) default '0',
				  `GNDHI` int(11) default '0',
				  `GOLBA` int(11) default '0',
				  `HABI1` int(11) default '0',
				  `HABI2` int(11) default '0',
				  `HABI3` int(11) default '0',
				  `HAMGU` int(11) default '0',
				  `HVYBO` int(11) default '0',
				  `HVYCA` int(11) default '0',
				  `HVYCR` int(11) default '0',
				  `HIBCA` int(11) default '0',
				  `HOSPI` int(11) default '0',
				  `HURFC` int(11) default '0',
				  `IMPFR` int(11) default '0',
				  `INSHT` int(11) default '0',
				  `INTEL` int(11) default '0',
				  `INTFR` int(11) default '0',
				  `INTMP` int(11) default '0',
				  `INTFO` int(11) default '0',
				  `JUDDR` int(11) default '0',
				  `JUMP1` int(11) default '0',
				  `JUMP2` int(11) default '0',
				  `LEOSC` int(11) default '0',
				  `LIGCA` int(11) default '0',
				  `LISTN` int(11) default '0',
				  `MANU1` int(11) default '0',
				  `MANU2` int(11) default '0',
				  `MATS1` int(11) default '0',
				  `MATS2` int(11) default '0',
				  `MATRC` int(11) default '0',
				  `MINE1` int(11) default '0',
				  `MINE2` int(11) default '0',
				  `RADI1` int(11) default '0',
				  `RADI2` int(11) default '0',
				  `OBULK` int(11) default '0',
				  `OCON1` int(11) default '0',
				  `OCON2` int(11) default '0',
				  `ODEFM` int(11) default '0',
				  `ODEF1` int(11) default '0',
				  `ODEF2` int(11) default '0',
				  `OMIN1` int(11) default '0',
				  `OMIN2` int(11) default '0',
				  `ORCBA` int(11) default '0',
				  `OSLD1` int(11) default '0',
				  `OSLD2` int(11) default '0',
				  `PBANK` int(11) default '0',
				  `PLATE` int(11) default '0',
				  `PRIHC` int(11) default '0',
				  `FUEL1` int(11) default '0',
				  `FUEL2` int(11) default '0',
				  `RAVMC` int(11) default '0',
				  `RSENS` int(11) default '0',
				  `RLAB1` int(11) default '0',
				  `RLAB2` int(11) default '0',
				  `SATE1` int(11) default '0',
				  `SATE2` int(11) default '0',
				  `SCOUT` int(11) default '0',
				  `FOLDR` int(11) default '0',
				  `SBASE` int(11) default '0',
				  `STIDR` int(11) default '0',
				  `STOCK` int(11) default '0',
				  `SDEF1` int(11) default '0',
				  `SDEF2` int(11) default '0',
				  `SSLD1` int(11) default '0',
				  `SSLD2` int(11) default '0',
				  `TANDB` int(11) default '0',
				  `TERCA` int(11) default '0',
				  `TORBA` int(11) default '0',
				  `TRACK` int(11) default '0',
				  `TSCHL` int(11) default '0',
				  `UNIVE` int(11) default '0',
				  `VENHF` int(11) default '0',
				  `VESSC` int(11) default '0',
				  `VINEM` int(11) default '0',
				  `WARFA` int(11) default '0',
				  `WASFI` int(11) default '0',
				  `WAYEC` int(11) default '0',
				  `WHSE1` int(11) default '0',
				  `WHSE2` int(11) default '0',
				  `WHSE3` int(11) default '0',
				  `WEATL` int(11) default '0',
				  `ZEPFD` int(11) default '0',
				  `AirOps` int(11) default '0',
				  `Capital` int(11) default '0',
				  `Defense` int(11) default '0',
				  `Diplomacy` int(11) default '0',
				  `Fighter` int(11) default '0',
				  `Habitat` int(11) default '0',
				  `IntelOps` int(11) default '0',
				  `Materials` int(11) default '0',
				  `Reproduction` int(11) default '0',
				  `Queues` int(11) default '0',
				  `Research` int(11) default '0',
				  `Scouting` int(11) default '0',
				  `Sensors` int(11) default '0',
				  `Special` int(11) default '0',
				  `Speed` int(11) default '0',
				  `Training` int(11) default '0',
				  `Wealth` int(11) default '0',
				  `Rank` int(11) default '0',
				  `AirCap` int(11) default '0',
				  `HabSpace` int(11) default '0',
				  `Slots` int(11) default '0',
				  `DefMaint` int(11) default '0',
				  `OffMaint` int(11) default '0',
				  `Current` char(1) default 'N',
				  PRIMARY KEY  (`RecordNumber`,`PlanetID`,`SourceID`),
				  KEY `PlanetID` (`PlanetID`),
				  KEY `ReportDate` (`ReportDate`),
				  KEY `ReportTime` (`ReportTime`),
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
    }
  }
?>
</body>
</html>