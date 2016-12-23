<?php
if (!defined('CMS_SYSTEM')) die();

$com = intval($_GET['company']);
$result = $db->sql_query_simple("SELECT title FROM ".$prefix."_company WHERE id='$com' ");
if(empty($com) || $db->sql_numrows($result) != 1) {
	header("Location: index.php?f=".$module_name);
	exit();
}

list($company_name) = $db->sql_fetchrow_simple($result);


$page_title = $company_name;
$description_page = $company_name;
$title_page = $company_name;

include_once("header.php");

echo "<div class=\"category-product\">";
	echo "<div class=\"category-product-list\">";
echo "<div class=\"title_home\"><h2>$company_name</h2></div>";

	$perpage = $prd_perpage;
	$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
	$offset = ($page-1) * $perpage;
	$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_products WHERE active=1 AND companyid='$com' AND alanguage='$currentlang'"));
	$total = ($countf[0]) ? $countf[0] : 1;
	$pageurl = "index.php?f=".$module_name."&do=company&company=$com";
	$result_prd_home = $db->sql_query_simple("SELECT id, title, price, text FROM {$prefix}_products WHERE companyid='$com' AND active='1' AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage");
	echo "<div>";
	if($db->sql_numrows($result_prd_home) > 0) {
		$i = 0;
		while(list($id, $title,$price,$text) = $db->sql_fetchrow_simple($result_prd_home)) {
			$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
			list($images) = $db->sql_fetchrow_simple($result_prd_img);
			$path_upload_img = "$path_upload/products";
			if(file_exists("$path_upload_img/$images") && $images !="") {
				//if(file_exists("".$path_upload."/products/thumb_".$images)) {
					//$images = "thumb_".$images;
				//}					
			//$images = tj_thumbnail("$path_upload/$module_name/$images",$title,$prd_thumbsize,"");
			$images = resizeImages("$path_upload/$module_name/$images", "$path_upload/$module_name/135x135_$images" ,135,135);			
			$images = "<img src=\"$images\">";
			}
			
			echo "<div class=\"km-prd\">";	
				echo "<div class=\"km-title\"><a href=\"".fetch_urltitle("index.php?f=products&do=detail&id=$id",$title)."\">$title</a> - <span class=\"km-company\">".showTitleCompany($id)."</span></div>";	

				echo "<div class=\"km-image\"><a href=\"".fetch_urltitle("index.php?f=products&do=detail&id=$id",$title)."\">$images</a> </div>";			
				//echo "<div class=\"title\">";						
					echo "<div class=\"km-text\">$text</div>";
					//echo "";
					echo "<div class=\"km-price\">".dsprice($price)." VND</div>";	
				echo "</div>";
			//echo "</div>";	
			$i++;	
		if($i%3==0){echo "<div class=\"cl\"></div>";}	
		}
		//if($i<$prd_perpage && $i%4!=0){echo "</div>";}
		echo "<div class=\"cl\"></div>";
		echo "</div>";
	echo paging($total,$pageurl,$perpage,$page);
	} else {
		echo "<center>"._NODATA."</div></center>";
	}
	//-----------------------------//

 

	echo "</div>";

/*	echo "<div class=\"category-product-sort\">";
		include_once("blocks/Loc_san_pham.php");
	echo "</div>";	*/
echo "</div>";

include_once("footer.php");
?>