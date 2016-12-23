<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$title = $escape_mysql_string($_GET['title']);
$load_hf = 1;

$db->sql_query_simple("UPDATE ".$prefix."_intro_cat SET title='$title' WHERE id=$id");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _INTRO_EDIT_CAT_TITLE);
include_once("modules/".$adm_modname."/Categories.php");
?>