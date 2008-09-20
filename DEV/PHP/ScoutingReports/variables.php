<?php
if ($_SERVER['SERVER_NAME'] == 'www.idsfadt.com')
{
	$DEV = false;

	//Cabal

	$mysql_server = "mysql137.secureserver.net";
	$mysql_user = "CabalDB";
	$mysql_password = "c4b4Lity";
	$mysql_db = "CabalDB";

	$LoginWebUrl = "http://www.idsfadt.com/Cabal/login_page.php";
	$ScoutWebUrl = "http://www.idsfadt.com/Cabal/Scout/scout.php";
	$StatsWebUrl = "http://www.idsfadt.com/Cabal/Planetstats/planetstats.php";

	//Murc
/*
	$mysql_server = "mysql231.secureserver.net";
	$mysql_user = "MurcDB";
	$mysql_password = "c4b4Lity";
	$mysql_db = "MurcDB";

	$LoginWebUrl = "http://www.idsfadt.com/Murc/login_page.php";
	$ScoutWebUrl = "http://www.idsfadt.com/Murc/Scout/scout.php";
	$DossierWebUrl = "http://www.idsfadt.com/Murc/Dossier/Dossier.php";
*/
	//Shriner
	/*
	$mysql_server = "mysql231.secureserver.net";
	$mysql_user = "ShrinerDB";
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
	$mysql_db = "CabalDB";

	$LoginDebugUrl = "http://localhost/Cabal/login_page.php";
	$ScoutDebugUrl = "http://localhost/Cabal/Scout/scout.php";
	$StatsDebugUrl = "http://localhost/Cabal/Planetstats/planetstats.php";
}
?>
