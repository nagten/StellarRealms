<?php
	require_once('xml.php');
	include("../connect_to_database.php");
	
	$timestart = microtime_float();
	$crlf = chr(13) . chr(10);
	$tab  = chr(9);
	$lf = "\n";
	
	echo date('l, F d, Y  h:i:s A') . $lf;
	
	$dbStart = microtime_float();
	
	//--- GET THE CONTROL RECORD -------------------------------------------------------------------------------
	$SQL = 'SELECT * FROM tblControl WHERE Active = \'Y\' ';
	$result = mysql_query($SQL);
	
	if (!$result)
	{
		 die('Invalid query: ' . mysql_error());
	}
	
	while ($row = mysql_fetch_assoc($result))
	{
		$dbRoundNumber = $row['RoundNumber'];
		$dbRoundName   = $row['RoundName'];
		$dbTurn        = $row['Turn'];
		$dbUpdateDate  = $row['UpdateDate'];
		$dbUpdateTime  = $row['UpdateTime'];
	}
	mysql_free_result($result);
	
	//--- GET THE PLANETS --------------------------------------------------------------------------------------
	$planets = array();
	$pIndex  = array();
	$indx    = 0;
	
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
	
	$dbEnd = microtime_float();
	$elasped = ($dbEnd - $dbStart);
	echo 'Database loaded in ' . sprintf('%.2f',$elasped) . ' seconds.       ' . $lf;
	
	$srStart = microtime_float();
	
	if ($_SERVER['SERVER_NAME'] == 'www.idsfadt.com')
	{
		$URL = 'http://sr.primeaxiom.com/webservices/galaxy_overview.asp?un=' . urlencode('api_user') . '&pwd=hit$cvjAnjrc';
		if (isset($_GET["site"])) { $URL = $_GET["site"]; }
		$ch = curl_init();
		echo "<br>URL = $URL <br>\n";
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt ($ch, CURLOPT_PROXY,"http://proxy.shr.secureserver.net:3128");
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_URL, $URL);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
		$resp = curl_exec ($ch);
		echo "<hr><br>\n";
		echo 'Errors: ' . curl_errno($ch) . ' ' . curl_error($ch) . '<br><br>';
		echo "<hr><br>\n";
		curl_close ($ch);
		echo "result = $resp";
		echo "<hr><br>\n";
	}
	else
	{
		//--- GET XML FROM STELLAR-REALMS ---------------------------------------------------------------------------
		$host = 'sr.primeaxiom.com';
		$doc  = 'http://sr.primeaxiom.com/webservices/galaxy_overview.asp?un=' . urlencode('api_user') . '&pwd=hit$cvjAnjrc';
		$length = 'Content-Length: ' . strlen($doc) . $crlf;
		//$cmd  = 'GET ' . $doc . $crlf . '  HTTP/1.1' . $crlf . 'Host: ' . $host . $crlf . $length . $crlf . $crlf;
		$cmd  = 'GET ' . $doc . $crlf . '  HTTP/1.1' . $crlf . 'Host: ' . $host . $crlf . $crlf;
		
		echo $cmd;
	
		$attempt = 0;
	
		do 
		{
			$attempt++;
			echo date('M d Y  H:i:s') . ' Attempt ' . $attempt . $lf;
			$fp = @fsockopen ('sr.primeaxiom.com', 80, $errno, $errstr, 10); 
			
			if (!$fp) 
			{ 
				echo "Error opening socket: $errstr ($errno)\n";
				if ($attempt > 30) die('Failed to connect to www.sr.primeaxion.com');
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
	}

	$pos = strpos($resp,'<?xml version=');
	$xmlstr = substr($resp,$pos);

	$srEnd = microtime_float();
	$elasped = ($srEnd - $srStart);
	echo 'Stellar-Realms loaded in ' . sprintf('%.2f',$elasped) . ' seconds.' . $lf;

	$xmlStart = microtime_float();
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
	echo 'Turn: ' . $srTurn . $lf;

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

			echo $rank . $tab . $pName . $tab . $pLeader . $tab . $pSpecies . $tab . $online . $tab . $prestige . $lf;

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
				echo 'Added planet ' . $pID . $tab . $pName . $tab . $pLeader . $tab . $pSpecies . $lf;
			}

			if ($srTurn > 0) 
			{
				//--- Update the Standings Table ---
				$SQL  = 'INSERT INTO tblStanding (PlanetID,Turn,Rank,Prestige,OnLine,UpdateDate,UpdateTime) VALUES (';
				$SQL .= $planets[$name]['ID'] . ',';
				$SQL .= $srTurn               . ',';
				$SQL .= $rank                 . ',';
				$SQL .= $prestige             . ',';
				$SQL .= '\'' . $online        . '\', ';
				$SQL .= '\'' . date('Y-m-d')  . '\', ';
				$SQL .= '\'' . date('H:i:s')  . '\') ';
				//echo $SQL . $lf;
				$result = mysql_query($SQL);
				
				if (!$result)
				{
					die('Invalid query: ' . mysql_error());
				}
	
				//--- Update the Planet Table ---
				$SQL = 'UPDATE tblplanet SET ';
				$SQL .= 'Rank = ' . $rank . ', ';
				$SQL .= 'OnLine = \'' . $online . '\', ';
				
				if ($online == 'y') 
				{
					$SQL .= 'OnLineCount=OnLineCount+1, ';
				}
				
				$SQL .= 'TurnCount=TurnCount+1 ';
				$SQL .= 'WHERE PlanetName = \'' . $pName . '\'';
				//echo $SQL . $lf;
				$result = mysql_query($SQL);
				if (!$result)
				{
					die('Invalid query: ' . mysql_error());
				}
			}
		}
	}
	unset($xml);
	unset($xmlstr);

	$xmlEnd = microtime_float();
	$elasped = ($xmlEnd - $xmlStart);
	echo 'XML parsed in ' . sprintf('%.2f',$elasped) . ' seconds.       ' . $lf;
	
	$glStart = microtime_float();
	
	//--- Update GainLoss --------------------------------------------------------------------------------------
	if ($dbTurn != $srTurn)
	{
		$prestige = array();
		$histTurn = $srTurn - 9;
		
		if ($histTurn < 0) 
		{
			$histTurn = 0;
		}

		$SQL = 'SELECT * FROM tblStanding WHERE Turn >= ' . $histTurn . ' ORDER BY PlanetID, Turn DESC';
		$result = mysql_query($SQL);
		
		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}
		
		while ($row = mysql_fetch_assoc($result)) 
		{
			$pID       = $row['PlanetID'];
			$pTurn     = $row['Turn'];
			$pPrestige = $row['Prestige'];
			$prestige[$pID][$pTurn] = $pPrestige;
		}
		mysql_free_result($result);

		$keys = array_keys($planets);
		$nbrPlanets = count($keys);
		
		for ($x=0;$x<$nbrPlanets;$x++) 
		{
			$key = $keys[$x];
			$pid   = $planets[$key]['ID'];
			$pName = $planets[$key]['Name'];
			
			if (array_key_exists($srTurn,$prestige[$pid])) 
			{
				$diff = pdelta($pid,$srTurn,1);
				if (($diff < 0) && abs($diff) > 90) 
				{
					$SQL  = 'INSERT INTO tblGainLoss (Turn,Planet,Type,Value,Text) VALUES (';
					$SQL .= $srTurn . ',';
					$SQL .= '\'' . addslashes($pName) . '\',';
					$SQL .= '\'Loss\',';
					$SQL .= abs($diff) . ',';
					$SQL .= '\'' . 'has prestige loss of' . '\')';
					$result = mysql_query($SQL);
					if (!$result) die('Invalid query: ' . mysql_error());
				}
				
				$avg = prestige_average($pid,$srTurn,9);
				if ($diff > ($avg * 2.2))
				{
					$SQL  = 'INSERT INTO tblGainLoss (Turn,Planet,Type,Value,Text) VALUES (';
					$SQL .= $srTurn . ',';
					$SQL .= '\'' . addslashes($pName) . '\',';
					$SQL .= '\'Gain\',';
					$SQL .= $diff . ',';
					$SQL .= '\'' . 'prestige gain by' . '\')';
					$result = mysql_query($SQL);
					if (!$result) die('Invalid query: ' . mysql_error());
				}
			}
		}
		unset($prestige);
	}
	unset($planets);

	$glEnd = microtime_float();
	$elasped = ($glEnd - $glStart);
	echo 'GainLoss procesed in ' . sprintf('%.2f',$elasped) . ' seconds.       ' . $lf;
	
	//--- UPDATE METRICS --------------------------------------------------------------------------------------------
	$umStart = microtime_float();

	if ($dbTurn != $srTurn)
	{
		$planets = array();
		$pIndex  = array();
		$indx    = 0;

		$SQL = 'SELECT RecordNumber, PlanetName, FirstRank FROM tblplanet ORDER BY Rank';
		$result = mysql_query($SQL);
		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}
		
		while ($row = mysql_fetch_assoc($result))
		{
			$pID        = $row['RecordNumber'];
			$pName      = stripslashes($row['PlanetName']);
			$pFirstRank = $row['FirstRank'];
			$planets[$pName]['ID']        = $pID;
			$planets[$pName]['Name']      = $pName;
			$planets[$pName]['FirstRank'] = $pFirstRank;
			$indx++;
			$pIndex[$indx] = $pName;
		}
		mysql_free_result($result);

		$current = array();

		$pCount = count($pIndex);
		for ($x=1;$x<=$pCount;$x++)
		{
			$key = $pIndex[$x];
			$id  = $planets[$key]['ID'];

			$rankings = array();
			$prestige = array();

			$modulo = ($srTurn % 72);
			$recent = ($srTurn - 18);

			$SQL  = 'SELECT Turn, PlanetID, Rank, Prestige ';
			$SQL .= 'FROM tblStanding ';
			$SQL .= 'WHERE PlanetID = ' .$id . ' ';
			$SQL .= 'ORDER BY PlanetID, Turn DESC';
			$result = mysql_query($SQL);
			if ( ! $result)
			{
				die('Invalid query: ' . mysql_error());
			}
			
			while ($row = mysql_fetch_assoc($result))
			{
				$pTurn     = $row['Turn'];
				$pID       = $row['PlanetID'];
				if ( ($pTurn >= $recent) || (($pTurn % 72) == $modulo) )
				{
					$pRank     = $row['Rank'];
					$pPrestige = $row['Prestige'];
					$rankings[$pID][$pTurn] = $pRank;
					$prestige[$pID][$pTurn] = $pPrestige;
				}
				
				if ( $pTurn == $srTurn )
				{
					$current[$pID] = $pPrestige;
				}
			}
			mysql_free_result($result);

			$RD0     = $rankings[$id][$srTurn];
			$RD1     = rankdelta($id,1);
			$RD6     = rankdelta($id,6);
			$RD12    = rankdelta($id,12);
			$RD72    = rankdelta($id,72);
			$RD144   = rankdelta($id,144);
			$RD216   = rankdelta($id,216);
			$RD288   = rankdelta($id,288);
			$RD360   = rankdelta($id,360);
			$RD720   = rankdelta($id,720);
			$RD1440  = rankdelta($id,1440);
			$RDFirst = $planets[$key]['FirstRank'] - $rankings[$id][$srTurn];

			$PD0     = $prestige[$id][$srTurn];
			$PD1     = prestigedelta($id,1);
			$PD6     = prestigedelta($id,6);
			$PD12    = prestigedelta($id,12);
			$PD18    = prestigedelta($id,18);
			$PD72    = prestigedelta($id,72);
			$PD144   = prestigedelta($id,144);
			$PD216   = prestigedelta($id,216);
			$PD288   = prestigedelta($id,288);
			$PD360   = prestigedelta($id,360);
			$PD720   = prestigedelta($id,720);
			$PD1440  = prestigedelta($id,1440);
			$PDNext  = prestigenext($x);

			$PA6     = prestige_average($id,$srTurn,6);

			$t = $srTurn;
			$PG1  = prestigediff($id,$t-0,$t-1);
			$PG2  = prestigediff($id,$t-1,$t-2);
			$PG3  = prestigediff($id,$t-2,$t-3);
			$PG4  = prestigediff($id,$t-3,$t-4);
			$PG5  = prestigediff($id,$t-4,$t-5);
			$PG6  = prestigediff($id,$t-5,$t-6);
			$PG7  = prestigediff($id,$t-6,$t-7);
			$PG8  = prestigediff($id,$t-7,$t-8);
			$PG9  = prestigediff($id,$t-8,$t-9);
			$PG10 = prestigediff($id,$t-9,$t-10);
			$PG11 = prestigediff($id,$t-10,$t-11);
			$PG12 = prestigediff($id,$t-11,$t-12);
			$PG13 = prestigediff($id,$t-12,$t-13);
			$PG14 = prestigediff($id,$t-13,$t-14);
			$PG15 = prestigediff($id,$t-14,$t-15);
			$PG16 = prestigediff($id,$t-15,$t-16);
			$PG17 = prestigediff($id,$t-16,$t-17);
			$PG18 = prestigediff($id,$t-17,$t-18);

			$SQL  = 'INSERT INTO tblMetrics (PlanetID,Turn,RD0,RD1,RD6,RD12,RD72,RD144,RD216,RD288,RD360,RD720,RD1440,RDFirst,';
			$SQL .= 'PD0,PD1,PD6,PD12,PD72,PD144,PD216,PD288,PD360,PD720,PD1440,PDNext,PA6,';
			$SQL .= 'PG1,PG2,PG3,PG4,PG5,PG6,PG7,PG8,PG9,PG10,PG11,PG12,PG13,PG14,PG15,PG16,PG17,PG18) VALUES (';
			$SQL .= '\'' . $id      . '\',';
			$SQL .= '\'' . $srTurn  . '\',';
			$SQL .= '\'' . $RD0     . '\',';
			$SQL .= '\'' . $RD1     . '\',';
			$SQL .= '\'' . $RD6     . '\',';
			$SQL .= '\'' . $RD12    . '\',';
			$SQL .= '\'' . $RD72    . '\',';
			$SQL .= '\'' . $RD144   . '\',';
			$SQL .= '\'' . $RD216   . '\',';
			$SQL .= '\'' . $RD288   . '\',';
			$SQL .= '\'' . $RD360   . '\',';
			$SQL .= '\'' . $RD720   . '\',';
			$SQL .= '\'' . $RD1440  . '\',';
			$SQL .= '\'' . $RDFirst . '\',';
			$SQL .= '\'' . $PD0     . '\',';
			$SQL .= '\'' . $PD1     . '\',';
			$SQL .= '\'' . $PD6     . '\',';
			$SQL .= '\'' . $PD12    . '\',';
			$SQL .= '\'' . $PD72    . '\',';
			$SQL .= '\'' . $PD144   . '\',';
			$SQL .= '\'' . $PD216   . '\',';
			$SQL .= '\'' . $PD288   . '\',';
			$SQL .= '\'' . $PD360   . '\',';
			$SQL .= '\'' . $PD720   . '\',';
			$SQL .= '\'' . $PD1440  . '\',';
			$SQL .= '\'' . $PDNext  . '\',';
			$SQL .= '\'' . $PA6     . '\',';
			$SQL .= '\'' . $PG1     . '\',';
			$SQL .= '\'' . $PG2     . '\',';
			$SQL .= '\'' . $PG3     . '\',';
			$SQL .= '\'' . $PG4     . '\',';
			$SQL .= '\'' . $PG5     . '\',';
			$SQL .= '\'' . $PG6     . '\',';
			$SQL .= '\'' . $PG7     . '\',';
			$SQL .= '\'' . $PG8     . '\',';
			$SQL .= '\'' . $PG9     . '\',';
			$SQL .= '\'' . $PG10    . '\',';
			$SQL .= '\'' . $PG11    . '\',';
			$SQL .= '\'' . $PG12    . '\',';
			$SQL .= '\'' . $PG13    . '\',';
			$SQL .= '\'' . $PG14    . '\',';
			$SQL .= '\'' . $PG15    . '\',';
			$SQL .= '\'' . $PG16    . '\',';
			$SQL .= '\'' . $PG17    . '\',';
			$SQL .= '\'' . $PG18    . '\')';
			//echo $SQL . $lf;
			$result = mysql_query($SQL);
		}
	}

	$umEnd = microtime_float();
	$elasped = ($umEnd - $umStart);
	echo 'Metrics procesed in ' . sprintf('%.2f',$elasped) . ' seconds.       ' . $lf;
	
	//--- PURGE METRICS --------------------------------------------------------------------------
	$pmStart = microtime_float();

	if ($dbTurn != $srTurn) 
	{
		$SQL = 'DELETE FROM tblMetrics WHERE Turn < ' . $dbTurn;
		$result = mysql_query($SQL);
	}

	$pmEnd = microtime_float();
	$elasped = ($pmEnd - $pmStart);
	echo 'Metrics purged in ' . sprintf('%.2f',$elasped) . ' seconds.       ' . $lf;

	//--- Update the control record if this is a new turn -----------------------------------------------------------
	if ($dbTurn != $srTurn)
	{
		$SQL  = 'UPDATE tblControl SET ';
		$SQL .= 'Turn = ' . $srTurn . ', ';
		$SQL .= 'UpdateDate = \'' . date('Y-m-d') . '\', ';
		$SQL .= 'UpdateTime = \'' . date('H:i:s') . '\' ';
		$SQL .= 'WHERE RoundNumber = ' . $dbRoundNumber;
		echo $SQL . $lf;
		$result = mysql_query($SQL);
		
		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}
	}
	
	$timeend = microtime_float();
	$elasped = ($timeend - $timestart);
	echo 'Page loaded in ' . sprintf('%.2f',$elasped) . ' seconds.' . $lf;

	//--- MICROTIME_FLOAT ------------------------------------------------------------------------
	function microtime_float() 
	{ 
	   list($usec, $sec) = explode(" ", microtime()); 
	   return ((float)$usec + (float)$sec); 
	} 

	//--- PDELTA ---------------------------------------------------------------------------------
	function pdelta($pid,$x,$mths) 
	{
		global $prestige;
		$t = $x - $mths;
		if (array_key_exists($t,$prestige[$pid])) 
		{
			return ($prestige[$pid][$x] - $prestige[$pid][$t]);
		} 
		else 
		{
			return 0;
		}
	}

	//--- PRESTIGE_AVERAGE ----------------------------------------------------------------------
	function prestige_average($pid,$turn,$mths)
	{
		global $prestige;
		$sum = 0;
		$cnt = 0;
		for ($x=1;$x<=99;$x++)
		{
			$t = $turn - $x;
			if ($t > 0)
			{
				if (array_key_exists($t,$prestige[$pid]))
				{
					$diff = pdelta($pid,$t,1); 
					if ($diff > 0)
					{
						$cnt++;
						$sum += $diff;
						if ($cnt >= $mths) $x = 9999;
					}
				}
			} 
			else
			{
				$x = 9999;
			}
		}
		if ($cnt > 0)
		{
			return sprintf('%d',($sum / $cnt));
		} else
		{
			return '0';
		}
	}


	//--- PRESTIGEDELTA -----------------------------------------------------------------
	function prestigedelta($pid,$mths)
	{
		global $srTurn;
		global $prestige;
		$t = $srTurn - $mths;
		if (array_key_exists($t,$prestige[$pid]))
		{
			return sprintf('%d',($prestige[$pid][$srTurn] - $prestige[$pid][$t])/$mths);
		} else
		{
			return '';
		}
	} 
	
	//--- PRESTIGEDIFF -----------------------------------------------------------------
	function prestigediff($pid,$t1,$t2)
	{
		global $prestige;
		if (array_key_exists($t1,$prestige[$pid]))
		{
			if (array_key_exists($t2,$prestige[$pid]))
			{
				return sprintf('%d',($prestige[$pid][$t1] - $prestige[$pid][$t2]));
			} 
			else
			{
				return '';
			}
		} 
		else
		{
			return '';
		}
	} 

	//--- PRESTIGENEXT -----------------------------------------------------------------
	function prestigenext($x)
	{
		global $srTurn;
		global $planets;
		global $prestige;
		global $current;
		global $pIndex;
		$t = $srTurn;
		if ($x > 1)
		{
			$key1 = $planets[$pIndex[$x-1]]['ID'];
			$key2 = $planets[$pIndex[$x-0]]['ID'];
			return $current[$key1] - $prestige[$key2][$t];
		}
		else
		{
			return '';
		}
	}
	
	//--- RANKDELTA ---------------------------------------------------------------------
	function rankdelta($pid,$mths)
	{
		global $srTurn;
		global $rankings;
		$t = $srTurn - $mths;
		if (array_key_exists($t,$rankings[$pid]))
		{
			return $rankings[$pid][$t] - $rankings[$pid][$srTurn];
		} 
		else
		{
			return '';
		}
	}
?>