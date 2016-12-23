<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query_simple("UPDATE ".$prefix."_link SET special='$stat' WHERE id='$id'");

header("Location: modules.php?f=".$adm_modname."");

?>