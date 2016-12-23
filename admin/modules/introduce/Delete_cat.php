<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['id']);
$load_hf = 1;

$db->sql_query_simple("DELETE FROM {$prefix}_intro_cat WHERE id=$catid");
$db->sql_query_simple("DELETE FROM {$prefix}_intro WHERE parent=$catid");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _INTRO_DELETE_CAT);
include_once("modules/".$adm_modname."/Categories.php");
?>