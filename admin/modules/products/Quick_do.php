<?php
if(!defined('CMS_ADMIN')) die();

$fc = intval($_POST['fc']);
$id = $_POST['id'];

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

if ($fc == 1) {
	for($i =0; $i < sizeof($id); $i ++) {
		list($images) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT images FROM ".$prefix."_products WHERE id='".intval($id[$i])."'"));
		@unlink("../$path_upload/$adm_modname/$images");
		@unlink("../$path_upload/$adm_modname/thumb_".$images);
		$db->sql_query_simple("DELETE FROM ".$prefix."_products WHERE id=".intval($id[$i]));
		fixcount_cat();
	}
	truncate_table("products");
	fixcount_cat();
}

if ($fc == 2) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_products SET active='0' WHERE id='".intval($id[$i])."'");
	}
}

if ($fc == 3) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_products SET active='1' WHERE id='".intval($id[$i])."'");
	}
}

if ($fc == 4) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_products SET pnews='1' WHERE id='".intval($id[$i])."'");
	}
}

if ($fc == 5) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_products SET ptops='1' WHERE id='".intval($id[$i])."'");
	}
}

if ($fc == 6) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_products SET pnews='0' WHERE id='".intval($id[$i])."'");
	}
}

if ($fc == 7) {
	for($i =0; $i < sizeof($id); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_products SET ptops='0' WHERE id='".intval($id[$i])."'");
	}
}

updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _QUICKDO);

header("Location: modules.php?f=$adm_modname&sort=$sort&page=$page");

?>