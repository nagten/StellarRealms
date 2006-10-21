<?php

include("../cabal_database.php");

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

$dateFound    = false;
$fromFound    = false;
$targetFound  = false;
$structsFound = false;

$firstLine = $ray[0];
$pos = strpos($firstLine,'Date:');

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
	$cnt = count($ray);
	for ($i = 0; $i < $cnt; $i++)
	{
		$line = $ray[$i];
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
			if ($pos !== false) 
			{
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
	$timestamp = $ray[0];
	$from      = $ray[1];
	$target    = $ray[2];
	$structs   = $ray[3];
	parseDate($timestamp);
	parseFrom($from);
	parseTarget($target);
	parseStructs($structs);
}

$result = updateDatabase();
echo $result;

//================================================================================
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
	
	for ($i = 0; $i < count($ray); $i++) 
	{
		$from .= $ray[$i] . ' ';
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
	
	for ($i = 0; $i < count($ray); $i++) 
	{
		$struct = $ray[$i];
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
				$Initial = substr($name,0,1);
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
	switch ($name) 
	{
		case 'Advanced Genetics Lab':
			$dat['ADVGE']       = $qty;
			$dat['Reproduction'] += $qty;
			break;
		case 'Advanced Interceptor':
			$dat['ADVIN']       = $qty;
			$dat['Fighter']    += $qty;
			break;
		case 'Advanced Technologies Shipyard':
			$dat['ADVTS']       = $qty;
		   $dat['Queues']     += $qty;
			$dat['Slots']      += ($qty * 1);
			break;
		case 'Aegis Mobile Shield':
			$dat['AEGMS']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 188);
			break;
		case 'Airbase':
			$dat['AIRB1']       = $qty;
			$dat['AirOps']     += $qty;
			$dat['AirCap']     += ($qty * 200);
			break;
		case 'Airbase (Improved)':
			$dat['AIRB2']       = $qty;
			$dat['AirOps']     += $qty;
			$dat['AirCap']     += ($qty * 300);
			break;
		case 'Anvil Battleship':
			$dat['ANVBS']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 249);
			break;
		case 'Asp Heavy Cruiser':
			$dat['ASPHC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 106);
			break;
		case 'Avalanche Siege Cruiser':
			$dat['AVASC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 116);
			break;
	}
}

function parse_B_structs($name,$qty)
{
	global $dat;
	switch ($name) 
	{
		case 'Badger Light Cruiser':
			$dat['BADLC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 199);
			break;
		case 'Barracks':
			$dat['BARR1']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 35);
			break;
		case 'Barracks (Veteran)':
			$dat['BARR2']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 49);
			break;
		case 'Barracuda Attack Frigate':
			$dat['BARAF']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 25);
			break;
		case 'Battleship':
			$dat['BATSH']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 229);
			break;
		case 'Berzerker Destroyer':
			$dat['BERDE']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 30);
			break;
		case 'Biological Research Facility':
			$dat['BIOLO']        = $qty;
			$dat['Reproduction']  += $qty;
			break;
		case 'Black Widow Brood Minder':
			$dat['BLABM']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 744);
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
			$dat['OffMaint']   += ($qty * 26);
			break;
		case 'Colossus Megaship':
			$dat['COLOS']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 880);
			break;
		case 'Crusader Battlecruiser':
			$dat['CRUBC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 153);
			break;
		case 'Cruiser':
			$dat['CRUIS']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 115);
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
			break;
		case 'Deep Recon Scout':
			$dat['DEERS']       = $qty;
			$dat['Scouting']   += $qty;
			break;
		case 'Defense Turret':
			$dat['DEFTU']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 7);
			break;
		case 'Destroyer':
			$dat['DESTR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 31);
			break;
		case 'Diplomatic Council':
			$dat['DIPCO']       = $qty;
			$dat['Diplomacy']  += $qty;
			break;
		case 'Dragon Mobile Assault Platform':
			$dat['DRAMA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 379);
			break;
		case 'Dreadnought':
			$dat['DREAD']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 258);
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
			break;
		case 'Farm I':
			$dat['FARM1']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Farm II':
			$dat['FARM2']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Farm III':
			$dat['FARM3']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Fighter Bomber':
			$dat['FIGBO']       = $qty;
			$dat['Fighter']    += $qty;
			break;
		case 'Fighter Interceptor':
			$dat['FIGIN']       = $qty;
			$dat['Fighter']    += $qty;
			break;
		case 'Fire Support Destroyer':
			$dat['FIRSD']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 34);
			break;
		case 'Frigate':
			$dat['FRIGA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 21);
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
			break;
		case 'Goliath Battleship':
			$dat['GOLBA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 260);
			break;
		case 'Grand Hive':
			$dat['GNDHI']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * 100000);
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
			$dat['HabSpace']   += ($qty * 30000);
			break;
		case 'Habitat (Improved)':
			$dat['HABI2']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * 50000);
			break;
		case 'Habitat (Ultradense)':
			$dat['HABI3']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * 70000);
			break;
		case 'Hammer Gunship':
			$dat['HAMGU']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 15);
			break;
		case 'Heavy Bomber':
			$dat['HVYBO']       = $qty;
			$dat['Fighter']    += $qty;
			break;
		case 'Heavy Carrier':
			$dat['HVYCA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 257);
			break;
		case 'Heavy Cruiser':
			$dat['HVYCR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 125);
			break;
		case 'Hibernation Caves':
			$dat['HIBCA']       = $qty;
			$dat['Habitat']    += $qty;
			$dat['HabSpace']   += ($qty * 250000);
			break;
		case 'Hospital':
			$dat['HOSPI']        = $qty;
			$dat['Reproduction']  += $qty;
			break;
		case 'Hurricane Fast Cruiser':
			$dat['HURFC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 115);
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
			$dat['OffMaint']   += ($qty * 23);
			break;
		case 'Institute of Higher Thought':
			$dat['INSHT']       = $qty;
			$dat['Research']   += $qty;
			$dat['HabSpace']   += ($qty * 500);
			break;
		case 'Intelligence Agency':
			$dat['INTEL']       = $qty;
			$dat['IntelOps']   += $qty;
			$dat['HabSpace']   += ($qty * 1000);
			break;
		case 'Interdictor Frigate':
			$dat['INTFR']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 21);
			break;
		case 'Interplanetary Marketplace':
			$dat['INTMP']       = $qty;
			$dat['Wealth']     += $qty;
			break;
		case 'Interstellar Forum':
			$dat['INTFO']       = $qty;
			$dat['Reproduction'] += $qty;
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
			$dat['OffMaint']   += ($qty * 234);
			break;
		case 'Jumpgate':
			$dat['JUMP1']       = $qty;
			$dat['Speed']      += $qty;
			break;
		case 'Jumpgate (Improved)':
			$dat['JUMP2']       = $qty;
			$dat['Speed']      += $qty;
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
			$dat['OffMaint']   += ($qty * 106);
			break;
		case 'Light Carrier':
			$dat['LIGCA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 139);
			break;
		case 'Listening Post':
			$dat['LISTN']       = $qty;
			$dat['Sensors']    += $qty;
			break;
	}
}

function parse_M_structs($name,$qty) 
{
	global $dat;
	switch ($name) 
	{
		case 'Manufacturing Plant':
			$dat['MANU1']       = $qty;
			$dat['Queues']     += $qty;
			$dat['Slots']      += ($qty * 1);
			break;
		case 'Manufacturing Plant (Improved)':
			$dat['MANU2']       = $qty;
			$dat['Queues']     += $qty;
			$dat['Slots']      += ($qty * 2);
			break;
		case 'Materials Processing Plant':
			$dat['MATS1']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Materials Processing Plant (Improved)':
			$dat['MATS2']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Materials Research Complex':
			$dat['MATRC']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Mining Facility (Metals)':
			$dat['MINE1']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Mining Facility (Metals - Improved)':
			$dat['MINE2']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Mining Facility (Radioactives)':
			$dat['RADI1']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Mining Facility (Radioactives - Improved)':
			$dat['RADI2']       = $qty;
			$dat['Materials']  += $qty;
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
			$dat['Defense']     += $qty;
			break;
		case 'Orbital Construction Yard':
			$dat['OCON1']        = $qty;
			$dat['Queues']      += $qty;
			$dat['Slots']       += ($qty * 1);
			break;
		case 'Orbital Construction Yard (Improved)':
			$dat['OCON2']        = $qty;
			$dat['Queues']      += $qty;
			$dat['Slots']       += ($qty * 2);
			break;
		case 'Orbital Defense Monitor':
			$dat['ODEFM']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 41);
			break;
		case 'Orbital Defense Platform':
			$dat['ODEF1']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 30);
			break;
		case 'Orbital Defense Platform (Improved)':
			$dat['ODEF2']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 38);
			break;
		case 'Orbital Minefield':
			$dat['OMIN1']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 5);
			break;
		case 'Orbital Minefield (Improved)':
			$dat['OMIN2']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 6);
			break;
		case 'Orbital Shield':
			$dat['OSLD1']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 29);
			break;
		case 'Orbital Shield (Improved)':
			$dat['OSLD2']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 39);
			break;
		case 'Orca Battleship':
			$dat['ORCBA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 246);
			break;
	}
}

function parse_P_structs($name,$qty)
{
	global $dat;
	switch ($name) 
	{
		case 'Planetary Bank':
			$dat['PBANK']       = $qty;
			$dat['Wealth']     += $qty;
			break;
		case 'Plating Factory':
			$dat['PLATE']       = $qty;
			$dat['Special']    += $qty;
			break;
		case 'Privateer Heavy Cruiser':
			$dat['PRIHC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 117);
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
			$dat['OffMaint']   += ($qty * 115);
			break;
		case 'Refinery (Fuel)':
			$dat['FUEL1']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Refinery (Fuel - Improved)':
			$dat['FUEL2']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Remote Sensor Array':
			$dat['RSENS']       = $qty;
			$dat['Sensors']    += $qty;
			break;
		case 'Research Lab':
			$dat['RLAB1']       = $qty;
			$dat['Research']   += $qty;
			break;
		case 'Research Lab (Improved)':
			$dat['RLAB2']       = $qty;
			$dat['Research']   += $qty;
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
			break;
		case 'Satellites (Improved)':
			$dat['SATE2']        = $qty;
			$dat['Sensors']     += $qty;
			break;
		case 'Scout':
			$dat['SCOUT']        = $qty;
			$dat['Scouting']    += $qty;
			break;
		case 'Space Folder':
			$dat['FOLDR']        = $qty;
			$dat['Speed']       += $qty;
			break;
		case 'Starbase':
			$dat['SBASE']       = $qty;
			$dat['Defense']    += $qty;
			$dat['HabSpace']   += ($qty * 10000);
			$dat['Slots']      += ($qty * 2);
			$dat['DefMaint']   += ($qty * 330);
			$dat['AirCap']     += ($qty * 50);
			break;
		case 'Stinger Drone':
			$dat['STIDR']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 1);
			break;
		case 'Stock Exchange':
			$dat['STOCK']       = $qty;
			$dat['Wealth']     += $qty;
			break;
		case 'Surface Defense Battery':
			$dat['SDEF1']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 15);
			break;
		case 'Surface Defense Battery (Improved)':
			$dat['SDEF2']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 21);
			break;
		case 'Surface Shield Generator':
			$dat['SSLD1']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 29);
			break;
		case 'Surface Shield Generator (Improved)':
			$dat['SSLD2']       = $qty;
			$dat['Defense']    += $qty;
			$dat['DefMaint']   += ($qty * 39);
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
			$dat['OffMaint']   += ($qty * 200);
			break;
		case 'Terrapin Carrier':
			$dat['TERCA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 324);
			break;
		case 'Tortoise Battleship':
			$dat['TORBA']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 240);
			break;
		case 'Tracking Station':
			$dat['TRACK']       = $qty;
			$dat['Sensors']    += $qty;
			break;
		case 'Trade School':
			$dat['TSCHL']       = $qty;
			$dat['Training']   += $qty;
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
			$dat['HabSpace']   += ($qty * 500);
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
			break;
		case 'Vespa Siege Carrier':
			$dat['VESSC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 268);
			break;
		case 'Vinemind':
			$dat['VINEM']       = $qty;
			$dat['Sensors']    += $qty;
			$dat['HabSpace']   += ($qty * 100000);
			break;
	}
}

function parse_W_structs($name,$qty) 
{
	global $dat;
	switch ($name) 
	{
		case 'War Factory':
			$dat['WARFA']       = $qty;
			$dat['Special']    += $qty;
			break;
		case 'Warehouse (Small)':
			$dat['WHSE1']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Warehouse (Medium)':
			$dat['WHSE2']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Warehouse (Large)':
			$dat['WHSE3']       = $qty;
			$dat['Materials']  += $qty;
			break;
		case 'Wasp Fighter':
			$dat['WASFI']       = $qty;
			$dat['Fighter']    += $qty;
			break;
		case 'Wayfarer Exploration Cruiser':
			$dat['WAYEC']       = $qty;
			$dat['Capital']    += $qty;
			$dat['OffMaint']   += ($qty * 127);
			break;
		case 'Weapons Technology Laboratory':
			$dat['WEATL']       = $qty;
			$dat['Special']    += $qty;
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
			$dat['OffMaint']   += ($qty * 32);
			break;
	}
}


function initialize_dat()
{
	global $dat;
	$dat['date']   = '';
	$dat['time']   = '';
	$dat['from']   = '';
	$dat['target'] = '';

	$dat['AirOps']       = 0;
	$dat['AirCap']       = 0;
	$dat['Capital']      = 0;
	$dat['Defense']      = 0;
	$dat['DefMaint']     = 0;
	$dat['Diplomacy']    = 0;
	$dat['Fighter']      = 0;
	$dat['Habitat']      = 0;
	$dat['HabSpace']     = 1250000;
	$dat['IntelOps']     = 0;
	$dat['Materials']    = 0;
	$dat['OffMaint']     = 0;
	$dat['Reproduction'] = 0;
	$dat['Queues']       = 0;
	$dat['Research']     = 0;
	$dat['Scouting']     = 0;
	$dat['Sensors']      = 0;
	$dat['Slots']        = 1;
	$dat['Special']      = 0;
	$dat['Speed']        = 0;
	$dat['Training']     = 0;
	$dat['Wealth']       = 0;

	$dat['MANU1']  = '';
	$dat['MINE1']  = '';
	$dat['ADVIN']  = '';
	$dat['ADVGE']  = '';
	$dat['ADVTS']  = '';
	$dat['AEGMS']  = '';
	$dat['AIRB1']  = '';
	$dat['AIRB2']  = '';
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
	$dat['FRIGA']  = '';
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
	$dat['FUEL1']  = '';
	$dat['FUEL2']  = '';
	$dat['RAVMC']  = '';
	$dat['RSENS']  = '';
	$dat['RLAB1']  = '';
	$dat['RLAB2']  = '';
	$dat['SATE1']  = '';
	$dat['SATE2']  = '';
	$dat['SCOUT']  = '';
	$dat['FOLDR']  = '';
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
	global $dat;
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
		// get target planet id
		$SQL = 'Select RecordNumber,Rank,SID1 FROM Planet WHERE PlanetName = \'' . $targetName . '\'';
		$result = mysql_query($SQL);
		if (!$result) die('Invalid query: ' . mysql_error());
		if (mysql_num_rows($result) > 0) 
		{
			$row = mysql_fetch_assoc($result);
			$planetID = $row['RecordNumber'];
			$rank     = $row['Rank'];
			$sid1     = $row['SID1'];
		} 
		else 
		{
			$ok   = false;
			$err .= 'target planet [' . $targetName . '] not found in database. ';
			//echo 'target planet [' . $targetName . '] not found in database. ';
		}
	}

	if ($ok) 
	{
		// get source planet id
		$SQL = 'Select RecordNumber FROM Planet WHERE PlanetName = \'' . $sourceName . '\'';
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
		$SQL .= 'FROM scout ';
		$SQL .= 'WHERE PlanetID = \'' . $planetID   . '\' ';
		$SQL .= 'AND ReportDate = \'' . $reportDate . '\' ';
		$SQL .= 'AND ReportTime = \'' . $reportTime . '\' ';
		$result = mysql_query($SQL);
		if (!$result) die('Invalid query: ' . mysql_error());
		
		if (mysql_num_rows($result) > 0) 
		{
			// do nothing, record already exists
		}
		else 
		{
			$SQL  = 'INSERT INTO scout (PlanetID,PlanetName,SourceID,SourceName,ReportDate,ReportTime,';
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
			$SQL .= 'WARFA,WASFI,WAYEC,WHSE1,WHSE2,WHSE3,WEATL,ZEPFD,';
			$SQL .= 'AirOps,Capital,Defense,Diplomacy,Fighter,Habitat,IntelOps,';
			$SQL .= 'Materials,Reproduction,Queues,Research,Scouting,Sensors,';
			$SQL .= 'Special,Speed,Training,Wealth,Rank,AirCap,HabSpace,Slots,DefMaint,OffMaint,Current';
			$SQL .= ') VALUES (';
			$SQL .= '\'' . $planetID         . '\',';
			$SQL .= '\'' . $dat['target']    . '\',';
			$SQL .= '\'' . $sourceID         . '\',';
			$SQL .= '\'' . $dat['from']      . '\',';
			$SQL .= '\'' . $reportDate       . '\',';
			$SQL .= '\'' . $reportTime       . '\',';
			$SQL .= '\'' . $dat['ADVIN']     . '\',';
			$SQL .= '\'' . $dat['ADVGE']     . '\',';
			$SQL .= '\'' . $dat['ADVTS']     . '\',';
			$SQL .= '\'' . $dat['AEGMS']     . '\',';
			$SQL .= '\'' . $dat['AIRB1']     . '\',';
			$SQL .= '\'' . $dat['AIRB2']     . '\',';
			$SQL .= '\'' . $dat['ANVBS']     . '\',';
			$SQL .= '\'' . $dat['ASPHC']     . '\',';
			$SQL .= '\'' . $dat['AVASC']     . '\',';
			$SQL .= '\'' . $dat['BADLC']     . '\',';
			$SQL .= '\'' . $dat['BARAF']     . '\',';
			$SQL .= '\'' . $dat['BARR1']     . '\',';
			$SQL .= '\'' . $dat['BARR2']     . '\',';
			$SQL .= '\'' . $dat['BATSH']     . '\',';
			$SQL .= '\'' . $dat['BERDE']     . '\',';
			$SQL .= '\'' . $dat['BIOLO']     . '\',';
			$SQL .= '\'' . $dat['BLABM']     . '\',';
			$SQL .= '\'' . $dat['COLFR']     . '\',';
			$SQL .= '\'' . $dat['COLOS']     . '\',';
			$SQL .= '\'' . $dat['CRUBC']     . '\',';
			$SQL .= '\'' . $dat['CRUIS']     . '\',';
			$SQL .= '\'' . $dat['DAGHF']     . '\',';
			$SQL .= '\'' . $dat['DEERS']     . '\',';
			$SQL .= '\'' . $dat['DEFTU']     . '\',';
			$SQL .= '\'' . $dat['DESTR']     . '\',';
			$SQL .= '\'' . $dat['DIPCO']     . '\',';
			$SQL .= '\'' . $dat['DRAMA']     . '\',';
			$SQL .= '\'' . $dat['DREAD']     . '\',';
			$SQL .= '\'' . $dat['EMBAS']     . '\',';
			$SQL .= '\'' . $dat['FANFB']     . '\',';
			$SQL .= '\'' . $dat['FARM1']     . '\',';
			$SQL .= '\'' . $dat['FARM2']     . '\',';
			$SQL .= '\'' . $dat['FARM3']     . '\',';
			$SQL .= '\'' . $dat['FIGBO']     . '\',';
			$SQL .= '\'' . $dat['FIGIN']     . '\',';
			$SQL .= '\'' . $dat['FIRSD']     . '\',';
			$SQL .= '\'' . $dat['FRIGA']     . '\',';
			$SQL .= '\'' . $dat['GELAB']     . '\',';
			$SQL .= '\'' . $dat['GNDHI']     . '\',';
			$SQL .= '\'' . $dat['GOLBA']     . '\',';
			$SQL .= '\'' . $dat['HABI1']     . '\',';
			$SQL .= '\'' . $dat['HABI2']     . '\',';
			$SQL .= '\'' . $dat['HABI3']     . '\',';
			$SQL .= '\'' . $dat['HAMGU']     . '\',';
			$SQL .= '\'' . $dat['HVYBO']     . '\',';
			$SQL .= '\'' . $dat['HVYCA']     . '\',';
			$SQL .= '\'' . $dat['HVYCR']     . '\',';
			$SQL .= '\'' . $dat['HIBCA']     . '\',';
			$SQL .= '\'' . $dat['HOSPI']     . '\',';
			$SQL .= '\'' . $dat['HURFC']     . '\',';
			$SQL .= '\'' . $dat['IMPFR']     . '\',';
			$SQL .= '\'' . $dat['INSHT']     . '\',';
			$SQL .= '\'' . $dat['INTEL']     . '\',';
			$SQL .= '\'' . $dat['INTFR']     . '\',';
			$SQL .= '\'' . $dat['INTMP']     . '\',';
			$SQL .= '\'' . $dat['INTFO']     . '\',';
			$SQL .= '\'' . $dat['JUDDR']     . '\',';
			$SQL .= '\'' . $dat['JUMP1']     . '\',';
			$SQL .= '\'' . $dat['JUMP2']     . '\',';
			$SQL .= '\'' . $dat['LEOSC']     . '\',';
			$SQL .= '\'' . $dat['LIGCA']     . '\',';
			$SQL .= '\'' . $dat['LISTN']     . '\',';
			$SQL .= '\'' . $dat['MANU1']     . '\',';
			$SQL .= '\'' . $dat['MANU2']     . '\',';
			$SQL .= '\'' . $dat['MATS1']     . '\',';
			$SQL .= '\'' . $dat['MATS2']     . '\',';
			$SQL .= '\'' . $dat['MATRC']     . '\',';
			$SQL .= '\'' . $dat['MINE1']     . '\',';
			$SQL .= '\'' . $dat['MINE2']     . '\',';
			$SQL .= '\'' . $dat['RADI1']     . '\',';
			$SQL .= '\'' . $dat['RADI2']     . '\',';
			$SQL .= '\'' . $dat['OBULK']     . '\',';
			$SQL .= '\'' . $dat['OCON1']     . '\',';
			$SQL .= '\'' . $dat['OCON2']     . '\',';
			$SQL .= '\'' . $dat['ODEFM']     . '\',';
			$SQL .= '\'' . $dat['ODEF1']     . '\',';
			$SQL .= '\'' . $dat['ODEF2']         . '\',';
			$SQL .= '\'' . $dat['OMIN1']         . '\',';
			$SQL .= '\'' . $dat['OMIN2']         . '\',';
			$SQL .= '\'' . $dat['ORCBA']         . '\',';
			$SQL .= '\'' . $dat['OSLD1']         . '\',';
			$SQL .= '\'' . $dat['OSLD2']         . '\',';
			$SQL .= '\'' . $dat['PBANK']         . '\',';
			$SQL .= '\'' . $dat['PLATE']         . '\',';
			$SQL .= '\'' . $dat['PRIHC']         . '\',';
			$SQL .= '\'' . $dat['FUEL1']         . '\',';
			$SQL .= '\'' . $dat['FUEL2']         . '\',';
			$SQL .= '\'' . $dat['RAVMC']         . '\',';
			$SQL .= '\'' . $dat['RSENS']         . '\',';
			$SQL .= '\'' . $dat['RLAB1']         . '\',';
			$SQL .= '\'' . $dat['RLAB2']         . '\',';
			$SQL .= '\'' . $dat['SATE1']         . '\',';
			$SQL .= '\'' . $dat['SATE2']         . '\',';
			$SQL .= '\'' . $dat['SCOUT']         . '\',';
			$SQL .= '\'' . $dat['FOLDR']         . '\',';
			$SQL .= '\'' . $dat['SBASE']         . '\',';
			$SQL .= '\'' . $dat['STIDR']         . '\',';
			$SQL .= '\'' . $dat['STOCK']         . '\',';
			$SQL .= '\'' . $dat['SDEF1']         . '\',';
			$SQL .= '\'' . $dat['SDEF2']         . '\',';
			$SQL .= '\'' . $dat['SSLD1']         . '\',';
			$SQL .= '\'' . $dat['SSLD2']         . '\',';
			$SQL .= '\'' . $dat['TANDB']         . '\',';
			$SQL .= '\'' . $dat['TERCA']         . '\',';
			$SQL .= '\'' . $dat['TORBA']         . '\',';
			$SQL .= '\'' . $dat['TRACK']         . '\',';
			$SQL .= '\'' . $dat['TSCHL']         . '\',';
			$SQL .= '\'' . $dat['UNIVE']         . '\',';
			$SQL .= '\'' . $dat['VENHF']         . '\',';
			$SQL .= '\'' . $dat['VESSC']         . '\',';
			$SQL .= '\'' . $dat['VINEM']         . '\',';
			$SQL .= '\'' . $dat['WARFA']         . '\',';
			$SQL .= '\'' . $dat['WASFI']         . '\',';
			$SQL .= '\'' . $dat['WAYEC']         . '\',';
			$SQL .= '\'' . $dat['WHSE1']         . '\',';
			$SQL .= '\'' . $dat['WHSE2']         . '\',';
			$SQL .= '\'' . $dat['WHSE3']         . '\',';
			$SQL .= '\'' . $dat['WEATL']         . '\',';
			$SQL .= '\'' . $dat['ZEPFD']         . '\',';
			$SQL .= '\'' . $dat['AirOps']        . '\',';
			$SQL .= '\'' . $dat['Capital']       . '\',';
			$SQL .= '\'' . $dat['Defense']       . '\',';
			$SQL .= '\'' . $dat['Diplomacy']     . '\',';
			$SQL .= '\'' . $dat['Fighter']       . '\',';
			$SQL .= '\'' . $dat['Habitat']       . '\',';
			$SQL .= '\'' . $dat['IntelOps']      . '\',';
			$SQL .= '\'' . $dat['Materials']     . '\',';
			$SQL .= '\'' . $dat['Reproduction']  . '\',';
			$SQL .= '\'' . $dat['Queues']        . '\',';
			$SQL .= '\'' . $dat['Research']      . '\',';
			$SQL .= '\'' . $dat['Scouting']      . '\',';
			$SQL .= '\'' . $dat['Sensors']       . '\',';
			$SQL .= '\'' . $dat['Special']       . '\',';
			$SQL .= '\'' . $dat['Speed']         . '\',';
			$SQL .= '\'' . $dat['Training']      . '\',';
			$SQL .= '\'' . $dat['Wealth']        . '\',';
			$SQL .= '\'' . $rank                 . '\',';
			$SQL .= '\'' . $dat['AirCap']        . '\',';
			$SQL .= '\'' . $dat['HabSpace']      . '\',';
			$SQL .= '\'' . $dat['Slots']         . '\',';
			$SQL .= '\'' . $dat['DefMaint']      . '\',';
			$SQL .= '\'' . $dat['OffMaint']      . '\',';
			$SQL .= '\'' . 'Y'                   . '\' ';
			$SQL .= ')';
			$result = mysql_query($SQL);
			
			if (!$result) die('Invalid query: ' . mysql_error());
			
			$newid = mysql_insert_id();
			$sid2 = $sid1;
         	$sid1 = $newid;
			$SQL  = 'UPDATE Planet SET ';
			$SQL .= 'SID1 = \'' . $sid1 . '\', ';
			$SQL .= 'SID2 = \'' . $sid2 . '\'  ';
			$SQL .= 'WHERE RecordNumber = ' . $planetID;
			$result = mysql_query($SQL);
			
			if (!$result) die('Invalid query: ' . mysql_error());
		}
		return $planetID;
	}
	else 
	{
		return $err;
	}
}
?>