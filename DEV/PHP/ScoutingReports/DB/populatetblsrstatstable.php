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

$users = array( 0=>array('username'=>'********', 'srplanetname'=>'Lifeboat'),
                1=>array('username'=>'********', 'srplanetname'=>'Erevna'),
                2=>array('username'=>'********', 'srplanetname'=>'Fatu-Krey'),
                3=>array('username'=>'******', 'srplanetname'=>'Oa'),
                4=>array('username'=>'******', 'srplanetname'=>'Independent News Organization'),
                5=>array('username'=>'*********', 'srplanetname'=>'Positive'),
                6=>array('username'=>'**********', 'srplanetname'=>'The Mercenary Guild'),
                7=>array('username'=>'*******', 'srplanetname'=>'Waystation'),
                8=>array('username'=>'*********', 'srplanetname'=>'Shantaram')
                );

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
                    ('". $arrayuser['srplanetname']. "', '". $arrayuser['username'] ."', '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', '0', '0',
                    '0', '0', ''
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
?>
</body>
</html>