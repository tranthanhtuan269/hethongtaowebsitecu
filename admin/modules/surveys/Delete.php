<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT*FROM ".$prefix."_survey WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else {
	$db->sql_query_simple("DELETE FROM ".$prefix."_survey WHERE id='$id'");
	include("modules/".$adm_modname."/index.php");
}

?>