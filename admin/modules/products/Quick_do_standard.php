<?php
if(!defined('CMS_ADMIN')) die();

$fc = intval($_POST['fc']);
$id = $_POST['id'];

$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

if ($fc == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		delete_standard($id[$i]);
	}
}

if ($fc == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE {$prefix}_property_standard SET status=0 WHERE id='".intval($id[$i])."'");
	}
}

if ($fc == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE {$prefix}_property_standard SET status=1 WHERE id='".intval($id[$i])."'");
	}
}

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDOSTANDARD);

header("Location: modules.php?f=$adm_modname&do=value_standard");

?>