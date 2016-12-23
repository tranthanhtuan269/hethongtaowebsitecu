<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$title = $escape_mysql_string($_GET['title']);
$load_hf = 1;

$db->sql_query_simple("UPDATE ".$prefix."_intro SET title='$title' WHERE id=$id");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _INTRO_EDIT_INTRO_TITLE);
include_once("modules/".$adm_modname."/index.php");
?>