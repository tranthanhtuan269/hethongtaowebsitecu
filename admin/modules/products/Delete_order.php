<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query_simple("DELETE FROM {$prefix}_products_order WHERE id=$id");
updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _PRD_DELETE_ORDER);
include("modules/".$adm_modname."/Orders.php");
?>