<?php
session_start();
include("../connect_to_database.php");
include("../login.php");
global $DEV;
ob_start("ob_gzhandler");

if ($DEV)
{
	//for testing only this makes sure we are logged in
	$logged_in = true;
}
else
{
	$logged_in = checkLogin();
}

if (!$logged_in) 
{
	//return back to the login page
	if ($DEV)
	{
		header('Location: ' . $CabalLoginDebugUrl  . '?where=scout');
	}
	else
	{
		header('Location: ' . $CabalLoginWebUrl  . '?where=scout');
	}
	exit;
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");						// Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");		// always modified
header("Cache-Control: no-store, no-cache, must-revalidate");		// HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);			// HTTP/1.1
header("Pragma: no-cache");													// HTTP/1.0
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
<META NAME="ROBOTS" CONTENT="noARCHIVE">
<TITLE> Scout Reports </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<LINK rel="stylesheet" type="text/css" href="./scout.css">
<LINK rel="stylesheet" type="text/css" href="./scout_dialog.css">
<script language="javascript" type="text/javascript" src="./scout_dialog.js"></script>
<script language="Javascript" type="text/javascript" src="./1k_standards.js"></script>
<script language="Javascript" type="text/javascript" src="./scout.js"></script>
</HEAD>

<BODY onload="getSummary()">
<div id="popup_layer" class="text"></div>
<form id="Editbox" name="Editbox" action="">

<table width=100% border=0 bgcolor="#4682B4">
  <tr>
	<td width="20%">
	  <img id="btn_dialog" src="./Images/btn_date_up.gif" onClick="toggleInput()"
		onmouseover="calSwapImg('btn_dialog', 'img_Date_OVER',true);"
		onmouseout="calSwapImg('btn_dialog', 'img_Date_UP',true);"
		title="Input Scout Report" alt="">
	</td>
	<td width="60%" align="center">Scouting Reports</td>
	<td width="20%"></td>
  </tr>
  <tr>
	<td colspan="3"><div id="summary"></div></td>
  </tr>
</table>

<div class=spacer>&nbsp;</div>
<table width=100% border=0 bgcolor="#7BA3C9">
  <tr>
	<td width="20%"></td>
	<td width="60%" align="center">Planet Detail</td>
	<td width="20%"><div id="result"></div></td>
  </tr>
  <tr>
	<td colspan="3"><div id="planet"></div></td>
  </tr>
</table>
<div class=spacer>&nbsp;</div>
<table width=100% border=0 bgcolor="#B0C4DE">
  <tr>
	<td width="20%"></td>
	<td width="60%" align="center">Scout Report</td>
	<td width="20%"></td>
  </tr>
  <tr>
	<td colspan="3"><div id="detail"></div></td>
  </tr>
</table>
</form>
<div id="hiddenHolder" class="hidden"></div>
</BODY>
</HTML>
