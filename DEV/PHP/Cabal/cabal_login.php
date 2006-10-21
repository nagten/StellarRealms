<?php

//Variable set for debugging
$DEV = true;

$CabalLoginDebugUrl = "http://localhost/Cabal/cabal_login_page.php";
$ScoutDebugUrl = "http://localhost/Cabal/Scout/scout.php";
$DossierDebugUrl = "http://localhost/Cabal/Dossier/Dossier.php";

$CabalLoginWebUrl = "http://www.idsfadt.com/Cabal/cabal_login_page.php";
$ScoutWebUrl = "http://www.idsfadt.com/Cabal/Scout/scout.php";
$DossierWebUrl = "http://www.idsfadt.com/Cabal/Dossier/Dossier.php";

// Checks whether or not the given username is in the database, if so it checks if the given password is
// the same password in the database for that user. If the user doesn't exist or if the passwords don't
// match up, it returns an error code (1 or 2).   On success it returns 0.
function confirmUser($username, $password) {
   global $dbconn;
   
	if ( ! get_magic_quotes_gpc()) 
	{	
		// Add slashes if necessary (for query) 
		$username = addslashes($username);
	}

	// Verify that user is in database
	$SQL = "SELECT * FROM users WHERE username = '$username'";
	$result = mysql_query($SQL,$dbconn);
	if ( ! $result || (mysql_numrows($result) < 1))
	{
		// Indicates username failure
		return 1;
	}
	
	// Retrieve password from result, strip slashes
	$dbarray = mysql_fetch_array($result);
	$dbarray['password']  = stripslashes($dbarray['password']);
	$password = stripslashes($password);
	$userid = $dbarray['userid'];

	// Validate that password is correct/
	if ($password == $dbarray['password']) 
	{
		$_SESSION['userid'] = $userid;
		//Success! Username and password confirmed
		return 0;
	} 
	else 
	{
		//Indicates password failure
		return 2;
	}
}

// checkLogin - Checks if the user has already previously logged in, and a session with the user has already been
// established. Also checks to see if user has been remembered.  If so, the database is queried to make sure of the user's 
// authenticity. Returns true if the user has logged in.
function checkLogin() {
	// Check if user has been remembered 
	if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass']))
	{
		$_SESSION['username'] = $_COOKIE['cookname'];
		$_SESSION['password'] = $_COOKIE['cookpass'];
	}

	/* Username and password have been set */
	if (isset($_SESSION['username']) && isset($_SESSION['password'])) 
	{
		if (confirmUser($_SESSION['username'], $_SESSION['password']) == 0) 
		{
			$_SESSION['logged_in'] = true;
			return true;
		}
		else
		{
			/* Variables are incorrect, user not logged in */
			unset($_SESSION['username']);
         	unset($_SESSION['password']);
         	unset($_SESSION['userid']);
         	unset($_SESSION['logged_in']);
         	return false;
         }
	}
	else
	{
		// User not logged in
		return false;
	}
}

// Determines whether or not to display the login form or to show the user that he is logged in based on if the session variables are set.
function displayLogin() {
	global $logged_in;
	global $DEV;
	
	if ($logged_in) {
		//echo "<h1>Logged In!</h1>";
		//echo "Welcome <b>$_SESSION[username]</b>, you are logged in. <a href=\"rjb_logout.php\">Logout</a>";
	} 
	else 
	{
		if ($DEV)
		{
			header('Location: $scoutLoginDebugUrl');
		}
		else
		{
			header('Location: $scoutLoginWebUrl');
		}
		
		exit;
		/*
		echo '<h1>Login</h1>';
		echo '<form action="" method="post">';
		echo '<table align="left" border="0" cellspacing="0" cellpadding="3">';
		echo '<tr><td>Username:</td><td><input type="text" name="user" maxlength="30"></td></tr>';
		echo '<tr><td>Password:</td><td><input type="password" name="pass" maxlength="30"></td></tr>';
		echo '<tr><td colspan="2" align="left"><input type="checkbox" name="remember">';
		echo '<font size="2">Remember me next time</td></tr>';
		echo '<tr><td colspan="2" align="right"><input type="submit" name="sublogin" value="Login"></td></tr>';
		echo '<tr><td colspan="2" align="left"><a href="rjb.register.php">Join</a></td></tr>';
		echo '</table>';
		echo '</form>';
		*/
   }
}

// Checks to see if the user has submitted his username and password through the login form,
// if so, checks authenticity in database and creates session.
/*
if (isset($_POST['sublogin'])) {
   // Check that all fields were typed in
   if (!$_POST['user'] || !$_POST['pass']) {
      die('You didn\'t fill in a required field.');
   }

   // Spruce up username, check length
   $_POST['user'] = trim($_POST['user']);
   if (strlen($_POST['user']) > 30) {
      die("Sorry, the username is longer than 30 characters, please shorten it.");
   }

   // Checks that username is in database and password is correct
   $md5pass = md5($_POST['pass']);
   $result = confirmUser($_POST['user'], $md5pass);

   // Check error codes
   if($result == 1) {
      die('That username doesn\'t exist in our database.');
   } else if($result == 2) {
      die('Incorrect password, please try again.');
   }

   // Username and password correct, register session variables
   $_POST['user'] = stripslashes($_POST['user']);
   $_SESSION['username'] = $_POST['user'];
   $_SESSION['password'] = $md5pass;

   //
    * This is the cool part: the user has requested that we remember that
    * he's logged in, so we set two cookies. One to hold his username,
    * and one to hold his md5 encrypted password. We set them both to
    * expire in 100 days. Now, next time he comes to our site, we will
    * log him in automatically.
    
   if (isset($_POST['remember'])) {
      setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
      setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
   }

   // Quick self-redirect to avoid resending data on refresh
   echo "<meta http-equiv=\"Refresh\" content=\"0;url=$HTTP_SERVER_VARS[PHP_SELF]\">";
   return;
}
*/

/* Sets the value of the logged_in variable, which can be used in your code */
//$logged_in = checkLogin();
?>
