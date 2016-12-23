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
global $urlsite, $fb;
$result = $db->sql_query_simple("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]' AND active=1");
list($showtitle) = $db->sql_fetchrow_simple($result);
$content = "";
echo "<div class=\"div-block bor\">";
	echo "<div class=\"box_block\">";
		if($showtitle==1){
			echo  "<div class=\"div-tblock-sp\"><h3>{$correctArr[1]}</h3></div>";
		}
		echo  "<div id=\"chay_prd\">";
			echo  "<div id='qcfb'>";
			echo '<div class="fb-page" data-href="'.$fb.'" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="'.$fb.'"><a href="'.$fb.'">Facebook</a></blockquote></div></div>';
			echo  "</div>";
 		echo  "</div>";
 	echo  "</div>";
 echo  "</div>";
?>
