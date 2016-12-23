<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$fc = intval($_POST['fc']);
$id = $_POST['id'];

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

if ($fc == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("DELETE FROM ".$prefix."_news WHERE id='".intval($id[$i])."'");
		fixcount_cat();
	}	
}	

if ($fc == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_news SET active='0' WHERE id='".intval($id[$i])."'");
	}	
}	

if ($fc == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_news SET active='1' WHERE id='".intval($id[$i])."'");
	}	
}

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Xu ly nhanh tin tuc");
header("Location: modules.php?f=".$adm_modname."&sort=$sort&page=$page");

?>