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
-->
</style>
</head>

<body>
<?php

//Set global variables to connect to MySQL DB
$mysql_server = "localhost";
$mysql_user = "root";
$mysql_password = "R0it";
$mysql_db = "sr";

if( !is_null( $_POST ) )
{
    if ($_POST["action"] == "uploadstats")
    {
      //echo "uploadstats";
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
    echo ('<p>Unable to connect to the database server.</p>' );
    exit();
  }
  else
  {
    if (!@mysql_select_db($mysql_db))
    {
        exit('<p>Unable to locate the sr database.</p>');
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

      //echo $order;
      //TODO debug remov
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

      //echo $strSqlString; //TODO debug remove

      $result = @mysql_query($strSqlString);

      if (!$result)
      {
        exit('<p>Error performing query:  mysql_error() </p>');
      }
      else
      {
        $sortOrder =  $_GET['sortOrder'] == 'ASC' ? 'DESC' : 'ASC';
        //table header
        echo "<table width=\"1206\" border=\"1\" >\n";
        echo "<tr bgcolor=\"#999999\">\n";
        echo "<td width=\"56\"><a href=\"{$_SERVER['PHP_SELF']}?order=fplanetname&sortOrder=$sortOrder\">Name</a></td>\n";
        echo "<td width=\"39\"><a href=\"{$_SERVER['PHP_SELF']}?order=fuser&sortOrder=$sortOrder\">User</a></td>\n";
        echo "<td width=\"55\"><a href=\"{$_SERVER['PHP_SELF']}?order=fprestige&sortOrder=$sortOrder\">Prestige</a></td>\n";
        echo "<td width=\"28\"><a href=\"{$_SERVER['PHP_SELF']}?order=fprestigedelta&sortOrder=$sortOrder\">Pr&Delta;</a></td>\n";
        echo "<td width=\"67\"><a href=\"{$_SERVER['PHP_SELF']}?order=fprestigerank&sortOrder=$sortOrder\">Pr&nbsp;R</a></td>\n";
        echo "<td width=\"50\"><a href=\"{$_SERVER['PHP_SELF']}?order=fcredits&sortOrder=$sortOrder\">Credits</a></td>\n";
        echo "<td width=\"40\"><a href=\"{$_SERVER['PHP_SELF']}?order=fcreditsdelta&sortOrder=$sortOrder\">Cr&Delta;</a></td>\n";
        echo "<td width=\"39\"><a href=\"{$_SERVER['PHP_SELF']}?order=fcreditsrank&sortOrder=$sortOrder\">Cr&nbsp;R</a></td>\n";
        echo "<td width=\"31\"><a href=\"{$_SERVER['PHP_SELF']}?order=ftaxrate&sortOrder=$sortOrder\">Tax</a></td>\n";
        echo "<td width=\"39\"><a href=\"{$_SERVER['PHP_SELF']}?order=fpop&sortOrder=$sortOrder\">Pop</a></td>\n";
        echo "<td width=\"50\"><a href=\"{$_SERVER['PHP_SELF']}?order=fpopdelta&sortOrder=$sortOrder\">Pop&Delta;</a></td>\n";
        echo "<td width=\"39\"><a href=\"{$_SERVER['PHP_SELF']}?order=fpoprank&sortOrder=$sortOrder\">Pop&nbsp;R</a></td>\n";
        echo "<td width=\"88\"><a href=\"{$_SERVER['PHP_SELF']}?order=fpopspace&sortOrder=$sortOrder\">Pop&nbsp;Space</a></td>\n";
        echo "<td width=\"20\"><a href=\"{$_SERVER['PHP_SELF']}?order=ffood&sortOrder=$sortOrder\">Fd</a></td>\n";
        echo "<td width=\"19\"><a href=\"{$_SERVER['PHP_SELF']}?order=ffuel&sortOrder=$sortOrder\">Fu</a></td>\n";
        echo "<td width=\"24\"><a href=\"{$_SERVER['PHP_SELF']}?order=fmetals&sortOrder=$sortOrder\">Mtl</a></td>\n";
        echo "<td width=\"29\"><a href=\"{$_SERVER['PHP_SELF']}?order=fradioactives&sortOrder=$sortOrder\">Rad</a></td>\n";
        echo "<td width=\"31\"><a href=\"{$_SERVER['PHP_SELF']}?order=ffooddelta&sortOrder=$sortOrder\">Fd&Delta;</a></td>\n";
        echo "<td width=\"36\"><a href=\"{$_SERVER['PHP_SELF']}?order=fmetalsdelta&sortOrder=$sortOrder\">Mtl&Delta;</a></td>\n";
        echo "<td width=\"81\"><a href=\"{$_SERVER['PHP_SELF']}?order=fmatspace&sortOrder=$sortOrder\">Mat&nbsp;Space</a></td>\n";
        echo "<td width=\"59\"><a href=\"{$_SERVER['PHP_SELF']}?order=fprojects&sortOrder=$sortOrder\">Q's Used </a></td>\n";
        echo "<td width=\"34\"><a href=\"{$_SERVER['PHP_SELF']}?order=fconstruction&sortOrder=$sortOrder\">Con</a></td>\n";
        echo "<td width=\"33\"><a href=\"{$_SERVER['PHP_SELF']}?order=fwealth&sortOrder=$sortOrder\">Wea</a></td>\n";
        echo "<td width=\"28\"><a href=\"{$_SERVER['PHP_SELF']}?order=falertlevel&sortOrder=$sortOrder\">Alt</a></td>\n";
        echo "<td width=\"41\">Age</td>\n";
        echo "</tr>\n";

        //variables for summary row
        $sumCredits; $sumCreditsDelta; $sumTaxRate; $sumPop;
        $sumPopDelta; $sumPopSpace; $sumFood; $sumFuel;
        $sumMetals; $sumRads; $sumFoodDelta; $sumMetalsDelta;
        $sumMatSpace; $sumProjects; $sumProjectsMaximum; $sumWealth;
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

          echo "<td>" . $row['fplanetname'] . "</td>\n";
          echo "<td>" . $row['fuser'] . "</td>\n";
          echo "<td>" . $row['fprestige'] . "</td>\n";
          echo "<td>" . $row['fprestigedelta'] . "</td>\n";
          echo "<td>" . $row['fprestigerank'] . "</td>\n";
          echo "<td>" . $row['fcredits'] . "</td>\n";
          echo "<td>" . $row['fcreditsdelta'] . "</td>\n";
          echo "<td>" . $row['fcreditsrank'] . "</td>\n";
          echo "<td>" . $row['ftaxrate'] . "%</td>\n";
          echo "<td>" . $row['fpop'] . "</td>\n";
          echo "<td>" . $row['fpopdelta'] . "</td>\n";
          echo "<td>" . $row['fpoprank'] . "</td>\n";
          echo "<td>" . $PopSpace . "</td>\n";
          echo "<td>" . $row['ffood'] . "</td>\n";
          echo "<td>" . $row['ffuel'] . "</td>\n";
          echo "<td>" . $row['fmetals'] . "</td>\n";
          echo "<td>" . $row['fradioactives'] . "</td>\n";
          echo "<td>" . $row['ffooddelta'] . "</td>\n";
          echo "<td>" . $row['fmetalsdelta'] . "</td>\n";
          echo "<td>" . $MatSpace . "</td>\n";
          echo "<td>" . $row['fprojects'] . "/" . $row['fprojectsmaximum'] . "</td>\n";
          echo "<td>" . $row['fconstruction'] . "</td>\n";
          echo "<td>" . $row['fwealth'] . "</td>\n";
          echo "<td>" . $row['falertlevel'] . "</td>\n";
          echo "<td>" . $Age . "</td>\n";

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
        echo "<td>SUMMARY</td>\n";
        echo "<td></td>\n";
        echo "<td></td>\n";
        echo "<td></td>\n";
        echo "<td></td>\n";
        echo "<td>" . $sumCredits . "</td>\n";
        echo "<td>" . $sumCreditsDelta . "</td>\n";
        echo "<td></td>\n";
        echo "<td>" . round($sumTaxRate / $i,2)  . "%</td>\n";
        echo "<td>" . $sumPop . "</td>\n";
        echo "<td>" . $sumPopDelta . "</td>\n";
        echo "<td>" . $row['fpoprank'] . "</td>\n";
        echo "<td>" . $sumPopSpace . "</td>\n";
        echo "<td>" . $sumFood . "</td>\n";
        echo "<td>" . $sumFuel . "</td>\n";
        echo "<td>" . $sumMetals . "</td>\n";
        echo "<td>" . $sumRads . "</td>\n";
        echo "<td>" . $sumFoodDelta . "</td>\n";
        echo "<td>" . $sumMetalsDelta . "</td>\n";
        echo "<td>" . $sumMatSpace . "</td>\n";
        echo "<td>" . $sumProjects . "/" . $sumProjectsMaximum . "</td>\n";
        echo "<td>" . $row['fconstruction'] . "</td>\n";
        echo "<td>" . round($sumWealth / $i,2) . "</td>\n";
        echo "<td></td>\n";
        echo "<td></td>\n";

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
    echo ('<p>Unable to connect to the database server at this time.</p>' );
    exit();
  }
  else
  {
    //echo "<p>connected</p>";

    if (!@mysql_select_db($mysql_db))
    {
        exit('<p>Unable to locate the sr database at this time.</p>');
    }
    else
    {
      $mat = $post_vars_string["mat"];
      $matdelta = $post_vars_string["matdelta"];
      $bonuses = $post_vars_string["bonuses"];
      $date = date("m-d-Y H:i:s"); //date for the age column
      echo $date;

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
        echo '<p>Error inserting data: ' . mysql_error() . '</p>';
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