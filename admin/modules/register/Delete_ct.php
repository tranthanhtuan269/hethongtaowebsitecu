<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query_simple("DELETE FROM ".$prefix."_register WHERE id='$id'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE);
include("modules/".$adm_modname."/index.php");

?>