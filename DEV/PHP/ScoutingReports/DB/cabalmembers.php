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
include("../connect_to_database.php");

$mysql_tablename = "tblplanet";
$strSqlString = "UPDATE " . $mysql_tablename . " SET Cabal = 'Y' WHERE GroupName LIKE '%cabal%'";

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
?>
</body>
</html>