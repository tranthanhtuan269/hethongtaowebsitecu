<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));


$db->sql_query_simple("UPDATE {$prefix}_property_standard SET status=$stat WHERE id=$id");
include("modules/{$adm_modname}/Value_standard.php");

?>