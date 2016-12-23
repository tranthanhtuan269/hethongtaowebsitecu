<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['catid']);
$stat = intval($_GET['stat']);

$result = $db->sql_query_simple("SELECT alanguage FROM ".$prefix."_news_cat WHERE catid='$catid'");
if(empty($catid) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}	

$db->sql_query_simple("UPDATE ".$prefix."_news_cat SET active='$stat' WHERE catid='$catid'");
//onfile_cat();
header("Location: modules.php?f=".$adm_modname."&do=categories");
?>