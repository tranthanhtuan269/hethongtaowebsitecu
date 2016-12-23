<?php
if (!defined('CMS_SYSTEM')) exit;
global $Default_Temp;
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


$content = "";
global $urlsite;
$result = $db->sql_query_simple("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]' AND active=1");
list($showtitle) = $db->sql_fetchrow_simple($result);

echo  "<div class=\"div-block\" style=\"float:left;width:100%;  \">";
if($showtitle==1){
	echo  "<div class=\"div-tblock\"><i class=\"fa fa-bars fa-1\"></i>{$correctArr[1]} </div>";
}
echo  "<div id='qcqc'>";
// echo  	show_slidehome1(41);
	echo advertising(49);
echo  "</div>";
echo  "</div>";

?>
