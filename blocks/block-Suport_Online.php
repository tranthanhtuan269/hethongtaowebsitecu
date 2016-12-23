<?php
if (!defined('CMS_SYSTEM')) exit;
global $Default_Temp, $yim_support, $hotline, $urlsite;
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
$result = $db->sql_query_simple("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]' AND active=1");
list($showtitle) = $db->sql_fetchrow_simple($result);

// echo "<div class=\"div-block\">";
	// if($showtitle==1)
	// {
	// 	echo  "<div class=\"div-tblock_tt\"><h3>$correctArr[1]</h3></div>";
	// }
	echo  "<div class=\"supp_img\">";
		show_gentext();
	echo "</div>";
// echo "</div>";

// if(!empty($yim_support)) 
// {
// 	$yim_support_arr = @explode(",", $yim_support);
	
// 	echo  "<div class=\"supp_img\">";
// 		echo "<div class=\"box_support\">";
// 			echo "<div class='div-tblock-sphot'></div>";
// 			echo "<div class=\"line_phone\">Hotline<br/><font>".$hotline."</font></div>";
// 			for ($i = 0; $i < sizeof($yim_support_arr); $i++) 
// 			{	
// 				$yim_support_arr2 = @explode("|", $yim_support_arr[$i]);
// 				echo "<div class='line1'>";
// 				echo "<p>".$yim_support_arr2[0].": <font>".$yim_support_arr2[1]."</font></p>";
// 					echo "<a href=\"ymsgr:sendim?$yim_support_arr2[3]\"><img src=\"".$urlsite."/images/yahoo.png\" /></a>";
// 					echo "<a href=\"skype:$yim_support_arr2[2]?chat\"><img src='".$urlsite."/images/skype.gif'></a>";
// 		    	echo "</div>";
// 			}
// 		echo "</div>";
// 	echo "</div>";
// }

?>
