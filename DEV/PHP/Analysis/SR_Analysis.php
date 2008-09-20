<?php 
ob_start("ob_gzhandler");
require('SR_Class.php');
$timestart = microtime_float();

write_to_log($_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_URI']);

if (isset($_REQUEST['planet'])) 
{
	$PlanetID = trim($_REQUEST['planet']);
}
else 
{
	$PlanetID = '';
}
$sr = new SR;
$sr->planet_ID = stripslashes($PlanetID);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> SR Analyst Report </TITLE>
<LINK rel="stylesheet" type="text/css" href="./sr.css">

<script language="Javascript" type="text/javascript" src="./1k_standards.js"></script>
<script language="javascript" type="text/javascript" src="./sr_analysis.js"></script>

</HEAD>
<BODY>
<?php 
echo $sr->display_header();
echo $sr->display_menu();

//$sr->calc_metrics();
$sr->calc_threats();

echo $sr->display_overview();
echo $sr->display_gainloss();
echo $sr->display_rankhistory();
echo $sr->display_prestige_average();
echo $sr->display_threats();
echo $sr->display_prestige_history();

echo $sr->display_menu();

$timeend = microtime_float();
$elasped = ($timeend - $timestart);

echo '<table width=100% border=0>';
echo'<tr>';
echo '<td width=50%><font size=1>Page loaded in ' . sprintf('%.2f',$elasped) . ' seconds.</font></td>';
echo '</tr>';
echo '</table>';

function microtime_float() 
{ 
   list($usec, $sec) = explode(" ", microtime()); 
   return ((float)$usec + (float)$sec); 
} 

function write_to_log($text) 
{
	$CRLF = "\r\n";

	$log_file = './Logs/access_log_' . date("mdy") . '.txt';
	$fp = fopen($log_file,'a');
	
	if ($fp) 
	{
		$msg = date("m.d.y H:i:s") . ' - ' . $text . $CRLF;
		fwrite($fp,$msg);
		fclose($fp);
	}
}
?>
</BODY>
</HTML>