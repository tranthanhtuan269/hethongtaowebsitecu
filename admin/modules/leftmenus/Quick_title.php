<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$title = $escape_mysql_string($_GET['title']);
$load_hf = 1;

$db->sql_query_simple("UPDATE ".$prefix."_leftmenus SET title='$title' WHERE mid='$id'");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICK_UPDATE_TITLE);
include("modules/".$adm_modname."/index.php");


?>