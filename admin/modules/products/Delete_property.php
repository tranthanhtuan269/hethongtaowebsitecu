<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$property_id = intval($_GET['property_id']);

$result = $db->sql_query_simple("SELECT id FROM ".$prefix."_property WHERE id='$property_id'");
if(empty($property_id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."");
	die();
}
delete_property($property_id);
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_PROPERTY);
header("Location: modules.php?f=".$adm_modname."&do=property");

?>