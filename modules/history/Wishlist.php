<?php
if (!defined('CMS_SYSTEM')) die();

$page_title = _WISHLIST_LIST;
$title_page = _WISHLIST_LIST;

include_once("header.php");
$userid = $userInfo['id'];

echo "<div class=\"title_home\" style=\"margin-bottom:8px;\"><h2>"._WISHLIST_LIST."</h2></div>";
echo "<div class=\"div-home\">";
	$perpage = 20;
	$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
	$offset = ($page-1) * $perpage;
	$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM ".$prefix."_wishlist WHERE userid='$userid'"));
	$total = ($countf[0]) ? $countf[0] : 1;
	$pageurl = "index.php?f=history";
	
	
	
	$result_wishlist = $db->sql_query_simple("SELECT prd_id FROM ".$prefix."_wishlist WHERE userid='$userid' ORDER BY time DESC LIMIT $offset, $perpage");
	if($db->sql_numrows($result_wishlist) > 0) {	
	$i=1;
	echo "<table width=\"100%\" style=\"border-collapse:collapse; collapse:0px;\">";
	echo "<tr style=\"background:#F5F5F5;\">";
		echo "<td class=\"aheader-o\">"._PICTURE."</td>";
		echo "<td class=\"aheader-o\">"._TITLE."</td>";
		echo "<td class=\"aheader-o\">"._PRICE."</td>";
		echo "<td class=\"aheader-o\">"._DELETE."</td>";
	echo "</tr>";	
	while(list($prdid) = $db->sql_fetchrow_simple($result_wishlist)) {	
	$result_prd = $db->sql_query_simple("SELECT id, title,price FROM ".$prefix."_products WHERE active='1' AND id='".$prdid."' ");
	if($db->sql_numrows($result_prd) > 0) {	
		list($idot, $titleot, $priceot) = $db->sql_fetchrow_simple($result_prd);
			$path_upload_img = "$path_upload/products";
			$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$idot ");
			list($imagesot) = $db->sql_fetchrow_simple($result_prd_img);
			if($imagesot !="" && file_exists("$path_upload_img/$imagesot")) {
				if(file_exists("".$path_upload."/".$module_name."/thumb_".$imagesot)) {
					$imagesot = "thumb_".$imagesot;
				}
					//$imagesot = tj_thumbnail("$path_upload_img/$imagesot",$titleot,"80","80");
					$imagesot = resizeImages("$path_upload_img/$imagesot", "$path_upload_img/70x70_$imagesot" ,70,70);
					$imagesot = "<img border=\"0\" src=\"$imagesot\" />";
			}	
			if($i%2==0){echo "<tr style=\"background:#FAFAFA;\">";}
			else{echo "<tr>";} $i++;
				//echo "<div class=\"km-prd\" style=\"border:0px;margin:0px;width:99%;height:auto;padding:5px 0px;\" >";
					echo "<td align=\"center\" class=\"td-a\"><div style=\"padding:3px 0px;\"><a href=\"".fetch_urltitle("index.php?f=products&do=detail&id=$idot",$titleot)."\">$imagesot</a></div></td>";
				//echo "</div>";
				
				echo "<td class=\"td-b\">";
					echo "<div class=\"km-title\"><a href=\"".fetch_urltitle("index.php?f=products&do=detail&id=$idot",$titleot)."\">".$titleot."</a></div>";						
				echo "</td>";
				
				echo"<td class=\"td-c\">";
					if($priceot == 0){echo "<div class=\"price\" style=\"color:#A60000;font-weight:bold;\">"._DANG_CAP_NHAT." </div>";}
					else {echo "<div class=\"price\" style=\"color:#A60000;font-weight:bold;\">".dsprice($priceot)." VND</div>";}
				echo "</td>";
				
				echo"<td class=\"td-d\" align=\"center\">";
					echo "<a href=\"index.php?f=history&do=delete_wishlist&page=$page&pid=$idot\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK_WISHLIST."');\"><img border=\"0\" src=\"images/delete.gif\"></a>";
				echo "</td>";
			echo "</tr>";	
				
		}
	}
	echo "</table>";	
		
	echo paging($total,$pageurl,$perpage,$page);
	
	echo "</div>";
	}
	else{
		echo "<br><br><center><span style=\"color:#FF6600;\">"._NO_WISHLIST."</span></center>";
	}
	
echo "</div>";

include_once("footer.php");
?>