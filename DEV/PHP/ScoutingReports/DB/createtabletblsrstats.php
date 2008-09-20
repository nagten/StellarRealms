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
$mysql_tablename  = "tblsrstats";

$strSqlString = "CREATE TABLE $mysql_tablename (
                      fplanetname varchar(64) NOT NULL PRIMARY KEY,
                      fuser varchar(64),
                      ffood int(5),
                      ffooddelta int(5),
                      ffuel int(5),
                      ffueldelta int(5),
                      fmetals int(5),
                      fmetalsdelta int(5),
                      fradioactives int(5),
                      fradioactivesdelta int(5),
                      fmatmaximum int(5),
                      fcredits int(10),
                      fcreditsdelta int(9),
                      fcreditsrank int(3),
                      ftaxrate int(2),
                      fprestige int(9),
                      fprestigedelta int(9),
                      fprestigerank int(3),
                      fpop int(9),
                      fpopdelta int(9),
                      fpoprank int(3),
                      fpopmaximum int(9),
                      fprojects int(3),
                      fprojectsmaximum int(3),
                      fbattleslost int(4),
                      falertlevel int(2),
                      fconstruction int(2),
                      fresearch int(2),
                      fdiplomacy int(2),
                      foffense int(2),
                      fdefense int(2),
                      fwealth int(2),
                      freproduction int(2),
                      fmaterials int(2),
                      fdurability int(2),
                      fspeed int(2),
                      fsensors int(2),
                      fstealth int(2),
                      fmaintenance int(2),
                      fplanet int(2),
                      fdate varchar(64)
                      ) TYPE=MyISAM;";

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