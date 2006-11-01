<?php
require_once('xml.php');

function UpdateRank($dbTurn)
{
	global $DEV;
	$DEV = false;
	
	//--- GET THE PLANETS --------------------------------------------------------------------------------------
	$planets = array();
	$pIndex  = array();
	$indx    = 0;
	$crlf = chr(13) . chr(10);
	$tab  = chr(9);
	$lf = "\n";

	$SQL = 'SELECT * FROM tblplanet ORDER BY Rank';
	$result = mysql_query($SQL);

	if (!$result)
	{
		die('Invalid query: ' . mysql_error());
	}

	while ($row = mysql_fetch_assoc($result))
	{
		$pID      = $row['RecordNumber'];
		$pName    = stripslashes($row['PlanetName']);
		$pLeader  = stripslashes($row['Leader']);
		$pSpecies = stripslashes($row['Species']);
		$pFirstRank = $row['FirstRank'];
		$planets[$pName]['ID']        = $pID;
		$planets[$pName]['Name']      = $pName;
		$planets[$pName]['Leader']    = $pLeader;
		$planets[$pName]['Species']   = $pSpecies;
		$planets[$pName]['FirstRank'] = $pFirstRank;
		$indx++;
		$pIndex[$indx] = $pName;
	}
	mysql_free_result($result);

	//--- GET XML FROM STELLAR-REALMS ---------------------------------------------------------------------------
	$host = 'www.sr.primeaxiom.com';
	$doc  = 'http://sr.primeaxiom.com/webservices/galaxy_overview.asp?un=' . urlencode('apiusers') . '&pwd=hitcvjnjrc';
	$cmd  = 'GET ' . $doc . '  HTTP/1.0' . $crlf . 'Host: ' . $host  . $crlf . $crlf;

	if ($DEV)
	{
		echo "Getting rank info <BR>";
	}
			
	$attempt = 0;

	do
	{
		$attempt++;
		
		if ($DEV)
		{
			echo date('M d Y  H:i:s') . ' Attempt ' . $attempt . $lf;
		}
		
		$fp = @fsockopen ('www.primeaxiom.com', 80, $errno, $errstr, 10);
		if (!$fp)
		{
			echo "Error opening socket: $errstr ($errno)\n";
			if ($attempt > 10) die('Failed to connect to www.sr.primeaxion.com');
		}
	}
	while (! $fp);

	$resp = '';
	fputs ($fp, $cmd);

	while (!feof($fp))
	{
		$resp .= fgets ($fp,128);
	}
	fclose ($fp);

	$pos = strpos($resp,'<?xml version=');
	$xmlstr = substr($resp,$pos);

	//--- PARSE THE XML --------------------------------------------------------------------------------------------
	$xml = new XML('string',$xmlstr);

	//--- Get the Turn Number for this data ---
	$expression = '//go:context';
	$context    = '';
	$results = $xml->evaluate($expression, $context);
	
	foreach ( $results as $result )
	{
		$a = array();
		$a = $xml->get_attributes($result);
		$srTurn = $a['turn'];
	}

	//--- Process all the planets ---
	if ($dbTurn != $srTurn || $srTurn == 0)
	{
		$expression = '//go:planet';
		$context    = '';
		$results = $xml->evaluate($expression, $context);
		$current_date = date("m-d-Y H:i:s");

		foreach ( $results as $result )
		{
			$a = array();
			$a = $xml->get_attributes($result);
			$rank      = $a['rank'];
			$name      = $a['name'];
			$leader    = $a['leader'];
			$species   = $a['species'];
			$online    = $a['online'];
			$inhabited = $a['inhabited'];
			$prestige  = $a['prestige'] + 0;

			$name      = trim($name);
			$leader    = trim($leader);

			$pName    = addslashes($name);
			$pLeader  = addslashes($leader);
			$pSpecies = addslashes($species);

			//echo $rank . $tab . $pName; //. $tab . $pLeader . $tab . $pSpecies . $tab . $online . $tab . $prestige . $lf;

			//--- New Planet? - Add to Database ---
			if ( ! array_key_exists($name,$planets))
			{
				$SQL  = 'INSERT INTO tblplanet (PlanetName,Leader,Species,Rank,FirstTurn,FirstRank, date) VALUES (';
				$SQL .= '\'' . $pName    . '\',';
				$SQL .= '\'' . $pLeader  . '\',';
				$SQL .= '\'' . $pSpecies . '\',';
				$SQL .= '\'' . $rank     . '\',';
				$SQL .= '\'' . $srTurn   . '\',';
				$SQL .= '\'' . $rank   . '\',';
				$SQL .= '\'' . $current_date . '\')';
				//echo $SQL . $lf;
				$result = mysql_query($SQL);
				$pIndex[] = $pName;

				$SQL = 'SELECT RecordNumber FROM tblplanet WHERE PlanetName = \'' . $pName . '\'';
				$result = mysql_query($SQL);
				if (!$result)
				{
					die('Invalid query: ' . mysql_error());
				}

				if ($row = mysql_fetch_assoc($result))
				{
					$pID = $row['RecordNumber'];
				}
				else
				{
					die('Selected nothing');
				}

				//$pID = mysql_insert_id($dbconn);
				$planets[$name]['ID']      = $pID;
				$planets[$name]['Name']    = $pName;
				$planets[$name]['Leader']  = $pLeader;
				$planets[$name]['Species'] = $pSpecies;
				//echo 'Added planet ' . $pID . $tab . $pName . $tab . $pLeader . $tab . $pSpecies . $lf;
			}

			//--- Update the Planet Table ---
			$SQL = "UPDATE tblplanet SET Rank = " . $rank . ", TurnCount= " . $srTurn  . ", date= '" . $current_date  . "' ";
			$SQL .= " WHERE PlanetName = '" . $pName . "' ";

			//echo $SQL . $lf;

			$result = mysql_query($SQL);
			if (!$result)
			{
				die('Invalid query: ' . mysql_error());
			}
			else
			{
				//echo "updated";
			}
		}
	}
	unset($xml);
	unset($xmlstr);
}
?>