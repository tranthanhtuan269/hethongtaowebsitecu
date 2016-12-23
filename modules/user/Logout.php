<?php
if (!defined('CMS_SYSTEM')) die();

if (defined('iS_USER') && isset($userInfo)) unset($_SESSION[USER_SESS]);

if (isset($_GET['type']) && ($_GET['type'] == 'quick') && file_exists("blocks/{$_GET['block']}")) {
	$userInfo = checkUser();
	if (!$userInfo) unset($userInfo);
	include_once("blocks/{$_GET['block']}");
	echo $content;
} else echo "<script language = javascript>history.back();</script>";	
?>