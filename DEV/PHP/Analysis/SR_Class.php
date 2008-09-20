<?php
include("../connect_to_database.php");

class SR {
	var $dbconn;
	var $dbRoundNumber;
	var $dbRoundName;
	var $dbTurn;
	var $dbUpdateDate;
	var $dbUpdateTime;
	var $dbRoundLength;

	var $planets;
	var $pIndex;
	var $metrics;
	var $gain;
	var $loss;
	var $threat;
	var $conquest;
	var $planet_name;
	var $planet_ID;
	

	//--- CONSTRUCTOR ------------------------------------------------------------------------------------------
	function SR()
	{
		$SQL = 'SELECT * FROM tblControl WHERE Active = \'Y\' ';
		$result = mysql_query($SQL);
		
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		
		//Get roundnumber roundname etc
		while ($row = mysql_fetch_assoc($result)) 
		{
			$this->dbRoundNumber = $row['RoundNumber'];
			$this->dbRoundName   = $row['RoundName'];
			$this->dbTurn        = $row['Turn'];
			$this->dbUpdateDate  = $row['UpdateDate'];
			$this->dbUpdateTime  = $row['UpdateTime'];
			$this->dbRoundLength = $row['RoundLength'];
		}
		mysql_free_result($result);

		$this->planets = array();
		$this->pIndex  = array();
		$this->loss = array();
		$this->gain = array();

		//Get the planetlist
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
			$pOnLine  = $row['OnLine'];
			$pOnLineCount = $row['OnLineCount'];
			$pTurnCount   = $row['TurnCount'];
			$pFirstRank = $row['FirstRank'];

			$indx++;
			$this->pIndex[$indx] = $pName;

			$this->planets[$pName]['ID']      = $pID;
			$this->planets[$pName]['Name']    = $pName;
			$this->planets[$pName]['Leader']  = $pLeader;
			$this->planets[$pName]['Species'] = $pSpecies;
			$this->planets[$pName]['Rank']    = $pRank;
			$this->planets[$pName]['OnLine']  = $pOnLine;
			$this->planets[$pName]['OnLineCount'] = $pOnLineCount;
			$this->planets[$pName]['TurnCount']   = $pTurnCount;
			$this->planets[$pName]['FirstRank'] = $pFirstRank;
		}
		mysql_free_result($result);

		$this->metrics = array();

		$SQL = 'SELECT * FROM tblMetrics WHERE Turn = ' . $this->dbTurn . ' ';
		$result = mysql_query($SQL);
		
		if ( ! $result)
		{
			die('Invalid query: ' . mysql_error());
		}
		
		while ($row = mysql_fetch_assoc($result)) 
		{
			$id                              = $row['PlanetID'];
			$this->metrics[$id]['RD0']       = $row['RD0'];
			$this->metrics[$id]['RD1']       = $row['RD1'];
			$this->metrics[$id]['RD6']       = $row['RD6'];
			$this->metrics[$id]['RD12']      = $row['RD12'];
			$this->metrics[$id]['RD72']      = $row['RD72'];
			$this->metrics[$id]['RD144']     = $row['RD144'];
			$this->metrics[$id]['RD216']     = $row['RD216'];
			$this->metrics[$id]['RD288']     = $row['RD288'];
			$this->metrics[$id]['RD360']     = $row['RD360'];
			$this->metrics[$id]['RD720']     = $row['RD720'];
			$this->metrics[$id]['RD1440']    = $row['RD1440'];
			$this->metrics[$id]['RDFirst']   = $row['RDFirst'];
			$this->metrics[$id]['PD0']       = $row['PD0'];
			$this->metrics[$id]['PD1']       = $row['PD1'];
			$this->metrics[$id]['PD6']       = $row['PD6'];
			$this->metrics[$id]['PD12']      = $row['PD12'];
			$this->metrics[$id]['PD72']      = $row['PD72'];
			$this->metrics[$id]['PD144']     = $row['PD144'];
			$this->metrics[$id]['PD216']     = $row['PD216'];
			$this->metrics[$id]['PD288']     = $row['PD288'];
			$this->metrics[$id]['PD360']     = $row['PD360'];
			$this->metrics[$id]['PD720']     = $row['PD720'];
			$this->metrics[$id]['PD1440']    = $row['PD1440'];
			$this->metrics[$id]['PDNext']    = $row['PDNext'];
			$this->metrics[$id]['PA6']       = $row['PA6'];
			$this->metrics[$id]['PG1']       = $row['PG1'];
			$this->metrics[$id]['PG2']       = $row['PG2'];
			$this->metrics[$id]['PG3']       = $row['PG3'];
			$this->metrics[$id]['PG4']       = $row['PG4'];
			$this->metrics[$id]['PG5']       = $row['PG5'];
			$this->metrics[$id]['PG6']       = $row['PG6'];
			$this->metrics[$id]['PG7']       = $row['PG7'];
			$this->metrics[$id]['PG8']       = $row['PG8'];
			$this->metrics[$id]['PG9']       = $row['PG9'];
			$this->metrics[$id]['PG10']      = $row['PG10'];
			$this->metrics[$id]['PG11']      = $row['PG11'];
			$this->metrics[$id]['PG12']      = $row['PG12'];
			$this->metrics[$id]['PG13']      = $row['PG13'];
			$this->metrics[$id]['PG14']      = $row['PG14'];
			$this->metrics[$id]['PG15']      = $row['PG15'];
			$this->metrics[$id]['PG16']      = $row['PG16'];
			$this->metrics[$id]['PG17']      = $row['PG17'];
			$this->metrics[$id]['PG18']      = $row['PG18'];
		}
		mysql_free_result($result);

		$limit = $this->dbTurn - 144;
		if ($limit < 0 )
		{
			$limit = 0;
		}
		
		$SQL = 'SELECT * FROM tblGainLoss WHERE Turn > ' . $limit . ' ORDER BY Turn DESC';
		$result = mysql_query($SQL);
		
		if (!$result)
		{
			die('Invalid query: ' . mysql_error());
		}
		
		while ($row = mysql_fetch_assoc($result))
		{
			$pTurn     = $row['Turn'];
			$pType     = $row['Type'];
			$period = $this->dbTurn - $pTurn;
			$a = array();
			
			$a['period'] = $period;
			$a['planet'] = $row['Planet'];
			$a['result'] = number_format($row['Value']);
			$a['text']   = $row['Text'];
			
			if ($pType == 'Loss') 
			{
				$this->loss[] = $a;
			} 
			else 
			{
				$this->gain[] = $a;
			}
		}
		mysql_free_result($result);
		mysql_close();
	}
	
	//--- CALC_METRICS ------------------------------------------------------------------------
	function calc_metrics() 
	{
	}

	//--- CALC_THREATS -------------------------------------------------------------------------
	function calc_threats() 
	{
		$this->threat    = array();
		$this->conquest  = array();
		$t = $this->dbTurn;
		$pCount = count($this->pIndex);
		
		for ($x=1;$x<=$pCount;$x++)
		{
			$pkey = $this->pIndex[$x];
			$pid  = $this->planets[$pkey]['ID'];
			$pAvg = $this->metrics[$pid]['PA6'];
			
			// Threats
			for ($y=$x+1;$y<=$pCount;$y++)
			{
				$tkey = $this->pIndex[$y];
				$tid  = $this->planets[$tkey]['ID'];
				$tAvg = $this->metrics[$tid]['PA6'];
				
				if ($tAvg > $pAvg)
				{
					$pd = $this->metrics[$pid]['PD0'] - $this->metrics[$tid]['PD0'];
					$gain = ($tAvg - $pAvg);
					$mths = ceil($pd / $gain);
					
					if ($mths <= 144)
					{
						$a = array();
						$a['Planet'] = $this->planets[$tkey]['Name'];
						$a['Rank']   = $y;
						$a['Gain']   = number_format($gain);
						$a['Delta']  = number_format($pd);
						$a['Desc']   = 'overtake';
						$a['Mths']   = $mths . '';
						$this->threat[$x][] = $a;
					}
				}
			}
			
			// Conquests
			for ($y=$x-1;$y>0;$y--)
			{
				$tkey = $this->pIndex[$y];
				$tid  = $this->planets[$tkey]['ID'];
				$tAvg = $this->metrics[$tid]['PA6'];
				
				if ($pAvg > $tAvg)
				{
					$pd = $this->metrics[$tid]['PD0'] - $this->metrics[$pid]['PD0'];
					$gain = ($pAvg - $tAvg);
					$mths = ceil($pd / $gain);
					
					if ($mths <= 144) 
					{
						$a = array();
						$a['Planet'] = $this->planets[$tkey]['Name'];
						$a['Rank']   = $y;
						$a['Gain']   = number_format($gain);
						$a['Delta']  = number_format($pd);
						$a['Desc']   = 'succumb';
						$a['Mths']   = $mths . '';
						$this->conquest[$x][] = $a;
					}
				}
			}
		}
	}

	//--- CALC_ON_LINE ------------------------------------------------------------------------------
	function calconline($pid) 
	{
		$t = $this->planets[$pid]['TurnCount'];
		$y = $this->planets[$pid]['OnLineCount'];
		
		if ($t > 0) 
		{
			return sprintf('%0.1f',($y/$t) * 100) . '%';
		}
		else
		{
			return '0.0%';
		}
	}

	//--- DISPLAY_GAINLOSS ---------------------------------------------------------------------------
	function display_gainloss() 
	{
		$r  = '<table id=gainloss style={display:none;} width=99% align=center border=0 bgcolor=#FFB6C1>';
		$r .= '<tr><td width=50% class=sh>&nbsp;&nbsp;Gainers &amp; Losers</td>';
		$r .= '<td width=50% align=right><span class=xx><a href=./Help/Help.html target=SR_Help>HELP</a></span>&nbsp;</td></tr>';
		$r .= '<tr><td colspan=2>';
		$r .= '<table width=100% border=0 cellspacing=1 bgcolor=#FFFFFF>';
		$r .= '<tr>';
		$r .= '<td class=hc colspan=3><b>GAINERS</b></td>';
		$r .= '<td class=hc rowspan=2>Months<br>Ago</td>';
		$r .= '<td class=hc colspan=3><b>LOSERS</b></td>';
		$r .= '</tr>';
		$r .= '<tr>';
		$r .= '<td class=hc>Gain</td>';
		$r .= '<td class=hc>Description</td>';
		$r .= '<td class=hc>Planet</td>';
		$r .= '<td class=hc>Planet</td>';
		$r .= '<td class=hc>Description</td>';
		$r .= '<td class=hc>Loss</td>';
		$r .= '</tr>';

		$bgcolor = '#F5F5F5';
		for ($z=0;$z<=72;$z++)
		{
			$lp = array();
			$lv = array();
			$lt = array();
			$gp = array();
			$gv = array();
			$gt = array();
			
			foreach($this->gain as $key => $value) 
			{
				if ($value['period'] == $z)
				{
					$gp[] = substr($value['planet'],0,22);
					$gv[] = $value['result'];
					$gt[] = $value['text'];
				}
			}
			foreach($this->loss as $key => $value)
			{
				if ($value['period'] == $z)
				{
					$lp[] = substr($value['planet'],0,22);
					$lv[] = $value['result'];
					$lt[] = $value['text'];
				}
			}
			
			if (count($lp) > 0 || count($gp) > 0)
			{
				if ($bgcolor == '#F5F5F5')
				{
					$bgcolor = '#FFFFFF';
				} 
				else
				{
					$bgcolor = '#F5F5F5';
				}
				
				$r .= '<tr valign=top bgcolor=' . $bgcolor . '>';
				$r .= '<td class=xc>' . implode('<br>',$gv) . '</td>';
				$r .= '<td class=xc>' . implode('<br>',$gt) . '</td>';
				$r .= '<td class=xc>' . implode('<br>',$gp) . '</td>';
				$r .= '<td class=hxcb valign=middle>' . $z  . '</td>';
				$r .= '<td class=xc>' . implode('<br>',$lp) . '</td>';
				$r .= '<td class=xc>' . implode('<br>',$lt) . '</td>';
				$r .= '<td class=xc>' . implode('<br>',$lv) . '</td>';
				$r .= '</tr>';
			}
		}

		$r .= '</table>';
		$r .= '</td>';
		$r .= '</tr>';
		$r .= '</table>';
		return $r;
	}

	//--- DISPLAY_HEADER ------------------------------------------------------------------
	function display_header()
	{
		$heading = ' - ' . $this->dbRoundName;
		$rMonths = ($this->dbRoundLength * 12) - $this->dbTurn;
		$rYears  = floor($rMonths /12);
		$rString = sprintf('%d',$rYears) . ' Years, ' . sprintf('%d',$rMonths - ($rYears * 12)) . ' Months';

		$rMinutes = $rMonths * 20;
		$rHours   = floor($rMinutes / 60);
		$rDays    = floor($rHours / 24);
		$rHrs     = fmod($rHours,24);
		$rMins    = $rMinutes - ($rHours * 60);
		$rlRemaining = $rDays . ' Days ' . $rHrs . ' Hrs ' . $rMins . ' Mins';

		$strTime = $this->dbUpdateDate . ' ' . $this->dbUpdateTime;
		$updTime = strtotime($strTime);

		$r  = '<table width=100% border=0 bgcolor=#000000>';
		$r .= '<tr>';
		$r .= '<td ><span class=srxx>&nbsp;ANALYSIS: Stellar Realms</span><span class=srx>' . $heading . '</span></td></tr>';
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
		$r .= '<td class=xc>' . number_format($this->dbTurn) . '</td>';
		$r .= '<td class=xc>' . $this->gamedate($this->dbTurn) . '</td>';
		$r .= '<td class=xc>' . number_format($rMonths) . '</td>';
		$r .= '<td class=xc>' . $rString . '</td>';
		$r .= '<td class=xc>' . $rlRemaining . '</td>';
		$r .= '<td class=xc>' . gmdate('d M y | H:i:s T',$updTime) . '</td>';
		$r .= '</tr>';
		$r .= '</table>';
		$r .= '</td></tr>';
		$r .= '</table>';
		return $r;
	}

	//--- DISPLAY_MENU ----------------------------------------------------------------------
	function display_menu()
	{
		$tx  = '<table class=mtable width=100% border=0 cellspacing=5>';
		$tx .= '<tr>';
		$tx .= '<td class=menu onclick="ShowOverview()" onmouseover="glowObject(this)" onmouseout="dimObject(this)">Overview</td>';
		$tx .= '<td class=menu onclick="ShowGainLoss()" onmouseover="glowObject(this)" onmouseout="dimObject(this)">Gainers &amp; Losers</td>';
		$tx .= '<td class=menu onclick="ShowThreats()" onmouseover="glowObject(this)" onmouseout="dimObject(this)">Threats &amp; Conquests</td>';
		$tx .= '<td class=menu onclick="ShowRankHistory()" onmouseover="glowObject(this)" onmouseout="dimObject(this)">Rank History</td>';
		$tx .= '<td class=menu onclick="ShowPrestigeAverage()" onmouseover="glowObject(this)" onmouseout="dimObject(this)">Prestige Average</td>';
		$tx .= '<td class=menu onclick="ShowPrestigeHistory()" onmouseover="glowObject(this)" onmouseout="dimObject(this)">Prestige History</td>';
		$tx .= '</tr>';
		$tx .= '</table>';
		return $tx;
	}

	//--- DISPLAY_OVERVIEW -------------------------------------------------------------------
	function display_overview()
	{
		$r  = '<table id=overview width=99% align=center border=0 bgcolor=#7FFFD4>';
		$r .= '<tr><td width=50% class=sh>&nbsp;&nbsp;Overview</td>';
		$r .= '<td width=50% align=right><span class=xx><a href=./Help/Help.html target=SR_Help>HELP</a></span>&nbsp;</td></tr>';
		$r .= '<tr><td colspan=2>';
		$r .= '<table width=100% cellspacing=1 bgcolor=#ffffff>';
		$r .= '<tr>';
		$r .= '<td class=hc colspan=5>Rank Change</td>';
		$r .= '<td class=hc colspan=6>Planet</td>';
		$r .= '<td class=hc colspan=3>Prestige</td>';
		$r .= '<td class=hc colspan=4>Averages</td>';
		$r .= '</tr>';
		$r .= '<tr>';
		$r .= '<td class=hc>1 mth</td>';
		$r .= '<td class=hc>6 mth</td>';
		$r .= '<td class=hc>1 yr</td>';
		$r .= '<td class=hc>6 yr</td>';
		$r .= '<td class=hc>12 yr</td>';
		$r .= '<td class=hh>Rank</td>';
		$r .= '<td class=hh>Planet</td>';
		$r .= '<td class=hh>Leader</td>';
		$r .= '<td class=hc>Detail</td>';
		//$r .= '<td class=hc colspan=2>OnLine</td>';
		$r .= '<td class=hc>Prestige</td>';
		$r .= '<td class=hc>Delta</td>';
		$r .= '<td class=hc>1 mth</td>';
		$r .= '<td class=hc>6 mth</td>';
		$r .= '<td class=hc>1 yr</td>';
		$r .= '<td class=hc>6 yr</td>';
		$r .= '<td class=hc>12 yr</td>';
		$r .= '</tr>';

		$bgcolor = '#F5F5F5';
		$pCount = count($this->pIndex);
		
		for ($x=1;$x<=$pCount;$x++)
		{
			$key     = $this->pIndex[$x];
			$id      = $this->planets[$key]['ID'];
			$name    = $this->planets[$key]['Name'];
			$leader  = $this->planets[$key]['Leader'];
			$species = $this->planets[$key]['Species'];
			$rank    = $this->planets[$key]['Rank'];
			$online  = $this->planets[$key]['OnLine'];

			$onlinepercent = $this->calconline($name);
			$onlineFlag = $this->onlinestatus($key);

			$r0    = $this->metrics[$id]['RD0'];
			$r1    = $this->metrics[$id]['RD1'];
			$r6    = $this->metrics[$id]['RD6'];
			$r12   = $this->metrics[$id]['RD12'];
			$r72   = $this->metrics[$id]['RD72'];
			$r144  = $this->metrics[$id]['RD144'];
			
			$p0    = $this->metrics[$id]['PD0'];
			$pd    = $this->metrics[$id]['PDNext'];
			$p1    = $this->metrics[$id]['PD1'];
			$p6    = $this->metrics[$id]['PD6'];
			$p12   = $this->metrics[$id]['PD12'];
			$p72   = $this->metrics[$id]['PD72'];
			$p144  = $this->metrics[$id]['PD144'];
			
			if ($name == $this->planet_ID)
			{
				$r .= '<tr bgcolor=#FFFF00>';
			} 
			else
			{
				if ($bgcolor == '#F5F5F5')
				{
					$bgcolor = '#FFFFFF';
				} 
				else
				{
					$bgcolor = '#F5F5F5';
				}
				$r .= '<tr bgcolor=' . $bgcolor . '>';
			}

			$r .= '<td class=xc>'    . $r1                   . '</td>';
			$r .= '<td class=xc>'    . $r6                   . '</td>';
			$r .= '<td class=xc>'    . $r12                  . '</td>';
			$r .= '<td class=xc>'    . $r72                  . '</td>';
			$r .= '<td class=xc>'    . $r144                 . '</td>';
			$r .= '<td class=hxcb>'  . $rank                 . '</td>';
			$r .= '<td class=hxl>'   . substr($name,0,22) . "   (" . $species . ")" . '</td>';
			$r .= '<td class=xx>'    . $leader               . '</td>';
			$r .= '<td class=xc><a href=./PlanetDetail.php?Planet=' . urlencode($name) . ' target=PlanetDetail>Detail</a></td>';
			//$r .= '<td class=xx>'    . $onlinepercent        . '</td>';
			//$r .= '<td class=xc>'    . $onlineFlag           . '</td>';
			$r .= '<td class=xr>'    . number_format($p0)    . '</td>';
			$r .= '<td class=xrdb>'  . number_format($pd)    . '</td>';
			$r .= '<td class=xr>'    . number_format($p1)    . '</td>';
			$r .= '<td class=xr>'    . number_format($p6)    . '</td>';
			$r .= '<td class=xr>'    . number_format($p12)   . '</td>';
			$r .= '<td class=xr>'    . number_format($p72)   . '</td>';
			$r .= '<td class=xr>'    . number_format($p144)  . '</td>';
			$r .= '</tr>';
		}
		$r .= '</table>';
		$r .= '</td></tr>';
		$r .= '</table>';
		return $r;
	}

	//--- DISPLAY_PRESTIGE_AVERAGE ------------------------------------------------------------------
	function display_prestige_average()
	{
		$r  = '<table id=prestigeaverage style={display:none;} width=99% align=center border=0 bgcolor=#E6E6FA>';
		$r .= '<tr><td width=50% class=sh>&nbsp;&nbsp;Prestige Average</td>';
		$r .= '<td width=50% align=right><span class=xx><a href=./Help/Help.html target=SR_Help>HELP</a></span>&nbsp;</td></tr>';
		$r .= '<tr><td colspan=2>';
		$r .= '<table width=100% border=0 cellspacing=1 bgcolor=#FFFFFF>';
		
		$r .= '<tr>';
		$r .= '<td class=hc>Rank</td>';
		$r .= '<td class=hh>Planet</td>';
		$r .= '<td class=hh>Leader</td>';
		$r .= '<td class=hh>Species</td>';
		$r .= '<td class=hc>Detail</td>';
		
		$r .= '<td class=hc>1m</td>';
		$r .= '<td class=hc>6m</td>';
		$r .= '<td class=hc>1y</td>';
		$r .= '<td class=hc>6y</td>';
		$r .= '<td class=hc>12y</td>';
		$r .= '<td class=hc>18y</td>';
		$r .= '<td class=hc>24y</td>';
		$r .= '<td class=hc>30y</td>';
		$r .= '<td class=hc>60y</td>';
		$r .= '<td class=hc>120y</td>';
		$r .= '</tr>';

		$bgcolor = '#F5F5F5';
		$pCount = count($this->pIndex);
		
		for ($x=1;$x<=$pCount;$x++)
		{
			$key     = $this->pIndex[$x];
			$id      = $this->planets[$key]['ID'];
			$name    = $this->planets[$key]['Name'];
			$leader  = $this->planets[$key]['Leader'];
			$species = $this->planets[$key]['Species'];
			$rank    = $this->planets[$key]['Rank'];

			if ($name == $this->planet_ID)
			{
				$r .= '<tr bgcolor=#FFFF00>';
			}
			else
			{
				if ($bgcolor == '#F5F5F5')
				{
					$bgcolor = '#FFFFFF';
				} 
				else 
				{
					$bgcolor = '#F5F5F5';
				}
				$r .= '<tr bgcolor=' . $bgcolor . '>';
			}

			$r .= '<td class=hxcb>' . $rank                        . '</td>';
			$r .= '<td class=hxl>'  . substr($name,0,22) . '</td>';
			$r .= '<td class=xx>'   . $leader                      . '</td>';
			$r .= '<td class=xx>'   . $species                     . '</td>';
			$r .= '<td class=xc><a href=./PlanetDetail.php?Planet=' . urlencode($name) . ' target=PlanetDetail>Detail</a></td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD1'])    . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD6'])    . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD12'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD72'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD144'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD216'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD288'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD360'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD720'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PD1440']) . '</td>';
			$r .= '</tr>';
		}	

		$r .= '</table>';
		$r .= '</td>';
		$r .= '</tr>';
		$r .= '</table>';
		return $r;
	}

	//--- DISPLAY_PRESTIGE_HISTORY ------------------------------------------------------------------
	function display_prestige_history() 
	{
		$r  = '<table id=prestigehistory style={display:none;} width=99% align=center border=0 bgcolor=#00CED1>';
		$r .= '<tr><td width=50% class=sh>&nbsp;&nbsp;Prestige History</td>';
		$r .= '<td width=50% align=right><span class=xx><a href=./Help/Help.html target=SR_Help>HELP</a></span>&nbsp;</td></tr>';
		$r .= '<tr><td colspan=2>';
		$r .= '<table width=100% border=0 cellspacing=1 bgcolor=#FFFFFF>';
		
		$r .= '<tr>';
		$r .= '<td class=hc>Rank</td>';
		$r .= '<td class=hh>Planet</td>';
		$r .= '<td class=hc>Detail</td>';
		
		$r .= '<td class=hc>1m</td>';
		$r .= '<td class=hc>2m</td>';
		$r .= '<td class=hc>3m</td>';
		$r .= '<td class=hc>4m</td>';
		$r .= '<td class=hc>5m</td>';
		$r .= '<td class=hc>6m</td>';
		$r .= '<td class=hc>7m</td>';
		$r .= '<td class=hc>8m</td>';
		$r .= '<td class=hc>9m</td>';
		$r .= '<td class=hc>10m</td>';
		$r .= '<td class=hc>11m</td>';
		$r .= '<td class=hc>12m</td>';
		$r .= '<td class=hc>13m</td>';
		$r .= '<td class=hc>14m</td>';
		$r .= '<td class=hc>15m</td>';
		$r .= '<td class=hc>16m</td>';
		$r .= '<td class=hc>17m</td>';
		$r .= '<td class=hc>18m</td>';
		$r .= '</tr>';

		$bgcolor = '#F5F5F5';
		$t = $this->dbTurn;
		$pCount = count($this->pIndex);
		for ($x=1;$x<=$pCount;$x++)
		{
			$key     = $this->pIndex[$x];
			$id      = $this->planets[$key]['ID'];
			$name    = $this->planets[$key]['Name'];
			$leader  = $this->planets[$key]['Leader'];
			$species = $this->planets[$key]['Species'];
			$rank    = $this->planets[$key]['Rank'];

			if ($name == $this->planet_ID) 
			{
				$r .= '<tr bgcolor=#FFFF00>';
			} 
			else 
			{
				if ($bgcolor == '#F5F5F5') 
				{
					$bgcolor = '#FFFFFF';
				} 
				else 
				{
					$bgcolor = '#F5F5F5';
				}
				$r .= '<tr bgcolor=' . $bgcolor . '>';
			}

			$r .= '<td class=hxcb>' . $rank                        . '</td>';
			$r .= '<td class=hxl>'  . substr($name,0,22) . ' (' . $species . ')' . '</td>';
			$r .= '<td class=xc><a href=./PlanetDetail.php?Planet=' . urlencode($name) . ' target=PlanetDetail>Detail</a></td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG1'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG2'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG3'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG4'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG5'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG6'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG7'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG8'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG9'])   . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG10'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG11'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG12'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG13'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG14'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG15'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG16'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG17'])  . '</td>';
			$r .= '<td class=xr>'   . number_format($this->metrics[$id]['PG18'])  . '</td>';
			$r .= '</tr>';
		}	

		$r .= '</table>';
		$r .= '</td>';
		$r .= '</tr>';
		$r .= '</table>';
		return $r;
	}


	//--- DISPLAY_RANK_HISTORY -----------------------------------------------------------------------

	function display_rankhistory() 
	{
		$r  = '<table id=rankhistory style={display:none;} width=99% align=center border=0 bgcolor=#00FFFF>';
		$r .= '<tr><td width=50% class=sh>&nbsp;&nbsp;Rank History</td>';
		$r .= '<td width=50% align=right><span class=xx><a href=./Help/Help.html target=SR_Help>HELP</a></span>&nbsp;</td></tr>';
		$r .= '<tr><td colspan=2>';
		$r .= '<table width=100% border=0 cellspacing=1 bgcolor=#FFFFFF>';
		
		$r .= '<tr>';
		$r .= '<td class=hc>Rank</td>';
		$r .= '<td class=hh>Planet</td>';
		$r .= '<td class=hh>Leader</td>';
		$r .= '<td class=hh>Species</td>';
		$r .= '<td class=hc>Detail</td>';
		
		$r .= '<td class=hc>1 mth</td>';
		$r .= '<td class=hc>6 mth</td>';
		$r .= '<td class=hc>1 yr</td>';
		$r .= '<td class=hc>6 yr</td>';
		$r .= '<td class=hc>12 yr</td>';
		$r .= '<td class=hc>18 yr</td>';
		$r .= '<td class=hc>24 yr</td>';
		$r .= '<td class=hc>30 yr</td>';
		$r .= '<td class=hc>60 yr</td>';
		$r .= '<td class=hc>120 yr</td>';
		$r .= '<td class=hc>First</td>';
		$r .= '</tr>';

		$bgcolor = '#F5F5F5';
		$pCount = count($this->pIndex);
		
		for ($x=1;$x<=$pCount;$x++) 
		{
			$key     = $this->pIndex[$x];
			$id      = $this->planets[$key]['ID'];
			$name    = $this->planets[$key]['Name'];
			$leader  = $this->planets[$key]['Leader'];
			$species = $this->planets[$key]['Species'];
			$rank    = $this->planets[$key]['Rank'];

			if ($name == $this->planet_ID) 
			{
				$r .= '<tr bgcolor=#FFFF00>';
			}
			else 
			{
				if ($bgcolor == '#F5F5F5') 
				{
					$bgcolor = '#FFFFFF';
				}
				else
				{
					$bgcolor = '#F5F5F5';
				}
				$r .= '<tr bgcolor=' . $bgcolor . '>';
			}

			$r .= '<td class=hxcb>' . $rank                          . '</td>';
			$r .= '<td class=hxl>'  . substr($name,0,22) . '</td>';
			$r .= '<td class=xx>'   . $leader                        . '</td>';
			$r .= '<td class=xx>'   . $species                       . '</td>';
			$r .= '<td class=xc><a href=./PlanetDetail.php?Planet='  . urlencode($name) . ' target=PlanetDetail>Detail</a></td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD1']     . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD6']     . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD12']    . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD72']    . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD144']   . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD216']   . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD288']   . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD360']   . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD720']   . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RD1440']  . '</td>';
			$r .= '<td class=xr>'   . $this->metrics[$id]['RDFirst'] . '</td>';
			$r .= '</tr>';
		}	
		
		$r .= '</table>';
		$r .= '</td>';
		$r .= '</tr>';
		$r .= '</table>';
		return $r;
	}
	
	//--- DISPLAY_THREATS --------------------------------------------------------------------
	function display_threats()
	{
		$r  = '<table id=threats style={display:none;} width=99% align=center border=0 bgcolor=#FFD700>';
		$r .= '<tr><td width=50% class=sh>&nbsp;&nbsp;Threats and Conquests</td>';
		$r .= '<td width=50% align=right><span class=xx><a href=./Help/Help.html target=SR_Help>HELP</a></span>&nbsp;</td></tr>';
		$r .= '<tr><td colspan=2>';
		$r .= '<table width=100% border=0 cellspacing=1 bgcolor=#FFFFFF>';

		$r .= '<tr>';
		$r .= '<td class=hc colspan=6><b>THREATS</b></td>';
		$r .= '<td class=hc colspan=3 rowspan=2><b>PLANET</b></td>';
		$r .= '<td class=hc colspan=6><b>CONQUESTS</b></td>';
		$r .= '</tr>';

		$r .= '<tr>';
		$r .= '<td class=hc>Planet</td>';
		$r .= '<td class=hc>Rank</td>';
		$r .= '<td class=hc>Delta</td>';
		$r .= '<td class=hc>Gain</td>';
		$r .= '<td class=hc>Description</td>';
		$r .= '<td class=hc>Months</td>';

		$r .= '<td class=hc>Planet</td>';
		$r .= '<td class=hc>Rank</td>';
		$r .= '<td class=hc>Delta</td>';
		$r .= '<td class=hc>Gain</td>';
		$r .= '<td class=hc>Description</td>';
		$r .= '<td class=hc>Months</td>';
		$r .= '</tr>';

		$bgcolor = '#F5F5F5';
		$pCount = count($this->pIndex);
		
		for ($x=1;$x<=$pCount;$x++)
		{
			$key     = $this->pIndex[$x];
			$id      = $this->planets[$key]['ID'];
			$name    = $this->planets[$key]['Name'];
			$leader  = $this->planets[$key]['Leader'];
			$species = $this->planets[$key]['Species'];
			$rank    = $this->planets[$key]['Rank'];

			$tp = array();
			$tr = array();
			$tf = array();
			$tg = array();
			$td = array();
			$tm = array();
			$cp = array();
			$cr = array();
			$cf = array();
			$cg = array();
			$cd = array();
			$cm = array();

			if(array_key_exists($x,$this->threat))
			{
				foreach($this->threat[$x] as $key => $value)
				{
					$tp[] = substr($value['Planet'],0,22);
					$tr[] = $value['Rank'];
					$tf[] = $value['Delta'];
					$tg[] = $value['Gain'];
					$td[] = $value['Desc'];
					$tm[] = $value['Mths'];
				}
			} 
			else
			{
				$td[] = 'No threats';
			}

			if(array_key_exists($x,$this->conquest))
			{
				foreach($this->conquest[$x] as $key => $value)
				{
					$cp[] = substr($value['Planet'],0,22);
					$cr[] = $value['Rank'];
					$cf[] = $value['Delta'];
					$cg[] = $value['Gain'];
					$cd[] = $value['Desc'];
					$cm[] = $value['Mths'];
				}
			}
			else
			{
				$cd[] = 'No conquests';
			}

			if ($name == $this->planet_ID)
			{
				$r .= '<tr bgcolor=#FFFF00>';
			} 
			else
			{
				if ($bgcolor == '#F5F5F5')
				{
					$bgcolor = '#FFFFFF';
				}
				else
				{
					$bgcolor = '#F5F5F5';
				}
				$r .= '<tr bgcolor=' . $bgcolor . '>';
			}

			$r .= '<td class=xc>' . implode('<br>',$tp) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$tr) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$tf) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$tg) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$td) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$tm) . '</td>';
			$r .= '<td class=hc>' . $x . '</td>';
			$r .= '<td class=hc align=center valign=middle><font size=3>' . substr($name,0,22) . '</font></td>';
			$r .= '<td class=hc>' . $x . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$cp) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$cr) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$cf) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$cg) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$cd) . '</td>';
			$r .= '<td class=xc>' . implode('<br>',$cm) . '</td>';
			$r .= '</tr>';
		}	
		
		$r .= '</table>';
		$r .= '</td>';
		$r .= '</tr>';
		$r .= '</table>';
		return $r;
	}

	function gamedate($t) 
	{
		$eYears = floor($t / 12);
		$eMonths = fmod($t,12);
		$gDate = mktime(0,0,0,$eMonths+1,1,2000);
		$curMonth = date('F',$gDate);
		$curYear  = date('Y',$gDate) + $eYears + 700;
		return $curMonth . ' ' . $curYear;
	}

	function onlinestatus($key)
	{
		if ($this->planets[$key]['OnLine'] == 'y')
		{
			return 'Yes';
		}
		else
		{
			return 'No';
		}
	}
}
?>
