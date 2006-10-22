<?php

if ($_SERVER['SERVER_NAME'] == 'www.idsfadt.com') 
{
	$dbconn = mysql_connect("mysql191.secureserver.net", "SRstats", "c4b4Lity");
	$result = mysql_select_db('SRstats',$dbconn) or die("DB Open Failure: " . mysql_error());
}
else 
{
	$dbconn = mysql_connect("localhost", "root", "root");
	$result = mysql_select_db('sr') or die("DB Open Failure: " . mysql_error());
}
?>