<?php
if (!defined('CMS_SYSTEM')) exit;
$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
	for ($h = 0; $h < count($bl_arr[$i]); $h++) {
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
			$correctArr = $temp;
			break;
		}
	}
}
global $urlsite;
$result = $db->sql_query_simple("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]' AND active=1");
list($showtitle) = $db->sql_fetchrow_simple($result);



$content ="";

global $path_upload, $mod_name, $id, $default_temp;
echo "<div class=\"div-block bg_fff\">";
	echo "<div class=\"box_block\">";
		if($showtitle==1){
			echo  "<div class=\"div-tblock\"><h3><i class=\"fa fa-search\" aria-hidden=\"true\"></i>{$correctArr[1]}</h3></div>";
		}
		echo '<ul id="menu">'; 
			show_search();
		echo "</ul>";		
	echo "</div>";
echo "</div>";		
?>