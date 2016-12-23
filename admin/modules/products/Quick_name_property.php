<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$property_id = intval($_GET['id']);
$title = $escape_mysql_string($_GET['title']);
$load_hf = 1;

$db->sql_query_simple("UPDATE ".$prefix."_property SET name='$title' WHERE id='$property_id'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICK_UPDATE_TITLE);
include("modules/".$adm_modname."/Property.php");
?>