<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$fc = intval($_POST['fc']);
$catid = $_POST['catid'];
$poz = $_POST['poz'];

if ($fc == 2) {
	for($i =0; $i < sizeof($catid); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_national SET active='0' WHERE id='$catid[$i]'");
	}	
}	

if ($fc == 3) {
	for($i =0; $i < sizeof($catid); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_national SET active='1' WHERE id='$catid[$i]'");
	}	
}

if ($fc == 4) {
	foreach ($poz as $catidx => $weight) {
		$catidx = intval($catidx);
		$weight = intval($weight);
		$db->sql_query_simple("UPDATE ".$prefix."_national SET weight='$weight' WHERE id='$catidx'");
	}	
	fixweight_cat();
}		

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO_CAT);
header("Location: modules.php?f=".$adm_modname."&do=national");

?>