<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}

$menu_main = ""._MENU_CONTACT."";

$submenu = array(
	"<li><a href=\"modules.php?f=contact\" target=\"_top\">"._MENU_CONTACT1."</a></li>",
    "<li><a href=\"modules.php?f=gentext&do=scroll\" target=\"_top\">Nội dung hotline</a></li>",
	"<li><a href=\"modules.php?f=contact&do=address\" target=\"_top\">"._MENU_CONTACT2." chân trang</a></li>",
	"<li><a href=\"modules.php?f=contact&do=banggia\" target=\"_top\">Thông tin trang liên hệ</a></li>",
    "<li><a href=\"modules.php?f=contact&do=part\" target=\"_top\">"._MENU_CONTACT3."</a></li>"
);


?>