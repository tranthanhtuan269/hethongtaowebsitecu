<?php
if (!defined('CMS_SYSTEM')) exit;

global $yim_support, $Default_Temp;

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

$content = "";
// $content .= "<div class=\"div-block\">";
// $content .= "<div class=\"div-cblock\">";
	$result = $db->sql_query_simple("SELECT banggia FROM ".$prefix."_contact_add WHERE alanguage='$currentlang'");
	list($banggia) = $db->sql_fetchrow_simple($result); 
 	echo $banggia;
?>


