<?php
if (!defined('CMS_SYSTEM')) exit;

global $Default_Temp,$imgFold;

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
	global $imgFold, $currentlang, $prefix, $urlsite, $db,$urlsite;
    $query1 = "SELECT catid, parentid, title FROM ".$prefix."_products_cat WHERE parentid=0 AND active=1 and alanguage='$currentlang' ORDER BY weight";
    $result_cat1 = $db->sql_query_simple($query1);	   
	if ($db->sql_numrows($result_cat1) > 0)
    {
    	if($showtitle==1){
			echo  "<div class=\"div-tblock\"><i class=\"fa fa-bars fa-1\"></i>{$correctArr[1]} </div>";
		}
          	while(list($mid, $url, $titlecat) = $db->sql_fetchrow_simple($result_cat1)) 
          	{
          		echo "<div class='menu-danhmuc'>";
	      		 echo "<div class=\"div-tblock\">$titlecat</div>"; 
	      		 echo '<ul id="menu">';  	
	    		 $query2 = "SELECT catid, parentid, title FROM ".$prefix."_products_cat WHERE parentid=$mid AND alanguage='$currentlang' ORDER BY weight";
	    		 //die($query2);
	    		 $result_cat2 = $db->sql_query_simple($query2);
			      if ($db->sql_numrows($result_cat2) > 0)
				  {
				  	while (list($catsub,$parentidsub,$titlesub) = $db->sql_fetchrow_simple($result_cat2)) {
				  		$url1=url_sid("index.php?f=products&do=categories&id=$catsub");
				  		 $query3 = $db->sql_query_simple("SELECT catid, parentid, title FROM ".$prefix."_products_cat WHERE parentid=$catsub AND alanguage='$currentlang' ORDER BY weight");
				  		 if ($db->sql_numrows($query3) > 0){
				  		 	echo "<li><a class='licha' href=".$url1.">$titlesub</a></li>";
				  		 		while (list($idsub,$parentidsub1,$titlesub1) = $db->sql_fetchrow_simple($query3)) {
				  		 			$url2=url_sid("index.php?f=products&do=detail&id=$idsub");
				  		 			echo "<li ><a class='licon' href=".$url2."> ".cutText($titlesub1,50)."</a></li>";
				  		 		}
				  		 	}
						  	else{
						  		echo "<li><a class='licha' href=".$url1.">$titlesub <i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></a></li>";
						  	}
				  		}
				  	}
	         	echo "</ul>";
	        echo "</div>";
	        }
	     } 	  

	

?>