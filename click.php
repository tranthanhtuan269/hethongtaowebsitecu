<?php

define('CMS_SYSTEM', true);
@require_once("config.php");

$id = intval($_GET['id']);
$result_adv_click = $db->sql_query_simple("SELECT links FROM ".$prefix."_advertise WHERE id='$id' AND alanguage='$currentlang' AND active='1'");
if(empty($id) || $db->sql_numrows($result_adv_click) != 1) die();

list($links) = $db->sql_fetchrow_simple($resultcat);
$db->sql_query_simple("UPDATE ".$prefix."_advertise SET hits=hits+1 WHERE id='$id'");
header("Location: $links");

?>