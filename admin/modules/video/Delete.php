<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = 1;

$result = $db->sql_query_simple("SELECT*FROM ".$prefix."_video WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}	

$db->sql_query_simple("DELETE FROM ".$prefix."_video WHERE id='$id'");
truncate_table("video");
//include("modules/".$adm_modname."/index.php");
header("Location: modules.php?f=$adm_modname");
?>