<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['catid']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT alanguage FROM ".$prefix."_color WHERE id='$catid'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/Color.php");
	die();
}	

$db->sql_query_simple("UPDATE ".$prefix."_color SET active='$stat' WHERE id='$catid'");

include("modules/".$adm_modname."/Color.php");

?>