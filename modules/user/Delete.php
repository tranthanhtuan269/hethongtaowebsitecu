<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: index.php?f=user&do=login");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query_simple("SELECT catid, counts FROM ".$prefix."_products_cat");
	 $i =0;
	 while(list($catid, $counts) = $db->sql_fetchrow_simple($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query_simple("SELECT*FROM ".$prefix."_products WHERE catid='$catid'"));
	 	if($counts != $numsnew) {
	 		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET counts='$numsnew' WHERE catid='$catid'");
	 	}
	 	$i ++;
	 }
}
$result = $db->sql_query_simple("SELECT images FROM ".$prefix."_products WHERE userid={$userInfo['id']} AND id='$id' ");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: index.php?f=user&do=product");
	die();
} else {
	list($images) = $db->sql_fetchrow_simple($result);
	@unlink("../".$path_upload."/product/".$images);
	@unlink("../".$path_upload."/product/thumb_".$images);
	$db->sql_query_simple("DELETE FROM ".$prefix."_products WHERE  userid={$userInfo['id']} AND id='$id'");
	updateadmlog($admin_ar[0], "product", _MODTITLE, _DELETE);
	fixcount_cat();
	header("Location: index.php?f=user&do=product");
	
}
?>