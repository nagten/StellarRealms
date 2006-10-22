<?php
$p       = array();
$p1      = array();
$p2      = array();
$d1      = array();
$xp      = array();
$xd      = array();
$sort    = 'planet';

include("../cabal_database.php");

if (isset($_REQUEST['action'])) 
{
	$action = $_REQUEST['action'];
} 
else 
{
	$action = '';
}

if (isset($_REQUEST['planet'])) 
{
	$planet = $_REQUEST['planet'];
} 
else 
{
	$planet = '';
}

if (isset($_REQUEST['report'])) 
{
	$reportID = $_REQUEST['report'];
} 
else 
{
	$reportID = '';
}

if (isset($_REQUEST['sort']))
{
	$sort = $_REQUEST['sort'];
} 
else 
{
	$sort = 'planet';
}

switch ($action) 
{
	case 'summary':
		displaySummary();
		break;
	case 'planet':
		displayPlanet($planet);
		break;
	case 'detail':
		displayDetail($reportID);
		break;
}

//=============================================================================

function displaySummary() {
	global $p;
	global $p1;
	global $p2;
	global $d1;
	global $sort;

	$bgcolor = '#F5F5F5';

	$SQL  = 'SELECT RecordNumber,PlanetID,PlanetName,ReportDate,ReportTime,AirCap,';
	$SQL .= 'Fighter,IntelOps,Materials,Reproduction,Research,';
	$SQL .= 'Scouting,Sensors,Speed,Rank,HabSpace,Slots,DefMaint,OffMaint ';
	$SQL .= 'FROM tblscout ';
	$SQL .= 'WHERE Current = \'Y\' ';
	$SQL .= 'ORDER BY PlanetName,ReportDate DESC,ReportTime DESC';
	
	$result = mysql_query($SQL);
	if (!$result) die('Invalid query: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result)) 
	{
		$planetID     = $row['PlanetID'];
		$recNbr       = $row['RecordNumber'];
		
		if ( ! array_key_exists($planetID,$p)) 
		{
			$p[$planetID] = 0;
		}

		if ($p[$planetID] < 2) 
		{
			if ($p[$planetID] == 0) 
			{
				$p[$planetID] = 1;
				$p1[$planetID]['planetName']   = stripslashes($row['PlanetName']);
				$p1[$planetID]['reportDate']   = $row['ReportDate'];
				$p1[$planetID]['reportTime']   = $row['ReportTime'];
				$p1[$planetID]['airCap']       = $row['AirCap'];
				$p1[$planetID]['defMaint']     = $row['DefMaint'];
				$p1[$planetID]['fighter']      = $row['Fighter'];
				$p1[$planetID]['habSpace']     = ($row['HabSpace'] / 1000000);
				$p1[$planetID]['intelOps']     = $row['IntelOps'];
				$p1[$planetID]['materials']    = $row['Materials'];
				$p1[$planetID]['offMaint']     = $row['OffMaint'];
				$p1[$planetID]['reproduction'] = $row['Reproduction'];
				$p1[$planetID]['research']     = $row['Research'];
				$p1[$planetID]['scouting']     = $row['Scouting'];
				$p1[$planetID]['sensors']      = $row['Sensors'];
				$p1[$planetID]['slots']        = $row['Slots'];
				$p1[$planetID]['speed']        = $row['Speed'];
				$p1[$planetID]['rank']         = $row['Rank'];
			} 
			else 
			{
				$p[$planetID] = 2;
				$p2[$planetID]['planetName']   = stripslashes($row['PlanetName']);
				$p2[$planetID]['reportDate']   = $row['ReportDate'];
				$p2[$planetID]['reportTime']   = $row['ReportTime'];
				$p2[$planetID]['airCap']       = $row['AirCap'];
				$p2[$planetID]['defMaint']     = $row['DefMaint'];
				$p2[$planetID]['fighter']      = $row['Fighter'];
				$p2[$planetID]['habSpace']     = ($row['HabSpace'] / 1000000);
				$p2[$planetID]['intelOps']     = $row['IntelOps'];
				$p2[$planetID]['materials']    = $row['Materials'];
				$p2[$planetID]['offMaint']     = $row['OffMaint'];
				$p2[$planetID]['reproduction'] = $row['Reproduction'];
				$p2[$planetID]['research']     = $row['Research'];
				$p2[$planetID]['scouting']     = $row['Scouting'];
				$p2[$planetID]['sensors']      = $row['Sensors'];
				$p2[$planetID]['sensors']      = $row['Sensors'];
				$p2[$planetID]['slots']        = $row['Slots'];
				$p2[$planetID]['speed']        = $row['Speed'];
				$p2[$planetID]['rank']         = $row['Rank'];
			}
		} 
		else 
		{
			$SQL = 'UPDATE tblscout SET Current = \'N\' WHERE RecordNumber = ' . $recNbr;
			$dummy = mysql_query($SQL);
		}
	}

	foreach ($p as $key => $value) 
	{
		if ($value > 1) 
		{
			calcSummaryDiff($key,'airCap');
			calcSummaryDiff($key,'offMaint');
			calcSummaryDiff($key,'defMaint');
			calcSummaryDiff($key,'fighter');
			calcHabitatDiff($key,'habSpace');
			calcSummaryDiff($key,'intelOps');
			calcSummaryDiff($key,'materials');
			calcSummaryDiff($key,'reproduction');
			calcSummaryDiff($key,'slots');
			calcSummaryDiff($key,'research');
			calcSummaryDiff($key,'scouting');
			calcSummaryDiff($key,'sensors');
			calcSummaryDiff($key,'speed');
		} 
		else 
		{
			$d1[$key]['airCap']       = '';
			$d1[$key]['offMaint']     = '';
			$d1[$key]['defMaint']     = '';
			$d1[$key]['fighter']      = '';
			$d1[$key]['habSpace']     = '';
			$d1[$key]['intelOps']     = '';
			$d1[$key]['materials']    = '';
			$d1[$key]['reproduction'] = '';
			$d1[$key]['slots']        = '';
			$d1[$key]['research']     = '';
			$d1[$key]['scouting']     = '';
			$d1[$key]['sensors']      = '';
			$d1[$key]['special']      = '';
			$d1[$key]['speed']        = '';
		}
	}

	switch ($sort) 
	{
		case 'planet':
			$array = $p;
			break;
		case 'rank':
			$array = array_column_sort($p1,$sort,SORT_ASC);
			break;
		default:
			$array = array_column_sort($p1,$sort,SORT_DESC);
			break;
	}

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	$r  = '';
	$r .= '<div class=divSummary>';
	$r .= '<table width=100% border=0 cellpadding=1 cellspacing=2 bgcolor=#FFFFFF>';

	$cnt     = 0;
	foreach ($array as $key => $value)
	{
		if (($cnt % 12) == 0) 
		{
			$r .= summaryColumnHeader();
		}
		
      	$cnt++;

		if ($bgcolor == '#F5F5F5') 
		{
			$bgcolor = '#FFFFFF';
		} 
		else 
		{
			$bgcolor = '#F5F5F5';
		}

		$pName = $p1[$key]['planetName'];
		$pName = substr($pName,0,15);

		$r .= '<tr bgcolor=' . $bgcolor . ' onclick="getPlanet(' . $key . ')">';
		$r .= '<td class=ull>' . $pName . '<div class=hidden id=p_' . $cnt . '>' . $key . '</div></td>';
		$r .= '<td class=xc>' . date('d-M',strtotime($p1[$key]['reportDate']))   . '</td>';
		$r .= '<td class=xc>' . date('H:i',strtotime($p1[$key]['reportTime']))   . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['rank']                        . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['airCap'])       . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['airCap']                      . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['fighter'])      . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['fighter']                     . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['offMaint'])     . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['offMaint']                    . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['defMaint'])     . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['defMaint']                    . '</td>';
		$r .= '<td class=xr>' . sprintf('%01.2f',$p1[$key]['habSpace'])  . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['habSpace']                    . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['materials']    . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['materials']    . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['reproduction'] . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['reproduction'] . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['slots']        . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['slots']        . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['research']     . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['research']     . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['sensors']      . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['sensors']      . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['speed']        . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['speed']        . '</td>';
	}

	$r .= '</table>';
	$r .= '</div>';
	echo $r;
}


function calcHabitatDiff($key,$item) 
{
	global $p1;
	global $p2;
	global $d1;

	$v1 = $p1[$key][$item];
	$v2 = $p2[$key][$item];

	$diff = $v1 - $v2;


	if ($diff > 0) 
	{
		$d1[$key][$item] = '<div class=positive>+' . sprintf('%01.2f',$diff)   . '</div>';
	} 
	elseif ($diff == 0) 
	{
		$d1[$key][$item] = '<div class=even>'      . ' '   . '</div>';
	} 
	else 
	{
		$d1[$key][$item] = '<div class=negative>' . sprintf('%01.2f',$diff)   . '</div>';
	}
}

function calcSummaryDiff($key,$item) 
{
	global $p1;
	global $p2;
	global $d1;

	$v1 = $p1[$key][$item];
	$v2 = $p2[$key][$item];

	$diff = $v1 - $v2;


	if ($diff > 0) 
	{
		$d1[$key][$item] = '<div class=positive>+' . number_format($diff)   . '</div>';
	} 
	elseif ($diff == 0) 
	{
		$d1[$key][$item] = '<div class=even>'      . ' '   . '</div>';
	} 
	else 
	{
		$d1[$key][$item] = '<div class=negative>' . number_format($diff)   . '</div>';
	}
}

function summaryColumnHeader() 
{
	global $sort;
	$col01 = 'uhc';
	$col02 = 'uhc';
	$col03 = 'uhc';
	$col04 = 'uhc';
	$col05 = 'uhc';
	$col06 = 'uhc';
	$col07 = 'uhc';
	$col08 = 'uhc';
	$col09 = 'uhc';
	$col10 = 'uhc';
	$col11 = 'uhc';
	$col12 = 'uhc';
	$col13 = 'uhc';
	$col14 = 'uhc';
	$col15 = 'uhc';
	$col16 = 'uhc';
	$col17 = 'uhc';
	$col18 = 'uhc';
	$col19 = 'uhc';
	$col20 = 'uhc';
	$col21 = 'uhc';

	switch ($sort) 
	{
		case 'planet':
			$col01 = 'shc';
			break;
		case 'airCap':
			$col04 = 'shc';
			break;
		case 'offMaint':
			$col05 = 'shc';
			break;
		case 'defMaint':
			$col06 = 'shc';
			break;
		case 'diplomacy':
			$col07 = 'shc';
			break;
		case 'fighter':
			$col08 = 'shc';
			break;
		case 'habSpace':
			$col09 = 'shc';
			break;
		case 'intelOps':
			$col10 = 'shc';
			break;
		case 'materials':
			$col11 = 'shc';
			break;
		case 'reproduction':
			$col12 = 'shc';
			break;
		case 'slots':
			$col13 = 'shc';
			break;
		case 'research':
			$col14 = 'shc';
			break;
		case 'scouting':
			$col15 = 'shc';
			break;
		case 'sensors':
			$col16 = 'shc';
			break;
		case 'special':
			$col17 = 'shc';
			break;
		case 'speed':
			$col18 = 'shc';
			break;
		case 'sensors':
			$col19 = 'shc';
			break;
		case 'wealth':
			$col20 = 'shc';
			break;
		case 'rank':
			$col21 = 'shc';
			break;
	}

	$r  = '';
	$r .= '<tr>';
	$r .= '<td class=' . $col01 . ' colspan=1 onclick=sortColumn("planet") onmouseover=glowObject(this) onmouseout=dimObject(this)>Planet</td>';
	$r .= '<td class=' . $col02 . ' colspan=1>Date</td>';
	$r .= '<td class=' . $col03 . ' colspan=1>Time</td>';
	$r .= '<td class=' . $col21 . ' colspan=1 onclick=sortColumn("rank") onmouseover=glowObject(this) onmouseout=dimObject(this)>Rank</td>';
	$r .= '<td class=' . $col04 . ' colspan=2 onclick=sortColumn("airCap") onmouseover=glowObject(this) onmouseout=dimObject(this)>AirBase</td>';
	$r .= '<td class=' . $col08 . ' colspan=2 onclick=sortColumn("fighter") onmouseover=glowObject(this) onmouseout=dimObject(this)>Fighter</td>';
	$r .= '<td class=' . $col05 . ' colspan=2 onclick=sortColumn("offMaint") onmouseover=glowObject(this) onmouseout=dimObject(this)>Offense</td>';
	$r .= '<td class=' . $col06 . ' colspan=2 onclick=sortColumn("defMaint") onmouseover=glowObject(this) onmouseout=dimObject(this)>Defense</td>';
	$r .= '<td class=' . $col09 . ' colspan=2 onclick=sortColumn("habSpace") onmouseover=glowObject(this) onmouseout=dimObject(this)>Habitat</td>';
	$r .= '<td class=' . $col10 . ' colspan=2 onclick=sortColumn("intelOps") onmouseover=glowObject(this) onmouseout=dimObject(this)>Intel</td>';
	$r .= '<td class=' . $col11 . ' colspan=2 onclick=sortColumn("materials") onmouseover=glowObject(this) onmouseout=dimObject(this)>Materials</td>';
	$r .= '<td class=' . $col12 . ' colspan=2 onclick=sortColumn("reproduction") onmouseover=glowObject(this) onmouseout=dimObject(this)>Repro</td>';
	$r .= '<td class=' . $col13 . ' colspan=2 onclick=sortColumn("slots") onmouseover=glowObject(this) onmouseout=dimObject(this)>Queues</td>';
	$r .= '<td class=' . $col14 . ' colspan=2 onclick=sortColumn("research") onmouseover=glowObject(this) onmouseout=dimObject(this)>Research</td>';
	$r .= '<td class=' . $col15 . ' colspan=2 onclick=sortColumn("scouting") onmouseover=glowObject(this) onmouseout=dimObject(this)>Scouting</td>';
	$r .= '<td class=' . $col16 . ' colspan=2 onclick=sortColumn("sensors") onmouseover=glowObject(this) onmouseout=dimObject(this)>Sensors</td>';
	$r .= '<td class=' . $col18 . ' colspan=2 onclick=sortColumn("speed") onmouseover=glowObject(this) onmouseout=dimObject(this)>Speed</td>';
	//$r .= '<td class=' . $col19 . ' colspan=2 onclick=sortColumn("training") onmouseover=glowObject(this) onmouseout=dimObject(this)>Training</td>';
	//$r .= '<td class=' . $col20 . ' colspan=2 onclick=sortColumn("wealth") onmouseover=glowObject(this) onmouseout=dimObject(this)>Wealth</td>';
	$r .= '</tr>';
	return $r;
}


function array_sort($array, $key) 
{
	for ($i = 0; $i < sizeof($array); $i++) 
	{
		$sort_values[$i] = $array[$i][$key];
	} 
	
	asort ($sort_values);
	reset ($sort_values);
	while (list ($arr_key, $arr_val) = each ($sort_values)) 
	{
		$sorted_arr[] = $array[$arr_key];
	}
	return $sorted_arr;
}

function array_csort($marray, $column) 
{    //coded by Ichier2003
	foreach ($marray as $row) 
	{
		$sortarr[] = $row[$column];
	}
 	array_multisort($sortarr, $marray);
 	return $marray;
}

function array_column_sort() 
{ 
	$args = func_get_args();
	$array = array_shift($args);
	// make a temporary copy of array for which will fix the keys to be strings, so that array_multisort() doesn't destroy them
	$array_mod = array();
	foreach ($array as $key => $value)
	{
		$array_mod['_' . $key] = $value;
	}
	
	$i = 0;
	$multi_sort_line = "return array_multisort( ";
	
	foreach ($args as $arg) 
	{
		$i++;
		if ( is_string($arg) ) 
		{
			foreach ($array_mod as $row_key => $row) 
			{
				$sort_array[$i][] = $row[$arg];
			}
		} 
		else 
		{
			$sort_array[$i] = $arg;
		}
		$multi_sort_line .= "\$sort_array[" . $i . "], ";
	}
	$multi_sort_line .= "\$array_mod );";

	eval($multi_sort_line);
	// now copy $array_mod back into $array, stripping off the "_" that we added earlier.
	$array = array();
	foreach ($array_mod as $key => $value)
	{
		$array[ substr($key, 1) ] = $value;
	}
	
	return $array;
}



//==========================================================================================
function displayPlanet($planet) 
{
	global $xp;
	global $xd;

	$cnt     = 0;

	$SQL  = 'SELECT RecordNumber, PlanetID,PlanetName,ReportDate,ReportTime,AirOps,AirCap,Capital,Defense,';
	$SQL .= 'Diplomacy,Fighter,Habitat,IntelOps,Materials,Reproduction,Queues,Research,';
	$SQL .= 'Scouting,Sensors,Special,Speed,Training,Wealth,Rank,HabSpace,Slots,DefMaint,OffMaint ';
	$SQL .= 'FROM tblscout ';
	$SQL .= 'WHERE PlanetID=' . $planet . ' ';
	$SQL .= 'ORDER BY ReportDate DESC, ReportTime DESC';
	$result = mysql_query($SQL);
	if (!$result) die('Invalid query: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result)) 
	{
		$cnt++;
		$xp[$cnt]['scoutID']      = $row['RecordNumber'];
		$xp[$cnt]['planetID']     = $row['PlanetID'];
		$xp[$cnt]['planetName']   = stripslashes($row['PlanetName']);
		$xp[$cnt]['reportDate']   = $row['ReportDate'];
		$xp[$cnt]['reportTime']   = $row['ReportTime'];
		$xp[$cnt]['airCap']       = $row['AirCap'];
		$xp[$cnt]['offMaint']     = $row['OffMaint'];
		$xp[$cnt]['defMaint']     = $row['DefMaint'];
		$xp[$cnt]['diplomacy']    = $row['Diplomacy'];
		$xp[$cnt]['fighter']      = $row['Fighter'];
		$xp[$cnt]['habSpace']     = ($row['HabSpace'] / 1000000);
		$xp[$cnt]['intelOps']     = $row['IntelOps'];
		$xp[$cnt]['materials']    = $row['Materials'];
		$xp[$cnt]['reproduction'] = $row['Reproduction'];
		$xp[$cnt]['slots']        = $row['Slots'];
		$xp[$cnt]['research']     = $row['Research'];
		$xp[$cnt]['scouting']     = $row['Scouting'];
		$xp[$cnt]['sensors']      = $row['Sensors'];
		$xp[$cnt]['special']      = $row['Special'];
		$xp[$cnt]['speed']        = $row['Speed'];
		$xp[$cnt]['training']     = $row['Training'];
		$xp[$cnt]['wealth']       = $row['Wealth'];
		$xp[$cnt]['rank']         = $row['Rank'];
	}

	$nbrReports = $cnt;
	for ($i = 1; $i <= $nbrReports; $i++) 
	{
		if ($i < $nbrReports) 
		{
			calcReportDiff($i,'airCap');
			calcReportDiff($i,'offMaint');
			calcReportDiff($i,'defMaint');
			calcReportDiff($i,'diplomacy');
			calcReportDiff($i,'fighter');
			calcDiffHabitat($i,'habSpace');
			calcReportDiff($i,'intelOps');
			calcReportDiff($i,'materials');
			calcReportDiff($i,'reproduction');
			calcReportDiff($i,'slots');
			calcReportDiff($i,'research');
			calcReportDiff($i,'scouting');
			calcReportDiff($i,'sensors');
			calcReportDiff($i,'special');
			calcReportDiff($i,'speed');
			calcReportDiff($i,'training');
			calcReportDiff($i,'wealth');
		} 
		else 
		{
			$xd[$i]['airCap']       = '';
			$xd[$i]['offMaint']     = '';
			$xd[$i]['defMaint']     = '';
			$xd[$i]['diplomacy']    = '';
			$xd[$i]['fighter']      = '';
			$xd[$i]['habSpace']     = '';
			$xd[$i]['intelOps']     = '';
			$xd[$i]['materials']    = '';
			$xd[$i]['reproduction'] = '';
			$xd[$i]['slots']        = '';
			$xd[$i]['research']     = '';
			$xd[$i]['scouting']     = '';
			$xd[$i]['sensors']      = '';
			$xd[$i]['special']      = '';
			$xd[$i]['speed']        = '';
			$xd[$i]['training']     = '';
			$xd[$i]['wealth']       = '';
		}
	}

	$bgcolor = '#F5F5F5';
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	$r  = '';
	$r .= '<div class=divDetail>';
	$r .= '<table width=100% border=0 cellpadding=1 cellspacing=2 bgcolor=#FFFFFF>';
	$r .= '<tr>';
	$r .= '<td class=hh colspan=1>Planet</td>';
	$r .= '<td class=hc colspan=1>Date</td>';
	$r .= '<td class=hc colspan=1>Time</td>';
	$r .= '<td class=hc colspan=1>Rank</td>';
	$r .= '<td class=hc colspan=2>AirBase</td>';
	$r .= '<td class=hc colspan=2>Fighter</td>';
	$r .= '<td class=hc colspan=2>Offense</td>';
	$r .= '<td class=hc colspan=2>Defense</td>';
	$r .= '<td class=hc colspan=2>Habitat</td>';
	$r .= '<td class=hc colspan=2>Intel</td>';
	$r .= '<td class=hc colspan=2>Materials</td>';
	$r .= '<td class=hc colspan=2>Repro</td>';
	$r .= '<td class=hc colspan=2>Queues</td>';
	$r .= '<td class=hc colspan=2>Research</td>';
	$r .= '<td class=hc colspan=2>Scouting</td>';
	$r .= '<td class=hc colspan=2>Sensors</td>';
	$r .= '<td class=hc colspan=2>Speed</td>';
	$r .= '</tr>';

	for ($i = 1; $i <= $nbrReports; $i++) 
	{
		if ($bgcolor == '#F5F5F5') 
		{
			$bgcolor = '#FFFFFF';
		} 
		else 
		{
			$bgcolor = '#F5F5F5';
		}

		$pName = $xp[$i]['planetName'];
		$pName = substr($pName,0,15);

		$r .= '<tr bgcolor=' . $bgcolor . ' onclick="getDetail(' . $xp[$i]['scoutID'] . ')">';
		$r .= '<td class=xx>' . $pName   . '<div class=hidden id=' . ('d_' . $i) . '>' . $xp[$i]['scoutID'] . '</div></td>';
		$r .= '<td class=ulc>' . date('d M',strtotime($xp[$i]['reportDate']))   . '</td>';
		$r .= '<td class=ulc>' . date('H:i',strtotime($xp[$i]['reportTime']))   . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['rank']         . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['airCap'])       . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['airCap']       . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['fighter'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['fighter']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['offMaint'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['offMaint']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['defMaint'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['defMaint']      . '</td>';
		$r .= '<td class=xr>' . sprintf('%01.2f',$xp[$i]['habSpace'])   . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['habSpace']      . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['materials']    . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['materials']    . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['reproduction'] . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['reproduction'] . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['slots']        . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['slots']        . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['research']     . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['research']     . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['sensors']      . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['sensors']      . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['speed']        . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['speed']        . '</td>';
	}

	$r .= '</table>';
	$r .= '</div>';
	echo $r;
}

function calcReportDiff($i,$item) 
{
	global $xp;
	global $xd;

	$v1 = $xp[$i + 0][$item];
	$v2 = $xp[$i + 1][$item];

	$diff = $v1 - $v2;

	if ($diff > 0) 
	{
		$xd[$i][$item] = '<div class=positive>+' . number_format($diff)   . '</div>';
	} 
	elseif ($diff == 0)
	{
		$xd[$i][$item] = '<div class=even>'      . ' '   . '</div>';
	} 
	else 
	{
		$xd[$i][$item] = '<div class=negative>'  . number_format($diff)   . '</div>';
	}
}

function calcDiffHabitat($i,$item) 
{
	global $xp;
	global $xd;

	$v1 = $xp[$i + 0][$item];
	$v2 = $xp[$i + 1][$item];

	$diff = $v1 - $v2;


	if ($diff > 0) 
	{
		$xd[$i][$item] = '<div class=positive>+' . sprintf('%01.2f',$diff)   . '</div>';
	} 
	elseif ($diff == 0)
	{
		$xd[$i][$item] = '<div class=even>'      . ' '   . '</div>';
	} 
	else 
	{
		$xd[$i][$item] = '<div class=negative>' . sprintf('%01.2f',$diff)   . '</div>';
	}
}

//==============================================================================================
function displayDetail($reportID) 
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	$r  = '<table width=100% border=0 cellpadding=1 cellspacing=1 bgcolor=#FFFFFF>';
	$r .= '<tr>';
	$r .= '<td class=hh>Planet</td>';
	$r .= '<td class=hc>Date</td>';
	$r .= '<td class=hc>Time</td>';
	$r .= '<td class=hc>Rank</td>';
	$r .= '<td class=hc>Air Ops</td>';
	$r .= '<td class=hc>Capital</td>';
	$r .= '<td class=hc>Fighters</td>';
	$r .= '<td class=hc>Habitat</td>';
	$r .= '<td class=hc>Reproduction</td>';
	$r .= '<td class=hc>Wealth</td>';
	$r .= '<td class=hc>Intel</td>';
	$r .= '<td class=hc>Research</td>';
	$r .= '<td class=hc>Scouting</td>';
	$r .= '<td class=hc>Materials</td>';
	$r .= '<td class=hc>Queues</td>';
	
	$r .= '<td class=hc>Defense</td>';
	$r .= '<td class=hc>Diplomacy</td>';
	$r .= '<td class=hc>Sensors</td>';
	$r .= '<td class=hc>Speed</td>';
	$r .= '</tr>';

	$SQL  = 'SELECT * FROM tblscout WHERE RecordNumber=' . $reportID;
	$result = mysql_query($SQL);
	if (!$result) die('Invalid query: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result))
	{
		$pName = stripslashes($row['PlanetName']);
		$pName = substr($pName,0,15);

		$r .= '<tr>';
		$r .= '<td class=xx>' . $pName                 . '</td>';
		$r .= '<td class=xc>' . date('d-M',strtotime($row['ReportDate']))      . '</td>';
		$r .= '<td class=xc>' . date('H:i',strtotime($row['ReportTime']))      . '</td>';
		$r .= '<td class=xc>' . $row['Rank']          . '</td>';
		$r .= '<td class=xc>' . $row['AirOps']        . '</td>';
		$r .= '<td class=xc>' . $row['Capital']       . '</td>';
		$r .= '<td class=xc>' . $row['Fighter']       . '</td>';
		$r .= '<td class=xc>' . $row['Habitat']                         . '</td>';
		$r .= '<td class=xc>' . $row['Reproduction']  . '</td>';
		$r .= '<td class=xc>' . $row['Wealth']        . '</td>';
		$r .= '<td class=xc>' . $row['IntelOps']      . '</td>';
		$r .= '<td class=xc>' . $row['Research']      . '</td>';
		$r .= '<td class=xc>' . $row['Scouting']      . '</td>';
		$r .= '<td class=xc>' . $row['Materials']     . '</td>';
		$r .= '<td class=xc>' . ($row['Queues'] + 1)  . '</td>';
		$r .= '<td class=xc>' . $row['Defense']       . '</td>';
		$r .= '<td class=xc>' . $row['Diplomacy']     . '</td>';
		$r .= '<td class=xc>' . $row['Sensors']       . '</td>';
		$r .= '<td class=xc>' . $row['Speed']         . '</td>';
		$r .= '</tr>';
		$r .= '</table>';

		$r .= '<div class=spacer></div>';

		$r .= '<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#A9A9A9>';

		$r .= '<tr valign=top>';
		$r .= '<td>' . getHabitat($row)      . getSpeed($row)     . getCapital($row)   . getIntelOps($row)   . '</td>';
		$r .= '<td>' . getReproduction($row) . getMaterials($row) . getAirOps($row)    . getResearch($row)   . '</td>';
		$r .= '<td>' . getWealth($row)       . getFighters($row)  . getDefense($row)   . getScout($row)      . '</td>';
		$r .= '<td>' . getSensors($row)      . getQueues($row)    . getDiplomacy($row) . getTraining($row)   . '</td>';
		$r .= '</tr>';
		$r .= '</table>';
	}
	echo $r;
}


function getAirOps($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Air Ops</td></tr>';
	if ($row['AIRB1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['AIRB1'] * 200) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['AIRB1'] . '</td>';
		$r .= '<td width=80% class=rptl>Airbase</td>';
		$r .= '</tr>';
	}
	
	if ($row['AIRB2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['AIRB2'] * 300) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['AIRB2'] . '</td>';
		$r .= '<td width=80% class=rptl>Airbase (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['SBASE'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['SBASE'] * 50) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['SBASE'] . '</td>';
		$r .= '<td width=80% class=rptl>Starbase</td>';
		$r .= '</tr>';
	}
	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getCapital($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Capital Ships</td></tr>';
	
	if ($row['ANVBS'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['ANVBS'] * 249) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ANVBS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Anvil" Battleship</td>';
		$r .= '</tr>';
	}
	
	if ($row['ASPHC'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['ASPHC'] * 106) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ASPHC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Asp" Heavy Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['AVASC'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['AVASC'] * 116) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['AVASC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Avalanche" Sige Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['BADLC'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BADLC'] * 199) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BADLC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Badger" Light Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['BARAF'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BARAF'] * 25) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARAF'] . '</td>';
		$r .= '<td width=80% class=rptl>"Barracuda" Attack Frigate</td>';
		$r .= '</tr>';
	}
	
	if ($row['BATSH'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BATSH'] * 229) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BATSH'] . '</td>';
		$r .= '<td width=80% class=rptl>Battleship</td>';
		$r .= '</tr>';
	}
	
	if ($row['BERDE'] > 0)
	 {
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BERDE'] * 30) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BERDE'] . '</td>';
		$r .= '<td width=80% class=rptl>"Berzerker" Destroyer</td>';
		$r .= '</tr>';
	}
	
	if ($row['BLABM'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BLABM'] * 744) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BLABM'] . '</td>';
		$r .= '<td width=80% class=rptl>"Black Widow" Brood Minder</td>';
		$r .= '</tr>';
	}
	
	if ($row['COLFR'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['COLFR'] * 26) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['COLFR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Collector" Frigate</td>';
		$r .= '</tr>';
	}
	
	if ($row['COLOS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['COLOS'] * 880) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['COLOS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Colossus" Megaship</td>';
		$r .= '</tr>';
	}
	
	if ($row['CRUBC'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['CRUBC'] * 153) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['CRUBC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Crusader" Battlecruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['CRUIS'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['CRUIS'] * 115) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['CRUIS'] . '</td>';
		$r .= '<td width=80% class=rptl>Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['DESTR'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['DESTR'] * 31) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DESTR'] . '</td>';
		$r .= '<td width=80% class=rptl>Destroyer</td>';
		$r .= '</tr>';
	}
	
	if ($row['DRAMA'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['DRAMA'] * 379) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DRAMA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Dragon" Mobil Assualt Platform</td>';
		$r .= '</tr>';
	}
	
	if ($row['DREAD'] > 0)
	 {
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['DREAD'] * 258) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DREAD'] . '</td>';
		$r .= '<td width=80% class=rptl>Dreadnought</td>';
		$r .= '</tr>';
	}
	
	if ($row['FIRSD'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['FIRSD'] * 34) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['FIRSD'] . '</td>';
		$r .= '<td width=80% class=rptl>Fire Support Destroyer</td>';
		$r .= '</tr>';
	}
	
	if ($row['FRIGA'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['FRIGA'] * 21) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['FRIGA'] . '</td>';
		$r .= '<td width=80% class=rptl>Frigate</td>';
		$r .= '</tr>';
	}
	
	if ($row['GOLBA'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['GOLBA'] * 260) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['GOLBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Goliath" Battleship</td>';
		$r .= '</tr>';
	}
	
	if ($row['HAMGU'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HAMGU'] * 15) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HAMGU'] . '</td>';
		$r .= '<td width=80% class=rptl>"Hammer" Gunship</td>';
		$r .= '</tr>';
	}
	
	if ($row['HVYCA'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HVYCA'] * 257) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HVYCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Heavy Carrier</td>';
		$r .= '</tr>';
	}
	
	if ($row['HVYCR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HVYCR'] * 125) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HVYCR'] . '</td>';
		$r .= '<td width=80% class=rptl>Heavy Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['HURFC'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HURFC'] * 115) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HURFC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Hurricane" Fast Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['IMPFR'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['IMPFR'] * 23) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['IMPFR'] . '</td>';
		$r .= '<td width=80% class=rptl>Improved Frigate</td>';
		$r .= '</tr>';
	}
	
	if ($row['INTFR'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['INTFR'] * 21) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['INTFR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Interdictor" Frigate</td>';
		$r .= '</tr>';
	}
	
	if ($row['JUDDR'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['JUDDR'] * 234) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['JUDDR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Judicator" Dreadnought</td>';
		$r .= '</tr>';
	}
	
	if ($row['LEOSC'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['LEOSC'] * 106) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['LEOSC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Leopard" Strike Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['LIGCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['LIGCA'] * 139) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['LIGCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Light Carrier</td>';
		$r .= '</tr>';
	}
	
	if ($row['ORCBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['ORCBA'] * 246) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ORCBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Orca" Battleship</td>';
		$r .= '</tr>';
	}
	
	if ($row['PRIHC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['PRIHC'] * 117) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['PRIHC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Privateer" Heavy Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['RAVMC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['RAVMC'] * 115) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['RAVMC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Raven" Missile Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['TANDB'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['TANDB'] * 200) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TANDB'] . '</td>';
		$r .= '<td width=80% class=rptl>"Tangler" Defense Barge</td>';
		$r .= '</tr>';
	}
	
	if ($row['TERCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['TERCA'] * 324) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TERCA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Terrapin" Carrier</td>';
		$r .= '</tr>';
	}
	
	if ($row['TORBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['TORBA'] * 240) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TORBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Tortoise" Battleship</td>';
		$r .= '</tr>';
	}
	
	if ($row['VESSC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['VESSC'] * 268) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['VESSC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Vespa" Siege Carrier</td>';
		$r .= '</tr>';
	}
	
	if ($row['WAYEC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['WAYEC'] * 127) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['WAYEC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Wayfarer" Exploration Cruiser</td>';
		$r .= '</tr>';
	}
	
	if ($row['ZEPFD'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['ZEPFD'] * 32) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ZEPFD'] . '</td>';
		$r .= '<td width=80% class=rptl>"Zephyr" Fast Destroyer</td>';
		$r .= '</tr>';
	}
	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getDefense($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Defense</td></tr>';
	
	if ($row['BARR1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BARR1'] * 35) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARR1'] . '</td>';
		$r .= '<td width=80% class=rptl>Barracks</td>';
		$r .= '</tr>';
	}
	
	if ($row['BARR2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BARR2'] * 49) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARR2'] . '</td>';
		$r .= '<td width=80% class=rptl>Barracks (Veteran)</td>';
		$r .= '</tr>';
	}
	
	if ($row['OMIN1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['OMIN1'] * 5) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OMIN1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Minefield</td>';
		$r .= '</tr>';
	}
	
	if ($row['OMIN2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['OMIN2'] * 6) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OMIN2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Minefield (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['OSLD1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['OSLD1'] * 29) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OSLD1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Shield</td>';
		$r .= '</tr>';
	}
	
	if ($row['OSLD2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['OSLD2'] * 39) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OSLD2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Shield (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['ODEF1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['ODEF1'] * 30) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEF1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Platform</td>';
		$r .= '</tr>';
	}
	
	if ($row['ODEF2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['ODEF2'] * 38) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEF2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Platform (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['ODEFM'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['ODEFM'] * 41) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEFM'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Monitor</td>';
		$r .= '</tr>';
	}
	
	if ($row['AEGMS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['AEGMS'] * 188) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['AEGMS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Aegis" Mobile Shield</td>';
		$r .= '</tr>';
	}
	
	if ($row['DEFTU'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['DEFTU'] * 7) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DEFTU'] . '</td>';
		$r .= '<td width=80% class=rptl>Defense Turret</td>';
		$r .= '</tr>';
	}
	
	if ($row['SDEF1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['SDEF1'] * 15) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SDEF1'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Defense Battery</td>';
		$r .= '</tr>';
	}
	
	if ($row['SDEF2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['SDEF2'] * 21) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SDEF2'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Defense Battery (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['SSLD1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['SSLD1'] * 29) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SSLD1'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Shield Generator</td>';
		$r .= '</tr>';
	}
	
	if ($row['SSLD2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['SSLD2'] * 39) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SSLD2'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Shield Generator (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['SBASE'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['SBASE'] * 330) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SBASE'] . '</td>';
		$r .= '<td width=80% class=rptl>Starbase</td>';
		$r .= '</tr>';
	}
	
//Added orbital bulwarks
	if ($row['OBULK'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['OBULK'] * 1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OBULK'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Bulwark</td>';
		$r .= '</tr>';
	}
	if ($row['STIDR'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['STIDR'] * 1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['STIDR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Stinger" Drone</td>';
		$r .= '</tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getDiplomacy($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Diplomacy</td></tr>';
	if ($row['DIPCO'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['DIPCO'] . '</td><td class=rptl>Diplomatic Council</td></tr>';
	}
	
	if ($row['EMBAS'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['EMBAS'] . '</td><td class=rptl>Embassy</td></tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getFighters($row) {
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Fighter Bomber</td></tr>';
	
	if ($row['FIGIN'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FIGIN'] . '</td><td class=rptl>Fighter Interceptor</td></tr>';
	}
	
	if ($row['FIGBO'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FIGBO'] . '</td><td class=rptl>Fighter Bomber</td></tr>';
	}
	
	if ($row['ADVIN'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['ADVIN'] . '</td><td class=rptl>Advanced Interceptor</td></tr>';
	}
	
	if ($row['HVYBO'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['HVYBO'] . '</td><td class=rptl>Heavy Bomber</td></tr>';
	}
	
	if ($row['WASFI'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['WASFI'] . '</td><td class=rptl>"Wasp" Fighter</td></tr>';
	}
	
	if ($row['FANFB'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FANFB'] . '</td><td class=rptl>"Fang" Fighter Bomber</td></tr>';
	}
	
	if ($row['DAGHF'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['DAGHF'] . '</td><td class=rptl>"Dagger" Heavy Fighter</td></tr>';
	}
	
	if ($row['VENHF'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['VENHF'] . '</td><td class=rptl>"Venom" Heavy Fighter</td></tr>';
	}
//Stinger as fighters, comment stinger code in getDefense function	
/*	if ($row['STIDR'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['STIDR'] . '</td><td class=rptl>"Stinger" Drone</td></tr>';
	}
*/	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getHabitat($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Habitat</td></tr>';
	$r .= '<tr>';
	$r .= '<td width=10% class=rptr>1,250,000</td>';
	$r .= '<td width=10% class=rptc>1</td>';
	$r .= '<td width=80% class=rptl>Planet Default</td>';
	$r .= '</tr>';
	
	if ($row['HABI1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HABI1'] * 30000) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HABI1'] . '</td>';
		$r .= '<td width=80% class=rptl>Habitat</td>';
		$r .= '</tr>';
	}
	
	if ($row['HABI2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HABI2'] * 50000) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HABI2'] . '</td>';
		$r .= '<td width=80% class=rptl>Habitat (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['HABI3'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HABI3'] * 70000) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HABI3'] . '</td>';
		$r .= '<td width=80% class=rptl>Habitat (Ultradense)</td>';
		$r .= '</tr>';
	}
	
	if ($row['GNDHI'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['GNDHI'] * 100000) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['GNDHI'] . '</td>';
		$r .= '<td width=80% class=rptl>Grand Hive</td>';
		$r .= '</tr>';
	}
	
	if ($row['HIBCA'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HIBCA'] * 250000) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HIBCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Hibernation Cave</td>';
		$r .= '</tr>';
	}
	
	if ($row['VINEM'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['VINEM'] * 100000) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['VINEM'] . '</td>';
		$r .= '<td width=80% class=rptl>Vinemind</td>';
		$r .= '</tr>';
	}
	
	if ($row['SBASE'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['SBASE'] * 10000) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['SBASE'] . '</td>';
		$r .= '<td width=80% class=rptl>Starbase</td>';
		$r .= '</tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getInfo($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Info</td></tr>';
	$r .= '<tr><td class=hh>Planet</td><td class=rptc>' . stripslashes($row['PlanetName'])            . '</td></tr>';
	$r .= '<tr><td class=hh>Date</td><td class=rptc>'   . date('d M y',strtotime($row['ReportDate'])) . '</td></tr>';
	$r .= '<tr><td class=hh>Time</td><td class=rptc>'   . date('h:i a',strtotime($row['ReportTime'])) . '</td></tr>';
	$r .= '<tr><td class=hh>Source</td><td class=rptc>' . stripslashes($row['SourceName'])            . '</td></tr>';
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getIntelOps($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Intel Ops</td></tr>';
	
	if ($row['INTEL'] > 0) 
	{
		$r .= '<tr><td width=10% width=10% class=rptc>' . $row['INTEL'] . '</td><td width=90% class=rptl>Intelligence Agency</td></tr>';
	}
	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getMaterials($row) {
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Materials</td></tr>';
	if ($row['FARM1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FARM1'] . '</td><td width=90% class=rptl>Farm I</td></tr>';
	}
	
	if ($row['FARM2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FARM2'] . '</td><td width=90% class=rptl>Farm II</td></tr>';
	}
	
	if ($row['FARM3'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FARM3'] . '</td><td width=90% class=rptl>Farm III</td></tr>';
	}
	
	if ($row['MINE1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['MINE1'] . '</td><td width=90% class=rptl>Mining Facility (Metals)</td></tr>';
	}
	
	if ($row['MINE2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['MINE2'] . '</td><td width=90% class=rptl>Mining Facility (Metals Improved)</td></tr>';
	}
	
	if ($row['RADI1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['RADI1'] . '</td><td width=90% class=rptl>Mining Facility (Radioactives)</td></tr>';
	}
	
	if ($row['RADI2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['RADI2'] . '</td><td width=90% class=rptl>Mining Facility (Radioactives Improved)</td></tr>';
	}
	
	if ($row['FUEL1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FUEL1'] . '</td><td width=90% class=rptl>Refinery (Fuel)</td></tr>';
	}
	
	if ($row['FUEL2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FUEL2'] . '</td><td width=90% class=rptl>Refinery (Fuel Improved)</td></tr>';
	}
	
	if ($row['MATS1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['MATS1'] . '</td><td width=90% class=rptl>Materials Processing Plant</td></tr>';
	}
	
	if ($row['MATS2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['MATS2'] . '</td><td width=90% class=rptl>Materials Processing Plant (Improved)</td></tr>';
	}
	
	if ($row['MATRC'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['MATRC'] . '</td><td width=90% class=rptl>Materials Research Complex</td></tr>';
	}
	
	if ($row['WHSE1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['WHSE1'] . '</td><td width=90% class=rptl>Warehouse (small)</td></tr>';
	}
	
	if ($row['WHSE2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['WHSE2'] . '</td><td width=90% class=rptl>Warehouse (Medium)</td></tr>';
	}
	
	if ($row['WHSE3'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['WHSE3'] . '</td><td width=90% class=rptl>Warehouse (Large)</td></tr>';
	}
	
	if ($row['INTMP'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['INTMP'] . '</td><td width=90% class=rptl>Interplanetary Marketplace</td></tr>';
	}
	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getQueues($row) {
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Queues</td></tr>';
	$r .= '<tr>';
	$r .= '<td width=10% class=rptc>1</td>';
	$r .= '<td width=10% class=rptc>1</td>';
	$r .= '<td width=80% class=rptl>Default Queue</td>';
	$r .= '</tr>';
	if ($row['MANU1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['MANU1'] * 1) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['MANU1'] . '</td>';
		$r .= '<td width=80% class=rptl>Manufacturing Plant</td>';
		$r .= '</tr>';
	}
	
	if ($row['MANU2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['MANU2'] * 2) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['MANU2'] . '</td>';
		$r .= '<td width=80% class=rptl>Manufacturing Plant (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['OCON1'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['OCON1'] * 1) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['OCON1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Construction Yard</td>';
		$r .= '</tr>';
	}  
	          
	if ($row['OCON2'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['OCON2'] * 2) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['OCON2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Construction Yard (Improved)</td>';
		$r .= '</tr>';
	}
	
	if ($row['SBASE'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['SBASE'] * 2) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['SBASE'] . '</td>';
		$r .= '<td width=80% class=rptl>Starbase</td>';
		$r .= '</tr>';
	}
	
	if ($row['ADVTS'] > 0) 
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['ADVTS'] * 1). '</td>';
		$r .= '<td width=10% class=rptc>' . $row['ADVTS'] . '</td>';
		$r .= '<td width=80% class=rptl>Advanced Technologies Shipyard</td>';
		$r .= '</tr>';
	}
	//if ($row['WARFA'] > 0) {
	//	$r .= '<tr>';
	//	$r .= '<td width=10% class=rptc>' . $row['WARFA'] . '</td>';
	//	$r .= '<td width=10% class=rptc>' . $row['WARFA'] . '</td>';
	//	$r .= '<td width=80% class=rptl>War Factory</td>';
	//	$r .= '</tr>';
	//}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}


function getReproduction($row) {
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Reproduction</td></tr>';
	
	if ($row['HOSPI'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['HOSPI'] . '</td><td class=rptl>Hospital</td></tr>';
	}
	
	if ($row['GELAB'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['GELAB'] . '</td><td class=rptl>Genetics Lab</td></tr>';
	}
	
	if ($row['ADVGE'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['ADVGE'] . '</td><td class=rptl>Advanced Genetics Lab</td></tr>';
	}
	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getResearch($row) {
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Research</td></tr>';
	
	if ($row['RLAB1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['RLAB1'] . '</td><td class=rptl>Research Lab</td></tr>';
	}
	
	if ($row['RLAB2'] > 0)
	 {
		$r .= '<tr><td width=10% class=rptc>' . $row['RLAB2'] . '</td><td class=rptl>Research Lab (Improved)</td></tr>';
	}
	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getScout($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Scouting</td></tr>';
	
	if ($row['SCOUT'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['SCOUT'] . '</td><td width=90% class=rptl>Scout</td></tr>';
	}
	
	if ($row['DEERS'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['DEERS'] . '</td><td width=90% class=rptl>Deep Recon Scout</td></tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getSensors($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Sensors</td></tr>';
	
	if ($row['LISTN'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['LISTN'] . '</td><td class=rptl>Listening Post</td></tr>';
	}
	
	if ($row['SATE1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['SATE1'] . '</td><td class=rptl>Satellites</td></tr>';
	}
	
	if ($row['SATE2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['SATE2'] . '</td><td class=rptl>Satellites (Improved)</td></tr>';
	}
	
	if ($row['RSENS'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['RSENS'] . '</td><td class=rptl>Remote Sensor Array</td></tr>';
	}
	
	if ($row['TRACK'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['TRACK'] . '</td><td class=rptl>Tracking Station</td></tr>';
	}
	
	if ($row['VINEM'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['VINEM'] . '</td><td class=rptl>Vinemind</td></tr>';
	}
	
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getSpeed($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Speed</td></tr>';
	
	if ($row['JUMP1'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['JUMP1'] . '</td><td class=rptl>Jump Gate</td></tr>';
	}
	
	if ($row['JUMP2'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['JUMP2'] . '</td><td class=rptl>Jump Gate (Improved)</td></tr>';
	}
	
	if ($row['FOLDR'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['FOLDR'] . '</td><td class=rptl>Space Folder</td></tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}


function getTraining($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Training</td></tr>';
	
	if ($row['TSCHL'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['TSCHL'] . '</td><td class=rptl>Trade School</td></tr>';
	}
	
	if ($row['UNIVE'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['UNIVE'] . '</td><td class=rptl>University</td></tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getWealth($row) 
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=2>Wealth</td></tr>';
	
	if ($row['PBANK'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['PBANK'] . '</td><td class=rptl>Planetary Bank</td></tr>';
	}
	
	if ($row['STOCK'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['STOCK'] . '</td><td class=rptl>Stock Exchange</td></tr>';
	}
	
	if ($row['INTMP'] > 0) 
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['INTMP'] . '</td><td class=rptl>Interplanetary Marketplace</td></tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}
?>