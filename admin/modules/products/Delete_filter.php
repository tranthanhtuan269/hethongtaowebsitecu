<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$result = $db->sql_query_simple("SELECT id FROM ".$prefix."_filter WHERE id=$id");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."");
	die();
}
$db->sql_query_simple("DELETE FROM ".$prefix."_filter WHERE id='$id'");
header("Location: modules.php?f=".$adm_modname."&do=create_filter");

?>