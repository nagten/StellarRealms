<?php

// Checks whether or not the given username is in the database, if so it checks if the given password is
// the same password in the database for that user. If the user doesn't exist or if the passwords don't
// match up, it returns an error code (1 or 2).   On success it returns 0.
function confirmUser($username, $password) 
{
	global $dbconn;
   
	if (!get_magic_quotes_gpc()) //Gets the current configuration setting of magic quotes gpc
	{	
		// Add slashes if necessary (for query) 
		$username = addslashes($username);
	}

	// Verify that user is in database
	$SQL = "SELECT * FROM tblusers WHERE username = '$username'";
	$result = mysql_query($SQL, $dbconn);
	
	if ( ! $result || (mysql_numrows($result) < 1))
	{
		//Indicates username failure
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
function checkLogin() 
{
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
?>
