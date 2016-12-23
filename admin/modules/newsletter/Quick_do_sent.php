<?php
if(!defined('CMS_ADMIN')) {
	die();
}

//$fc = intval($_POST['fc']);
$id = $_POST['id'];

//if ($fc == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("DELETE FROM ".$prefix."_newsletter_send WHERE id='".intval($id[$i])."'");
	}	
//}	

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Xu ly nhanh tin tuc");
header("Location: modules.php?f=".$adm_modname."&do=sent");

?>