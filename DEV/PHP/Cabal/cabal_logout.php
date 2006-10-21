<?php
session_start(); 
include("cabal_database.php");
include("cabal_login.php");
global $DEV;

// Delete cookies - the time must be in the past, so just negate what you added when creating the cookie.
if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass']))
{
	setcookie("cookname", "", time()-60*60*24*100, "/");
	setcookie("cookpass", "", time()-60*60*24*100, "/");
}

$logged_in = $_SESSION['logged_in'];

if ($logged_in)
{
	// Kill session variables
	unset($_SESSION['username']);
	unset($_SESSION['password']);
	$_SESSION = array(); // reset session array
	session_destroy(); // destroy session.
}

if ($DEV)
{
	header('Location: $DossierDebugUrl');
}
else
{
	header('Location: $DossierWebUrl');
}
exit;
?>
