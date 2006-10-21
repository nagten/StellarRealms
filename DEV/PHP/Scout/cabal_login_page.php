<?php

session_start();
include("cabal_database.php");
include("cabal_login.php");

$errMsg = '';
$success = false;

if (isset($_REQUEST['where'])) 
{
	$where = $_REQUEST['where'];
} 
else 
{
	$where = 'scout';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   if (!$_POST['user'] || !$_POST['pass']) {
      $errMsg = 'You didn\'t fill in a required field.';
   }

   /* Spruce up username, check length */
   $_POST['user'] = trim($_POST['user']);
   if (strlen($_POST['user']) > 30) {
      $errMsg = "Sorry, the username is longer than 30 characters, please shorten it.";
   }

   /* Checks that username is in database and password is correct */
   $md5pass = md5($_POST['pass']);
   $result = confirmUser($_POST['user'], $md5pass);

   /* Check result error codes */
   if ($result == 0) {
		$success = true;
	} elseif ($result == 1) {
      $errMsg = 'That username doesn\'t exist in our database.';
   } else if($result == 2) {
      $errMsg = 'Incorrect password, please try again.';
   }

	if ($success) {
		// Username and password correct, register session variables
		$_POST['user'] = stripslashes($_POST['user']);
		$_SESSION['username'] = $_POST['user'];
		$_SESSION['password'] = $md5pass;
		$_SESSION['logged_in'] = true;

		if (isset($_POST['remember'])) {
			setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
			setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
		}
		if (isset($_POST['where'])) {
			$where = $_POST['where'];
		} else {
			$where = 'main';
		}
		switch ($where) {
			case 'dossier':
				header('Location: $dossierWebUrl');
				exit;
				break;
			case 'scout':
				if ($debugDev)
				{
					header('Location: ' . $scoutDebugUrl . '');
				}
				else
				{
					header('Location: ' . $scoutWebUrl . '');
				}
				
				exit;
				break;
			default:
				header('Location: ' . $dossierWebUrl . '');
				exit;
				break;
		}
	}
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<LINK rel="stylesheet" type="text/css" href="./SR_Dossier.css">
<script language="Javascript" type="text/javascript">
function glowObj(obj) {
	obj.style.backgroundColor = '#FFFF00';
}

function dimObj(obj) {
	obj.style.backgroundColor = '#FFFFFF';
}
</script>
</HEAD>

<BODY>

<h2>Login</h2>

		<form action="" method="post">
		<input type="hidden" id="where" name="where" value="<?php echo $where ?>">
		<table align="left" border="0" cellspacing="0" cellpadding="3">
		<tr><td>Username:</td><td><input type="text" name="user" maxlength="30" onFocus="glowObj(this)" onBlur="dimObj(this)"></td></tr>
		<tr><td>Password:</td><td><input type="password" name="pass" maxlength="30" onFocus="glowObj(this)" onBlur="dimObj(this)"></td></tr>
		<tr><td colspan="2" align="left"><input type="checkbox" name="remember">
		<font size="2">Remember me next time</td></tr>
		<tr><td colspan="2" class="error"><?php echo $errMsg ?></td></tr>
		<tr><td colspan="2" align="center"><input type="submit" name="sublogin" value="Login"></td></tr>
		</table>
		</form>
</BODY>
</HTML>
