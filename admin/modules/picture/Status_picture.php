<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$db->sql_query_simple("UPDATE {$prefix}_picture SET active=$stat WHERE id=$id");
include("modules/".$adm_modname."/index.php");
?>