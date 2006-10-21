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
$mysql_server = "localhost";
$mysql_user = "root";
$mysql_password = "R0it";
$mysql_db = "sr";

$users = array( 0=>array('username'=>'********', 'srplanetname'=>'Infinity Edge'),
                1=>array('username'=>'********', 'srplanetname'=>'Oven'),
                2=>array('username'=>'********', 'srplanetname'=>'Chaos'),
                3=>array('username'=>'******', 'srplanetname'=>'100-Aker-Forest'),
                4=>array('username'=>'******', 'srplanetname'=>'Kindom of Loathing'),
                5=>array('username'=>'*********', 'srplanetname'=>'Biotica'),
                6=>array('username'=>'**********', 'srplanetname'=>'Noviy Magadan II'),
                7=>array('username'=>'*******', 'srplanetname'=>'Thermocouple'),
                8=>array('username'=>'*********', 'srplanetname'=>'The Hotel')
                );

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
        foreach($users as $arrayuser)
        {
            echo "Creating user username = " . $arrayuser['username'] . " srplanetname =  ". $arrayuser['srplanetname'] . "<BR>\n";

            $strSqlString = "INSERT INTO tblsrstats
                            (fplanetname, fuser, ffood, ffooddelta, ffuel, ffueldelta,
                            fmetals, fmetalsdelta, fradioactives, fradioactivesdelta,
                            fmatmaximum, fcredits, fcreditsdelta, fcreditsrank,
                            ftaxrate, fprestige, fprestigedelta, fprestigerank,
                            fpop, fpopdelta, fpoprank, fpopmaximum,
                            fprojects, fprojectsmaximum, fbattleslost, falertlevel,
                            fconstruction, fresearch, fdiplomacy, foffense,
                            fdefense, fwealth, freproduction, fmaterials,
                            fdurability, fspeed, fsensors, fstealth,
                            fmaintenance, fplanet, fdate)
                            VALUES
                            ('". $arrayuser['srplanetname']. "', '". $arrayuser['username'] ."', ".rand(0,2000).", ".rand(0,100).", ".rand(0,2000).", ".rand(0,100).",
                            ".rand(0,2000).", ".rand(0,100).", ".rand(0,2000).", ".rand(0,100).",
                            ".rand(0,20000).", ".rand(0,1000000).", ".rand(-5000,5000).", ".rand(0,100).",
                            ".rand(0,5).", ".rand(0,500000).", ".rand(-5000,5000).", ".rand(0,100).",
                            ".rand(0,2500000).", ".rand(-250,1500).", ".rand(0,100).", ".rand(1250000,2500000).",
                            ".rand(0,25).", ".rand(1,25).", ".rand(0,100).", ".rand(0,100). ",
                            ".rand(0,100).", ".rand(0,100).", ".rand(0,100).", ".rand(0,100).",
                            ".rand(0,100).", ".rand(0,100).", ".rand(0,100).", ".rand(0,100).",
                            ".rand(0,100).", ".rand(0,100).", ".rand(0,100).", ".rand(0,100).",
                            ".rand(0,5000). ", " .rand(0,100). ", ''
                            )";
                            
            $result = @mysql_query($strSqlString);

            if (!$result)
            {
            	echo "<p>Error performing query: " . mysql_error() . "</p>";
    			exit();
            }
            else
            {
                echo "User: " . $arrayuser['username'] ." succesfully created <BR>\n\n";
            }
        }
    }
  }

?>
</body>
</html>