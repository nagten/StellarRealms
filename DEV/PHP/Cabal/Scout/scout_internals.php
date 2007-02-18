<?php

include("../connect_to_database.php");
include("rank.php");
include("convariables.php");

if (isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
}
else
{
	$action = '';
}

if (isset($_REQUEST['report']))
{
	$report = $_REQUEST['report'];
}
else
{
	$report = '';
}

$dat = array();
initialize_dat();

$report = str_replace("\n"  ,'|',$report);
$report = str_replace("\n\r",'|',$report);
$report = str_replace('<br>','|',$report);
$report = str_replace('<BR>','|',$report);
$report = str_replace('||'  ,'|',$report);
$ray = explode('|',$report);
$reconnaitertype = '1'; //1 structure, 2 fleet

$dateFound    = false;
$fromFound    = false;
$targetFound  = false;
$structsFound = false;

$blnmatproc = false;
$blnimpmatproc = false;
$blnPlatingFactory = false;
$blnAdvancedTechShipyard = false;
$blnMatResearchComplex = false;
$blnWarFactory = false;
$blnBiologicalResearch = false;

$firstLine = $ray[0];
$pos = strpos($firstLine,'Date:');

//determine if we got a full report or not
if ($pos !== false)
{
	$method = 'Full Report';
}
else
{
	if (strtotime($firstLine) === -1)
	{
		$method = 'Full Report';
	}
	else
	{
		$method = 'Short Report';
	}
}

if ($method == 'Full Report')
{	
	$intraycount = count($ray);
	for ($intI = 0; $intI < $intraycount; $intI++)
	{
		$line = $ray[$intI];
		if ( ! $dateFound)
		{
			if (strpos($line,'Date:') !== false)
			{
				$dateFound = true;
				$timestamp = substr($line,6);
				parseDate($timestamp);
			}
		}
		elseif ( ! $fromFound)
		{
			if (strpos($line,'From:') !== false)
			{
				$fromFound = true;
				$from = substr($line,6);
				parseFrom($from);
			}
		}
		elseif ( ! $targetFound)
		{
			$pos = strpos($line,'reconnoiter structures at');
			
			if ($pos === false) //reconnaiter structures wasn't found
			{
				//Determine target from a fleet reconnaissance
				$pos = strpos($line,' fleet reconnaissance at'); //leave the extra space it's important
				
				if ($pos === false) //fleet reconnaissance wasn't found
				{
					//error
					$result = 'Reconnaissance report not recognized, something went wrong';
				}
				else
				{
					$reconnaitertype = '2'; //fleet
					$targetFound = true;
					$target = substr($line,$pos +25,-1);
					parseTarget($target);
				}			
			}
			else //reconnaiter structures was found
			{
				$reconnaitertype = '1'; //structure
				$targetFound = true;
				$target = substr($line,$pos +25,-1);
				parseTarget($target);
			}
		}
		elseif ( ! $structsFound)
		{
			$pos = strpos($line,'defending force consisted of');
			if ($pos !== false)
			{
				$structsFound = true;
				$structs = substr($line,$pos + 28, -1);
				parseStructs($structs);
			}
		}
	}
}
else
{
	if (count($ray) == 4)
	{
		//Old style short report (before the fleet/structure change)
		$timestamp	= $ray[0];
		$from	= $ray[1];
		$target	= $ray[2];
		$structs	= $ray[3];
		
		$reconnaitertype = '1'; //structure
	}
	else
	{
		//New style short report (before the fleet/structure change)
		$timestamp	= $ray[0];
		$from	= $ray[1];
		$recon	= $ray[2]; //reconnoiter structures, conduct fleet reconnaissance
		$target	= $ray[3];
		$structs	= $ray[4];
		
		//echo $timestamp . '<br>';
		//echo $from . '<br>';
		//echo $target . '<br>';
		//echo $recon . '<br>';
		//echo $structs . '<br>';
		
		//Determine structure or fleet recon
		if ($recon == 'reconnoiter structures')
		{
			$reconnaitertype = '1'; //structure
		}
		else
		{
			$reconnaitertype = '2'; //fleet
		}
	}
	parseDate($timestamp);
	parseFrom($from);
	parseTarget($target);
	parseStructs($structs);
}

if ($DEV)
{
	//echo "All data parsed <BR>";
}

$result = updateDatabase();
//$result = $result . "target = " . $target;

if ($DEV)
{
	echo $result;
}
else
{
	echo $result;
}

//================================================================================

function TurnAge($start_date, $end_date)
{
	//Calculate turns passed between two dates
	
	//Split a string by string
	$_d1 = explode("-", $start_date);
	$_d3 = explode(":", substr($start_date, -8));

	$m1 = $_d1[0];
	$d1 = $_d1[1];
	$y1 = $_d1[2];
	$hour1 = $_d3[0];
	$min1 = $_d3[1];
	$sec1 = $_d3[2];

	$_d2 = explode("-", $end_date);
	$_d4 = explode(":", substr($end_date, -8));

	$m2 = $_d2[0];
	$d2 = $_d2[1];
	$y2 = $_d2[2];
	$hour2 = $_d4[0];
	$min2 = $_d4[1];
	$sec2 = $_d4[2];

	if (($y1 < 1970 || $y1 > 2037) || ($y2 < 1970 || $y2 > 2037))
	{
		return 0;
	}
	else
	{
		$today_stamp = mktime($hour1,$min1,$sec1,$m1,$d1,$y1);
		$end_date_stamp = mktime($hour2,$min2,$sec2,$m2,$d2,$y2);

		$difference = round(($end_date_stamp-$today_stamp));
		$turns = floor($difference / 60 / 20);
		return $turns;
	}
}

function parseDate($line)
{
	global $dat;
	$ray = explode(' ',$line);
	$dat['date'] = $ray[0];

	if (array_key_exists(1,$ray))
	{
		$dat['time'] = $ray[1];
	}

	if (array_key_exists(2,$ray))
	{
		$dat['time'] .= ' ' . $ray[2];
	}
}

function parseFrom($line)
{
	global $dat;
	$ray = explode(' ',$line);
	$from = '';

	for ($intI = 0; $intI < count($ray); $intI++)
	{
		$from .= $ray[$intI] . ' ';
	}
	$dat['from'] = trim($from);
}

function parseTarget($line)
{
	global $dat;
	$dat['target'] = trim($line);
}

function parseStructs($line)
{
	$ray = explode('(s)',$line);

	//Iterate all structures
	$intraycount = count($ray);
	
	for ($intI = 0; $intI < $intraycount; $intI++)
	{
		$struct = $ray[$intI];
		$struct = trim($struct);
		
		if ($struct != '')
		{
			$pos = strpos($struct,' ');

			if ($pos !== false)
			{
				$qty  = substr($struct,0,$pos);
				$name = substr($struct,$pos+1);
				$name = stripslashes($name);
				$name = str_replace('"','',$name);
				$Initial = strtoupper(substr($name,0,1));

				switch ($Initial)
				{
					case 'A' : parse_A_structs($name,$qty); break;
					case 'B' : parse_B_structs($name,$qty); break;
					case 'C' : parse_C_structs($name,$qty); break;
					case 'D' : parse_D_structs($name,$qty); break;
					case 'E' : parse_E_structs($name,$qty); break;
					case 'F' : parse_F_structs($name,$qty); break;
					case 'G' : parse_G_structs($name,$qty); break;
					case 'H' : parse_H_structs($name,$qty); break;
					case 'I' : parse_I_structs($name,$qty); break;
					case 'J' : parse_J_structs($name,$qty); break;
					case 'K' : parse_K_structs($name,$qty); break;
					case 'L' : parse_L_structs($name,$qty); break;
					case 'M' : parse_M_structs($name,$qty); break;
					case 'N' : parse_N_structs($name,$qty); break;
					case 'O' : parse_O_structs($name,$qty); break;
					case 'P' : parse_P_structs($name,$qty); break;
					case 'Q' : parse_Q_structs($name,$qty); break;
					case 'R' : parse_R_structs($name,$qty); break;
					case 'S' : parse_S_structs($name,$qty); break;
					case 'T' : parse_T_structs($name,$qty); break;
					case 'U' : parse_U_structs($name,$qty); break;
					case 'V' : parse_V_structs($name,$qty); break;
					case 'W' : parse_W_structs($name,$qty); break;
					case 'X' : parse_X_structs($name,$qty); break;
					case 'Y' : parse_Y_structs($name,$qty); break;
					case 'Z' : parse_Z_structs($name,$qty); break;
				}
			}
		}
	}
}

function parse_A_structs($name,$qty)
{
	global $dat;
	global $blnAdvancedTechShipyard;

	switch ($name)
	{
		case 'Advanced Genetics Lab':
			$dat['ADVGE']       = $qty;
			$dat['Reproduction'] += $qty;
			$dat['BuildRating']   += ($qty * conADVGE);
			break;
		case 'Advanced Interceptor':
			$dat['ADVIN']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conADVIN);
			break;
		case 'Advanced Technologies Shipyard':
			$dat['ADVTS']	= $qty;
			$dat['Speed']	+= ($qty * conADVTSSPEED);
			$dat['Queues']	+= ($qty * 1);
			$dat['BuildRating']	+= ($qty * conADVTS);
			$blnAdvancedTechShipyard = true;
			break;
		case 'Aegis Mobile Shield':
			$dat['AEGMS']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conAEGMS);
			break;
		case 'Airbase':
			$dat['AIRB1']       = $qty;
			$dat['AirOps']     += $qty;
			$dat['AirCap']     += ($qty * conAIRB1CAP);
			$dat['BuildRating']   += ($qty * conAIRB1);
			break;
		case 'Airbase (Improved)':
			$dat['AIRB2']       = $qty;
			$dat['AirOps']     += $qty;
			$dat['AirCap']     += ($qty * conAIRB2CAP);
			$dat['BuildRating']   += ($qty * conAIRB2);
			break;
		case 'Anvil Battleship':
			$dat['ANVBS']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conANVBS);
			break;
		case 'Asp Heavy Cruiser':
			$dat['ASPHC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conASPHC);
			break;
		case 'Avalanche Siege Cruiser':
			$dat['AVASC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conAVASC);
			break;
	}
}

function parse_B_structs($name,$qty)
{
	global $dat;
	global $blnBiologicalResearch;

	switch ($name)
	{
		case 'Badger Light Cruiser':
			$dat['BADLC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conBADLC);
			break;
		case 'Barracks':
			$dat['BARR1']       = $qty;
			$dat['SurRating']   += ($qty * conBARR1);
			break;
		case 'Barracks (Veteran)':
			$dat['BARR2']       = $qty;
			$dat['SurRating']   += ($qty * conBARR2);
			break;
		case 'Barracuda Attack Frigate':
			$dat['BARAF']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conBARAF);
			break;
		case 'Battleship':
			$dat['BATSH']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conBATSH);
			break;
		case 'Berzerker Destroyer':
			$dat['BERDE']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conBERDE);
			break;
		case 'Biological Research Facility':
			$dat['BIOLO']        = $qty;
			$dat['Reproduction']  += $qty;
			$dat['BuildRating']   += ($qty * conBIOLO);
			$blnBiologicalResearch = true;
			break;
		case 'Black Widow Brood Minder':
			$dat['BLABM']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conBLABM);
			break;
		case 'Brood Center' || 'brood center':
			$dat['BROCE']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * conBROCECAP);
			$dat['BuildRating']   += ($qty * conBROCE);
			break;
	}
}

function parse_C_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Collector Frigate':
			$dat['COLFR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conCOLFR);
			break;
		case 'Colossus Megaship':
			$dat['COLOS']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conCOLOS);
			break;
		case 'Crusader Battlecruiser':
			$dat['CRUBC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conCRUBC);
			break;
		case 'Cruiser':
			$dat['CRUIS']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conCRUIS);
			break;
	}
}

function parse_D_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Dagger Heavy Fighter':
			$dat['DAGHF']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conDAGHF);
			break;
		case 'Deep Recon Scout':
			$dat['DEERS']       = $qty;
			$dat['Scouting']   += $qty;
			$dat['FleetRating']   += ($qty * conDEERS);
			break;
		case 'Defense Turret':
			$dat['DEFTU']       = $qty;
			$dat['SurDefense']    += ($qty * conDEFTU);
			$dat['SurRating']   += ($qty * conDEFTU);
			break;
		case 'Destroyer':
			$dat['DESTR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conDESTR);
			break;
		case 'Diplomatic Council':
			$dat['DIPCO']       = $qty;
			$dat['Diplomacy']  += $qty;
			$dat['BuildRating']   += ($qty * conDIPCO);
			break;
		case 'Dragon Mobile Assault Platform':
			$dat['DRAMA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conDRAMA);
			break;
		case 'Dreadnought':
			$dat['DREAD']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conDREAD);
			break;
	}
}

function parse_E_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Embassy':
			$dat['EMBAS']       = $qty;
			$dat['Diplomacy']  += $qty;
			$dat['BuildRating']   += ($qty * conEMBAS);
			break;
	}
}

function parse_F_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Fang Fighter Bomber':
			$dat['FANFB']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conFANFB);
			break;
		case 'Farm I':
			$dat['FARM1']       = $qty;
			$dat['Materials']  += ($qty * conFARM1PROD);
			$dat['BuildRating']   += ($qty * conFARM1);
			break;
		case 'Farm II':
			$dat['FARM2']       = $qty;
			$dat['Materials']  += ($qty * conFARM2PROD);
			$dat['BuildRating']   += ($qty * conFARM2);
			break;
		case 'Farm III':
			$dat['FARM3']       = $qty;
			$dat['Materials']  += ($qty * conFARM3PROD);
			$dat['BuildRating']   += ($qty * conFARM3);
			break;
		case 'Fighter Bomber':
			$dat['FIGBO']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conFIGBO);
			break;
		case 'Fighter Interceptor':
			$dat['FIGIN']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conFIGIN);
			break;
		case 'Fire Support Destroyer':
			$dat['FIRSD']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conFIRSD);
			break;
		case 'Frigate':
			$dat['FRIGA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conFRIGA);
			break;
	}
}

function parse_G_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Genetics Lab':
			$dat['GELAB']        = $qty;
			$dat['Reproduction']  += $qty;
			$dat['BuildRating']   += ($qty * conGELAB);
			break;
		case 'Goliath Battleship':
			$dat['GOLBA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conGOLBA);
			break;
		case 'Grand Hive':
			$dat['GNDHI']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * conGNDHICAP);
			$dat['BuildRating']   += ($qty * conGNDHI);
			break;
	}
}

function parse_H_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Habitat':
			$dat['HABI1']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * conHABI1CAP);
			$dat['BuildRating']   += ($qty * conHABI1);
			break;
		case 'Habitat (Improved)':
			$dat['HABI2']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * conHABI2CAP);
			$dat['BuildRating']   += ($qty * conHABI2);
			break;
		case 'Habitat (Ultradense)':
			$dat['HABI3']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * conHABI3CAP);
			$dat['BuildRating']   += ($qty * conHABI3);
			break;
		case 'Hammer Gunship':
			$dat['HAMGU']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conHAMGU);
			break;
		case 'Heavy Bomber':
			$dat['HVYBO']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conHVYBO);
			break;
		case 'Heavy Carrier':
			$dat['HVYCA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conHVYCA);
			break;
		case 'Heavy Cruiser':
			$dat['HVYCR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conHVYCR);
			break;
		case 'Hibernation Caves':
			$dat['HIBCA']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * conHIBCACAP);
			$dat['BuildRating']   += ($qty * conHIBCA);
			break;
		case 'Hospital':
			$dat['HOSPI']        = $qty;
			$dat['Reproduction']  += $qty;
			$dat['BuildRating']   += ($qty * conHOSPI);
			break;
		case 'Hurricane Fast Cruiser':
			$dat['HURFC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conHURFC);
			break;
	}
}

function parse_I_structs($name,$qty)
{
	global $dat;
	switch ($name) {
		case 'Improved Frigate':
			$dat['IMPFR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conIMPFR);
			break;
		case 'Institute of Higher Thought':
			$dat['INSHT']       = $qty;
			$dat['Research']   += $qty;
			$dat['HabSpace']   += ($qty * conINSHTCAP);
			$dat['BuildRating']   += ($qty * conINSHT);
			break;
		case 'Intelligence Agency':
			$dat['INTEL']       = $qty;
			$dat['IntelOps']   += $qty;
			$dat['HabSpace']   += ($qty * conINTELCAP);
			$dat['BuildRating']   += ($qty * conINTEL);
			break;
		case 'Interdictor Frigate':
			$dat['INTFR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conINTFR);
			break;
		case 'Interplanetary Marketplace':
			$dat['INTMP']       = $qty;
			$dat['Wealth']     += $qty;
			$dat['BuildRating']   += ($qty * conINTMP);
			break;
		case 'Interstellar Forum':
			$dat['INTFO']       = $qty;
			$dat['Reproduction'] += $qty;
			$dat['BuildRating']   += ($qty * conINTFO);
			break;
	}
}

function parse_J_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Judicator Dreadnought':
			$dat['JUDDR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conJUDDR);
			break;
		case 'Jumpgate':
			$dat['JUMP1']	= $qty;
			$dat['Speed']	+= ($qty * conJUMP1SPEED);
			$dat['BuildRating']	+= ($qty * conJUMP1);
			break;
		case 'Jumpgate (Improved)':
			$dat['JUMP2']	= $qty;
			$dat['Speed']	+= ($qty * conJUMP2SPEED);
			$dat['BuildRating']	+= ($qty * conJUMP2);
			break;
	}
}

function parse_K_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
	}
}

function parse_L_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Leopard Strike Cruiser':
			$dat['LEOSC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conLEOSC);
			break;
		case 'Light Carrier':
			$dat['LIGCA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conLIGCA);
			break;
		case 'Listening Post':
			$dat['LISTN']       = $qty;
			$dat['Sensors']    += $qty;
			$dat['BuildRating']   += ($qty * conLISTN);
			break;
	}
}

function parse_M_structs($name,$qty)
{
	global $dat;
	global $blnmatproc;
	global $blnimpmatproc;
	global $blnMatResearchComplex;

	switch ($name)
	{
		case 'Manufacturing Plant':
			$dat['MANU1']       = $qty;
			$dat['Queues']     += ($qty * 1);
			$dat['BuildRating']   += ($qty * conMANU1);
			break;
		case 'Manufacturing Plant (Improved)':
			$dat['MANU2']       = $qty;
			$dat['Queues']     += ($qty * 2);
			$dat['BuildRating']   += ($qty * conMANU2);
			break;
		case 'Materials Processing Plant':
			$blnmatproc = true;
			$dat['MATS1']       = $qty;
			$dat['BuildRating']   += ($qty * conMATS1);
			break;
		case 'Materials Processing Plant (Improved)':
		  	$blnimpmatproc = true;
			$dat['MATS2']       = $qty;
			$dat['BuildRating']   += ($qty * conMATS2);
			break;
		case 'Materials Research Complex':
			$dat['MATRC']       = $qty;
			$dat['Queues']     += ($qty * 1);
			$dat['BuildRating']   += ($qty * conMATRC);
			$blnMatResearchComplex = true;
			break;
		case 'Mining Facility (Metals)':
			$dat['MINE1']       = $qty;
			$dat['Materials']  += ($qty * conMINE1PROD);
			$dat['BuildRating']   += ($qty * conMINE1);
			break;
		case 'Mining Facility (Metals - Improved)':
			$dat['MINE2']       = $qty;
			$dat['Materials']  += ($qty * conMINE2PROD);
			$dat['BuildRating']   += ($qty * conMINE2);
			break;
		case 'Mining Facility (Radioactives)':
			$dat['RADI1']       = $qty;
			$dat['Materials']  += ($qty * conRAD1PROD);
			$dat['BuildRating']   += ($qty * conRADI1);
			break;
		case 'Mining Facility (Radioactives - Improved)':
			$dat['RADI2']       = $qty;
			$dat['Materials']  += ($qty * conRAD2PROD);
			$dat['BuildRating']   += ($qty * conRADI2);
			break;
	}
}

function parse_N_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
	}
}

function parse_O_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Orbital Bulwark':
			$dat['OBULK']        = $qty;
			$dat['OrbRating']   += ($qty * conOBULK);
			break;
		case 'Orbital Construction Yard':
			$dat['OCON1']        = $qty;
			$dat['Queues']      += ($qty * 1);
			$dat['BuildRating']   += ($qty * conOCON1);
			break;
		case 'Orbital Construction Yard (Improved)':
			$dat['OCON2']        = $qty;
			$dat['Queues']      += ($qty * 2);
			$dat['BuildRating']   += ($qty * conOCON2);
			break;
		case 'Orbital Defense Monitor':
			$dat['ODEFM']       = $qty;
			$dat['OrbRating']   += ($qty * conODEFM);
			break;
		case 'Orbital Defense Platform':
			$dat['ODEF1']       = $qty;
			$dat['OrbRating']   += ($qty * conODEF1);
			break;
		case 'Orbital Defense Platform (Improved)':
			$dat['ODEF2']       = $qty;
			$dat['OrbRating']   += ($qty * conODEF2);
			break;
		case 'Orbital Minefield':
			$dat['OMIN1']       = $qty;
			$dat['OrbRating']   += ($qty * conOMIN1);
			break;
		case 'Orbital Minefield (Improved)':
			$dat['OMIN2']       = $qty;
			$dat['OrbRating']   += ($qty * conOMIN2);
			break;
		case 'Orbital Shield':
			$dat['OSLD1']       = $qty;
			$dat['OrbRating']   += ($qty * conOSLD1);
			break;
		case 'Orbital Shield (Improved)':
			$dat['OSLD2']       = $qty;
			$dat['OrbRating']   += ($qty * conOSLD2);
			break;
		case 'Orca Battleship':
			$dat['ORCBA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conORCBA);
			break;
	}
}

function parse_P_structs($name,$qty)
{
	global $dat;
	global $blnPlatingFactory;

	switch ($name)
	{
		case 'Planetary Bank':
			$dat['PBANK']       = $qty;
			$dat['Wealth']     += $qty;
			$dat['BuildRating']   += ($qty * conPBANK);
			break;
		case 'Plating Factory':
			$dat['PLATE']       = $qty;
			$dat['Special']    += $qty;
			$dat['SurRating']   += ($qty * conPLATE);
			$blnPlatingFactory = true;
			break;
		case 'Privateer Heavy Cruiser':
			$dat['PRIHC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conPRIHC);
			break;
		case 'planetary shield' || 'Planetary Shield':
			$dat['AMIPS']       = $qty;
			$dat['OrbRating']   += ($qty * conAMIPS);
			break;
	}
}

function parse_Q_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
	}
}

function parse_R_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Raven Missile Cruiser':
			$dat['RAVMC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conRAVMC);
			break;
		case 'Refinery (Fuel)':
			$dat['FUEL1']       = $qty;
			$dat['Materials']  += ($qty * conFUEL1PROD);
			$dat['BuildRating']   += ($qty * conFUEL1);
			break;
		case 'Refinery (Fuel - Improved)':
			$dat['FUEL2']       = $qty;
			$dat['Materials']  += ($qty * conFUEL2PROD);
			$dat['BuildRating']   += ($qty * conFUEL2);
			break;
		case 'Remote Sensor Array':
			$dat['RSENS']       = $qty;
			$dat['Sensors']    += $qty;
			$dat['BuildRating']   += ($qty * conRSENS);
			break;
		case 'Research Lab':
			$dat['RLAB1']       = $qty;
			$dat['Research']   += $qty;
			$dat['BuildRating']   += ($qty * conRLAB1);
			break;
		case 'Research Lab (Improved)':
			$dat['RLAB2']       = $qty;
			$dat['Research']   += $qty;
			$dat['BuildRating']   += ($qty * conRLAB2);
			break;
	}
}

function parse_S_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Satellites':
			$dat['SATE1']        = $qty;
			$dat['Sensors']     += $qty;
			$dat['BuildRating']   += ($qty * conSATE1);
			break;
		case 'Satellites (Improved)':
			$dat['SATE2']        = $qty;
			$dat['Sensors']     += $qty;
			$dat['BuildRating']   += ($qty * conSATE2);
			break;
		case 'Scout':
			$dat['SCOUT']        = $qty;
			$dat['Scouting']    += $qty;
			$dat['FleetRating']   += ($qty * conSCOUT);
			break;
		case 'Space Folder':
			$dat['FOLDR']	= $qty;
			$dat['Speed']	+= ($qty * conFOLDRSPEED);
			$dat['BuildRating']	+= ($qty * conFOLDR);
			break;
		case 'Starbase':
			$dat['SBASE']       = $qty;
			$dat['HabSpace']   += ($qty * conSBASECAP);
			$dat['Queues']     += ($qty * 2);
			$dat['OrbRating']   += ($qty * conSBASE);
			$dat['AirCap']     += ($qty * conSBASEAIRBCAP);
			break;
		case 'Stinger Drone':
			$dat['STIDR']       = $qty;
			$dat['OrbRating']   += ($qty * conSTIDR);
			break;
		case 'Stock Exchange':
			$dat['STOCK']       = $qty;
			$dat['Wealth']     += $qty;
			$dat['BuildRating']   += ($qty * conSTOCK);
			break;
		case 'Surface Defense Battery':
			$dat['SDEF1']       = $qty;
			$dat['SurRating']   += ($qty * conSDEF1);
			break;
		case 'Surface Defense Battery (Improved)':
			$dat['SDEF2']       = $qty;
			$dat['SurRating']   += ($qty * conSDEF2);
			break;
		case 'Surface Shield Generator':
			$dat['SSLD1']       = $qty;
			$dat['SurRating']   += ($qty * conSSLD1);
			break;
		case 'Surface Shield Generator (Improved)':
			$dat['SSLD2']       = $qty;
			$dat['SurRating']   += ($qty * conSSLD2);
			break;
	}
}

function parse_T_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Tangler Defense Barge':
			$dat['TANDB']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conTANDB);
			break;
		case 'Terrapin Carrier':
			$dat['TERCA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conTERCA);
			break;
		case 'Tortoise Battleship':
			$dat['TORBA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conTORBA);
			break;
		case 'Tracking Station':
			$dat['TRACK']       = $qty;
			$dat['Sensors']    += $qty;
			$dat['BuildRating']   += ($qty * conTRACK);
			break;
		case 'Trade School':
			$dat['TSCHL']       = $qty;
			$dat['Training']   += $qty;
			$dat['BuildRating']   += ($qty * conTSCHL);
			break;
	}
}

function parse_U_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'University':
			$dat['UNIVE']       = $qty;
			$dat['Training']   += $qty;
			$dat['HabSpace']   += ($qty * conUNIVECAP);
			$dat['BuildRating']   += ($qty * conUNIVE);
			break;
	}
}

function parse_V_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Venom Heavy Fighter':
			$dat['VENHF']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conVENHF);
			break;
		case 'Vespa Siege Carrier':
			$dat['VESSC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conVESSC);
			break;
		case 'Vinemind':
			$dat['VINEM']       = $qty;
			$dat['Sensors']    += $qty;
			$dat['HabSpace']   += ($qty * conVINEMCAP);
			$dat['BuildRating']   += ($qty * conVINEM);
			break;
	}
}

function parse_W_structs($name,$qty)
{
	global $dat;
	global $blnWarFactory;

	switch ($name)
	{
		case 'War Factory':
			$dat['WARFA']       = $qty;
			$dat['Special']    += $qty;
			$dat['SurRating']   += ($qty *conWARFA);
			$blnWarFactory = true;
			break;
		case 'Warehouse (Small)':
			$dat['WHSE1']       = $qty;
			$dat['Warehouse']  += ($qty * conWHSE1CAP);
			$dat['BuildRating']   += ($qty * conWHSE1);
			break;
		case 'Warehouse (Medium)':
			$dat['WHSE2']       = $qty;
			$dat['Warehouse']  += ($qty * conWHSE2CAP);
			$dat['BuildRating']   += ($qty * conWHSE2);
			break;
		case 'Warehouse (Large)':
			$dat['WHSE3']       = $qty;
			$dat['Warehouse']  += ($qty * conWHSE3CAP);
			$dat['BuildRating']   += ($qty * conWHSE3);
			break;
		case 'Wasp Fighter':
			$dat['WASFI']       = $qty;
			$dat['Fighter']    += $qty;
			$dat['FleetRating']   += ($qty * conWASFI);
			break;
		case 'Wayfarer Exploration Cruiser':
			$dat['WAYEC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conWAYEC);
			break;
		case 'Weapons Technology Laboratory':
			$dat['WEATL']       = $qty;
			$dat['Special']    += $qty;
			$dat['SurRating']   += ($qty * conWEATL);
			break;
	}
}

function parse_X_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
	}
}

function parse_Y_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
	}
}

function parse_Z_structs($name,$qty)
{
	global $dat;
	switch ($name)
	{
		case 'Zephyr Fast Destroyer':
			$dat['ZEPFD']       = $qty;
			$dat['Capital']    += $qty;
			$dat['FleetRating']   += ($qty * conZEPFD);
			break;
	}
}


function initialize_dat()
{
	global $dat;
	$dat['date']	= '';
	$dat['time']	= '';
	$dat['from']	= '';
	$dat['target']	= '';
	$dat['AirOps']	= 0;
	$dat['AirCap']	= 0;
	$dat['Capital']	= 0;
	$dat['SurRating']   = 0;
	$dat['OrbRating']   = 0;
	$dat['FleetRating']   = 0;
	$dat['BuildRating']   = 0;
	$dat['Diplomacy']	= 0;
	$dat['Fighter']	= 0;
	$dat['Habitat']	= 0;
	$dat['HabSpace']	= 1250000;
	$dat['IntelOps']	= 0;
	$dat['Materials']	= 0;
	$dat['Reproduction']	= 0;
	$dat['Queues']	= 1;
	$dat['Research']	= 0;
	$dat['Scouting']	= 0;
	$dat['Sensors']	= 0;
	$dat['Warehouse']	= 0;
	$dat['Special']	= 0;
	$dat['Speed']	= 0;
	$dat['Training']	= 0;
	$dat['Wealth']	= 0;

	$dat['ADVIN']  = '';
	$dat['ADVGE']  = '';
	$dat['ADVTS']  = '';
	$dat['AEGMS']  = '';
	$dat['AIRB1']  = '';
	$dat['AIRB2']  = '';
	$dat['AMIPS']  = '';
	$dat['ANVBS']  = '';
	$dat['ASPHC']  = '';
	$dat['AVASC']  = '';
	$dat['BADLC']  = '';
	$dat['BARAF']  = '';
	$dat['BARR1']  = '';
	$dat['BARR2']  = '';
	$dat['BATSH']  = '';
	$dat['BERDE']  = '';
	$dat['BIOLO']  = '';
	$dat['BLABM']  = '';
	$dat['BROCE']  = '';
	$dat['COLFR']  = '';
	$dat['COLOS']  = '';
	$dat['CRUBC']  = '';
	$dat['CRUIS']  = '';
	$dat['DAGHF']  = '';
	$dat['DEERS']  = '';
	$dat['DEFTU']  = '';
	$dat['DESTR']  = '';
	$dat['DIPCO']  = '';
	$dat['DRAMA']  = '';
	$dat['DREAD']  = '';
	$dat['EMBAS']  = '';
	$dat['FANFB']  = '';
	$dat['FARM1']  = '';
	$dat['FARM2']  = '';
	$dat['FARM3']  = '';
	$dat['FIGBO']  = '';
	$dat['FIGIN']  = '';
	$dat['FIRSD']  = '';
	$dat['FOLDR']  = '';
	$dat['FRIGA']  = '';
	$dat['FUEL1']  = '';
	$dat['FUEL2']  = '';
	$dat['GELAB']  = '';
	$dat['GNDHI']  = '';
	$dat['GOLBA']  = '';
	$dat['HABI1']  = '';
	$dat['HABI2']  = '';
	$dat['HABI3']  = '';
	$dat['HAMGU']  = '';
	$dat['HVYBO']  = '';
	$dat['HVYCA']  = '';
	$dat['HVYCR']  = '';
	$dat['HIBCA']  = '';
	$dat['HOSPI']  = '';
	$dat['HURFC']  = '';
	$dat['IMPFR']  = '';
	$dat['INSHT']  = '';
	$dat['INTEL']  = '';
	$dat['INTFR']  = '';
	$dat['INTMP']  = '';
	$dat['INTFO']  = '';
	$dat['JUDDR']  = '';
	$dat['JUMP1']  = '';
	$dat['JUMP2']  = '';
	$dat['LEOSC']  = '';
	$dat['LIGCA']  = '';
	$dat['LISTN']  = '';
	$dat['MANU1']  = '';
	$dat['MANU2']  = '';
	$dat['MATS1']  = '';
	$dat['MATS2']  = '';
	$dat['MATRC']  = '';
	$dat['MINE1']  = '';
	$dat['MINE2']  = '';
	$dat['RADI1']  = '';
	$dat['RADI2']  = '';
	$dat['OBULK']  = '';
	$dat['OCON1']  = '';
	$dat['OCON2']  = '';
	$dat['ODEFM']  = '';
	$dat['ODEF1']  = '';
	$dat['ODEF2']  = '';
	$dat['OMIN1']  = '';
	$dat['OMIN2']  = '';
	$dat['ORCBA']  = '';
	$dat['OSLD1']  = '';
	$dat['OSLD2']  = '';
	$dat['PBANK']  = '';
	$dat['PLATE']  = '';
	$dat['PRIHC']  = '';
	$dat['RAVMC']  = '';
	$dat['RSENS']  = '';
	$dat['RLAB1']  = '';
	$dat['RLAB2']  = '';
	$dat['SATE1']  = '';
	$dat['SATE2']  = '';
	$dat['SCOUT']  = '';
	$dat['SBASE']  = '';
	$dat['STIDR']  = '';
	$dat['STOCK']  = '';
	$dat['SDEF1']  = '';
	$dat['SDEF2']  = '';
	$dat['SSLD1']  = '';
	$dat['SSLD2']  = '';
	$dat['TANDB']  = '';
	$dat['TERCA']  = '';
	$dat['TORBA']  = '';
	$dat['TRACK']  = '';
	$dat['TSCHL']  = '';
	$dat['UNIVE']  = '';
	$dat['VENHF']  = '';
	$dat['VESSC']  = '';
	$dat['VINEM']  = '';
	$dat['WARFA']  = '';
	$dat['WASFI']  = '';
	$dat['WAYEC']  = '';
	$dat['WHSE1']  = '';
	$dat['WHSE2']  = '';
	$dat['WHSE3']  = '';
	$dat['WEATL']  = '';
	$dat['ZEPFD']  = '';
}

function updateDatabase()
{
	//update the database
	global $dat;
	global $matproc;
	global $impmatproc;
	global $DEV;
	global $blnPlatingFactory;
	global $blnAdvancedTechShipyard;
	global $blnMatResearchComplex;
	global $blnWarFactory;
	global $blnBiologicalResearch;
	global $reconnaitertype;

	//default durability
	$durability = '1.0';

	$targetName = $dat['target'];
	$sourceName = $dat['from'];
	$reportDate = $dat['date'];
	$reportTime = $dat['time'];

	$ok  = true;
	$err = '';

	if ($targetName == '')
	{
		$ok   = false;
		$err .= 'Target planet missing. ';
	}

	if ($sourceName == '')
	{
		$ok   = false;
		$err .= 'Source planet missing. ';
	}

	if ($reportDate == '')
	{
		$ok   = false;
		$err .= 'Report date missing. ';
	}

	if ($reportTime == '')
	{
		$ok = false;
		$err .= 'Report time missing. ';
	}

	if ($ok)
	{
		$targetName = trim($targetName);
		$sourceName = trim($sourceName);
		$reportDate = date('Y-m-d',strtotime($reportDate));
		$reportTime = date('H:i:s',strtotime($reportTime));
	}


	if ($ok)
	{
		//get target planet id and rank
		//first we check if rank is up to date with the help of the date column
		$SQL = 'Select max(date), date, TurnCount FROM tblplanet GROUP BY date';
		$result = mysql_query($SQL);

		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}
		else
		{
			if (mysql_num_rows($result) == 0)
			{
				//No planets in planet table so updaterank() this will also add all planets the first time
				// or update the planettable with new planets
				UpdateRank(0);
			}

			if (mysql_num_rows($result) > 0)
			{
				//12-20-2006 23:02:03
				$current_date = date("m-d-Y H:i:s");
				$row = mysql_fetch_assoc($result);

				$Age = TurnAge($row['date'], $current_date);
				//echo "rowdate = " . $row['date'];
				//echo "current_date = " . $current_date;

				//echo "Age = " . $Age;
				//echo "turns = " . $row['TurnCount'];

				if ($Age > 1)
				{
					//Rank isn't up to date so update it
					UpdateRank($row['TurnCount']);
				}
			}
		}

		$SQL = 'Select RecordNumber,Rank,SID1,TurnCount, Species FROM tblplanet WHERE PlanetName = \'' . $targetName . '\'';
		$result = mysql_query($SQL);
		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}

		if (mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);

			//get planetID species and rank from tblplanet
			$planetID = $row['RecordNumber'];
			$species  = $row['Species'];
			$rank     = $row['Rank'];
			$sid1     = $row['SID1'];
		}
		else
		{
			$ok   = false;
			$err .= 'target planet [' . $targetName . '] not found in database. ';
		}
	}

	if ($ok)
	{
		// get source planet id
		$SQL = 'Select RecordNumber FROM tblplanet WHERE PlanetName = \'' . $sourceName . '\'';
		$result = mysql_query($SQL);
		if (!$result) die('Invalid query: ' . mysql_error());
		if (mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			$sourceID = $row['RecordNumber'];
		}
		else
		{
			$ok   = false;
			$err .= 'source planet [' . $sourceName . '] not found in database. ';
			//echo 'source planet [' . $sourceName . '] not found in database. ';
		}
	}
	
	if ($ok)
	{
		// see if scouting report has already been entered
		$SQL  = 'SELECT RecordNumber ';
		$SQL .= 'FROM tblscout ';
		$SQL .= 'WHERE PlanetID = \'' . $planetID   . '\' ';
		$SQL .= 'AND ReportDate = \'' . $reportDate . '\' ';
		$SQL .= 'AND ReportTime = \'' . $reportTime . '\' ';
		$SQL .= 'AND Reconnaitertype = \'' .$reconnaitertype . '\' ';
		$result = mysql_query($SQL);

		if (!$result)
		{
			echo "Invalid query: " . mysql_error();
		}

		if (mysql_num_rows($result) > 0)
		{
			// do nothing, record already exists
			if ($DEV)
			{
				//echo "Scouting report has already been added<BR>";
				mysql_free_result($result);
			}
		}
		else
		{
			//Calculate durability take into account durability bonusses
			if ($blnPlatingFactory || $blnMatResearchComplex || $blnAdvancedTechShipyard || $blnWarFactory || $blnBiologicalResearch)
			{
				if ($blnMatResearchComplex)
				{
					//durability +10%
					$durability = '1.10';
				}
				else if ($blnPlatingFactory)
				{
					//durability +7%
					$durability = '1.07';
				}
				else if ($blnWarFactory || $blnAdvancedTechShipyard)
				{
					//durability +5%
					$durability = '1.05';
				}
				else if ($blnBiologicalResearch)
				{
					//durability +3%
					$durability = '1.03';
				}

				$dat['ADVIN']  = $dat['ADVIN'];
				$dat['ADVGE']  = $dat['ADVGE'];
				$dat['ADVTS']  = $dat['ADVTS'];
				$dat['AEGMS']  = $dat['AEGMS'];
				$dat['AIRB1']  = $dat['AIRB1'];
				$dat['AIRB2']  = $dat['AIRB2'];
				$dat['AMIPS']  = $dat['AMIPS'];
				$dat['ANVBS']  = $dat['ANVBS'];
				$dat['ASPHC']  = $dat['ASPHC'];
				$dat['AVASC']  = $dat['AVASC'];
				$dat['BADLC']  = $dat['BADLC'];
				$dat['BARAF']  = $dat['BARAF'];
				$dat['BARR1']  = $dat['BARR1'];
				$dat['BARR2']  = $dat['BARR2'];
				$dat['BATSH']  = $dat['BATSH'];
				$dat['BERDE']  = $dat['BERDE'];
				$dat['BIOLO']  = $dat['BIOLO'];
				$dat['BLABM']  = $dat['BLABM'];
				$dat['BROCE']  = $dat['BROCE'];
				$dat['COLFR']  = $dat['COLFR'];
				$dat['COLOS']  = $dat['COLOS'];
				$dat['CRUBC']  = $dat['CRUBC'];
				$dat['CRUIS']  = $dat['CRUIS'];
				$dat['DAGHF']  = $dat['DAGHF'];
				$dat['DEERS']  = $dat['DEERS'];
				$dat['DEFTU']  = $dat['DEFTU'];
				$dat['DESTR']  = $dat['DESTR'];
				$dat['DIPCO']  = $dat['DIPCO'];
				$dat['DRAMA']  = $dat['DRAMA'];
				$dat['DREAD']  = $dat['DREAD'];
				$dat['EMBAS']  = $dat['EMBAS'];
				$dat['FANFB']  = $dat['FANFB'];
				$dat['FARM1']  = $dat['FARM1'];
				$dat['FARM2']  = $dat['FARM2'];
				$dat['FARM3']  = $dat['FARM3'];
				$dat['FIGBO']  = $dat['FIGBO'];
				$dat['FIGIN']  = $dat['FIGIN'];
				$dat['FIRSD']  = $dat['FIRSD'];
				$dat['FOLDR']  = $dat['FOLDR'];
				$dat['FRIGA']  = $dat['FRIGA'];
				$dat['FUEL1']  = $dat['FUEL1'];
				$dat['FUEL2']  = $dat['FUEL2'];
				$dat['GELAB']  = $dat['GELAB'];
				$dat['GNDHI']  = $dat['GNDHI'];
				$dat['GOLBA']  = $dat['GOLBA'];
				$dat['HABI1']  = $dat['HABI1'];
				$dat['HABI2']  = $dat['HABI2'];
				$dat['HABI3']  = $dat['HABI3'];
				$dat['HAMGU']  = $dat['HAMGU'];
				$dat['HVYBO']  = $dat['HVYBO'];
				$dat['HVYCA']  = $dat['HVYCA'];
				$dat['HVYCR']  = $dat['HVYCR'];
				$dat['HIBCA']  = $dat['HIBCA'];
				$dat['HOSPI']  = $dat['HOSPI'];
				$dat['HURFC']  = $dat['HURFC'];
				$dat['IMPFR']  = $dat['IMPFR'];
				$dat['INSHT']  = $dat['INSHT'];
				$dat['INTEL']  = $dat['INTEL'];
				$dat['INTFR']  = $dat['INTFR'];
				$dat['INTMP']  = $dat['INTMP'];
				$dat['INTFO']  = $dat['INTFO'];
				$dat['JUDDR']  = $dat['JUDDR'];
				$dat['JUMP1']  = $dat['JUMP1'];
				$dat['JUMP2']  = $dat['JUMP2'];
				$dat['LEOSC']  = $dat['LEOSC'];
				$dat['LIGCA']  = $dat['LIGCA'];
				$dat['LISTN']  = $dat['LISTN'];
				$dat['MANU1']  = $dat['MANU1'];
				$dat['MANU2']  = $dat['MANU2'];
				$dat['MATS1']  = $dat['MATS1'];
				$dat['MATS2']  = $dat['MATS2'];
				$dat['MATRC']  = $dat['MATRC'];
				$dat['MINE1']  = $dat['MINE1'];
				$dat['MINE2']  = $dat['MINE2'];
				$dat['RADI1']  = $dat['RADI1'];
				$dat['RADI2']  = $dat['RADI2'];
				$dat['OBULK']  = $dat['OBULK'];
				$dat['OCON1']  = $dat['OCON1'];
				$dat['OCON2']  = $dat['OCON2'];
				$dat['ODEFM']  = $dat['ODEFM'];
				$dat['ODEF1']  = $dat['ODEF1'];
				$dat['ODEF2']  = $dat['ODEF2'];
				$dat['OMIN1']  = $dat['OMIN1'];
				$dat['OMIN2']  = $dat['OMIN2'];
				$dat['ORCBA']  = $dat['ORCBA'];
				$dat['OSLD1']  = $dat['OSLD1'];
				$dat['OSLD2']  = $dat['OSLD2'];
				$dat['PBANK']  = $dat['PBANK'];
				$dat['PLATE']  = $dat['PLATE'];
				$dat['PRIHC']  = $dat['PRIHC'];
				$dat['RAVMC']  = $dat['RAVMC'];
				$dat['RSENS']  = $dat['RSENS'];
				$dat['RLAB1']  = $dat['RLAB1'];
				$dat['RLAB2']  = $dat['RLAB2'];
				$dat['SATE1']  = $dat['SATE1'];
				$dat['SATE2']  = $dat['SATE2'];
				$dat['SCOUT']  = $dat['SCOUT'];
				$dat['SBASE']  = $dat['SBASE'];
				$dat['STIDR']  = $dat['STIDR'];
				$dat['STOCK']  = $dat['STOCK'];
				$dat['SDEF1']  = $dat['SDEF1'];
				$dat['SDEF2']  = $dat['SDEF2'];
				$dat['SSLD1']  = $dat['SSLD1'];
				$dat['SSLD2']  = $dat['SSLD2'];
				$dat['TANDB']  = $dat['TANDB'];
				$dat['TERCA']  = $dat['TERCA'];
				$dat['TORBA']  = $dat['TORBA'];
				$dat['TRACK']  = $dat['TRACK'];
				$dat['TSCHL']  = $dat['TSCHL'];
				$dat['UNIVE']  = $dat['UNIVE'];
				$dat['VENHF']  = $dat['VENHF'];
				$dat['VESSC']  = $dat['VESSC'];
				$dat['VINEM']  = $dat['VINEM'];
				$dat['WARFA']  = $dat['WARFA'];
				$dat['WASFI']  = $dat['WASFI'];
				$dat['WAYEC']  = $dat['WAYEC'];
				$dat['WHSE1']  = $dat['WHSE1'];
				$dat['WHSE2']  = $dat['WHSE2'];
				$dat['WHSE3']  = $dat['WHSE3'];
				$dat['WEATL']  = $dat['WEATL'];
				$dat['ZEPFD']  = $dat['ZEPFD'];
				$dat['SurRating'] = $dat['SurRating'] * $durability;
				$dat['OrbRating'] = $dat['OrbRating'] * $durability;
				$dat['FleetRating'] = $dat['FleetRating'] * $durability;
				$dat['BuildRating'] = $dat['BuildRating'] * $durability;
			}

			//Calculate materials take into account material bonusses
			if ($matproc)
			{
				$dat['Materials'] = $dat['Materials'] * 1.05;
			}

			if ($impmatproc)
			{
				$dat['Materials'] = $dat['Materials'] * 1.07;
			}

			//Insert the new scouting report
			$SQL  = 'INSERT INTO tblscout (PlanetID,PlanetName,SourceID,SourceName,ReportDate,ReportTime,';
			$SQL .= 'ADVIN,ADVGE,ADVTS,AEGMS,AIRB1,AIRB2,ANVBS,ASPHC,AVASC,BADLC,';
			$SQL .= 'BARAF,BARR1,BARR2,BATSH,BERDE,BIOLO,BLABM,COLFR,COLOS,CRUBC,CRUIS,';
			$SQL .= 'DAGHF,DEERS,DEFTU,DESTR,DIPCO,DRAMA,DREAD,EMBAS,FANFB,FARM1,';
			$SQL .= 'FARM2,FARM3,FIGBO,FIGIN,FIRSD,FRIGA,GELAB,GNDHI,GOLBA,HABI1,';
			$SQL .= 'HABI2,HABI3,HAMGU,HVYBO,HVYCA,HVYCR,HIBCA,HOSPI,HURFC,IMPFR,';
			$SQL .= 'INSHT,INTEL,INTFR,INTMP,INTFO,JUDDR,JUMP1,JUMP2,LEOSC,LIGCA,';
			$SQL .= 'LISTN,MANU1,MANU2,MATS1,MATS2,MATRC,MINE1,MINE2,RADI1,RADI2,';
			$SQL .= 'OBULK,OCON1,OCON2,ODEFM,ODEF1,ODEF2,OMIN1,OMIN2,ORCBA,OSLD1,';
			$SQL .= 'OSLD2,PBANK,PLATE,PRIHC,FUEL1,FUEL2,RAVMC,RSENS,RLAB1,RLAB2,';
			$SQL .= 'SATE1,SATE2,SCOUT,FOLDR,SBASE,STIDR,STOCK,SDEF1,SDEF2,SSLD1,';
			$SQL .= 'SSLD2,TANDB,TERCA,TORBA,TRACK,TSCHL,UNIVE,VENHF,VESSC,VINEM,';
			$SQL .= 'WARFA,WASFI,WAYEC,WHSE1,WHSE2,WHSE3,WEATL,ZEPFD,BROCE,AMIPS,';
			$SQL .= 'AirOps,Capital,Diplomacy,Fighter,Habitat,IntelOps,';
			$SQL .= 'Materials,Reproduction,Queues,Research,Scouting,Sensors,Warehouse,';
			$SQL .= 'Special,Speed,Training,Wealth,Rank,AirCap,HabSpace,Current,Species,';
			$SQL .= 'FleetRating,OrbRating,SurRating,BuildRating,Reconnaitertype,DurabilityPerc';
			$SQL .= ') VALUES (';
			$SQL .= '\'' . $planetID	. '\',';
			$SQL .= '\'' . $dat['target']	. '\',';
			$SQL .= '\'' . $sourceID	. '\',';
			$SQL .= '\'' . $dat['from']	. '\',';
			$SQL .= '\'' . $reportDate	. '\',';
			$SQL .= '\'' . $reportTime	. '\',';
			$SQL .= '\'' . $dat['ADVIN']	. '\',';
			$SQL .= '\'' . $dat['ADVGE']	. '\',';
			$SQL .= '\'' . $dat['ADVTS']	. '\',';
			$SQL .= '\'' . $dat['AEGMS']	. '\',';
			$SQL .= '\'' . $dat['AIRB1']	. '\',';
			$SQL .= '\'' . $dat['AIRB2']	. '\',';
			$SQL .= '\'' . $dat['ANVBS']	. '\',';
			$SQL .= '\'' . $dat['ASPHC']	. '\',';
			$SQL .= '\'' . $dat['AVASC']	. '\',';
			$SQL .= '\'' . $dat['BADLC']	. '\',';
			$SQL .= '\'' . $dat['BARAF']	. '\',';
			$SQL .= '\'' . $dat['BARR1']	. '\',';
			$SQL .= '\'' . $dat['BARR2']	. '\',';
			$SQL .= '\'' . $dat['BATSH']	. '\',';
			$SQL .= '\'' . $dat['BERDE']	. '\',';
			$SQL .= '\'' . $dat['BIOLO']	. '\',';
			$SQL .= '\'' . $dat['BLABM']	. '\',';
			$SQL .= '\'' . $dat['COLFR']	. '\',';
			$SQL .= '\'' . $dat['COLOS']	. '\',';
			$SQL .= '\'' . $dat['CRUBC']	. '\',';
			$SQL .= '\'' . $dat['CRUIS']	. '\',';
			$SQL .= '\'' . $dat['DAGHF']	. '\',';
			$SQL .= '\'' . $dat['DEERS']	. '\',';
			$SQL .= '\'' . $dat['DEFTU']	. '\',';
			$SQL .= '\'' . $dat['DESTR']	. '\',';
			$SQL .= '\'' . $dat['DIPCO']	. '\',';
			$SQL .= '\'' . $dat['DRAMA']	. '\',';
			$SQL .= '\'' . $dat['DREAD']	. '\',';
			$SQL .= '\'' . $dat['EMBAS']	. '\',';
			$SQL .= '\'' . $dat['FANFB']	. '\',';
			$SQL .= '\'' . $dat['FARM1']	. '\',';
			$SQL .= '\'' . $dat['FARM2']	. '\',';
			$SQL .= '\'' . $dat['FARM3']	. '\',';
			$SQL .= '\'' . $dat['FIGBO']	. '\',';
			$SQL .= '\'' . $dat['FIGIN']	. '\',';
			$SQL .= '\'' . $dat['FIRSD']	. '\',';
			$SQL .= '\'' . $dat['FRIGA']	. '\',';
			$SQL .= '\'' . $dat['GELAB']	. '\',';
			$SQL .= '\'' . $dat['GNDHI']	. '\',';
			$SQL .= '\'' . $dat['GOLBA']	. '\',';
			$SQL .= '\'' . $dat['HABI1']	. '\',';
			$SQL .= '\'' . $dat['HABI2']	. '\',';
			$SQL .= '\'' . $dat['HABI3']	. '\',';
			$SQL .= '\'' . $dat['HAMGU']	. '\',';
			$SQL .= '\'' . $dat['HVYBO']	. '\',';
			$SQL .= '\'' . $dat['HVYCA']	. '\',';
			$SQL .= '\'' . $dat['HVYCR']	. '\',';
			$SQL .= '\'' . $dat['HIBCA']	. '\',';
			$SQL .= '\'' . $dat['HOSPI']	. '\',';
			$SQL .= '\'' . $dat['HURFC']	. '\',';
			$SQL .= '\'' . $dat['IMPFR']	. '\',';
			$SQL .= '\'' . $dat['INSHT']	. '\',';
			$SQL .= '\'' . $dat['INTEL']	. '\',';
			$SQL .= '\'' . $dat['INTFR']	. '\',';
			$SQL .= '\'' . $dat['INTMP']	. '\',';
			$SQL .= '\'' . $dat['INTFO']	. '\',';
			$SQL .= '\'' . $dat['JUDDR']	. '\',';
			$SQL .= '\'' . $dat['JUMP1']	. '\',';
			$SQL .= '\'' . $dat['JUMP2']	. '\',';
			$SQL .= '\'' . $dat['LEOSC']	. '\',';
			$SQL .= '\'' . $dat['LIGCA']	. '\',';
			$SQL .= '\'' . $dat['LISTN']	. '\',';
			$SQL .= '\'' . $dat['MANU1']	. '\',';
			$SQL .= '\'' . $dat['MANU2']	. '\',';
			$SQL .= '\'' . $dat['MATS1']	. '\',';
			$SQL .= '\'' . $dat['MATS2']	. '\',';
			$SQL .= '\'' . $dat['MATRC']	. '\',';
			$SQL .= '\'' . $dat['MINE1']	. '\',';
			$SQL .= '\'' . $dat['MINE2']	. '\',';
			$SQL .= '\'' . $dat['RADI1']	. '\',';
			$SQL .= '\'' . $dat['RADI2']	. '\',';
			$SQL .= '\'' . $dat['OBULK']	. '\',';
			$SQL .= '\'' . $dat['OCON1']	. '\',';
			$SQL .= '\'' . $dat['OCON2']	. '\',';
			$SQL .= '\'' . $dat['ODEFM']	. '\',';
			$SQL .= '\'' . $dat['ODEF1']	. '\',';
			$SQL .= '\'' . $dat['ODEF2']	. '\',';
			$SQL .= '\'' . $dat['OMIN1']	. '\',';
			$SQL .= '\'' . $dat['OMIN2']	. '\',';
			$SQL .= '\'' . $dat['ORCBA']	. '\',';
			$SQL .= '\'' . $dat['OSLD1']	. '\',';
			$SQL .= '\'' . $dat['OSLD2']	. '\',';
			$SQL .= '\'' . $dat['PBANK']	. '\',';
			$SQL .= '\'' . $dat['PLATE']	. '\',';
			$SQL .= '\'' . $dat['PRIHC']	. '\',';
			$SQL .= '\'' . $dat['FUEL1']	. '\',';
			$SQL .= '\'' . $dat['FUEL2']	. '\',';
			$SQL .= '\'' . $dat['RAVMC']	. '\',';
			$SQL .= '\'' . $dat['RSENS']	. '\',';
			$SQL .= '\'' . $dat['RLAB1']	. '\',';
			$SQL .= '\'' . $dat['RLAB2']	. '\',';
			$SQL .= '\'' . $dat['SATE1']	. '\',';
			$SQL .= '\'' . $dat['SATE2']	. '\',';
			$SQL .= '\'' . $dat['SCOUT']	. '\',';
			$SQL .= '\'' . $dat['FOLDR']	. '\',';
			$SQL .= '\'' . $dat['SBASE']	. '\',';
			$SQL .= '\'' . $dat['STIDR']	. '\',';
			$SQL .= '\'' . $dat['STOCK']	. '\',';
			$SQL .= '\'' . $dat['SDEF1']	. '\',';
			$SQL .= '\'' . $dat['SDEF2']	. '\',';
			$SQL .= '\'' . $dat['SSLD1']	. '\',';
			$SQL .= '\'' . $dat['SSLD2']	. '\',';
			$SQL .= '\'' . $dat['TANDB']	. '\',';
			$SQL .= '\'' . $dat['TERCA']	. '\',';
			$SQL .= '\'' . $dat['TORBA']	. '\',';
			$SQL .= '\'' . $dat['TRACK']	. '\',';
			$SQL .= '\'' . $dat['TSCHL']	. '\',';
			$SQL .= '\'' . $dat['UNIVE']	. '\',';
			$SQL .= '\'' . $dat['VENHF']	. '\',';
			$SQL .= '\'' . $dat['VESSC']	. '\',';
			$SQL .= '\'' . $dat['VINEM']	. '\',';
			$SQL .= '\'' . $dat['WARFA']	. '\',';
			$SQL .= '\'' . $dat['WASFI']	. '\',';
			$SQL .= '\'' . $dat['WAYEC']	. '\',';
			$SQL .= '\'' . $dat['WHSE1']	. '\',';
			$SQL .= '\'' . $dat['WHSE2']	. '\',';
			$SQL .= '\'' . $dat['WHSE3']	. '\',';
			$SQL .= '\'' . $dat['WEATL']	. '\',';
			$SQL .= '\'' . $dat['ZEPFD']	. '\',';
			$SQL .= '\'' . $dat['BROCE']	. '\',';
			$SQL .= '\'' . $dat['AMIPS']	. '\',';
			$SQL .= '\'' . $dat['AirOps']	. '\',';
			$SQL .= '\'' . $dat['Capital']	. '\',';
			$SQL .= '\'' . $dat['Diplomacy']	. '\',';
			$SQL .= '\'' . $dat['Fighter']	. '\',';
			$SQL .= '\'' . $dat['Habitat']	. '\',';
			$SQL .= '\'' . $dat['IntelOps']	. '\',';
			$SQL .= '\'' . $dat['Materials']	. '\',';
			$SQL .= '\'' . $dat['Reproduction']	. '\',';
			$SQL .= '\'' . $dat['Queues']	. '\',';
			$SQL .= '\'' . $dat['Research']	. '\',';
			$SQL .= '\'' . $dat['Scouting']	. '\',';
			$SQL .= '\'' . $dat['Sensors']	. '\',';
			$SQL .= '\'' . $dat['Warehouse']	. '\',';
			$SQL .= '\'' . $dat['Special']	. '\',';
			$SQL .= '\'' . $dat['Speed']	. '\',';
			$SQL .= '\'' . $dat['Training']	. '\',';
			$SQL .= '\'' . $dat['Wealth']	. '\',';
			$SQL .= '\'' . $rank	. '\',';
			$SQL .= '\'' . $dat['AirCap']	. '\',';
			$SQL .= '\'' . $dat['HabSpace']	. '\',';
			$SQL .= '\'' . 'Y'	. '\',';
			$SQL .= '\'' . addslashes($species)	. '\',';
			$SQL .= '\'' . $dat['FleetRating']	. '\',';
			$SQL .= '\'' . $dat['OrbRating']	. '\',';
			$SQL .= '\'' . $dat['SurRating']	. '\',';
			$SQL .= '\'' . $dat['BuildRating']	. '\',';
			$SQL .= '\'' . $reconnaitertype	. '\',';
			$SQL .= '\'' . $durability	. '\'';
			$SQL .= ')';
			$result = mysql_query($SQL);

			if (!$result)
			{
				echo 'Invalid query: ' . mysql_error();
			}
			else
			{
				if ($DEV)
				{
					//echo "Scouting added <BR>";
				}
			}

			$newid = mysql_insert_id(); //get the new record number
			$sid2 = $sid1;	//assign the old report number
			$sid1 = $newid; //assign the new report number
         	
			$SQL  = 'UPDATE tblplanet SET ';
			$SQL .= 'SID1 = \'' . $sid1 . '\', ';
			$SQL .= 'SID2 = \'' . $sid2 . '\'  ';
			$SQL .= 'WHERE RecordNumber = ' . $planetID;
			$result = mysql_query($SQL);

			if (!$result)
			{
				echo 'Invalid query: ' . mysql_error();
			}
			else
			{
				if ($DEV)
				{
					//echo "tblplanet SID1 and SID2 updated <BR>";
				}
			}
		}
		return $planetID;
	}
	else
	{
		return $err;
	}
}
?>