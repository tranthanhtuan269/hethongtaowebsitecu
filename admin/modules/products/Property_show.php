<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$property_id = intval($_GET['property_id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT id FROM ".$prefix."_property WHERE id='$property_id'");
if(empty($property_id) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/Property.php");
	die();
}	
$db->sql_query_simple("UPDATE ".$prefix."_property SET is_show='$stat' WHERE id='$property_id'");
//onfile_cat();
include("modules/".$adm_modname."/Property.php");

?>