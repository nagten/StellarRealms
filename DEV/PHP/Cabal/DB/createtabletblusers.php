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
  $mysql_tablename  = "tblusers";

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
                    userid int(11) NOT NULL auto_increment,
  					username varchar(30) NOT NULL default '',
  					password varchar(32) default NULL,
  					PRIMARY KEY (userid),
  					KEY username (username)
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