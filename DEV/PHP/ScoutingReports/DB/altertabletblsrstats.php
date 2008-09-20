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
//This script changes the tblplanet table it add the date column
include("../connect_to_database.php");

//the tablename we want to create
$mysql_tablename  = "tblplanet";
$strSqlString = "ALTER TABLE $mysql_tablename ADD date VARCHAR(64)";

$result = @mysql_query($strSqlString);

if (!$result)
{
	echo "<p>Error performing query: " . mysql_error() . "</p>";
	exit();
}
else
{
	echo "column added";
	
	$current_date = date("m-d-Y H:i:s");
	$strSqlString = "UPDATE $mysql_tablename SET date = '" . $current_date . "'";
	
	$result = @mysql_query($strSqlString);

	if (!$result)
	{
		echo "<p>Error performing query: " . mysql_error() . "</p>";
		exit();
	}
	else
	{
		echo "Table: " .$mysql_tablename. " succesfully updated";
	}
}
?>
</body>
</html>