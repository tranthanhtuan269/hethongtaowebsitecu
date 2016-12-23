<?php

$id = intval($_GET['id']);
$prdid = intval($_GET['prdid']);

$db->sql_query_simple("DELETE FROM ".$prefix."_comment WHERE id='$id'");

header("Location: index.php?f=products&do=detail&id=$prdid#link-comment");

?>