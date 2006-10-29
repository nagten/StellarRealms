<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Planet Stats</title>
<style type="text/css">
<!--
body {
	background-color: #b0c4de;
}
.style1 {font-size: 13px}
-->
</style>
</head>

<body>
<?php
include("../variables.php");

if( !is_null( $_POST ) )
{
    if ($_POST["action"] == "uploadstats")
    {
      submitstats($_POST);
    }
    else
    {
      //echo "generatehtml";
      generatehtmltable();
    }
}

function generatehtmltable()
{
  global $mysql_server;
  global $mysql_user;
  global $mysql_password;
  global $mysql_db;

  $dbcnx = @mysql_connect($mysql_server, $mysql_user, $mysql_password);

  if (!$dbcnx)
  {
    echo "<p>Unable to connect to the database server.</p>";
    exit();
  }
  else
  {
    if (!@mysql_select_db($mysql_db))
    {
        echo "<p>Unable to locate the " . $mysql_db . " database.</p>";
        exit();
    }
    else
    {
      /* set the allowed order by columns */
      $default_sort = 'fprestige';
     // $allowed_order = array ('fuser', 'fprestige');

      /* if order is not set, or it is not in the allowed
      list, then set it to a default value. Otherwise,
      set it to what was passed in. */
      if (!isset ($_GET['order']) )   //|| !in_array ($_GET['order'], $allowed_order)
      {
          $order = $default_sort;;
      }
      else
      {
          $order = $_GET['order'];
      }

      if (!isset ($_GET['sortOrder']))
      {
          $sortOrder = "DESC";
      }
      else
      {
          $sortOrder = $_GET['sortOrder'];
      }

      if ($order == "fpopspace")
      {
        $strSqlString = "SELECT *, fpopmaximum - fpop AS fpopspace FROM tblsrstats ORDER BY fpopspace $sortOrder";
      }
      else if ($order == "fmatspace")
          {
            $strSqlString = "SELECT *, fmatmaximum - ffood - ffuel - fmetals - fradioactives AS fmatspace FROM tblsrstats ORDER BY fmatspace $sortOrder";
          }
          else
          {
            $strSqlString = "SELECT * FROM tblsrstats ORDER BY $order $sortOrder";
          }

      $result = @mysql_query($strSqlString);

      if (!$result)
      {
        echo "<p>Error performing query: " . mysql_error() . " </p>";
        exit();
      }
      else
      {
        $sortOrder =  $_GET['sortOrder'] == 'ASC' ? 'DESC' : 'ASC';
        //table header
        echo "<table width=\"1030\" border=\"0\" >\n";
        echo "<tr bgcolor=\"#999999\">\n";
        echo "<td width=\"77\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fplanetname&sortOrder=$sortOrder\">Name</a></span></td>\n";
        echo "<td width=\"57\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fuser&sortOrder=$sortOrder\">User</a></span></td>\n";
        echo "<td width=\"43\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fprestige&sortOrder=$sortOrder\">Prestige</a></span></td>\n";
        echo "<td width=\"34\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fprestigedelta&sortOrder=$sortOrder\">Pr&Delta;</a></span></td>\n";
        echo "<td width=\"22\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fprestigerank&sortOrder=$sortOrder\">Pr&nbsp;R</a></span></td>\n";
        echo "<td width=\"42\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fcredits&sortOrder=$sortOrder\">Credits</a></span></td>\n";
        echo "<td width=\"28\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fcreditsdelta&sortOrder=$sortOrder\">Cr&Delta;</a></span></td>\n";
        echo "<td width=\"23\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fcreditsrank&sortOrder=$sortOrder\">Cr&nbsp;R</a></span></td>\n";
        echo "<td width=\"32\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=ftaxrate&sortOrder=$sortOrder\">Tax</a></span></td>\n";
        echo "<td width=\"48\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fpop&sortOrder=$sortOrder\">Pop</a></span></td>\n";
        echo "<td width=\"30\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fpopdelta&sortOrder=$sortOrder\">Pop&Delta;</a></span></td>\n";
        echo "<td width=\"32\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fpoprank&sortOrder=$sortOrder\">Pop&nbsp;R</a></span></td>\n";
        echo "<td width=\"56\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fpopspace&sortOrder=$sortOrder\">Pop&nbsp;Space</a></span></td>\n";
        echo "<td width=\"30\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=ffood&sortOrder=$sortOrder\">Fd</a></span></td>\n";
        echo "<td width=\"30\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=ffuel&sortOrder=$sortOrder\">Fu</a></span></td>\n";
        echo "<td width=\"30\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fmetals&sortOrder=$sortOrder\">Mtl</a></span></td>\n";
        echo "<td width=\"30\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fradioactives&sortOrder=$sortOrder\">Rad</a></span></td>\n";
        echo "<td width=\"24\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=ffooddelta&sortOrder=$sortOrder\">Fd&Delta;</a></span></td>\n";
        echo "<td width=\"27\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fmetalsdelta&sortOrder=$sortOrder\">Mtl&Delta;</a></span></td>\n";
        echo "<td width=\"38\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fmatspace&sortOrder=$sortOrder\">Mat&nbsp;S</a></span></td>\n";
        echo "<td width=\"50\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fprojects&sortOrder=$sortOrder\">Q's Used </a></span></td>\n";
        echo "<td width=\"23\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fconstruction&sortOrder=$sortOrder\">Con</a></span></td>\n";
        echo "<td width=\"19\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=fwealth&sortOrder=$sortOrder\">Wea</a></span></td>\n";
        echo "<td width=\"27\"><span class=style1><a href=\"{$_SERVER['PHP_SELF']}?order=falertlevel&sortOrder=$sortOrder\">Alt</a></span></td>\n";
        echo "<td width=\"24\"><span class=style1>Age</span></td>\n";
        echo "</tr>\n";

        //variables for summary row
        $sumCredits = 0;
        $sumCreditsDelta = 0;
        $sumTaxRate = 0;
        $sumPop = 0;
        $sumPopDelta = 0;
        $sumPopSpace = 0; $sumFood = 0; $sumFuel = 0;
        $sumMetals = 0; $sumRads = 0; $sumFoodDelta = 0; $sumMetalsDelta = 0;
        $sumMatSpace = 0; $sumProjects = 0; $sumProjectsMaximum = 0; $sumWealth = 0;
        $i = 0;

        for($i = 0; $i < $row = mysql_fetch_array($result); $i++)
        {
          $MatSpace = $row['fmatmaximum'] - ($row['ffood'] + $row['ffuel'] + $row['fmetals'] + $row['fradioactives']);
          $PopSpace = $row['fpopmaximum'] - $row['fpop'];
          $current_date = date("m-d-Y H:i:s");
          if ($row['fdate'] != "")
          {
            $Age = TurnAge($row['fdate'], $current_date);
          }
          else
          {
            $Age = 0;
          }

          //Alternate row color
          if ($i%2)
          {
            echo "<tr bgcolor=\"#DCDCDC\">\n";
          }
          else
          {
            echo "<tr bgcolor=\"#f5f5f5\">\n";
          }

          echo "<td><span class=style1>" . $row['fplanetname'] . "</span></td>\n";
          echo "<td><span class=style1>" . $row['fuser'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fprestige'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fprestigedelta'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fprestigerank'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fcredits'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fcreditsdelta'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fcreditsrank'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['ftaxrate'] . "%</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fpop'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fpopdelta'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fpoprank'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $PopSpace . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['ffood'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['ffuel'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fmetals'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fradioactives'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['ffooddelta'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fmetalsdelta'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $MatSpace . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fprojects'] . "/" . $row['fprojectsmaximum'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fconstruction'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['fwealth'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $row['falertlevel'] . "</span></td>\n";
          echo "<td align='right'><span class=style1>" . $Age . "</span></td>\n";

          $sumCredits = $sumCredits + $row['fcredits'];
          $sumCreditsDelta = $sumCreditsDelta + $row['fcreditsdelta'];
          $sumTaxRate = $sumTaxRate + $row['ftaxrate'];
          $sumPop = $sumPop + $row['fpop'];
          $sumPopDelta = $sumPopDelta + $row['fpopdelta'] ;
          $sumPopSpace = $sumPopSpace + $PopSpace;
          $sumFood = $sumFood + $row['ffood'];
          $sumFuel = $sumFuel + $row['ffuel'];
          $sumMetals = $sumMetals + $row['fmetals'];
          $sumRads = $sumRads + $row['fradioactives'];
          $sumFoodDelta = $sumFoodDelta + $row['ffooddelta'];
          $sumMetalsDelta = $sumMetalsDelta + $row['fmetalsdelta'];
          $sumMatSpace = $sumMatSpace + $MatSpace;
          $sumProjects = $sumProjects + $row['fprojects'];
          $sumProjectsMaximum = $sumProjectsMaximum + $row['fprojectsmaximum'];
          $sumWealth = $sumWealth + $row['fwealth'];
        }

        //Summary row
        echo "<tr bgcolor=\"#DCDCDC\">\n";
        echo "<td><span class=style1>Summary</td>\n";
        echo "<td><span class=style1></td>\n";
        echo "<td><span class=style1></td>\n";
        echo "<td><span class=style1></td>\n";
        echo "<td><span class=style1></td>\n";
        echo "<td align='right'><span class=style1>" . $sumCredits . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumCreditsDelta . "</span></td>\n";
        echo "<td align='right'><span class=style1></span></td>\n";
        echo "<td align='right'><span class=style1>" . round($sumTaxRate / $i,2)  . "%</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumPop . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumPopDelta . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $row['fpoprank'] . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumPopSpace . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumFood . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumFuel . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumMetals . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumRads . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumFoodDelta . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumMetalsDelta . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumMatSpace . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $sumProjects . "/" . $sumProjectsMaximum . "</span></td>\n";
        echo "<td align='right'><span class=style1>" . $row['fconstruction'] . "</span></td>\n";
        echo "<td align='right'><span class=style1></span></td>\n";   //echo "<td>" . round($sumWealth / $i,2) . "</td>\n";
        echo "<td align='right'><span class=style1></span></td>\n";
        echo "<td align='right'><span class=style1></span></td>\n";
        echo "</table>";
      }
    }
  }
}

function submitstats($post_vars_string)
{
  global $mysql_server;
  global $mysql_user;
  global $mysql_password;
  global $mysql_db;

  $dbcnx = @mysql_connect($mysql_server, $mysql_user, $mysql_password);

  if (!$dbcnx)
  {
    echo "<p>Unable to connect to the database server at this time.</p>";
    exit();
  }
  else
  {
    if (!@mysql_select_db($mysql_db))
    {
    	echo "<p>Unable to locate the " . $mysql_db . " database at this time.</p>";
        exit();
    }
    else
    {
      $mat = $post_vars_string["mat"];
      $matdelta = $post_vars_string["matdelta"];
      $bonuses = $post_vars_string["bonuses"];
      $date = date("m-d-Y H:i:s"); //date for the age column
      //echo $date;

      $sqlstring = "UPDATE tblsrstats SET
          ffood = " . $mat[0] .",
          ffooddelta = " . $matdelta[0] .",
          ffuel = " . $mat[1] .",
          ffueldelta = " . $matdelta[1] .",
          fmetals = " . $mat[2] .",
          fmetalsdelta = " . $matdelta[2] .",
          fradioactives = " . $mat[3] .",
          fradioactivesdelta = " . $matdelta[3] .",
          fmatmaximum = " . $post_vars_string["matmaximum"] .",
          fcredits = " . $post_vars_string["credits"] .",
          fcreditsdelta = " . $post_vars_string["creditsdelta"] .",
          fcreditsrank = " . $post_vars_string["creditsrank"] .",
          ftaxrate = " . $post_vars_string["taxrate"] .",
          fprestige = " . $post_vars_string["prestige"] .",
          fprestigedelta = " . $post_vars_string["prestigedelta"] .",
          fprestigerank = " . $post_vars_string["prestigerank"] .",
          fpop = " . $post_vars_string["pop"] .",
          fpopdelta = " . $post_vars_string["popdelta"] .",
          fpoprank = " . $post_vars_string["poprank"] .",
          fpopmaximum = " . $post_vars_string["popmaximum"] .",
          fprojects = " . $post_vars_string["projects"] .",
          fprojectsmaximum = " . $post_vars_string["projectsmaximum"] .",
          fbattleslost = " . $post_vars_string["battleslost"] .",
          falertlevel = " . $post_vars_string["alertlevel"] .",
          fconstruction = " . $bonuses[0] .",
          fresearch = " . $bonuses[1] .",
          fdiplomacy = " . $bonuses[2] .",
          foffense = " . $bonuses[3] .",
          fdefense = " . $bonuses[4] .",
          fwealth = " . $bonuses[5] .",
          freproduction = " . $bonuses[6] .",
          fmaterials = " . $bonuses[7] .",
          fdurability = " . $bonuses[8] .",
          fspeed = " . $bonuses[9] .",
          fsensors = " . $bonuses[10] .",
          fstealth = " . $bonuses[11] .",
          fmaintenance = " . $bonuses[12] .",
          fplanet = " . $bonuses[13] .",
          fdate = '" . $date ."'
          WHERE fplanetname LIKE '" . $post_vars_string["planetname"] ."'";

      if (@mysql_query($sqlstring))
      {
        //echo '<p>Succes</p>';
        generatehtmltable();
      }
      else
      {
        echo "<p>Error inserting data: " . mysql_error() . "</p>";
      }
    }
  }
}

function TurnAge($start_date, $end_date)
{
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
?>
</body>
</html>