<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = _MAINCONFIG;

$submenu = array(
	"<li><a href=\"modules.php?f=configuration\" target=\"_top\">"._CONFIG."</a></li>",
	"<li><a href=\"modules.php?f=gentext&do=scroll\" target=\"_top\">"._SCROLLTEXT."</a></li>",
	"<li><a href=\"modules.php?f=adminmenu\" target=\"_top\">"._MAINMENUS_ADMIN."</a></li>",
	"<li><a href=\"modules.php?f=langEditor\" target=\"_top\">"._LANG_EDITOR."</a></li>",
	"<li><a href=\"modules.php?f=database\" target=\"_top\">"._MENU_DATABASE."</a></li>"
	
);


?>