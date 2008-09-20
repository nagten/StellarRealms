<?php
include("variables.php");

if ($_SERVER['SERVER_NAME'] == 'www.idsfadt.com')
{
	$dbconn = mysql_connect($mysql_server, $mysql_user, $mysql_password);
	
	if(!$dbconn)
	{
    	if($DEV)
    	{
    		echo "DB Open Failure: " . mysql_error();
    		exit();
    	}
  	}
	else
	{
		if (!@mysql_select_db($mysql_db, $dbconn))
	    {
	        echo "<p>Unable to locate the " . $mysql_db . " database.</p>";
	        exit();
	    }
	    else
		{	
			if($DEV)
    		{
    			//echo "Succesfully connected to " . $mysql_server . " ";
    		}
		}
	}
}
else
{
	$dbconn = mysql_connect($mysql_server, $mysql_user, $mysql_password);
	
	if(!$dbconn)
	{
    	if($DEV)
    	{
    		echo "DB Open Failure: " . mysql_error();
    		exit();
    	}
  	}
	else
	{
		if (!@mysql_select_db($mysql_db, $dbconn))
	    {
	        echo "<p>Unable to locate the " . $mysql_db . " database.</p>";
	        exit();
	    }
	    else
		{	
			if($DEV)
    		{
    			//echo "Succesfully connected to " . $mysql_server . " " . $mysql_db . " ";
    		}
		}
	}
}
?>