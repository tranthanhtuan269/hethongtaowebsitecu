<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = "Album ảnh";

$submenu = array(
	"<li><a href=\"modules.php?f=picture\" target=\"_top\">Danh sách</a></li>",
	"<li><a href=\"modules.php?f=picture&do=create\" target=\"_top\">Đăng ảnh</a></li>",
	"<li><a href=\"modules.php?f=picture&do=categories\" target=\"_top\">Danh mục</a></li>",
);
?>