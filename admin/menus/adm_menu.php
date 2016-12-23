<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_MENUS."";

$submenu = array(
	"<li><a href=\"modules.php?f=mainmenus\" target=\"_top\">"._MAINMENUS_USER."</a></li>",
	"<li><a href=\"modules.php?f=leftmenus\" target=\"_top\">"._MAINMENUS_LEFT."</a></li>",
	"<li><a href=\"modules.php?f=footermenus\" target=\"_top\">"._MAINMENUS_FOOTER."</a></li>",
	// "<li><a href=\"modules.php?f=footermenus\" target=\"_top\">Danh mục trang chủ</a></li>",


);


?>