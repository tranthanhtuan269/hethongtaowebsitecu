<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['id']);
$load_hf = 1;

$db->sql_query_simple("DELETE FROM {$prefix}_intro WHERE id=$catid");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _INTRO_DELETE_INTRO);
include_once("modules/".$adm_modname."/index.php");
?>