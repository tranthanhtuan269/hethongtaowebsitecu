<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$prdid = intval($_GET['prdid']);
$rid = intval($_GET['rid']);

$result_prd1 = $db->sql_query_simple("SELECT relation FROM {$prefix}_products WHERE id=$prdid");
list($relation1) = $db->sql_fetchrow_simple($result_prd1);
if($relation1 != ""){
	$rel_id = explode(",", $relation1);
	$relate = "";
	for($i = 0; $i < sizeof($rel_id);$i ++){
		if($rel_id[$i] == 0){
			$relate .= "";			
		} else if($rel_id[$i] == $rid){
			$relate .= "";	
		} else {
			$relate .= $rel_id[$i].",";
		}
	}
}

$result = $db->sql_query_simple("UPDATE {$prefix}_products SET relation = '$relate' WHERE id = '$prdid'");

header("Location: modules.php?f=".$adm_modname."&do=edit&id=$prdid");

?>