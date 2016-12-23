<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['catid']);

$db->sql_query_simple("DELETE FROM ".$prefix."_age WHERE id='$catid'");

truncate_table("age");
truncate_table("products");
fixweight_cat("age","id");
fixsubcat();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAT);
header("Location: modules.php?f=products&do=age");


?>