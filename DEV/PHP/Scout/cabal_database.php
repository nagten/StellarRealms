<?php

if ($_SERVER['SERVER_NAME'] == 'www.alignus.com') 
{
	$dbconn = mysql_connect("MySQL.alignus.com", "username", "password");
	$result = mysql_select_db('xalignus-1',$dbconn) or die("DB Open Failure: " . mysql_error());
}
else 
{
	$dbconn = mysql_connect("localhost", "root", "root");
	$result = mysql_select_db('sr') or die("DB Open Failure: " . mysql_error());
}
?>