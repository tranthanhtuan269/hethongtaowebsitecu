<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$catid = intval($_GET['catid']);

$db->sql_query_simple("DELETE FROM ".$prefix."_company WHERE id='$catid'");

truncate_table("company");
truncate_table("products");
fixweight_cat("company","id");
fixsubcat();
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _DELETE_CAT);
header("Location: modules.php?f=products&do=company");


?>