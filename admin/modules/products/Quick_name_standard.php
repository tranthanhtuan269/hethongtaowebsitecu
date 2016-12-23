<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$standard_id = intval($_GET['id']);
$title = $escape_mysql_string($_GET['title']);
$load_hf = 1;

$db->sql_query_simple("UPDATE ".$prefix."_property_standard SET name='$title' WHERE id='$standard_id'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICK_UPDATE_TITLE);
include("modules/".$adm_modname."/Value_standard.php");
?>