<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT*FROM ".$prefix."_footermenus WHERE mid='$mid'");
if(empty($mid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}	

$db->sql_query_simple("UPDATE ".$prefix."_footermenus SET active='$stat' WHERE mid='$mid'");
include("modules/".$adm_modname."/index.php");

?>