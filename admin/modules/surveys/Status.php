<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT*FROM ".$prefix."_survey WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	$db->sql_query_simple("UPDATE ".$prefix."_survey SET active='$stat' WHERE id='$id'");
	include("modules/".$adm_modname."/index.php");
}

?>