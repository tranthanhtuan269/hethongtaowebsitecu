<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
delete_standard($id);
header("Location: modules.php?f=".$adm_modname."&do=value_standard");

?>