<?php

$pid = intval($_GET['pid']);
$page = intval($_GET['page']);
$ip = getRealIpAddr();
$result = $db->sql_query_simple("SELECT id FROM ".$prefix."_history WHERE prd_id='$pid' AND ip='$ip'");
if($db->sql_numrows($result) != 1) {
	header("Location: index.php?f=history&page=$page");
	die();
} else {
	$db->sql_query_simple("DELETE FROM ".$prefix."_history WHERE prd_id='$pid' AND ip='$ip'");
	header("Location: index.php?f=history&page=$page");
}
?>