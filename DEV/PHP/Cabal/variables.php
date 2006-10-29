<?php
if ($_SERVER['SERVER_NAME'] == 'www.idsfadt.com') 
{
	$mysql_server = "mysql191.secureserver.net";
	$mysql_user = "SRstats";
	$mysql_password = "c4b4Lity";
	$mysql_db = "sr";
	
	$CabalLoginWebUrl = "http://www.idsfadt.com/Cabal/cabal_login_page.php";
	$ScoutWebUrl = "http://www.idsfadt.com/Cabal/Scout/scout.php";
	$DossierWebUrl = "http://www.idsfadt.com/Cabal/Dossier/Dossier.php";
}
else 
{
	$DEV = true;
	$mysql_server = "localhost";
	$mysql_user = "root";
	$mysql_password = "R0it";
	$mysql_db = "sr";
	
	$CabalLoginDebugUrl = "http://localhost/Cabal/cabal_login_page.php";
	$ScoutDebugUrl = "http://localhost/Cabal/Scout/scout.php";
	$DossierDebugUrl = "http://localhost/Cabal/Dossier/Dossier.php";
}
?>
