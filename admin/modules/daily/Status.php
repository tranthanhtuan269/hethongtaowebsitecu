<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

$result = $db->sql_query_simple("SELECT alanguage FROM ".$prefix."_nhacungcap WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}	

$db->sql_query_simple("UPDATE ".$prefix."_nhacungcap SET active='$stat' WHERE id='$id'");
//onfile_cat();
header("Location: modules.php?f=".$adm_modname."&page=$page");
?>