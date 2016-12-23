<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$mid = intval($_GET['mid']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT*FROM ".$prefix."_mainmenus WHERE mid='$mid'");
if(empty($mid) || $db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
}
$resultimg = $db->sql_query_simple("SELECT images FROM ".$prefix."_mainmenus WHERE mid='$mid'");
while(list($images) = $db->sql_fetchrow_simple($resultimg)) {
	$path_upload_img = "$path_upload/menu";
	@unlink("../$path_upload_img/$images");
	@unlink("../$path_upload_img/thumb_".$images."");
}
deletecat($mid);
truncate_table("mainmenus");
fixweight_mn();
include("modules/".$adm_modname."/index.php");

?>