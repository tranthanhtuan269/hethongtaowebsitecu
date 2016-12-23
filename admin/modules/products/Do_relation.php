<?php
if(!defined('CMS_ADMIN')) die();

$id = $_POST['id'];

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

$prdid = intval(isset($_GET['pid']) ? $_GET['pid'] : (isset($_POST['pid']) ? $_POST['pid']:0));

$result_prd = $db->sql_query_simple("SELECT relation FROM {$prefix}_products WHERE id=$prdid");
list($relation) = $db->sql_fetchrow_simple($result_prd);

	for($i =0; $i < sizeof($id); $i ++) {
		$relation .= intval($id[$i]).",";		
	}
	
	$db->sql_query_simple("UPDATE ".$prefix."_products SET relation='$relation' WHERE id=$prdid");
	
echo "<script type=\"text/javascript\">
	window.close();
</script>";
	
?>