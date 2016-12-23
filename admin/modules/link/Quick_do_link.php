<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$fc = intval($_POST['fc']);
$id = $_POST['id'];
$poz = $_POST['poz'];

if ($fc == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_link SET active='0' WHERE id='$id[$i]'");
	}	
}	

if ($fc == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_link SET active='1' WHERE id='$id[$i]'");
	}	
}

if ($fc == 4) {
	foreach ($poz as $idx => $weight) {
		$idx = intval($idx);
		$weight = intval($weight);
		$db->sql_query_simple("UPDATE ".$prefix."_link SET weight='$weight' WHERE id='$idx'");
	}	
	fixweight_cat();
}		

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO_CAT);
header("Location: modules.php?f=".$adm_modname."");

?>