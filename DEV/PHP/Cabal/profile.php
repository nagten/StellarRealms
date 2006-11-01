<?php
session_start(); 
include("connect_to_database.php");
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$username = $_POST['username'];
	$oldpwd   = $_POST['oldpwd'];
	$newpwd   = $_POST['newpwd'];

	$md5oldpwd = md5($oldpwd);
	$md5newpwd = md5($newpwd);

	$SQL  = 'SELECT * FROM tblusers ';
	$SQL .= 'WHERE username = \'' . $username . '\' ';
	$SQL .= 'AND password = \'' . $md5oldpwd . '\' ';
	$result = mysql_query($SQL,$dbconn);
	
	if (mysql_num_rows($result) > 0) 
	{
		$SQL  = 'UPDATE tblusers ';
		$SQL .= 'SET password = \'' . $md5newpwd . '\' ';
		$SQL .= 'WHERE username = \'' . $username . '\' ';
		$result = mysql_query($SQL,$dbconn);
		
		if (!$result)
		{
			echo 'Invalid query: ' . mysql_error();
		}
		else
		{
			$_SESSION['reguname'] = $username;
			$_SESSION['password'] = $md5newpwd;
			$_SESSION['registered'] = true;
			
			echo "Password for user  " . $username . " has been changed"; 	
		}

		/*
		if ($DEV)
		{
			header('Location: $DossierDebugUrl');
		}
		else
		{
			header('Location: $DossierWebUrl');
		}
		*/
		exit;
	} 
	else 
	{
		$msg = 'Update failed. Invalid user name or password';
	}
}
?>
<HTML>
<HEAD>
<TITLE> Profile </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<script language="Javascript" type="text/javascript" src="./1k_standards.js"></script>
<script language="Javascript" type="text/javascript">
<!--
function submitCheck() 
{
	var pwd1 = gE('newpwd').value;
	var pwd2 = gE('cnfpwd').value;

	if (pwd1 != pwd2) {
		alert ('Confirm password must match new password, please try again');
		gE('newpwd').value = '';
		gE('cnfpwd').value = '';
		return false;
	} else {
		return true;
	}
}

function submitTZ() 
{
	return false;
}

function cancelProfile() 
{
	window.location = 'http://www.alignus.com/Cabal/Dossier.php';
	//window.location = 'http://www.alignus.com/Murc/Dossier.php';
	//window.location = 'http://www.alignus.com/Shriner/Dossier.php';
}

function init() 
{
	gE('oldpwd').focus();
}

function glowObj(obj) 
{
	obj.style.backgroundColor = '#FFFF00';
}

function dimObj(obj) 
{
	obj.style.backgroundColor = '#FFFFFF';
}
-->
</script>
</HEAD>
<BODY onload=init()>
<h2>PROFILE: <?php echo $_SESSION['username'] ?></h2>
<table border=0>
  <tr><td>
	<!-- Password Table -->

<form name="profile" action="profile.php" method="post" onsubmit="return submitCheck()">
	<table  align=left border=0 bgcolor=#00FFFF>
	  <tr><td>&nbsp;Change Password</td></tr>
	  <tr><td>
		<table width=100% border="0" cellspacing="1" cellpadding="1" bgcolor=#FFFFFF>
		  <tr><td class=hmr>Username:</td><td><input type="text" name="username" maxlength="30" value="<?php echo $_SESSION['username'] ?>" onFocus="glowObj(this)" onBlur="dimObj(this)"></td></tr>
		  <tr><td class=hmr>Old Password:</td><td><input type="password" name="oldpwd" id="oldpwd" maxlength="30" onFocus="glowObj(this)" onBlur="dimObj(this)"></td></tr>
		  <tr><td class=hmr>New Password:</td><td><input type="password" name="newpwd" id="newpwd" maxlength="30" onFocus="glowObj(this)" onBlur="dimObj(this)"></td></tr>
		  <tr><td class=hmr>Confirm Password:</td><td><input type="password" name="cnfpwd" id="cnfpwd" maxlength="30" onFocus="glowObj(this)" onBlur="dimObj(this)"></td></tr>
		  <tr><td colspan=2 class="error"><?php echo $msg ?></td></tr>
		  <tr><td colspan="2" align="center">
			<input type="submit" name="submit" id="submit" value="Submit" >
			<input type="button" name="cancel" id="cancel" value="Cancel" onClick="cancelProfile()"></td></tr>
		</table>
	  </td></tr>
	</table>
</form>
</td></tr>
<tr><td><br><br></td></tr>
<tr><td>

	<!-- Time Zone -->
<!--	<form name="profile" action="rjb_profile.php" method="post" onsubmit="return submitTZ()">
	<table  align=left border=0 bgcolor=#DCDCDC>
	  <tr><td>&nbsp;Time Zone (not active yet)</td></tr>
	  <tr><td>
		<table width=100% border=0 bgcolor=#FFFFFF>
		  <tr><td class=hmr>Time Zone</td><td>
				<select name="tzone" id="tzone" size=1>
					<option value="-12">(GMT-12:00) Eniwetok</option>
					<option value="-11">(GMT-11:00) Samoa</option>
					<option value="-10">(GMT-10:00) Hawaii</option>
					<option value="-9">(GMT-09:00) Alaska</option>
					<option value="-8">(GMT-08:00) Pacific</option>
					<option value="-7">(GMT-07:00) Mountain</option>
					<option value="-6">(GMT-06:00) Central</option>
					<option value="-5">(GMT-05:00) Eastern</option>
					<option value="-4">(GMT-04:00) Atlantic</option>
					<option value="-3">(GMT-03:00) Greenland</option>
					<option value="-2">(GMT-02:00) Mid-Atlantic</option>
					<option value="-1">(GMT-01:00) Azores</option>
					<option value="+0">(GMT+00:00) London</option>
					<option value="+1">(GMT+01:00) Amsterdam</option>
					<option value="+2">(GMT+02:00) Istanbul</option>
					<option value="+3">(GMT+03:00) Moscow</option>
					<option value="+4">(GMT+04:00) Baku</option>
					<option value="+5">(GMT+05:00) Islambad</option>
					<option value="+6">(GMT+06:00) Novosibirsk</option>
					<option value="+7">(GMT+07:00) Bangkok</option>
					<option value="+8">(GMT+08:00) Hong Kong</option>
					<option value="+9">(GMT+09:00) Tokyo</option>
					<option value="+10">(GMT+10:00) Melbourne</option>
					<option value="+11">(GMT+11:00) Solomon Is.</option>
					<option value="+12">(GMT+12:00) Auckland</option>
				</select>
		  </td></tr>
		  <tr><td colspan="2" align=center><button>Set</button></td></tr>
		</table>
	  </td></tr>
	</table>
</form>
-->
  </td></tr>
</table>
</BODY>
</HTML>
