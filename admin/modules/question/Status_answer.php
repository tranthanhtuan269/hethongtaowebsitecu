<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$stat = intval($_GET['stat']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$table = "{$prefix}_answer";


$db->sql_query_simple("UPDATE $table SET active=$stat WHERE id=$id");
include("modules/".$adm_modname."/answer.php");
?>