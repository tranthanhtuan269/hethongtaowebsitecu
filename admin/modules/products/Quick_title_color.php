<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['id']);
$title = $escape_mysql_string($_GET['title']);
$load_hf = 1;

$db->sql_query_simple("UPDATE ".$prefix."_color SET title='$title' WHERE id='$catid'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICK_UPDATE_TITLE);
include("modules/".$adm_modname."/Color.php");
?>