<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);

$db->sql_query_simple("DELETE FROM ".$prefix."_link WHERE id='$id'");

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAT);
header("Location: modules.php?f=".$adm_modname."");


?>