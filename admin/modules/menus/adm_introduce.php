<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = _MENU_INTRO;

$submenu = array(
	"<li><a href=\"modules.php?f=introduce\" target=\"_top\">"._MENU_INTRO1."</a></li>",
	"<li><a href=\"modules.php?f=introduce&do=create_intro\" target=\"_top\">"._MENU_INTRO2."</a></li>",
	"<li><a href=\"modules.php?f=introduce&do=categories\" target=\"_top\">"._MENU_INTRO3."</a></li>",
	"<li><a href=\"modules.php?f=introduce&do=create_cat\" target=\"_top\">"._MENU_INTRO4."</a></li>",
	"<li><a href=\"modules.php?f=introduce&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);
?>