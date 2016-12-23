<?php
if (!defined('CMS_ADMIN')) {
	die("Illegal File Access");
	exit;
}
$menu_main = "Menu Hotline";
$submenu = array(
	"<li><a href=\"modules.php?f=daily\" target=\"_top\">Danh sách tư vấn viên</a></li>",
	"<li><a href=\"modules.php?f=daily&do=Tinh\" target=\"_top\">Hotline hỗ trợ</a></li>",
// 	 "<li><a href=\"modules.php?f=daily&do=Huyen\" target=\"_top\">Danh Sách Huyện</a></li>",
// 	// "<li><a href=\"modules.php?f=news&do=config\" target=\"_top\">"._CONFIG."</a></li>"
);

?>