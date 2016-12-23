<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

$query = "SELECT images FROM {$prefix}_picture WHERE id=$id";

$result = $db->sql_query_simple($query);
if($db->sql_numrows($result) != 1) {
	include("modules/".$adm_modname."/index.php");
	die();
} else { 
$table = "{$prefix}_picture";
	list($time, $images) = $db->sql_fetchrow_simple($result);
	$get_path = get_path($time);
	$path_upload_img = "$path_upload/pictures";
		
	@unlink(RPATH."$path_upload_img/$images");
	@unlink(RPATH."$path_upload_img/thumb_$images");
	$db->sql_query_simple("DELETE FROM $table WHERE id=$id");
	fixcount_cat();
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_NEWS);
	truncate_table(substr($table, strlen($prefix) + 1));
	//include("modules/".$adm_modname."/index.php");
	header("Location: modules.php?f=".$adm_modname."&sort=$sort&page=$page");
}
?>