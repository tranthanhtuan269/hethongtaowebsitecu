<?php

if (!defined('CMS_SYSTEM')) die();

global $onls_g, $statcls, $stathits1,$urlsite;
$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) 
{
	for ($h = 0; $h < count($bl_arr[$i]); $h++) 
	{
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) 
		{
			$correctArr = $temp;
			break;
		}
	}
}
if ($onls_g!="") 
{ 
	$onls_g1 = explode("|",$onls_g); 
	$num_h2 = sizeof($onls_g1); 
} 
else 
{ 
	$num_h2 = 0; 
}
$num_h2 = str_pad( $num_h2, 4, "0", STR_PAD_LEFT );
?>
<?
$counter_path_http = "$urlsite/";
$counter_expire = 100;

$ignore = false; 

// get counter information
$sql = "select * from counter_values";
$res = mysql_query($sql);

// fill when empty
if (mysql_num_rows($res) == 0)
{	  
	$sql = "INSERT INTO `counter_values` (`id`, `day_id`, `day_value`, `yesterday_id`, `yesterday_value`, `week_id`, `week_value`, `month_id`, `month_value`, `year_id`, `year_value`, `all_value`, `record_date`, `record_value`) VALUES ('1', '" . date("z") . "',  '1', '" . (date("z")-1) . "', '0', '" . date("W") . "', '1', '" . date("n") . "', '1', '" . date("Y") . "',  '1',  '1',  NOW(),  '1')";
	mysql_query($sql);

	$sql = "select * from counter_values";
	$res = mysql_query($sql);

	$ignore = true;
}   
$row = mysql_fetch_assoc($res);

$day_id = $row['day_id'];
$day_value = $row['day_value'];
$yesterday_id = $row['yesterday_id'];
$yesterday_value = $row['yesterday_value'];
$week_id = $row['week_id'];
$week_value = $row['week_value'];
$month_id = $row['month_id'];
$month_value = $row['month_value'];
$year_id = $row['year_id'];
$year_value = $row['year_value'];
$all_value = $row['all_value'];
$record_date = $row['record_date'];
$record_value = $row['record_value'];

$counter_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? addslashes(trim($_SERVER['HTTP_USER_AGENT'])) : "";
$counter_time = time();
$counter_ip = trim(addslashes($_SERVER['REMOTE_ADDR'])); 

// ignorore some bots
if (substr_count($counter_agent, "bot") > 0)  $ignore = true;
  
// delete free ips
if ($ignore == false)
{
	$sql = "delete from counter_ips where unix_timestamp(NOW())-unix_timestamp(visit) > $counter_expire"; 
	mysql_query($sql);
}
  
// check for entry
if ($ignore == false)
{
	$sql = "select * from counter_ips where ip = '$counter_ip' and session = '".session_id()."'";
	$res = mysql_query($sql);
	if (mysql_num_rows($res) == 0)
	{
		// insert
		$sql = "INSERT INTO counter_ips (ip, visit, session) VALUES ('$counter_ip', NOW(), '".session_id()."')";
		mysql_query($sql);
	}
	else
	{
		$ignore = true;
		$sql = "update counter_ips set visit = NOW() where ip = '$counter_ip' and session = '".session_id()."'";
		mysql_query($sql);
	}
}

// online?
$sql = "select * from counter_ips";
$res = mysql_query($sql);
$online = mysql_num_rows($res);
  
// add counter
if ($ignore == false)
{     	  
	// yesterday
	if ($day_id == (date("z")-1)) 
	{
		$yesterday_value = $day_value; 
		$yesterday_id = (date("z")-1);
	}
	else
	{
		if ($yesterday_id != (date("z")-1))
		{
			$yesterday_value = 0; 
			$yesterday_id = date("z")-1;
		}
	}
	// day
	if ($day_id == date("z")) 
	{
		$day_value++; 
	}
	else 
	{
		$day_value = 1;
		$day_id = date("z");
	}

	// week
	if ($week_id == date("W")) 
	{
		$week_value++; 
	}
	else 
	{ 
		$week_value = 1;
		$week_id = date("W");
	}

	// month
	if ($month_id == date("n")) 
	{
		$month_value++; 
	}
	else 
	{
		$month_value = 1;
		$month_id = date("n");
	}

	// year
	if ($year_id == date("Y")) 
	{
		$year_value++; 
	}
	else 
	{
		$year_value = 1;
		$year_id = date("Y");
	}

	// all
	$all_value++;

	// neuer record?
	if ($day_value > $record_value)
	{
		$record_value = $day_value;
		$record_date = date("Y-m-d H:i:s");
	}

	// speichern und aufräumen
	$sql = "update counter_values set day_id = '$day_id', day_value = '$day_value', yesterday_id = '$yesterday_id', yesterday_value = '$yesterday_value', week_id = '$week_id', week_value = '$week_value', month_id = '$month_id', month_value = '$month_value', year_id = '$year_id', year_value = '$year_value', all_value = '$all_value', record_date = '$record_date', record_value = '$record_value' where id = 1";
	mysql_query($sql); 
}


$result = $db->sql_query_simple("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]' AND active=1");
list($showtitle) = $db->sql_fetchrow_simple($result);

$content = "";
$content .= "<div class=\"box_block bor\">";
if($showtitle==1){
$content .= "<div class=\"div-tblock-sp\"><h3>{$correctArr[1]}</h3></div>";
}
$content .= "<div class=\"div-cblock\">";

$content .= "<div style='padding: 5px; font-size: 12px; color: #666;' ><img src=\"$urlsite/templates/Adoosite/images/online2.gif\"  style=\"padding-right:4px\"><font color=\"#2A2A2A\">Online: &nbsp;</font><font><b>".($online)."</b></font></div>";
$content .= "<div style='padding: 5px; font-size: 12px; color: #666;' ><img src=\"$urlsite/templates/Adoosite/images/online2.gif\"  style=\"padding-right:4px\"><font color=\"#2A2A2A\">Hôm nay : &nbsp;</font><font><b>".$day_value."</b></font></div>";
$content .= "<div style='padding: 5px; font-size: 12px; color: #666;' ><img src=\"$urlsite/templates/Adoosite/images/online2.gif\"  style=\"padding-right:4px\"><font color=\"#2A2A2A\">Hôm qua : &nbsp;</font><font><b>".$yesterday_value."</b></font></div>";
$content .= "<div style='padding: 5px; font-size: 12px; color: #666;' ><img src=\"$urlsite/templates/Adoosite/images/online1.gif\" style=\"padding-right:5px\"><font color=\"#2A2A2A\">Tổng: </font><font><b>".$all_value."</b></font></div>";

$content .= "</div>";
$content .= "</div>";

?>
