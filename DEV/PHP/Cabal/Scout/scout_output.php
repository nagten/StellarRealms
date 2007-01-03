<?php
$p       = array();
$p1      = array();
$p2      = array();
$d1      = array();
$xp      = array();
$xd      = array();
$sort    = 'planet';

include("../connect_to_database.php");
include("convariables.php");

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

function displaySummary()
{
	//Scouting Reports
	global $p;
	global $p1;
	global $p2;
	global $d1;
	global $sort;

	$bgcolor = '#F5F5F5';

	$SQL  = 'SELECT RecordNumber,PlanetID,PlanetName,ReportDate,ReportTime,AirCap,';
	$SQL .= 'Fighter,IntelOps,Materials,FleetRating,OrbRating,SurRating,BuildRating,';
	$SQL .= 'Scouting,Warehouse,Speed,Rank,HabSpace,Queues,Species,SBASE,STIDR ';
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
				$p1[$planetID]['planetName']	= stripslashes($row['PlanetName']);
				$p1[$planetID]['species']	= $row['Species'];
				$p1[$planetID]['reportDate']	= $row['ReportDate'];
				$p1[$planetID]['reportTime']	= $row['ReportTime'];
				$p1[$planetID]['rank']	= $row['Rank'];
				$p1[$planetID]['fleetrating']	= $row['FleetRating'];
				$p1[$planetID]['orbrating']	 = $row['OrbRating'];
				$p1[$planetID]['surrating']	= $row['SurRating'];
				$p1[$planetID]['buildrating']	= $row['BuildRating'];
				$p1[$planetID]['starbases']	= $row['SBASE'];
				$p1[$planetID]['airCap']	= $row['AirCap'];
				$p1[$planetID]['fighter']	= $row['Fighter'];
				$p1[$planetID]['drones']	= $row['STIDR'];
				$p1[$planetID]['habSpace']	= ($row['HabSpace'] / 1000000);
				$p1[$planetID]['intelOps']	= $row['IntelOps'];
				$p1[$planetID]['materials']	= $row['Materials'];
				$p1[$planetID]['scouting']	= $row['Scouting'];
				$p1[$planetID]['warehouse']	= $row['Warehouse'];
				$p1[$planetID]['queues']	= $row['Queues'];
				$p1[$planetID]['speed']	= $row['Speed'];
			}
			else
			{
				$p[$planetID] = 2;
				$p2[$planetID]['planetName']	= stripslashes($row['PlanetName']);
				$p2[$planetID]['reportDate']	= $row['ReportDate'];
				$p2[$planetID]['reportTime']	= $row['ReportTime'];
				$p2[$planetID]['rank']	= $row['Rank'];
				$p2[$planetID]['fleetrating']	= $row['FleetRating'];
				$p2[$planetID]['orbrating']	= $row['OrbRating'];
				$p2[$planetID]['surrating']	= $row['SurRating'];
				$p2[$planetID]['buildrating']	= $row['BuildRating'];
				$p2[$planetID]['starbases']	= $row['SBASE'];
				$p2[$planetID]['airCap']	= $row['AirCap'];
				$p2[$planetID]['fighter']	= $row['Fighter'];
				$p2[$planetID]['drones']	= $row['STIDR'];
				$p2[$planetID]['habSpace']	= ($row['HabSpace'] / 1000000);
				$p2[$planetID]['intelOps']	= $row['IntelOps'];
				$p2[$planetID]['materials']	= $row['Materials'];
				$p2[$planetID]['scouting']	= $row['Scouting'];
				$p2[$planetID]['warehouse']	= $row['Warehouse'];
				$p2[$planetID]['queues']	= $row['Queues'];
				$p2[$planetID]['speed']	= $row['Speed'];
			}
		}
		else
		{
			//TODO check why we update it?
			$SQL = 'UPDATE tblscout SET Current = \'N\' WHERE RecordNumber = ' . $recNbr;
			$dummy = mysql_query($SQL);
		}
	}

	foreach ($p as $key => $value)
	{
		if ($value > 1)
		{
			calcSummaryDiff($key,'fleetrating');
			calcSummaryDiff($key,'orbrating');
			calcSummaryDiff($key,'surrating');
			calcSummaryDiff($key,'buildrating');
			calcSummaryDiff($key,'starbases');
			calcSummaryDiff($key,'airCap');
			calcSummaryDiff($key,'fighter');
			calcSummaryDiff($key,'drones');
			calcHabitatDiff($key,'habSpace');
			calcSummaryDiff($key,'intelOps');
			calcSummaryDiff($key,'materials');
			calcSummaryDiff($key,'queues');
			calcSummaryDiff($key,'scouting');
			calcSummaryDiff($key,'warehouse');
			calcSummaryDiff($key,'speed');
		}
		else
		{
			$d1[$key]['fleetrating']	= '';
			$d1[$key]['orbrating']		= '';
			$d1[$key]['surrating']		= '';
			$d1[$key]['buildrating']	= '';
			$d1[$key]['starbases']	= '';
			$d1[$key]['airCap']	= '';
			$d1[$key]['fighter']	= '';
			$d1[$key]['drones']	= '';
			$d1[$key]['habSpace']	= '';
			$d1[$key]['intelOps']	= '';
			$d1[$key]['materials']	= '';
			$d1[$key]['queues']	= '';
			$d1[$key]['scouting']	= '';
			$d1[$key]['warehouse']	= '';
			$d1[$key]['speed']	= '';
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
		$specie = $p1[$key]['species'];
		$pName = substr($pName,0,15);

		$r .= '<tr bgcolor=' . $bgcolor . ' onclick="getPlanet(' . $key . ')">';
		$r .= '<td class=ull>' . $pName . ' (' . $specie . ')'. '<div class=hidden id=p_' . $cnt . '>' . $key . '</div></td>';
		$r .= '<td class=xc>' . date('d-M',strtotime($p1[$key]['reportDate']))   . '</td>';
		$r .= '<td class=xc>' . date('H:i',strtotime($p1[$key]['reportTime']))   . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['rank']                        . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['fleetrating'])  . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['fleetrating']                 . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['orbrating'])    . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['orbrating']                   . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['surrating'])    . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['surrating']                   . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['buildrating'])  . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['buildrating']                 . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['starbases'] . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['starbases'] . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['airCap'])       . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['airCap']                      . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['fighter'])      . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['fighter']                     . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['drones'])      . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['drones']                     . '</td>';
		$r .= '<td class=xr>' . sprintf('%01.2f',$p1[$key]['habSpace'])  . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['habSpace']                    . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['materials']    . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['materials']    . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['queues']        . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['queues']        . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $p1[$key]['warehouse']      . '</td>';
		$r .= '<td class=xc>' . $d1[$key]['warehouse']      . '</td>';
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

	switch ($sort)
	{
		case 'planet':
			$col01 = 'shc';
			break;
		case 'rank':
			$col04 = 'shc';
			break;
		case 'fleetrating':
			$col05 = 'shc';
			break;
		case 'orbrating':
			$col06 = 'shc';
			break;
		case 'surrating':
			$col07 = 'shc';
			break;
		case 'buildrating':
			$col08 = 'shc';
			break;
		case 'starbases':
			$col09 = 'shc';
			break;
		case 'airCap':
			$col10 = 'shc';
			break;
		case 'fighter':
			$col11 = 'shc';
			break;
		case 'drones':
			$col19 = 'shc';
			break;
		case 'habSpace':
			$col12 = 'shc';
			break;
		case 'intelOps':
			$col13 = 'shc';
			break;
		case 'materials':
			$col14 = 'shc';
			break;
		case 'queues':
			$col15 = 'shc';
			break;
		case 'scouting':
			$col16 = 'shc';
			break;
		case 'warehouse':
			$col17 = 'shc';
			break;
		case 'speed':
			$col18 = 'shc';
			break;
	}

	$r  = '';
	$r .= '<tr>';
	$r .= '<td class=' . $col01 . ' colspan=1 onclick=sortColumn("planet") onmouseover=glowObject(this) onmouseout=dimObject(this)>Planet</td>';
	$r .= '<td class=' . $col02 . ' colspan=1>Date</td>';
	$r .= '<td class=' . $col03 . ' colspan=1>Time</td>';
	$r .= '<td class=' . $col04 . ' colspan=1 onclick=sortColumn("rank") onmouseover=glowObject(this) onmouseout=dimObject(this)>Rank</td>';
	$r .= '<td class=' . $col05 . ' colspan=2 onclick=sortColumn("fleetrating") onmouseover=glowObject(this) onmouseout=dimObject(this)>Fleet</td>';
	$r .= '<td class=' . $col06 . ' colspan=2 onclick=sortColumn("orbrating") onmouseover=glowObject(this) onmouseout=dimObject(this)>Orbital Off/Def</td>';
	$r .= '<td class=' . $col07 . ' colspan=2 onclick=sortColumn("surrating") onmouseover=glowObject(this) onmouseout=dimObject(this)>Surface Off/Def</td>';
	$r .= '<td class=' . $col08 . ' colspan=2 onclick=sortColumn("buildrating") onmouseover=glowObject(this) onmouseout=dimObject(this)>Buildings</td>';
	$r .= '<td class=' . $col09 . ' colspan=2 onclick=sortColumn("starbases") onmouseover=glowObject(this) onmouseout=dimObject(this)>Starbases</td>';
	$r .= '<td class=' . $col10 . ' colspan=2 onclick=sortColumn("airCap") onmouseover=glowObject(this) onmouseout=dimObject(this)>AirBase</td>';
	$r .= '<td class=' . $col11 . ' colspan=2 onclick=sortColumn("fighter") onmouseover=glowObject(this) onmouseout=dimObject(this)>Fighter</td>';
	$r .= '<td class=' . $col19 . ' colspan=2 onclick=sortColumn("drones") onmouseover=glowObject(this) onmouseout=dimObject(this)>Drones</td>';
	$r .= '<td class=' . $col12 . ' colspan=2 onclick=sortColumn("habSpace") onmouseover=glowObject(this) onmouseout=dimObject(this)>Habitat</td>';
	$r .= '<td class=' . $col13 . ' colspan=2 onclick=sortColumn("intelOps") onmouseover=glowObject(this) onmouseout=dimObject(this)>Intel</td>';
	$r .= '<td class=' . $col14 . ' colspan=2 onclick=sortColumn("materials") onmouseover=glowObject(this) onmouseout=dimObject(this)>Materials</td>';
	$r .= '<td class=' . $col15 . ' colspan=2 onclick=sortColumn("queues") onmouseover=glowObject(this) onmouseout=dimObject(this)>Queues</td>';
	$r .= '<td class=' . $col16 . ' colspan=2 onclick=sortColumn("scouting") onmouseover=glowObject(this) onmouseout=dimObject(this)>Scouting</td>';
	$r .= '<td class=' . $col17 . ' colspan=2 onclick=sortColumn("warehouse") onmouseover=glowObject(this) onmouseout=dimObject(this)>Warehouse</td>';
	$r .= '<td class=' . $col18 . ' colspan=2 onclick=sortColumn("speed") onmouseover=glowObject(this) onmouseout=dimObject(this)>Speed</td>';
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
	//Planet Detail
	global $xp;
	global $xd;

	$cnt     = 0;

	$SQL  = 'SELECT RecordNumber, PlanetID,PlanetName,ReportDate,ReportTime,AirOps,AirCap,';
	$SQL .= 'Fighter,Habitat,IntelOps,Materials,';
	$SQL .= 'Scouting,Sensors,Warehouse,Special,Speed,Rank,HabSpace,SBASE,STIDR, ';
	$SQL .= 'FleetRating,OrbRating,SurRating,BuildRating ';
	$SQL .= 'FROM tblscout ';
	$SQL .= 'WHERE PlanetID=' . $planet . ' ';
	$SQL .= 'ORDER BY ReportDate DESC, ReportTime DESC';
	$result = mysql_query($SQL);
	if (!$result) die('Invalid query: ' . mysql_error());
	while ($row = mysql_fetch_assoc($result))
	{
		$cnt++;
		$xp[$cnt]['scoutID']	= $row['RecordNumber'];
		$xp[$cnt]['planetID']	= $row['PlanetID'];
		$xp[$cnt]['planetName']	= stripslashes($row['PlanetName']);
		$xp[$cnt]['reportDate']	= $row['ReportDate'];
		$xp[$cnt]['reportTime']	= $row['ReportTime'];
		$xp[$cnt]['rank']	= $row['Rank'];
		$xp[$cnt]['fleetrating']	= $row['FleetRating'];
		$xp[$cnt]['orbrating']	= $row['OrbRating'];
		$xp[$cnt]['surrating']	= $row['SurRating'];
		$xp[$cnt]['buildrating']	= $row['BuildRating'];
		$xp[$cnt]['starbases']	= $row['SBASE'];
		$xp[$cnt]['airCap']	= $row['AirCap'];
		$xp[$cnt]['fighter']	= $row['Fighter'];
		$xp[$cnt]['drones']	= $row['STIDR'];
		$xp[$cnt]['habSpace']	= ($row['HabSpace'] / 1000000);
		$xp[$cnt]['intelOps']	= $row['IntelOps'];
		$xp[$cnt]['materials']	= $row['Materials'];
		$xp[$cnt]['queues']	= $row['Queues'];
		$xp[$cnt]['scouting']	= $row['Scouting'];
		$xp[$cnt]['warehouse']	= $row['Warehouse'];
		$xp[$cnt]['speed']	= $row['Speed'];
	}

	$nbrReports = $cnt;
	for ($i = 1; $i <= $nbrReports; $i++)
	{
		if ($i < $nbrReports)
		{
			calcReportDiff($i,'fleetrating');
			calcReportDiff($i,'orbrating');
			calcReportDiff($i,'surrating');
			calcReportDiff($i,'buildrating');
			calcReportDiff($i,'starbases');
			calcReportDiff($i,'airCap');
			calcReportDiff($i,'fighter');
			calcReportDiff($i,'drones');
			calcDiffHabitat($i,'habSpace');
			calcReportDiff($i,'intelOps');
			calcReportDiff($i,'materials');
			calcReportDiff($i,'queues');
			calcReportDiff($i,'scouting');
			calcReportDiff($i,'warehouse');
			calcReportDiff($i,'speed');
		}
		else
		{
			$xd[$i]['fleetrating']	= '';
			$xd[$i]['orbrating']	= '';
			$xd[$i]['surrating']	= '';
			$xd[$i]['buildrating']	= '';
			$xd[$i]['starbases']	= '';
			$xd[$i]['airCap']	= '';
			$xd[$i]['fighter']	= '';
			$xd[$i]['drones']	= '';
			$xd[$i]['habSpace']	= '';
			$xd[$i]['intelOps']	= '';
			$xd[$i]['materials']	= '';
			$xd[$i]['queues']	= '';
			$xd[$i]['scouting']	= '';
			$xd[$i]['warehouse']	= '';
			$xd[$i]['speed']	= '';
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
	$r .= '<td class=hc colspan=2>Fleet</td>';
	$r .= '<td class=hc colspan=2>Orbital Off/Def</td>';
	$r .= '<td class=hc colspan=2>Surface Off/Def</td>';
	$r .= '<td class=hc colspan=2>Buildings</td>';
	$r .= '<td class=hc colspan=2>Starbases</td>';
	$r .= '<td class=hc colspan=2>AirBase</td>';
	$r .= '<td class=hc colspan=2>Fighter</td>';
	$r .= '<td class=hc colspan=2>Drones</td>';
	$r .= '<td class=hc colspan=2>Habitat</td>';
	$r .= '<td class=hc colspan=2>Intel</td>';
	$r .= '<td class=hc colspan=2>Materials</td>';
	$r .= '<td class=hc colspan=2>Queues</td>';
	$r .= '<td class=hc colspan=2>Scouting</td>';
	$r .= '<td class=hc colspan=2>Warehouse</td>';
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
		$r .= '<td class=xr>' . number_format($xp[$i]['fleetrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['fleetrating']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['orbrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['orbrating']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['surrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['surrating']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['buildrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['buildrating']      . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['starbases'] . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['starbases'] . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['airCap'])       . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['airCap']       . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['fighter'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['fighter']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$i]['drones'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['drones']      . '</td>';
		$r .= '<td class=xr>' . sprintf('%01.2f',$xp[$i]['habSpace'])   . '</td>';
		$r .= '<td class=xr>' . $xd[$i]['habSpace']      . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['intelOps']     . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['materials']    . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['materials']    . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['queues']     . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['queues']     . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['scouting']     . '</td>';
		$r .= '<td class=xc>' . $xp[$i]['warehouse']      . '</td>';
		$r .= '<td class=xc>' . $xd[$i]['warehouse']      . '</td>';
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
	//Selected planet Scouting Report
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
	$r .= '<td class=hc>Diplomacy</td>';
	$r .= '<td class=hc>Warehouse</td>';
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
		$r .= '<td class=xc>' . $row['Habitat']       . '</td>';
		$r .= '<td class=xc>' . $row['Reproduction']  . '</td>';
		$r .= '<td class=xc>' . $row['Wealth']        . '</td>';
		$r .= '<td class=xc>' . $row['IntelOps']      . '</td>';
		$r .= '<td class=xc>' . $row['Research']      . '</td>';
		$r .= '<td class=xc>' . $row['Scouting']      . '</td>';
		$r .= '<td class=xc>' . $row['Materials']     . '</td>';
		$r .= '<td class=xc>' . $row['Queues']	. '</td>';
		$r .= '<td class=xc>' . $row['Diplomacy']     . '</td>';
		$r .= '<td class=xc>' . $row['Warehouse']       . '</td>';
		$r .= '<td class=xc>' . $row['Speed']         . '</td>';
		$r .= '</tr>';
		$r .= '</table>';

		$r .= '<div class=spacer></div>';

		$r .= '<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#A9A9A9>';

		$r .= '<tr valign=top>';
		$r .= '<td>' . getHabitat($row)      . getSpeed($row)     . getCapital($row)    . '</td>';
		$r .= '<td>' . getReproduction($row) . getMaterials($row) . getAirOps($row)    . getResearch($row)   . '</td>';
		$r .= '<td>' . getWealth($row)       . getFighters($row)  . getDrones($row)	. getSurfaceDefense($row)   . getOrbitalDefense($row) 	 . getScout($row)      . '</td>';
		$r .= '<td>' . getSensors($row)      . getQueues($row)    . getDiplomacy($row) . getTraining($row)   .  getIntelOps($row) . '</td>';
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
		$r .= '<td width=10% class=rptc>' . ($row['SBASE'] * 50 ) . '</td>';
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
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ANVBS'] * conANVBS) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ANVBS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Anvil" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['ASPHC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ASPHC'] * conASPHC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ASPHC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Asp" Heavy Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['AVASC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['AVASC'] * conAVASC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['AVASC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Avalanche" Sige Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['BADLC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BADLC'] * conBADLC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BADLC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Badger" Light Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['BARAF'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BARAF'] * conBARAF) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARAF'] . '</td>';
		$r .= '<td width=80% class=rptl>"Barracuda" Attack Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['BATSH'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BATSH'] * conBATSH) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BATSH'] . '</td>';
		$r .= '<td width=80% class=rptl>Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['BERDE'] > 0)
	 {
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BERDE'] * conBERDE) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BERDE'] . '</td>';
		$r .= '<td width=80% class=rptl>"Berzerker" Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['BLABM'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BLABM'] * conBLABM) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BLABM'] . '</td>';
		$r .= '<td width=80% class=rptl>"Black Widow" Brood Minder</td>';
		$r .= '</tr>';
	}

	if ($row['COLFR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['COLFR'] * conCOLFR) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['COLFR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Collector" Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['COLOS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['COLOS'] * conCOLOS) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['COLOS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Colossus" Megaship</td>';
		$r .= '</tr>';
	}

	if ($row['CRUBC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['CRUBC'] * conCRUBC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['CRUBC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Crusader" Battlecruiser</td>';
		$r .= '</tr>';
	}

	if ($row['CRUIS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['CRUIS'] * conCRUIS) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['CRUIS'] . '</td>';
		$r .= '<td width=80% class=rptl>Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['DESTR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DESTR'] * conDESTR) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DESTR'] . '</td>';
		$r .= '<td width=80% class=rptl>Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['DRAMA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DRAMA'] * conDRAMA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DRAMA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Dragon" Mobil Assualt Platform</td>';
		$r .= '</tr>';
	}

	if ($row['DREAD'] > 0)
	 {
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DREAD'] * conDREAD) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DREAD'] . '</td>';
		$r .= '<td width=80% class=rptl>Dreadnought</td>';
		$r .= '</tr>';
	}

	if ($row['FIRSD'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['FIRSD'] * conFIRSD) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['FIRSD'] . '</td>';
		$r .= '<td width=80% class=rptl>Fire Support Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['FRIGA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['FRIGA'] * conFRIGA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['FRIGA'] . '</td>';
		$r .= '<td width=80% class=rptl>Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['GOLBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['GOLBA'] * conGOLBA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['GOLBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Goliath" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['HAMGU'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HAMGU'] * conHAMGU) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HAMGU'] . '</td>';
		$r .= '<td width=80% class=rptl>"Hammer" Gunship</td>';
		$r .= '</tr>';
	}

	if ($row['HVYCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HVYCA'] * conHVYCA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HVYCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Heavy Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['HVYCR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HVYCR'] * conHVYCR) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HVYCR'] . '</td>';
		$r .= '<td width=80% class=rptl>Heavy Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['HURFC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HURFC'] * conHURFC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HURFC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Hurricane" Fast Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['IMPFR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['IMPFR'] * conIMPFR) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['IMPFR'] . '</td>';
		$r .= '<td width=80% class=rptl>Improved Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['INTFR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['INTFR'] * conINTFR) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['INTFR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Interdictor" Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['JUDDR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['JUDDR'] * conJUDDR) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['JUDDR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Judicator" Dreadnought</td>';
		$r .= '</tr>';
	}

	if ($row['LEOSC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['LEOSC'] * conLEOSC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['LEOSC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Leopard" Strike Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['LIGCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['LIGCA'] * conLIGCA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['LIGCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Light Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['ORCBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ORCBA'] * conORCBA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ORCBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Orca" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['PRIHC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['PRIHC'] * conPRIHC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['PRIHC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Privateer" Heavy Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['RAVMC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['RAVMC'] * conRAVMC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['RAVMC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Raven" Missile Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['TANDB'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['TANDB'] * conTANDB) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TANDB'] . '</td>';
		$r .= '<td width=80% class=rptl>"Tangler" Defense Barge</td>';
		$r .= '</tr>';
	}

	if ($row['TERCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['TERCA'] * conTERCA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TERCA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Terrapin" Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['TORBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['TORBA'] * conTORBA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TORBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Tortoise" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['VESSC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['VESSC'] * conVESSC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['VESSC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Vespa" Siege Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['WAYEC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['WAYEC'] * conWAYEC) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['WAYEC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Wayfarer" Exploration Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['ZEPFD'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ZEPFD'] * conZEPFD) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ZEPFD'] . '</td>';
		$r .= '<td width=80% class=rptl>"Zephyr" Fast Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['AEGMS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['AEGMS'] * conAEGMS) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['AEGMS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Aegis" Mobile Shield</td>';
		$r .= '</tr>';
	}

	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}
function getDrones($row)
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Drones</td></tr>';

	if ($row['STIDR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['STIDR'] * conSTIDR) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['STIDR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Stinger" Drone</td>';
		$r .= '</tr>';
	}

	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getSurfaceDefense($row)
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Surface Defense</td></tr>';

	if ($row['BARR1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BARR1'] * conBARR1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARR1'] . '</td>';
		$r .= '<td width=80% class=rptl>Barracks (off +2%, def +2%)</td>';
		$r .= '</tr>';
	}

	if ($row['BARR2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BARR2'] * conBARR2) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARR2'] . '</td>';
		$r .= '<td width=80% class=rptl>Barracks (Veteran) (off +3%, def +3%)</td>';
		$r .= '</tr>';
	}

	if ($row['DEFTU'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DEFTU'] * conDEFTU) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DEFTU'] . '</td>';
		$r .= '<td width=80% class=rptl>Defense Turret</td>';
		$r .= '</tr>';
	}

	if ($row['SDEF1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SDEF1'] * conSDEF1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SDEF1'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Defense Battery</td>';
		$r .= '</tr>';
	}

	if ($row['SDEF2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SDEF2'] * conSDEF2) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SDEF2'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Defense Battery (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['SSLD1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SSLD1'] * conSSLD1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SSLD1'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Shield Generator</td>';
		$r .= '</tr>';
	}

	if ($row['SSLD2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SSLD2'] * conSSLD2) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SSLD2'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Shield Generator (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['WARFA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['WARFA'] * conWARFA) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['WARFA'] . '</td>';
		$r .= '<td width=80% class=rptl>War Factory (dur +5%, off +5%)</td>';
		$r .= '</tr>';
	}

	if ($row['WEATL'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['WEATL'] * conWEATL) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['WEATL'] . '</td>';
		$r .= '<td width=80% class=rptl>Weapons Technology Laboratory (con +3%, res +3%, off +3%, def +3%)</td>';
		$r .= '</tr>';
	}

	if ($row['PLATE'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['PLATE'] * conPLATE) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['PLATE'] . '</td>';
		$r .= '<td width=80% class=rptl>Plating Factory (dur +7%, maint +7%)</td>';
		$r .= '</tr>';
	}

	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getOrbitalDefense($row)
{
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Orbital Defense</td></tr>';

	if ($row['OMIN1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OMIN1'] * conOMIN1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OMIN1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Minefield</td>';
		$r .= '</tr>';
	}

	if ($row['OMIN2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OMIN2'] * conOMIN2) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OMIN2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Minefield (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['OSLD1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OSLD1'] * conOSLD1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OSLD1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Shield</td>';
		$r .= '</tr>';
	}

	if ($row['OSLD2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OSLD2'] * conOSLD2) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OSLD2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Shield (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['ODEF1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ODEF1'] * conODEF1) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEF1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Platform</td>';
		$r .= '</tr>';
	}

	if ($row['ODEF2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ODEF2'] * conODEF2) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEF2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Platform (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['ODEFM'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ODEFM'] * conODEFM) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEFM'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Monitor</td>';
		$r .= '</tr>';
	}

	if ($row['SBASE'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SBASE'] * conSBASE) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SBASE'] . '</td>';
		$r .= '<td width=80% class=rptl>Starbase</td>';
		$r .= '</tr>';
	}

	//Added orbital bulwarks
	if ($row['OBULK'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OBULK'] * conOBULK) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OBULK'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Bulwark</td>';
		$r .= '</tr>';
	}

	if ($row['AMIPS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['AMIPS'] * conAMIPS) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['AMIPS'] . '</td>';
		$r .= '<td width=80% class=rptl>Planetary Shield</td>';
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
		$r .= '<tr><td width=10% class=rptc>' . $row['DIPCO'] . '</td><td class=rptl>Diplomatic Council (diplo +5%, wealth +5%)</td></tr>';
	}

	if ($row['EMBAS'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['EMBAS'] . '</td><td class=rptl>Embassy (diplo +' . ($row['EMBAS'] * 5) . '%)</td></tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getFighters($row) {
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Fighter Bomber</td></tr>';

	if ($row['FIGIN'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['FIGIN'] * conFIGIN) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FIGIN'] . '</td>';
		$r .= '<td width=80% class=rptl>Fighter Interceptor</td>';
		$r .= '</tr>';
	}

	if ($row['FIGBO'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['FIGBO'] * conFIGBO) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FIGBO'] . '</td>';
		$r .= '<td width=80% class=rptl>Fighter Bomber</td>';
		$r .= '</tr>';
	}

	if ($row['ADVIN'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['ADVIN'] * conADVIN) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['ADVIN'] . '</td>';
		$r .= '<td width=80% class=rptl>Advanced Interceptor</td>';
		$r .= '</tr>';
	}

	if ($row['HVYBO'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['HVYBO'] * conHVYBO) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HVYBO'] . '</td>';
		$r .= '<td width=80% class=rptl>Heavy Bomber</td>';
		$r .= '</tr>';
	}

	if ($row['WASFI'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['WASFI'] * conWASFI) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['WASFI'] . '</td>';
		$r .= '<td width=80% class=rptl>"Wasp" Fighter</td>';
		$r .= '</tr>';
	}

	if ($row['FANFB'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['FANFB'] * conFANFB) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FANFB'] . '</td>';
		$r .= '<td width=80% class=rptl>"Fang" Fighter Bomber</td>';
		$r .= '</tr>';
	}

	if ($row['DAGHF'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['DAGHF'] * conDAGHF) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['DAGHF'] . '</td>';
		$r .= '<td width=80% class=rptl>"Dagger" Heavy Fighter</td>';
		$r .= '</tr>';
	}

	if ($row['VENHF'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptcdur>' . number_format($row['VENHF'] * conVENHF) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['VENHF'] . '</td>';
		$r .= '<td width=80% class=rptl>"Venom" Heavy Fighter</td>';
		$r .= '</tr>';
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
		$r .= '<td width=10% class=rptr>' . number_format($row['HABI1'] * conHABI1CAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HABI1'] . '</td>';
		$r .= '<td width=80% class=rptl>Habitat</td>';
		$r .= '</tr>';
	}

	if ($row['HABI2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HABI2'] * conHABI2CAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HABI2'] . '</td>';
		$r .= '<td width=80% class=rptl>Habitat (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['HABI3'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HABI3'] * conHABI3CAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HABI3'] . '</td>';
		$r .= '<td width=80% class=rptl>Habitat (Ultradense)</td>';
		$r .= '</tr>';
	}

	if ($row['GNDHI'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['GNDHI'] * conGNDHICAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['GNDHI'] . '</td>';
		$r .= '<td width=80% class=rptl>Grand Hive (con +' . ($row['GNDHI'] * 3) . '%, repro +' . ($row['GNDHI'] * 3) . '%)</td>';
		$r .= '</tr>';
	}

	if ($row['HIBCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['HIBCA'] * conHIBCACAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['HIBCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Hibernation Cave (repro +' . ($row['HIBCA'] * 5) . '%)</td>';
		$r .= '</tr>';
	}

	if ($row['VINEM'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['VINEM'] * conVINEMCAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['VINEM'] . '</td>';
		$r .= '<td width=80% class=rptl>Vinemind (con +' . ($row['VINEM'] * 2) . '%, res +' . ($row['VINEM'] * 2) . '%, repro +' . ($row['VINEM'] * 2) . '%, sensor +' . ($row['VINEM'] * 5) .'%)</td>';
		$r .= '</tr>';
	}

	if ($row['SBASE'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['SBASE'] * conSBASECAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['SBASE'] . '</td>';
		$r .= '<td width=80% class=rptl>Starbase</td>';
		$r .= '</tr>';
	}

	if ($row['BROCE'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptr>' . number_format($row['BROCE'] * conBROCECAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['BROCE'] . '</td>';
		$r .= '<td width=80% class=rptl>Brood Center (con +' . ($row['BROCE'] * 10) . '%, diplo +' . ($row['BROCE'] * 5) . '%, wealth +' . ($row['BROCE'] * 5) . '%)</td>';
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
		$r .= '<tr><td width=10% width=10% class=rptc>' . $row['INTEL'] . '</td><td width=90% class=rptl>Intelligence Agency (sensor +5%)</td></tr>';
	}

	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}

function getMaterials($row) {
	$r  = '';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#A9A9A9><tr><td>';
	$r .= '<table width=100% border=0 cellspacing=1 cellpadding=1 bgcolor=#FFFFFF>';
	$r .= '<tr><td class=hc colspan=3>Materials</td></tr>';

	if ($row['FARM1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['FARM1'] *  conFARM1PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FARM1'] . '</td>';
		$r .= '<td width=80% class=rptl>Farm 1</td>';
		$r .= '</tr>';
	}

	if ($row['FARM2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['FARM2'] *  conFARM2PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FARM2'] . '</td>';
		$r .= '<td width=80% class=rptl>Farm II</td>';
		$r .= '</tr>';
	}

	if ($row['FARM3'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['FARM3'] *  conFARM3PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FARM3'] . '</td>';
		$r .= '<td width=80% class=rptl>Farm III</td>';
		$r .= '</tr>';
	}

	if ($row['MINE1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['MINE1'] *  conMINE1PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['MINE1'] . '</td>';
		$r .= '<td width=80% class=rptl>Mining Facility (Metals)</td>';
		$r .= '</tr>';
	}

	if ($row['MINE2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['MINE2'] *  conMINE2PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['MINE2'] . '</td>';
		$r .= '<td width=80% class=rptl>Mining Facility (Metals Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['RADI1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['RADI1'] *  conRAD1PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['RADI1'] . '</td>';
		$r .= '<td width=80% class=rptl>Mining Facility (Radioactives)</td>';
		$r .= '</tr>';
	}

	if ($row['RADI2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['RADI2'] *  conRAD2PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['RADI2'] . '</td>';
		$r .= '<td width=80% class=rptl>Mining Facility (Radioactives Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['FUEL1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['FUEL1'] * conFUEL1PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FUEL1'] . '</td>';
		$r .= '<td width=80% class=rptl>Refinery (Fuel)</td>';
		$r .= '</tr>';
	}

	if ($row['FUEL2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['FUEL2'] * conFUEL2PROD) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['FUEL2'] . '</td>';
		$r .= '<td width=80% class=rptl>Refinery (Fuel Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['MATS1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc> +5%</td>';
		$r .= '<td width=10% class=rptc>' . $row['MATS1'] . '</td>';
		$r .= '<td width=80% class=rptl>Materials Processing Plant (mats +5%)</td>';
		$r .= '</tr>';
	}

	if ($row['MATS2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc> +7%</td>';
		$r .= '<td width=10% class=rptc>' . $row['MATS2'] . '</td>';
		$r .= '<td width=80% class=rptl>Materials Processing Plant (Improved) (mats +7%)</td>';
		$r .= '</tr>';
	}

	if ($row['WHSE1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['WHSE1'] *  conWHSE1CAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['WHSE1'] . '</td>';
		$r .= '<td width=80% class=rptl>Warehouse (Small)</td>';
		$r .= '</tr>';
	}

	if ($row['WHSE2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['WHSE2'] *  conWHSE2CAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['WHSE2'] . '</td>';
		$r .= '<td width=80% class=rptl>Warehouse (Medium)</td>';
		$r .= '</tr>';
	}

	if ($row['WHSE3'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . number_format($row['WHSE3'] *  conWHSE3CAP) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['WHSE3'] . '</td>';
		$r .= '<td width=80% class=rptl>Warehouse (Large)</td>';
		$r .= '</tr>';
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
		$r .= '<td width=80% class=rptl>Manufacturing Plant (con +' . $row['MANU1'] . '%)</td>';
		$r .= '</tr>';
	}

	if ($row['MANU2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['MANU2'] * 2) . '</td>';
		$r .= '<td width=10% class=rptc>' . $row['MANU2'] . '</td>';
		$r .= '<td width=80% class=rptl>Manufacturing Plant (Improved) (con +' . ($row['MANU2'] * 2) . '%)</td>';
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
		$r .= '<td width=80% class=rptl>Advanced Technologies Shipyard (con +' . ($row['ADVTS'] * 5) . '%, dur +' . ($row['ADVTS'] * 5) . '%, speed +' . ($row['ADVTS'] * 5) . '%)</td>';
		$r .= '</tr>';
	}

	if ($row['MATRC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptc>' . ($row['MATRC'] * 1). '</td>';
		$r .= '<td width=10% class=rptc>' . $row['MATRC'] . '</td>';
		$r .= '<td width=80% class=rptl>Materials Research Complex (res +2%, dur +10%)</td>';
		$r .= '</tr>';
	}

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
		$r .= '<tr><td width=10% class=rptc>' . $row['GELAB'] . '</td><td class=rptl>Genetics Lab (repro +' . ($row['GELAB'] * 5) . '%)</td></tr>';
	}

	if ($row['ADVGE'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['ADVGE'] . '</td><td class=rptl>Advanced Genetics Lab (con +2%, res +2%, diplo +2%, off +2%, def +2%, wealth +2%, repro +2%)</td></tr>';
	}

	if ($row['BIOLO'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['BIOLO'] . '</td><td class=rptl>Biological Research Facility (off +3%, repro +5%, dur +3%)</td></tr>';
	}

	if ($row['INTFO'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['INTFO'] . '</td><td class=rptl>Interstellar Forum (diplo +5%, repro +5%)</td></tr>';
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
		$r .= '<tr><td width=10% class=rptc>' . $row['RLAB1'] . '</td><td class=rptl>Research Lab (res +' . ($row['RLAB1'] * 5) . '%)</td></tr>';
	}

	if ($row['RLAB2'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['RLAB2'] . '</td><td class=rptl>Research Lab (Improved) (res +' . ($row['RLAB2'] * 7) . '%)</td></tr>';
	}

	if ($row['INSHT'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['INSHT'] . '</td><td class=rptl>Institute of Higher Thought (res +10%, train +2%)</td></tr>';
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
		$r .= '<tr><td width=10% class=rptc>' . $row['TRACK'] . '</td><td class=rptl>Tracking Station (sensor +' . ($row['TRACK'] * 1) . '%)</td></tr>';
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
		$r .= '<tr><td width=10% class=rptc>' . $row['TSCHL'] . '</td><td class=rptl>Trade School (train +2%)</td></tr>';
	}

	if ($row['UNIVE'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['UNIVE'] . '</td><td class=rptl>University (train +5%)</td></tr>';
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
		$r .= '<tr><td width=10% class=rptc>' . $row['PBANK'] . '</td><td class=rptl>Planetary Bank (wealth +' . ($row['PBANK'] * 2) . '%)</td></tr>';
	}

	if ($row['STOCK'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['STOCK'] . '</td><td class=rptl>Stock Exchange (wealth +' . ($row['STOCK'] * 5) . '%)</td></tr>';
	}

	if ($row['INTMP'] > 0)
	{
		$r .= '<tr><td width=10% class=rptc>' . $row['INTMP'] . '</td><td class=rptl>Interplanetary Marketplace (wealth +10%, diplo +5%)</td></tr>';
	}
	$r .= '</table>';
	$r .= '</td></tr></table>';
	return $r;
}
?>