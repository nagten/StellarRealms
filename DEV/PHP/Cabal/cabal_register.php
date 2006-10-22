<?php
session_start(); 
include("cabal_database.php");

//Returns true if the username has been taken by another user, false otherwise.
function usernameTaken($username)
{
	global $dbconn;
	if(!get_magic_quotes_gpc()) 
	{
		$username = addslashes($username);
	}
	
	$q = "select username from users where username = '$username'";
	$result = mysql_query($q,$dbconn);
	return (mysql_numrows($result) > 0);
}

// Inserts the given (username, password) pair into the database. Returns true on success, false otherwise.
function addNewUser($username, $password) 
{
	global $dbconn;
	$SQL  = 'INSERT INTO tblusers (username, password) Values (';
	$SQL .= '\'' . $username . '\', ';
	$SQL .= '\'' . $password . '\') ';
	echo $SQL . '<br>';
	$result = mysql_query($SQL,$dbconn);
	if (!$result) die('Invalid query: ' . mysql_error());
	
	return $result;
}

// Displays the appropriate message to the user after the registration attempt. 
// It displays a  success or failure status depending on a session variable set during registration.
function displayStatus() 
{
	$uname = $_SESSION['reguname'];
	if ($_SESSION['regresult']) 
	{
		echo '<h1>Registered!</h1>';
		echo '<p>Thank you <b><?php echo $uname; ?></b>, your information has been added to the database, you may now <a href="rjb_main.php" title="Login">log in</a>.</p>';
	} 
	else 
	{
		echo '<h1>Registration Failed</h1>';
		echo '<p>We\'re sorry, but an error has occurred and your registration for the username <b>' . $uname . '</b>, could not be completed.<br>';
		echo 'Please try again at a later time.</p>';
		unset($_SESSION['reguname']);
		unset($_SESSION['registered']);
		unset($_SESSION['regresult']);
	}
}

unset($_SESSION['registered']);

if (isset($_SESSION['registered'])) 
{
	// This is the page that will be displayed after the registration has been attempted.
	?>
	
	<html>
	<title>Registration Page</title>
	<body>
	<?php displayStatus(); ?>
	</body>
	</html>
	<?php
	   return;
}

// Determines whether or not to show to sign-up form based on whether the form has been submitted, if it
// has, check the database for consistency and create the new account.

if (isset($_POST['subjoin'])) 
{
	// Make sure all fields were entered 
	if ( ! $_POST['user'] || ! $_POST['pass']) 
	{
		die('You didn\'t fill in a required field.');
	}

	// Spruce up username, check length 
	$_POST['user'] = trim($_POST['user']);
	if (strlen($_POST['user']) > 30) 
	{
		die("Sorry, the username is longer than 30 characters, please shorten it.");
	}

	// Check if username is already in use 
	if (usernameTaken($_POST['user'])) 
	{
		$use = $_POST['user'];
		die("Sorry, the username: <strong>$use</strong> is already taken, please pick another one.");
	}

	// Add the new account to the database 
	$md5pass = md5($_POST['pass']);
	$md5user = md5($_POST['user']);
	$_SESSION['reguname'] = $_POST['user'];
	$_SESSION['regresult'] = addNewUser($_POST['user'], $md5pass);
	//$_SESSION['registered'] = true;
	echo "<meta http-equiv=\"Refresh\" content=\"0;url=$HTTP_SERVER_VARS[PHP_SELF]\">";
	return;
} 
else 
{
	echo '';
	echo '';
	echo '';
	echo '';
?>
<html>
<title>Registration Page</title>
<body>
<h1>Register</h1>
<form action="cabal_register.php" method="post">
<table align="left" border="0" cellspacing="0" cellpadding="3">
<tr><td>Username:</td><td><input type="text" name="user" maxlength="30"></td></tr>
<tr><td>Password:</td><td><input type="password" name="pass" maxlength="30"></td></tr>
<tr><td colspan="2" align="right"><input type="submit" name="subjoin" value="Join!"></td></tr>
</table>
</form>
</body>
</html>
<?php
}
?>
