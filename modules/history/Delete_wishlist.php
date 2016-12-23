<?php

global $userInfo;  

$pid = intval($_GET['pid']);
$page = intval($_GET['page']);
$userid = $userInfo['id'];
$result = $db->sql_query_simple("SELECT id FROM ".$prefix."_wishlist WHERE prd_id='$pid' AND userid='$userid'");
if($db->sql_numrows($result) != 1) {
	header("Location: index.php?f=history&do=wishlist&page=$page");
	die();
} else {
	$db->sql_query_simple("DELETE FROM ".$prefix."_wishlist WHERE prd_id='$pid' AND userid='$userid'");
	header("Location: index.php?f=history&do=wishlist&page=$page");
}
?>