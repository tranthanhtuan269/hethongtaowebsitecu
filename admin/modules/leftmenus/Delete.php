<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid = intval($_GET['mid']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT*FROM ".$prefix."_leftmenus WHERE mid='$mid'");
if(empty($mid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}

deletecat($mid);
truncate_table("leftmenus");
fixweight_mn();
include("modules/".$adm_modname."/index.php");

?>