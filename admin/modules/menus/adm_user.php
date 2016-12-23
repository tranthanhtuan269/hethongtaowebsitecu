<?php

if (!defined('CMS_ADMIN')) die("Illegal File Access");

if(defined('iS_RADMIN')) {
	$menu_main = _MENU_USER;
	$submenu = array(
		"<li><a href=\"modules.php?f=user\" target=\"_top\">"._MENU_USER_MANAGE_USER."</a></li>",
		"<li><a href=\"modules.php?f=user&do=edit\" target=\"_top\">"._MENU_USER_ADD_USER."</a></li>",
		"<li><a href=\"modules.php?f=user&do=rule\" target=\"_top\">"._MENU_USER_RULE."</a></li>"
	);
}

?>
