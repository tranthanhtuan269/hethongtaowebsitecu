<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$load_hf = isset($_GET['load_hf']) ? 1 : 0;

$result = $db->sql_query_simple("SELECT date FROM ".$prefix."_number_date WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) 
{
	include("modules/".$adm_modname."/index.php");
	die();
} 
else 
{
	if($admin_ar[0]=="admin" || $admin_ar[0]=="tuongdd")
	{
		$db->sql_query_simple("DELETE FROM ".$prefix."_number_date WHERE id='$id'");
		include("modules/".$adm_modname."/index.php");
	}
	else
	{
		include("modules/".$adm_modname."/index.php");
	}
}
?>