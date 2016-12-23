<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_QUESTION_ANWSER."";

$submenu = array(
	"<li><a href=\"modules.php?f=question&do=question\" target=\"_top\">"._MENU_QUESTION."</a></li>",
	"<li><a href=\"modules.php?f=question&do=answer\" target=\"_top\">"._MENU_ANWSER."</a></li>",
	"<li><a href=\"modules.php?f=question&do=categories\" target=\"_top\">"._MENU_QUESTION_CAT."</a></li>",
	"<li><a href=\"modules.php?f=question&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);


?>