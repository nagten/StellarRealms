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

//Check what the user requested
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
		displayPlanetDetails($planet, 1); //Structures
		displayPlanetDetails($planet, 2); //Fleet
		break;
	case 'detail':
		displayScoutingReport($reportID);
		break;
}

//=============================================================================

function displaySummary()
{
	//Scouting Reports
	//Reconnaitertype: 1 structure, 2 fleet
	global $p;
	global $p1;
	global $p2;
	global $d1;
	global $sort;

	$bgcolor = '#F5F5F5';

	for ($intTypeOfReports = 0; $intTypeOfReports < 2; $intTypeOfReports++)
	{
		//echo $intTypeOfReports . '<br>';
		if ($intTypeOfReports == 0)
		{
			//get structure recons
			$SQL  = 'SELECT RecordNumber,PlanetID,PlanetName,ReportDate,ReportTime,AirCap, SourceName,';
			$SQL .= 'Fighter,IntelOps,Materials,FleetRating,OrbRating,SurRating,BuildRating,';
			$SQL .= 'Scouting,Warehouse,Speed,Rank,HabSpace,Queues,Species,SBASE,STIDR,Reconnaitertype ';
			$SQL .= 'FROM tblscout ';
			$SQL .= 'WHERE Current = \'Y\' and Reconnaitertype = 1 ';
			$SQL .= 'ORDER BY PlanetName,ReportDate DESC,ReportTime DESC';
		}
		else
		{
			//get fleet recons
			$SQL  = 'SELECT RecordNumber,PlanetID,PlanetName,ReportDate,ReportTime,AirCap, SourceName,';
			$SQL .= 'Fighter,IntelOps,Materials,FleetRating,OrbRating,SurRating,BuildRating,';
			$SQL .= 'Scouting,Warehouse,Speed,Rank,HabSpace,Queues,Species,SBASE,STIDR,Reconnaitertype ';
			$SQL .= 'FROM tblscout ';
			$SQL .= 'WHERE Current = \'Y\' and Reconnaitertype = 2 ';
			$SQL .= 'ORDER BY PlanetName,ReportDate DESC,ReportTime DESC';
		}
		//echo $SQL;
		$result = mysql_query($SQL);

		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}
		else
		{
			if (mysql_num_rows($result) > 0 && $intTypeOfReports == 1)
			{
				//We clear our planet array for the fleet recons
				$p = array();
			}
		}

		while ($row = mysql_fetch_assoc($result))
		{
			$planetID = $row['PlanetID'];
			$recNbr	= $row['RecordNumber'];

			if ( ! array_key_exists($planetID,$p))
			{
				//no planets yet
				$p[$planetID] = 0;
			}

			//We only want to compare the two most recent ones
			if ($p[$planetID] < 2)
			{
				if ($p[$planetID] == 0)
				{
					//first report
					$p[$planetID] = 1;
					$p1[$planetID]['planetName'] = stripslashes($row['PlanetName']);

					if ($row['Reconnaitertype'] == 1)
					{
						$p1[$planetID]['reportDateTimeStructureReport']	= $row['ReportDate'] . " " . $row['ReportTime'];
					}
					else
					{
						$p1[$planetID]['reportDateTimeFleetReport'] = $row['ReportDate'] . " " . $row['ReportTime'];
					}

					$p1[$planetID]['rank']	= $row['Rank'];
					$p1[$planetID]['sourcename']	= $row['SourceName'];
					$p1[$planetID]['fleetrating']	= $p1[$planetID]['fleetrating'] + $row['FleetRating'];
					$p1[$planetID]['orbrating']	= $p1[$planetID]['orbrating'] + $row['OrbRating'];
					$p1[$planetID]['surrating']	= $p1[$planetID]['surrating'] + $row['SurRating'];
					$p1[$planetID]['buildrating']	= $p1[$planetID]['buildrating'] + $row['BuildRating'];
					$p1[$planetID]['starbases']	= $p1[$planetID]['starbases'] + $row['SBASE'];
					$p1[$planetID]['airCap']	= $p1[$planetID]['airCap'] + $row['AirCap'];
					$p1[$planetID]['fighter']	= $p1[$planetID]['fighter'] + $row['Fighter'];
					$p1[$planetID]['drones']	= $row['STIDR'];

					if ($row['Reconnaitertype'] == 1)
					{
						$p1[$planetID]['habSpace']	= $p1[$planetID]['habSpace'] + ($row['HabSpace'] / 1000000);
					}
					else
					{
						$p1[$planetID]['habSpace']	= $p1[$planetID]['habSpace'];
					}

					$p1[$planetID]['intelOps']	= $p1[$planetID]['intelOps'] + $row['IntelOps'];
					$p1[$planetID]['materials']	= $p1[$planetID]['materials'] + $row['Materials'];
					$p1[$planetID]['scouting']	= $p1[$planetID]['scouting'] + $row['Scouting'];
					$p1[$planetID]['warehouse']	= $p1[$planetID]['warehouse'] + $row['Warehouse'];

					if ($row['Reconnaitertype'] == 1)
					{
						$p1[$planetID]['queues']	= $p1[$planetID]['queues'] + $row['Queues'];
					}
					else
					{
						$p1[$planetID]['queues']	= $p1[$planetID]['queues'] + $row['Queues'] - 1;
					}

					$p1[$planetID]['speed']	= $p1[$planetID]['speed'] + $row['Speed'];
					$p1[$planetID]['species']	= $row['Species'];
					$p1[$planetID]['reconnaitertype']	= $p1[$planetID]['reconnaitertype'] + $row['Reconnaitertype'];
				}
				else
				{
					//second report
					$p[$planetID] = 2;
					$p2[$planetID]['planetName'] = stripslashes($row['PlanetName']);

					if ($row['Reconnaitertype'] == 1)
					{
						$p2[$planetID]['reportDateTimeStructureReport']	= $row['ReportDate'] . " " . $row['ReportTime'];
					}
					else
					{
						$p2[$planetID]['reportDateTimeFleetReport'] = $row['ReportDate'] . " " . $row['ReportTime'];
					}

					$p2[$planetID]['rank']	= $row['Rank'];
					$p2[$planetID]['sourcename']	= $row['SourceName'];
					$p2[$planetID]['fleetrating']	= $p2[$planetID]['fleetrating'] + $row['FleetRating'];
					$p2[$planetID]['orbrating']	= $p2[$planetID]['orbrating'] + $row['OrbRating'];
					$p2[$planetID]['surrating']	= $p2[$planetID]['surrating'] + $row['SurRating'];
					$p2[$planetID]['buildrating']	= $p2[$planetID]['buildrating'] + $row['BuildRating'];
					$p2[$planetID]['starbases']	= $p2[$planetID]['starbases'] + $row['SBASE'];
					$p2[$planetID]['airCap']	= $p2[$planetID]['airCap'] + $row['AirCap'];
					$p2[$planetID]['fighter']	= $p2[$planetID]['fighter'] + $row['Fighter'];
					$p2[$planetID]['drones']	= $row['STIDR'];

					if ($row['Reconnaitertype'] == 1)
					{
						$p2[$planetID]['habSpace']	= $p2[$planetID]['habSpace'] + ($row['HabSpace'] / 1000000);
					}
					else
					{
						$p2[$planetID]['habSpace']	= $p2[$planetID]['habSpace'] ;
					}
					//$p2[$planetID]['habSpace']	= $p2[$planetID]['habSpace'] + $row['HabSpace'];

					$p2[$planetID]['intelOps']	= $p2[$planetID]['intelOps'] + $row['IntelOps'];
					$p2[$planetID]['materials']	= $p2[$planetID]['materials'] + $row['Materials'];
					$p2[$planetID]['scouting']	= $p2[$planetID]['scouting'] + $row['Scouting'];
					$p2[$planetID]['warehouse']	= $p2[$planetID]['warehouse'] + $row['Warehouse'];

					if ($row['Reconnaitertype'] == 1)
					{
						$p2[$planetID]['queues']	= $p2[$planetID]['queues'] + $row['Queues'];
					}
					else
					{
						$p2[$planetID]['queues']	= $p2[$planetID]['queues'] + $row['Queues'] - 1;
					}

					$p2[$planetID]['speed']	= $p2[$planetID]['speed'] + $row['Speed'];
					$p2[$planetID]['species']	= $row['Species'];
					$p2[$planetID]['reconnaitertype']	= $p2[$planetID]['reconnaitertype'] + $row['Reconnaitertype'];
				}
			}
			else
			{
				//We only want to compare the two most recent ones
				//set the current recNbr to 'N'
				$SQL = 'UPDATE tblscout SET Current = \'N\' WHERE RecordNumber = ' . $recNbr;
				$dummy = mysql_query($SQL);
			}
		}
		
		mysql_free_result($result);
	}

	foreach ($p1 as $key => $value)
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
			$d1[$key]['orbrating']	= '';
			$d1[$key]['surrating']	= '';
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
			$array = $p1;
			break;
		case 'rank':
			$array = array_column_sort($p1,$sort,SORT_ASC);
			break;
		default:
			$array = array_column_sort($p1,$sort,SORT_DESC); //SORT_DESC
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

		//alternating colors
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
		$pName = substr($pName,0,15); //Limit name to 15 chars

		$r .= '<tr bgcolor=' . $bgcolor . ' onclick="getPlanet(' . $key . ')">';
		//$r .= '<td class=ull>' . $pName . ' (' . $specie . ')'. '<div class=hidden id=p_' . $cnt . '>' . $key . '</div></td>';
		//$r .= '<td class=xc>' . $p1[$key]['reportDateTime']   . '</td>';

		if ($p1[$key]['reconnaitertype'] == 1)
		{
			//Structure
			$r .= '<td class=ull>' . $pName . ' (' . $specie . ') (S)' . '<div class=hidden id=p_' . $cnt . '>' . $key . '</div></td>';
		}
		else if ($p1[$key]['reconnaitertype'] == 2)
		{
			//Fleet
			$r .= '<td class=ull>' . $pName . ' (' . $specie . ') (F)' . '<div class=hidden id=p_' . $cnt . '>' . $key . '</div></td>';
		}
		else
		{
			//Combined
			$r .= '<td class=ull>' . $pName . ' (' . $specie . ') (C)' . '<div class=hidden id=p_' . $cnt . '>' . $key . '</div></td>';
		}

		if ($p1[$key]['reportDateTimeStructureReport'] != '')
		{
			$r .= '<td class=xc>' . date('d-M H:i',strtotime($p1[$key]['reportDateTimeStructureReport'])) . '</td>';
		}
		else
		{
			$r .= '<td class=xc>' . $p1[$key]['reportDateTimeStructureReport'] . '</td>';
		}

		if ($p1[$key]['reportDateTimeFleetReport'] != '')
		{
			$r .= '<td class=xc>' . date('d-M H:i',strtotime($p1[$key]['reportDateTimeFleetReport'])) . '</td>';
		}
		else
		{
			$r .= '<td class=xc>' . $p1[$key]['reportDateTimeFleetReport'] . '</td>';
		}

		$r .= '<td class=xc>' . $p1[$key]['rank']	. '</td>';
		$r .= '<td class=xc>' . $p1[$key]['sourcename']	. '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['fleetrating'])  . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['fleetrating']	. '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['orbrating'])	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['orbrating']	. '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['surrating'])	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['surrating']	. '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['buildrating'])  . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['buildrating']	. '</td>';
		$r .= '<td class=xr>' . $p1[$key]['starbases'] . '</td>';
		$r .= '<td class=xr>' . $d1[$key]['starbases'] . '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['airCap'])	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['airCap']	. '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['fighter'])	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['fighter']	. '</td>';
		$r .= '<td class=xr>' . number_format($p1[$key]['drones'])	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['drones']	. '</td>';
		$r .= '<td class=xr>' . sprintf('%01.2f',$p1[$key]['habSpace'])	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['habSpace']	. '</td>';
		$r .= '<td class=xr>' . $p1[$key]['intelOps']	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['intelOps']	. '</td>';
		$r .= '<td class=xr>' . $p1[$key]['materials']	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['materials']	. '</td>';
		$r .= '<td class=xr>' . $p1[$key]['queues']	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['queues']	. '</td>';
		$r .= '<td class=xr>' . $p1[$key]['scouting']	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['scouting']	. '</td>';
		$r .= '<td class=xr>' . $p1[$key]['warehouse']	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['warehouse']	. '</td>';
		$r .= '<td class=xr>' . $p1[$key]['speed']	. '</td>';
		$r .= '<td class=xr>' . $d1[$key]['speed']	. '</td>';
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
	$col22 = 'uhc';

	switch ($sort)
	{
		case 'planet':
			$col01 = 'shc';
			break;
		case 'reportDateTimeStructureReport':
			$col20 = 'shc';
			break;
		case 'rank':
			$col04 = 'shc';
			break;
		case 'sourcename':
			$col21 = 'shc';
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
		case 'reportDateTimeFleetReport':
			$col22 = 'shc';
			break;
	}

	$r  = '';
	$r .= '<tr>';
	$r .= '<td class=' . $col01 . ' colspan=1 onclick=sortColumn("planet") onmouseover=glowObject(this) onmouseout=dimObject(this)>Planet</td>';
	$r .= '<td class=' . $col20 . ' colspan=1 onclick=sortColumn("reportDateTimeStructureReport") onmouseover=glowObject(this) onmouseout=dimObject(this)>StructDateTime</td>';
	$r .= '<td class=' . $col22 . ' colspan=1 onclick=sortColumn("reportDateTimeFleetReport") onmouseover=glowObject(this) onmouseout=dimObject(this)>FleetDateTime</td>';
	$r .= '<td class=' . $col04 . ' colspan=1 onclick=sortColumn("rank") onmouseover=glowObject(this) onmouseout=dimObject(this)>Rank</td>';
	$r .= '<td class=' . $col21 . ' colspan=1 onclick=sortColumn("sourcename") onmouseover=glowObject(this) onmouseout=dimObject(this)>Source</td>';
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
	for ($intI = 0; $intI < sizeof($array); $intI++)
	{
		$sort_values[$intI] = $array[$intI][$key];
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

	$intI = 0;
	$multi_sort_line = "return array_multisort( ";

	foreach ($args as $arg)
	{
		$intI++;
		if ( is_string($arg) )
		{
			foreach ($array_mod as $row_key => $row)
			{
				$sort_array[$intI][] = $row[$arg];
			}
		}
		else
		{
			$sort_array[$intI] = $arg;
		}
		$multi_sort_line .= "\$sort_array[" . $intI . "], ";
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
function displayPlanetDetails($planet, $Type)
{
	//Planet Detail
	global $xp;
	global $xd;

	$cnt = 0;

	if ($Type == 1)
	{
		$SQL  = 'SELECT RecordNumber, PlanetID,PlanetName,ReportDate,ReportTime,AirOps,AirCap,';
		$SQL .= 'Fighter,Habitat,IntelOps,Materials,';
		$SQL .= 'Scouting,Sensors,Warehouse,Special,Speed,Rank,HabSpace,SBASE,STIDR, ';
		$SQL .= 'FleetRating,OrbRating,SurRating,BuildRating,Queues, SourceName, Reconnaitertype ';
		$SQL .= 'FROM tblscout ';
		$SQL .= 'WHERE PlanetID=' . $planet . '  and Reconnaitertype = 1 ';
		$SQL .= 'ORDER BY ReportDate DESC, ReportTime DESC';
	}
	else
	{
		$SQL  = 'SELECT RecordNumber, PlanetID,PlanetName,ReportDate,ReportTime,AirOps,AirCap,';
		$SQL .= 'Fighter,Habitat,IntelOps,Materials,';
		$SQL .= 'Scouting,Sensors,Warehouse,Special,Speed,Rank,HabSpace,SBASE,STIDR, ';
		$SQL .= 'FleetRating,OrbRating,SurRating,BuildRating,Queues, SourceName, Reconnaitertype ';
		$SQL .= 'FROM tblscout ';
		$SQL .= 'WHERE PlanetID=' . $planet . '  and Reconnaitertype = 2 ';
		$SQL .= 'ORDER BY ReportDate DESC, ReportTime DESC';
	}
	$result = mysql_query($SQL);

	if (!$result)
	{
		die('Invalid query: ' . mysql_error());
	}

	while ($row = mysql_fetch_assoc($result))
	{
		$cnt++;
		$xp[$cnt]['scoutID']	= $row['RecordNumber'];
		$xp[$cnt]['planetID']	= $row['PlanetID'];
		$xp[$cnt]['planetName']	= stripslashes($row['PlanetName']);
		$xp[$cnt]['reportDate']	= $row['ReportDate'];
		$xp[$cnt]['reportTime']	= $row['ReportTime'];
		$xp[$cnt]['rank']	= $row['Rank'];
		$xp[$cnt]['sourcename']	= $row['SourceName'];
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
		$xp[$cnt]['reconnaitertype'] = $row['Reconnaitertype'];
	}
	
	mysql_free_result($result);

	$nbrReports = $cnt;
	for ($intI = 1; $intI <= $nbrReports; $intI++)
	{
		if ($intI < $nbrReports)
		{
			calcReportDiff($intI,'fleetrating');
			calcReportDiff($intI,'orbrating');
			calcReportDiff($intI,'surrating');
			calcReportDiff($intI,'buildrating');
			calcReportDiff($intI,'starbases');
			calcReportDiff($intI,'airCap');
			calcReportDiff($intI,'fighter');
			calcReportDiff($intI,'drones');
			calcDiffHabitat($intI,'habSpace');
			calcReportDiff($intI,'intelOps');
			calcReportDiff($intI,'materials');
			calcReportDiff($intI,'queues');
			calcReportDiff($intI,'scouting');
			calcReportDiff($intI,'warehouse');
			calcReportDiff($intI,'speed');
		}
		else
		{
			$xd[$intI]['fleetrating']	= '';
			$xd[$intI]['orbrating']	= '';
			$xd[$intI]['surrating']	= '';
			$xd[$intI]['buildrating']	= '';
			$xd[$intI]['starbases']	= '';
			$xd[$intI]['airCap']	= '';
			$xd[$intI]['fighter']	= '';
			$xd[$intI]['drones']	= '';
			$xd[$intI]['habSpace']	= '';
			$xd[$intI]['intelOps']	= '';
			$xd[$intI]['materials']	= '';
			$xd[$intI]['queues']	= '';
			$xd[$intI]['scouting']	= '';
			$xd[$intI]['warehouse']	= '';
			$xd[$intI]['speed']	= '';
		}
	}

	$bgcolor = '#F5F5F5';

	/*
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	*/

	$r  = '';
	$r .= '<div class=divDetail>';
	$r .= '<table width=100% border=0 cellpadding=1 cellspacing=2 bgcolor=#FFFFFF>';
	$r .= '<tr>';
	$r .= '<td class=hh colspan=1>Planet</td>';
	$r .= '<td class=hc colspan=1>Date</td>';
	$r .= '<td class=hc colspan=1>Time</td>';
	$r .= '<td class=hc colspan=1>Rank</td>';
	$r .= '<td class=hc colspan=1>Source</td>';
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

	for ($intI = 1; $intI <= $nbrReports; $intI++)
	{
		if ($bgcolor == '#F5F5F5')
		{
			$bgcolor = '#FFFFFF';
		}
		else
		{
			$bgcolor = '#F5F5F5';
		}

		$pName = $xp[$intI]['planetName'];
		$pName = substr($pName,0,15); //Limit name to 15 chars

		$r .= '<tr bgcolor=' . $bgcolor . ' onclick="getDetail(' . $xp[$intI]['scoutID'] . ')">';

	//	$r .= '<td class=xx>' . $pName  . '<div class=hidden id=' . ('d_' . $intI) . '>' . $xp[$intI]['scoutID'] . '</div></td>';

		if ($xp[$intI]['reconnaitertype'] == 1)
		{
			//Structure
			$r .= '<td class=xx>' . $pName  . " (S)" . '<div class=hidden id=' . ('d_' . $intI) . '>' . $xp[$intI]['scoutID'] . '</div></td>';
		}
		else if ($xp[$intI]['reconnaitertype'] == 2)
		{
			//Fleet
			//c_ to workaround an IE only bug (getelementbyID is buggy in IE)
			$r .= '<td class=xx>' . $pName  . " (F)" . '<div class=hidden id=' . ('c_' . $intI) . '>' . $xp[$intI]['scoutID'] . '</div></td>';
		}
		else
		{
			//Combined
			$r .= '<td class=xx>' . $pName  . " (C)" . '<div class=hidden id=' . ('d_' . $intI) . '>' . $xp[$intI]['scoutID'] . '</div></td>';
		}

		$r .= '<td class=ulc>' . date('d-M-y',strtotime($xp[$intI]['reportDate']))   . '</td>';
		$r .= '<td class=ulc>' . date('H:i',strtotime($xp[$intI]['reportTime']))   . '</td>';
		$r .= '<td class=xc>' . $xp[$intI]['rank']         . '</td>';
		$r .= '<td class=xc>' . $xp[$intI]['sourcename']         . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$intI]['fleetrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['fleetrating']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$intI]['orbrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['orbrating']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$intI]['surrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['surrating']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$intI]['buildrating'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['buildrating']      . '</td>';
		$r .= '<td class=xr>' . $xp[$intI]['starbases'] . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['starbases'] . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$intI]['airCap'])       . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['airCap']       . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$intI]['fighter'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['fighter']      . '</td>';
		$r .= '<td class=xr>' . number_format($xp[$intI]['drones'])      . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['drones']      . '</td>';
		$r .= '<td class=xr>' . sprintf('%01.2f',$xp[$intI]['habSpace'])   . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['habSpace']      . '</td>';
		$r .= '<td class=xr>' . $xp[$intI]['intelOps']     . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['intelOps']     . '</td>';
		$r .= '<td class=xr>' . $xp[$intI]['materials']    . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['materials']    . '</td>';
		$r .= '<td class=xr>' . $xp[$intI]['queues']     . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['queues']     . '</td>';
		$r .= '<td class=xr>' . $xp[$intI]['scouting']     . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['scouting']     . '</td>';
		$r .= '<td class=xr>' . $xp[$intI]['warehouse']      . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['warehouse']      . '</td>';
		$r .= '<td class=xr>' . $xp[$intI]['speed']        . '</td>';
		$r .= '<td class=xr>' . $xd[$intI]['speed']        . '</td>';
	}

	$r .= '</table>';
	$r .= '</div>';
	echo $r;
}

function calcReportDiff($intI,$item)
{
	global $xp;
	global $xd;

	$v1 = $xp[$intI + 0][$item];
	$v2 = $xp[$intI + 1][$item];

	$diff = $v1 - $v2;

	if ($diff > 0)
	{
		$xd[$intI][$item] = '<div class=positive>+' . number_format($diff)   . '</div>';
	}
	elseif ($diff == 0)
	{
		$xd[$intI][$item] = '<div class=even>'      . ' '   . '</div>';
	}
	else
	{
		$xd[$intI][$item] = '<div class=negative>'  . number_format($diff)   . '</div>';
	}
}

function calcDiffHabitat($intI,$item)
{
	global $xp;
	global $xd;

	$v1 = $xp[$intI + 0][$item];
	$v2 = $xp[$intI + 1][$item];

	$diff = $v1 - $v2;


	if ($diff > 0)
	{
		$xd[$intI][$item] = '<div class=positive>+' . sprintf('%01.2f',$diff)   . '</div>';
	}
	elseif ($diff == 0)
	{
		$xd[$intI][$item] = '<div class=even>'      . ' '   . '</div>';
	}
	else
	{
		$xd[$intI][$item] = '<div class=negative>' . sprintf('%01.2f',$diff)   . '</div>';
	}
}

//==============================================================================================
function displayScoutingReport($reportID)
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
	$r .= '<td class=hc>Source</td>';
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

	//$SQL  = 'SELECT * FROM tblscout WHERE RecordNumber=' . $reportID;
	$SQL  = 'select PlanetID, ReportDate, ReportTime from tblscout where recordnumber =' . $reportID;
	$result = mysql_query($SQL);

	if (!$result)
	{
		die('Invalid query: ' . mysql_error());
	}
	else
	{
		$row = mysql_fetch_assoc($result);

		$SQL  = 'SELECT RecordNumber,PlanetID,PlanetName,SourceID,SourceName,ReportDate,ReportTime,';
		$SQL .= 'sum(Reconnaitertype) as Reconnaitertype,';
		$SQL .= 'sum(ADVGE) as ADVGE,';
		$SQL .= 'sum(ADVIN) as ADVIN,';
		$SQL .= 'sum(ADVTS) as ADVTS,';
		$SQL .= 'sum(AEGMS) as AEGMS,';
		$SQL .= 'sum(AIRB1) as AIRB1,';
		$SQL .= 'sum(AIRB2) as AIRB2,';
		$SQL .= 'sum(AMIPS) as AMIPS,';
		$SQL .= 'sum(ANVBS) as ANVBS,';
		$SQL .= 'sum(ASPHC) as ASPHC,';
		$SQL .= 'sum(AVASC) as AVASC,';
		$SQL .= 'sum(BADLC) as BADLC,';
		$SQL .= 'sum(BARAF) as BARAF,';
		$SQL .= 'sum(BARR1) as BARR1,';
		$SQL .= 'sum(BARR2) as BARR2,';
		$SQL .= 'sum(BATSH) as BATSH,';
		$SQL .= 'sum(BERDE) as BERDE,';
		$SQL .= 'sum(BIOLO) as BIOLO,';
		$SQL .= 'sum(BLABM) as BLABM,';
		$SQL .= 'sum(BROCE) as BROCE,';
		$SQL .= 'sum(COLFR) as COLFR,';
		$SQL .= 'sum(COLOS) as COLOS,';
		$SQL .= 'sum(CRUBC) as CRUBC,';
		$SQL .= 'sum(CRUIS) as CRUIS,';
		$SQL .= 'sum(DAGHF) as DAGHF,';
		$SQL .= 'sum(DEERS) as DEERS,';
		$SQL .= 'sum(DEFTU) as DEFTU,';
		$SQL .= 'sum(DESTR) as DESTR,';
		$SQL .= 'sum(DIPCO) as DIPCO,';
		$SQL .= 'sum(DRAMA) as DRAMA,';
		$SQL .= 'sum(DREAD) as DREAD,';
		$SQL .= 'sum(EMBAS) as EMBAS,';
		$SQL .= 'sum(FANFB) as FANFB,';
		$SQL .= 'sum(FARM1) as FARM1,';
		$SQL .= 'sum(FARM2) as FARM2,';
		$SQL .= 'sum(FARM3) as FARM3,';
		$SQL .= 'sum(FIGBO) as FIGBO,';
		$SQL .= 'sum(FIGIN) as FIGIN,';
		$SQL .= 'sum(FIRSD) as FIRSD,';
		$SQL .= 'sum(FRIGA) as FRIGA,';
		$SQL .= 'sum(FUEL1) as FUEL1,';
		$SQL .= 'sum(FUEL2) as FUEL2,';
		$SQL .= 'sum(FOLDR) as FOLDR,';
		$SQL .= 'sum(GELAB) as GELAB,';
		$SQL .= 'sum(GOLBA) as GOLBA,';
		$SQL .= 'sum(GNDHI) as GNDHI,';
		$SQL .= 'sum(HABI1) as HABI1,';
		$SQL .= 'sum(HABI2) as HABI2,';
		$SQL .= 'sum(HABI3) as HABI3,';
		$SQL .= 'sum(HAMGU) as HAMGU,';
		$SQL .= 'sum(HVYBO) as HVYBO,';
		$SQL .= 'sum(HVYCA) as HVYCA,';
		$SQL .= 'sum(HVYCR) as HVYCR,';
		$SQL .= 'sum(HIBCA) as HIBCA,';
		$SQL .= 'sum(HOSPI) as HOPSI,';
		$SQL .= 'sum(HURFC) as HURFC,';
		$SQL .= 'sum(IMPFR) as IMPFR,';
		$SQL .= 'sum(INSHT) as INSHT,';
		$SQL .= 'sum(INTEL) as INTEL,';
		$SQL .= 'sum(INTFR) as INTFR,';
		$SQL .= 'sum(INTMP) as INTMP,';
		$SQL .= 'sum(INTFO) as INTFO,';
		$SQL .= 'sum(JUDDR) as JUDDR,';
		$SQL .= 'sum(JUMP1) as JUMP1,';
		$SQL .= 'sum(JUMP2) as JUMP2,';
		$SQL .= 'sum(LEOSC) as LEOSC,';
		$SQL .= 'sum(LIGCA) as LIGCA,';
		$SQL .= 'sum(LISTN) as LISTN,';
		$SQL .= 'sum(MANU1) as MANU1,';
		$SQL .= 'sum(MANU2) as MANU2,';
		$SQL .= 'sum(MATS1) as MATS1,';
		$SQL .= 'sum(MATS2) as MATS2,';
		$SQL .= 'sum(MATRC) as MATRC,';
		$SQL .= 'sum(MINE1) as MINE1,';
		$SQL .= 'sum(MINE2) as MINE2,';
		$SQL .= 'sum(RADI1) as RADI1,';
		$SQL .= 'sum(RADI2) as RADI2,';
		$SQL .= 'sum(OBULK) as OBULK,';
		$SQL .= 'sum(OCON1) as OCON1,';
		$SQL .= 'sum(OCON2) as OCON2,';
		$SQL .= 'sum(ODEFM) as ODEFM,';
		$SQL .= 'sum(ODEF1) as ODEF1,';
		$SQL .= 'sum(ODEF2) as ODEF2,';
		$SQL .= 'sum(OMIN1) as OMIN1,';
		$SQL .= 'sum(OMIN2) as OMIN2,';
		$SQL .= 'sum(ORCBA) as ORCBA,';
		$SQL .= 'sum(OSLD1) as OSLD1,';
		$SQL .= 'sum(OSLD2) as OSLD2,';
		$SQL .= 'sum(PBANK) as PBANK,';
		$SQL .= 'sum(PLATE) as PLATE,';
		$SQL .= 'sum(PRIHC) as PRIHC,';
		$SQL .= 'sum(RAVMC) as RAVMC,';
		$SQL .= 'sum(RSENS) as RSENS,';
		$SQL .= 'sum(RLAB1) as RLAB1,';
		$SQL .= 'sum(RLAB2) as RLAB2,';
		$SQL .= 'sum(SATE1) as SATE1,';
		$SQL .= 'sum(SATE2) as SATE2,';
		$SQL .= 'sum(SCOUT) as SCOUT,';
		$SQL .= 'sum(SBASE) as SBASE,';
		$SQL .= 'sum(STIDR) as STIDR,';
		$SQL .= 'sum(STOCK) as STOCK,';
		$SQL .= 'sum(SDEF1) as SDEF1,';
		$SQL .= 'sum(SDEF2) as SDEF2,';
		$SQL .= 'sum(SSLD1) as SSLD1,';
		$SQL .= 'sum(SSLD2) as SSLD2,';
		$SQL .= 'sum(TANDB) as TANDB,';
		$SQL .= 'sum(TERCA) as TERCA,';
		$SQL .= 'sum(TORBA) as TORBA,';
		$SQL .= 'sum(TRACK) as TRACK,';
		$SQL .= 'sum(TSCHL) as TSCHL,';
		$SQL .= 'sum(UNIVE) as UNIVE,';
		$SQL .= 'sum(VENHF) as VENHF,';
		$SQL .= 'sum(VESSC) as VESSC,';
		$SQL .= 'sum(VINEM) as VINEM,';
		$SQL .= 'sum(WARFA) as WARFA,';
		$SQL .= 'sum(WASFI) as WASFI,';
		$SQL .= 'sum(WAYEC) as WAYEC,';
		$SQL .= 'sum(WHSE1) as WHSE1,';
		$SQL .= 'sum(WHSE2) as WHSE2,';
		$SQL .= 'sum(WHSE3) as WHSE3,';
		$SQL .= 'sum(WEATL) as WEATL,';
		$SQL .= 'sum(ZEPFD) as ZEPFD,';
		$SQL .= 'AirOps, Capital, Diplomacy, sum(Fighter) as Fighter, Habitat, sum(IntelOps) as IntelOps, sum(Materials) as Materials,';
		$SQL .= 'Reproduction, (sum(Queues) - 1) as Queues, Research, sum(Scouting) as Scouting, Sensors, Special, Speed, Training,';
		$SQL .= 'Warehouse, Wealth, Rank, Aircap, Habspace, sum(FleetRating) as FleetRating, sum(OrbRating) as OrbRating,';
		$SQL .= 'sum(SurRating) as SurRating, sum(BuildRating) as BuildRating, Current, Species, DurabilityPerc ';
		$SQL .= 'FROM tblscout ';
		$SQL .= 'WHERE PlanetID= ' . $row['PlanetID'] . ' and reportdate= \'' . $row['ReportDate'] . '\' and reporttime= \'' . $row['ReportTime'] . '\'';
		$SQL .= ' group by PlanetID, ReportDate, ReportTime';

		$result = mysql_query($SQL);

		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}

		$row = mysql_fetch_assoc($result);

		$pName = stripslashes($row['PlanetName']);
		$pName = substr($pName,0,15); //Limit name to 15 chars

		$r .= '<tr>';

		if ($row['Reconnaitertype'] == 1)
		{
			//Structure
			$r .= '<td class=xx>' . $pName	. ' (S)</td>';
		}
		else if($row['Reconnaitertype'] == 2)
		{
			//Fleet
			$r .= '<td class=xx>' . $pName	. ' (F)</td>';
		}
		else if($row['Reconnaitertype'] == 3)
		{
			//Combined
			$r .= '<td class=xx>' . $pName	. ' (C)</td>';
		}

		$r .= '<td class=xc>' . date('d-M-y',strtotime($row['ReportDate']))	. '</td>';
		$r .= '<td class=xc>' . date('H:i',strtotime($row['ReportTime']))	. '</td>';
		$r .= '<td class=xc>' . $row['Rank']	. '</td>';
		$r .= '<td class=xc>' . $row['SourceName']	. '</td>';
		$r .= '<td class=xc>' . $row['AirOps']	. '</td>';
		$r .= '<td class=xc>' . $row['Capital']	. '</td>';
		$r .= '<td class=xc>' . $row['Fighter']	. '</td>';
		$r .= '<td class=xc>' . $row['Habitat']	. '</td>';
		$r .= '<td class=xc>' . $row['Reproduction']	. '</td>';
		$r .= '<td class=xc>' . $row['Wealth']	. '</td>';
		$r .= '<td class=xc>' . $row['IntelOps']	. '</td>';
		$r .= '<td class=xc>' . $row['Research']	. '</td>';
		$r .= '<td class=xc>' . $row['Scouting']	. '</td>';
		$r .= '<td class=xc>' . $row['Materials']	. '</td>';
		$r .= '<td class=xc>' . $row['Queues']	. '</td>';
		$r .= '<td class=xc>' . $row['Diplomacy']	. '</td>';
		$r .= '<td class=xc>' . $row['Warehouse']	. '</td>';
		$r .= '<td class=xc>' . $row['Speed']	. '</td>';
		$r .= '</tr>';
		$r .= '</table>';
		$r .= '<div class=spacer></div>';
		$r .= '<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=#A9A9A9>';
		$r .= '<tr valign=top>';
		$r .= '<td>' . getHabitat($row)	. getSpeed($row)	. getCapital($row)	. '</td>';
		$r .= '<td>' . getReproduction($row) . getMaterials($row) . getAirOps($row)	. getResearch($row)   . '</td>';
		$r .= '<td>' . getWealth($row)	. getFighters($row)	. getDrones($row)	. getSurfaceDefense($row)   . getOrbitalDefense($row) 	 . getScout($row)      . '</td>';
		$r .= '<td>' . getSensors($row)	. getQueues($row)	. getDiplomacy($row)	. getTraining($row)   .  getIntelOps($row) . '</td>';
		$r .= '</tr>';
		$r .= '</table>';
	}
	mysql_free_result($result);
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
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ANVBS'] * conANVBS * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ANVBS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Anvil" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['ASPHC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ASPHC'] * conASPHC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ASPHC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Asp" Heavy Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['AVASC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['AVASC'] * conAVASC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['AVASC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Avalanche" Sige Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['BADLC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BADLC'] * conBADLC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BADLC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Badger" Light Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['BARAF'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BARAF'] * conBARAF * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARAF'] . '</td>';
		$r .= '<td width=80% class=rptl>"Barracuda" Attack Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['BATSH'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BATSH'] * conBATSH * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BATSH'] . '</td>';
		$r .= '<td width=80% class=rptl>Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['BERDE'] > 0)
	 {
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BERDE'] * conBERDE * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BERDE'] . '</td>';
		$r .= '<td width=80% class=rptl>"Berzerker" Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['BLABM'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BLABM'] * conBLABM * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BLABM'] . '</td>';
		$r .= '<td width=80% class=rptl>"Black Widow" Brood Minder</td>';
		$r .= '</tr>';
	}

	if ($row['COLFR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['COLFR'] * conCOLFR * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['COLFR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Collector" Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['COLOS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['COLOS'] * conCOLOS * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['COLOS'] . '</td>';
		$r .= '<td width=80% class=rptl>"Colossus" Megaship</td>';
		$r .= '</tr>';
	}

	if ($row['CRUBC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['CRUBC'] * conCRUBC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['CRUBC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Crusader" Battlecruiser</td>';
		$r .= '</tr>';
	}

	if ($row['CRUIS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['CRUIS'] * conCRUIS * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['CRUIS'] . '</td>';
		$r .= '<td width=80% class=rptl>Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['DESTR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DESTR'] * conDESTR * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DESTR'] . '</td>';
		$r .= '<td width=80% class=rptl>Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['DRAMA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DRAMA'] * conDRAMA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DRAMA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Dragon" Mobil Assualt Platform</td>';
		$r .= '</tr>';
	}

	if ($row['DREAD'] > 0)
	 {
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DREAD'] * conDREAD * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DREAD'] . '</td>';
		$r .= '<td width=80% class=rptl>Dreadnought</td>';
		$r .= '</tr>';
	}

	if ($row['FIRSD'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['FIRSD'] * conFIRSD * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['FIRSD'] . '</td>';
		$r .= '<td width=80% class=rptl>Fire Support Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['FRIGA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['FRIGA'] * conFRIGA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['FRIGA'] . '</td>';
		$r .= '<td width=80% class=rptl>Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['GOLBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['GOLBA'] * conGOLBA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['GOLBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Goliath" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['HAMGU'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HAMGU'] * conHAMGU * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HAMGU'] . '</td>';
		$r .= '<td width=80% class=rptl>"Hammer" Gunship</td>';
		$r .= '</tr>';
	}

	if ($row['HVYCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HVYCA'] * conHVYCA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HVYCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Heavy Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['HVYCR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HVYCR'] * conHVYCR * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HVYCR'] . '</td>';
		$r .= '<td width=80% class=rptl>Heavy Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['HURFC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['HURFC'] * conHURFC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['HURFC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Hurricane" Fast Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['IMPFR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['IMPFR'] * conIMPFR * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['IMPFR'] . '</td>';
		$r .= '<td width=80% class=rptl>Improved Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['INTFR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['INTFR'] * conINTFR * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['INTFR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Interdictor" Frigate</td>';
		$r .= '</tr>';
	}

	if ($row['JUDDR'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['JUDDR'] * conJUDDR * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['JUDDR'] . '</td>';
		$r .= '<td width=80% class=rptl>"Judicator" Dreadnought</td>';
		$r .= '</tr>';
	}

	if ($row['LEOSC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['LEOSC'] * conLEOSC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['LEOSC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Leopard" Strike Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['LIGCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['LIGCA'] * conLIGCA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['LIGCA'] . '</td>';
		$r .= '<td width=80% class=rptl>Light Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['ORCBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ORCBA'] * conORCBA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ORCBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Orca" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['PRIHC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['PRIHC'] * conPRIHC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['PRIHC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Privateer" Heavy Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['RAVMC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['RAVMC'] * conRAVMC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['RAVMC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Raven" Missile Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['TANDB'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['TANDB'] * conTANDB * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TANDB'] . '</td>';
		$r .= '<td width=80% class=rptl>"Tangler" Defense Barge</td>';
		$r .= '</tr>';
	}

	if ($row['TERCA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['TERCA'] * conTERCA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TERCA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Terrapin" Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['TORBA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['TORBA'] * conTORBA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['TORBA'] . '</td>';
		$r .= '<td width=80% class=rptl>"Tortoise" Battleship</td>';
		$r .= '</tr>';
	}

	if ($row['VESSC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['VESSC'] * conVESSC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['VESSC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Vespa" Siege Carrier</td>';
		$r .= '</tr>';
	}

	if ($row['WAYEC'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['WAYEC'] * conWAYEC * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['WAYEC'] . '</td>';
		$r .= '<td width=80% class=rptl>"Wayfarer" Exploration Cruiser</td>';
		$r .= '</tr>';
	}

	if ($row['ZEPFD'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ZEPFD'] * conZEPFD * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ZEPFD'] . '</td>';
		$r .= '<td width=80% class=rptl>"Zephyr" Fast Destroyer</td>';
		$r .= '</tr>';
	}

	if ($row['AEGMS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['AEGMS'] * conAEGMS * $row['DurabilityPerc']) . '</td>';
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
		$r .= '<td width=10% class=rptrdur>' . number_format($row['STIDR'] * conSTIDR * $row['DurabilityPerc']) . '</td>';
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
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BARR1'] * conBARR1 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARR1'] . '</td>';
		$r .= '<td width=80% class=rptl>Barracks (off +2%, def +2%)</td>';
		$r .= '</tr>';
	}

	if ($row['BARR2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['BARR2'] * conBARR2 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['BARR2'] . '</td>';
		$r .= '<td width=80% class=rptl>Barracks (Veteran) (off +3%, def +3%)</td>';
		$r .= '</tr>';
	}

	if ($row['DEFTU'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['DEFTU'] * conDEFTU * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['DEFTU'] . '</td>';
		$r .= '<td width=80% class=rptl>Defense Turret</td>';
		$r .= '</tr>';
	}

	if ($row['SDEF1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SDEF1'] * conSDEF1 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SDEF1'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Defense Battery</td>';
		$r .= '</tr>';
	}

	if ($row['SDEF2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SDEF2'] * conSDEF2 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SDEF2'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Defense Battery (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['SSLD1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SSLD1'] * conSSLD1 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SSLD1'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Shield Generator</td>';
		$r .= '</tr>';
	}

	if ($row['SSLD2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SSLD2'] * conSSLD2 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SSLD2'] . '</td>';
		$r .= '<td width=80% class=rptl>Surface Shield Generator (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['WARFA'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['WARFA'] * conWARFA * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['WARFA'] . '</td>';
		$r .= '<td width=80% class=rptl>War Factory (dur +5%, off +5%)</td>';
		$r .= '</tr>';
	}

	if ($row['WEATL'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['WEATL'] * conWEATL * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['WEATL'] . '</td>';
		$r .= '<td width=80% class=rptl>Weapons Technology Laboratory (con +3%, res +3%, off +3%, def +3%)</td>';
		$r .= '</tr>';
	}

	if ($row['PLATE'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['PLATE'] * conPLATE * $row['DurabilityPerc']) . '</td>';
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
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OMIN1'] * conOMIN1 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OMIN1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Minefield</td>';
		$r .= '</tr>';
	}

	if ($row['OMIN2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OMIN2'] * conOMIN2 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OMIN2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Minefield (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['OSLD1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OSLD1'] * conOSLD1 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OSLD1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Shield</td>';
		$r .= '</tr>';
	}

	if ($row['OSLD2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OSLD2'] * conOSLD2 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OSLD2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Shield (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['ODEF1'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ODEF1'] * conODEF1 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEF1'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Platform</td>';
		$r .= '</tr>';
	}

	if ($row['ODEF2'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ODEF2'] * conODEF2 * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEF2'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Platform (Improved)</td>';
		$r .= '</tr>';
	}

	if ($row['ODEFM'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['ODEFM'] * conODEFM * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['ODEFM'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Defense Monitor</td>';
		$r .= '</tr>';
	}

	if ($row['SBASE'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['SBASE'] * conSBASE * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['SBASE'] . '</td>';
		$r .= '<td width=80% class=rptl>Starbase</td>';
		$r .= '</tr>';
	}

	//Added orbital bulwarks
	if ($row['OBULK'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['OBULK'] * conOBULK * $row['DurabilityPerc']) . '</td>';
		$r .= '<td width=10% class=rptr>' . $row['OBULK'] . '</td>';
		$r .= '<td width=80% class=rptl>Orbital Bulwark</td>';
		$r .= '</tr>';
	}

	if ($row['AMIPS'] > 0)
	{
		$r .= '<tr>';
		$r .= '<td width=10% class=rptrdur>' . number_format($row['AMIPS'] * conAMIPS * $row['DurabilityPerc']) . '</td>';
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