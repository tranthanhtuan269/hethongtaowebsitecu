<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = isset($_POST['mid']) ? intval($_POST['mid']):0;
$stat = isset($_POST['stat']) ? intval($_POST['stat']):0;
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT mid FROM ".$prefix."_admin_menu WHERE mid='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
}
else {	
	$db->sql_query_simple("UPDATE ".$prefix."_admin_menu SET action='$stat' WHERE mid='$id'");
	updateadmlog($admin_ar[0], $adm_modname, "Admin menus", ""._STATUS."");
	include("modules/".$adm_modname."/index.php");
}

?>