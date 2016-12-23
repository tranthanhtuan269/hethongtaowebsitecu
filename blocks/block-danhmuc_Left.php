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
echo "<div class=\"div-block\">";
$result_newsindex = $db->sql_query_simple("SELECT mid, title, url FROM {$prefix}_leftmenus WHERE active=1 and parentid=0 and alanguage = '$currentlang' ORDER BY weight ");
if($db->sql_numrows($result_newsindex) > 0) 
{	
	// if($showtitle==1){
	// 	echo  "<div class=\"div-tblock\"><i class=\"fa fa-bars fa-1\"></i>{$correctArr[1]} </div>";
	// }
	// echo '<ul id="menu">'; 
	$i=1;
	while(list($idm, $titlenewind, $guid) = $db->sql_fetchrow_simple($result_newsindex)) 
	{
		$url_cat =url_sid($guid);
		// echo "<div class=\"box_block\">";
			echo  "<div class=\"div-tblock\"><h3>$titlenewind</h3></div>";
				// echo  "<div class=\"div-block\">";
				$query2 = "SELECT mid, url, title FROM ".$prefix."_leftmenus WHERE active=1 AND parentid=$idm AND alanguage='$currentlang' ORDER BY weight";
		    	$result_cat2 = $db->sql_query_simple($query2);
				if ($db->sql_numrows($result_cat2) > 0)
				{
					echo '<ul id="menu">'; 
					$j=1;
					while (list($catsub,$url1,$titlesub) = $db->sql_fetchrow_simple($result_cat2)) 
					{
					  	$url11=url_sid($url1);
					  	echo "<li><a title='".$titlesub."' href=".$url11.">".$titlesub." <i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></a>";
					  		// $query3 = $db->sql_query_simple("SELECT id, guid, title FROM ".$prefix."_news WHERE catid=$catsub AND alanguage='$currentlang' ORDER BY id");
					  		// if ($db->sql_numrows($query3) > 0)
					  		// {
					  		//  	echo "<ul class='subli'>";
					  		//  		while (list($idsub,$url12,$titlesub1) = $db->sql_fetchrow_simple($query3)) {
					  		//  			$url2=url_sid($url12);
					  		//  			echo "<li>  <a class='licon' href=".$url2."> ".cutText($titlesub1,34)."</a></li>";
					  		//  		}
					  		//  	echo "</ul>";
					  		// }
					  	echo "</li>";
					  	$j++;
					}
					echo "</ul>";
				}
				// echo "</div>";
			// echo "</div>";
		$i++;
	}	
	// echo "</ul>";		
}	
echo "</div>";		
?>