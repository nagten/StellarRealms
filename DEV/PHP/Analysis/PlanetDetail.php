<script type="text/javascript">
var now = new Date();
// Get the absolute client-to-GMT time zone offset in minutes.
var timezoneOffset = (now.getTimezoneOffset()*60)*(-1);
// Create the cookie string.
var cookieVar = "timezoneOffset=" + timezoneOffset;
// Set the cookie if it is not set already.
if (document.cookie.indexOf(cookieVar) < 0) document.cookie = cookieVar;
</script>

<?php
ob_start("ob_gzhandler");
$timestart = microtime_float();
include("../connect_to_database.php");

// Check if the cookie is set already
if (! isset($_COOKIE['timezoneOffset']))
{
  $clientGMT = 0;
}
else
{
  //client-to-server time zone offset
  $clientGMT = $_COOKIE['timezoneOffset'];
}

$serverGMT = intval( date('Z') );

if (isset($_REQUEST['Planet']))
{
	$PlanetID = trim($_REQUEST['Planet']);
}
else
{
	$PlanetID = '';
}

write_to_log($_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_URI']);

$SQL = 'SELECT * FROM tblControl WHERE Active = \'Y\' ';
$result = mysql_query($SQL);

if (!$result) die('Invalid query: ' . mysql_error());

while ($row = mysql_fetch_assoc($result))
{
	$dbRoundNumber = $row['RoundNumber'];
	$dbRoundName   = $row['RoundName'];
	$dbTurn        = $row['Turn'];
	$dbUpdateDate  = $row['UpdateDate'];
	$dbUpdateTime  = $row['UpdateTime'];
	$dbRoundLength = $row['RoundLength'];
}

mysql_free_result($result);

$planets = array();
$pIndex  = array();
$found = false;

$SQL = 'SELECT * FROM tblplanet ORDER BY Rank';
$result = mysql_query($SQL);

if (!$result)
{
	die('Invalid query: ' . mysql_error());
}

$indx = 0;

while ($row = mysql_fetch_assoc($result))
{
	$pID      = $row['RecordNumber'];
	$pName    = stripslashes($row['PlanetName']);
	$pLeader  = stripslashes($row['Leader']);
	$pSpecies = stripslashes($row['Species']);
	$pRank    = $row['Rank'];

	$indx++;
	$pIndex[$indx] = $pName;

	$planets[$pName]['ID'] = $pID;
	$planets[$pName]['Name'] = $pName;
	$planets[$pName]['Leader'] = $pLeader;
	$planets[$pName]['Species'] = $pSpecies;
	$planets[$pName]['Rank'] = $pRank;

	if ($pName == stripslashes($PlanetID))
	{
		$found = true;
	}
}

mysql_free_result($result);

if ($PlanetID == '')
{
	$errPlanet = '[Not Specified]';
}
else
{
	$errPlanet = $PlanetID;
}

if ($found == false)
{
	die("SORRY !!! Planet $errPlanet was not found in this galaxy!<br>Please try again, or visit another galaxy.<br>Have a nice day!");
}

$rankings = array();
$prestige = array();
//$online   = array();
$updates  = array();

$PlanetID = stripslashes($PlanetID);
$pKey = addslashes($planets[$PlanetID]['ID']);

//echo $PlanetID . ' ' . $pKey . '<br>';

$SQL = 'SELECT * FROM tblStanding WHERE PlanetID = \'' . $pKey . '\' ORDER BY Turn DESC';
$result = mysql_query($SQL);

if (!$result)
{
	die('Invalid query: ' . mysql_error());
}

while ($row = mysql_fetch_assoc($result))
{
	$pID       = $row['PlanetID'];
	$pTurn     = $row['Turn'];
	$pRank     = $row['Rank'];
	$pPrestige = $row['Prestige'];
	//$pOnLine   = $row['OnLine'];
	$pUpdateDate = $row['UpdateDate'];
	$pUpdateTime = $row['UpdateTime'];
	$rankings[$pID][$pTurn] = $pRank;
	$prestige[$pID][$pTurn] = $pPrestige;
	//$online[$pID][$pTurn]   = $pOnLine;
	$updates[$pID][$pTurn]  = $pUpdateDate . ' ' . $pUpdateTime;
}
mysql_free_result($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> SR Planet Detail </TITLE>
<style type="text/css">
	body    {font-family:arial;}
	.srxx   {font-size:18px; color:#FFFFFF;}
	.srx    {font-size:12px; color:#FFFFFF;}
	.sh     {font-size:12pt; font-weight:bold;}
	.hl     {font-size:8pt; background-color:#DCDCDC;}
	.hc     {font-size:8pt; background-color:#DCDCDC; text-align:center;}
	.xx     {font-size:8pt;}
	.xc     {font-size:8pt; text-align:center;}
	.xr     {font-size:8pt; text-align:right;}
</style>
<script language="javascript" type="text/javascript">
	function init()
	{
		window.focus();
	}
</script>
</HEAD>

<BODY onload="init()">
<?php
	$pCount = count($pIndex);

	if ($PlanetID != '')
	{
		$key = urldecode($PlanetID);
	}
	else
	{
		$key = $pIndex[1];
	}

	$id      = $planets[$key]['ID'];
	$name    = $planets[$key]['Name'];
	$leader  = $planets[$key]['Leader'];
	$species = $planets[$key]['Species'];
	$rank    = $planets[$key]['Rank'];

	echo display_header();
	echo '<center><font size=5 color=#FF0000><b>' . $name . ' (' . $species . ')' . ' Planet Detail</b></font></center>';
	echo '<table id=overview width=100% border=0 bgcolor=#B0C4DE>';
	echo '<tr><td width=50% class=sh>&nbsp;&nbsp;' . $name . ' (' . $species . ')' . ' History</td><td width=50% align=right><font size=1><a href=./Help.html target=SR_Help>HELP</a></font>&nbsp;</td></tr>';
	echo '<tr><td colspan=2>';
	echo '<table width=100% cellspacing=1 bgcolor=#FFFFFF>';
	echo '<tr>';
	echo '<td class=hc>Turn</td>';
	echo '<td class=hc>Ago</td>';
	echo '<td class=hl>Game Date</td>';
	echo '<td class=hl>Live Date</td>';
	//echo '<td class=hc>Online</td>';
	echo '<td class=hc>Rank</td>';
	echo '<td class=hc>Change</td>';
	echo '<td class=hc>Prestige</td>';
	echo '<td class=hc>Change</td>';
	echo '</tr>';
//echo $id . '<br>';
	$bgcolor = '#F5F5F5';
	for ($x=$dbTurn;$x>0;$x--)
	{
		if (array_key_exists($x,$rankings[$id]))
		{
			if ($bgcolor == '#F5F5F5')
			{
				$bgcolor = '#FFFFFF';
			}
			else
			{
				$bgcolor = '#F5F5F5';
			}

			echo '<tr bgcolor=' . $bgcolor . '>';
			echo '<td>'          . $x                      . '</td>';
			echo '<td class=xc>(' . ($x - $dbTurn)         . ')</td>';
			echo '<td class=xx>' . gamedate($x)            . '</td>';
			echo '<td class=xx>' . livedate($id,$x) . '</td>';
			//echo '<td class=xc>' . onlinestatus($id,$x)    . '</td>';
			echo '<td class=xc>' . $rankings[$id][$x]      . '</td>';
			echo '<td class=xc>' . rankdelta($id,$x,1)     . '</td>';
			echo '<td class=xr>' . number_format($prestige[$id][$x])      . '</td>';
			echo '<td class=xr>' . number_format(prestigedelta($id,$x,1)) . '</td>';
			echo '</tr>';
		}
	}
	echo '</table>';
	echo '</td></tr>';
	echo '</table>';
	echo '<br><br>';

function gamedate($t)
{
	$eYears = floor($t / 12);
	$eMonths = fmod($t,12);
	$gDate = mktime(0,0,0,$eMonths+1,1,2000);
	$curMonth = date('F',$gDate);
	$curYear  = date('Y',$gDate) + $eYears + 700;
	return $curMonth . ' ' . $curYear;
}

function livedate($id,$t)
{
	global $updates;
	global $clientGMT;
	global $serverGMT;

	if (array_key_exists($t,$updates[$id]))
	{
	}

	if ($updates[$id][$t] != '0000-00-00 00:00:00')
	{
		$updTime = strtotime($updates[$id][$t]);
		// +3600 daylight saving time dst
		return gmdate('D d M Y  H:i:s O T',$updTime + 3600) . ' (Local time: ' . date('H:i:s',$updTime + ($clientGMT - $serverGMT)) . ')';
	}
	else
	{
		$gMin = $t * 20;
		$eDays = floor($gMin / 1440);
		$eMins = $gMin - ($eDays * 1440);
		$rlDate = mktime(0,$eMins,0,3,17+$eDays,2005);
		return date('D d M Y  H:i:s T',$rlDate);
	}
}

function rankdelta($pid,$x,$mths)
{
	global $rankings;

	$t = $x - $mths;
	if (array_key_exists($t,$rankings[$pid]))
	{
		return $rankings[$pid][$t] - $rankings[$pid][$x];
	}
	else
	{
		return '';
	}
}

function prestigedelta($pid,$x,$mths)
{
	global $prestige;

	$t = $x - $mths;
	if (array_key_exists($t,$prestige[$pid]))
	{
		return sprintf('%d',($prestige[$pid][$x] - $prestige[$pid][$t]));
	}
	else
	{
		return '';
	}
}

function prestigenext($x,$t)
{
	global $planets;
	global $prestige;
	global $pIndex;

	if ($x > 1)
	{
		$key1 = $planets[$pIndex[$x-1]]['ID'];
		$key2 = $planets[$pIndex[$x-0]]['ID'];
		return $prestige[$key1][$t] - $prestige[$key2][$t];
	}
	else
	{
		return '';
	}
}

function onlinestatus($pid,$t)
{
	global $online;
	if ($online[$pid][$t] == 'y')
	{
		return 'Yes';
	}
	else
	{
		return 'No';
	}
}

function display_header()
{
	global $dbRoundName;
	global $dbTurn;
	global $dbUpdateDate;
	global $dbUpdateTime;
	global $dbRoundLength;

	$heading = ' - ' . $dbRoundName;
	$rMonths = ($dbRoundLength * 12) - $dbTurn;
	$rYears  = floor($rMonths /12);
	$rString = sprintf('%d',$rYears) . ' Years, ' . sprintf('%d',$rMonths - ($rYears * 12)) . ' Months';
	$rMinutes = $rMonths * 20;
	$rHours   = floor($rMinutes / 60);
	$rDays    = floor($rHours / 24);
	$rHrs     = fmod($rHours,24);
	$rMins    = $rMinutes - ($rHours * 60);
	$rlRemaining = $rDays . ' Days ' . $rHrs . ' Hrs ' . $rMins . ' Mins';
	$strTime = $dbUpdateDate . ' ' . $dbUpdateTime;
	$updTime = strtotime($strTime);

	$r  = '<table width=100% border=0 bgcolor=#000000>';
	$r .= '<tr><td width=100%><span class=srxx>&nbsp;ANALYSIS: Stellar Realms</span><span class=srx>' . $heading . '</span></td></tr>';
	$r .= '<tr><td>';
	$r .= '<table width=100% border=0 bgcolor=#FFFFFF cellspacing=1>';
	$r .= '<tr>';
	$r .= '<td class=hc>Turn</td>';
	$r .= '<td class=hc>Game Date</td>';
	$r .= '<td class=hc>Turns Remaining</td>';
	$r .= '<td class=hc>Game Time Remaining</td>';
	$r .= '<td class=hc>Real Time Remaining</td>';
	$r .= '<td class=hc>Last Update</td>';
	$r .= '</tr>';
	$r .= '<tr>';
	$r .= '<td class=xc>' . number_format($dbTurn) . '</td>';
	$r .= '<td class=xc>' . gamedate($dbTurn) . '</td>';
	$r .= '<td class=xc>' . number_format($rMonths) . '</td>';
	$r .= '<td class=xc>' . $rString . '</td>';
	$r .= '<td class=xc>' . $rlRemaining . '</td>';
	$r .= '<td class=xc>' . gmdate('d M y  |  H:i:s T',$updTime) . '</td>';
	$r .= '</tr>';
	$r .= '</table>';
	$r .= '</td></tr>';
	$r .= '</table>';
	$r .= '<br>';
	return $r;
}

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

$timeend = microtime_float();
$elasped = ($timeend - $timestart);
echo '<font size=1>Page loaded in ' . sprintf('%.2f',$elasped) . ' seconds.</font>';
?>
</BODY>
</HTML>