<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$prdid = intval($_GET['prdid']);
$img = intval($_GET['img']);

$result = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE id='$img'");
if(empty($img) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."&do=edit&id=$prdid");
	die();
} else {
	list($images) = $db->sql_fetchrow_simple($result);
	@unlink("../".$path_upload."/".$adm_modname."/".$images);
	@unlink("../".$path_upload."/".$adm_modname."/thumb_".$images);
	$db->sql_query_simple("DELETE FROM ".$prefix."_prd_images WHERE id='$img'");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
	fixcount_cat();
	header("Location: modules.php?f=".$adm_modname."&do=edit&id=$prdid");
}
?>