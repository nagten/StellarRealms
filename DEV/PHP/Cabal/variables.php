<?php
if ($_SERVER['SERVER_NAME'] == 'www.idsfadt.com')
{
	$DEV = false;

	//Cabal
	$mysql_server = "mysql191.secureserver.net";
	$mysql_user = "SRstats";
	$mysql_password = "c4b4Lity";
	$mysql_db = "SRstats";

	$LoginWebUrl = "http://www.idsfadt.com/Cabal/login_page.php";
	$ScoutWebUrl = "http://www.idsfadt.com/Cabal/Scout/scout.php";
	$DossierWebUrl = "http://www.idsfadt.com/Cabal/Dossier/Dossier.php";

	//Murc
	/*
	$mysql_server = "mysql191.secureserver.net";
	$mysql_user = "SRstats";
	$mysql_password = "c4b4Lity";
	$mysql_db = "MurcDB";

	$LoginWebUrl = "http://www.idsfadt.com/Murc/login_page.php";
	$ScoutWebUrl = "http://www.idsfadt.com/Murc/Scout/scout.php";
	$DossierWebUrl = "http://www.idsfadt.com/Murc/Dossier/Dossier.php";
	*/

	//Shriner
	/*
	$mysql_server = "mysql191.secureserver.net";
	$mysql_user = "SRstats";
	$mysql_password = "c4b4Lity";
	$mysql_db = "ShrinerDB";

	$LoginWebUrl = "http://www.idsfadt.com/Shriner/login_page.php";
	$ScoutWebUrl = "http://www.idsfadt.com/Shriner/Scout/scout.php";
	$DossierWebUrl = "http://www.idsfadt.com/Shriner/Dossier/Dossier.php";
	*/
}
else
{
	$DEV = true;
	$mysql_server = "localhost";
	$mysql_user = "root";
	$mysql_password = "R0it";
	$mysql_db = "sr";

	$LoginDebugUrl = "http://localhost/Cabal/login_page.php";
	$ScoutDebugUrl = "http://localhost/Cabal/Scout/scout.php";
	$DossierDebugUrl = "http://localhost/Cabal/Dossier/Dossier.php";
}
?>
