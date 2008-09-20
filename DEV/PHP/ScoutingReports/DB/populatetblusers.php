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
//Set global variables to connect to MySQL DB
include("../connect_to_database.php");

$myfile = "users_db.sql";
$handle = @fopen($myfile, "r");

if ($handle)
{
	while (!feof($handle))
	{
		$strSqlString = fgets($handle, 4096);
		$result = @mysql_query($strSqlString);

		if (!$result)
		{
			echo "<p>Error performing query: " . mysql_error() . "</p>";
			exit();
		}
	}
	fclose($handle);

	echo "<p>Succesfully imported " . $myfile . "</p>";
}
?>
</body>
</html>