<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

if ($_GET['type'] == 'normal') $table = "{$prefix}_news";
else $table = "{$prefix}_news_temp";

$db->sql_query_simple("UPDATE $table SET active=$stat WHERE id=$id");
include("modules/".$adm_modname."/index.php");
?>