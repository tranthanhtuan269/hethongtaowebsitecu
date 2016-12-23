<?php
if (!defined('CMS_ADMIN')) die("Illegal File Access");

$menu_main = _MENU_PROPERTY;

$submenu = array(	
	"<li><a href=\"modules.php?f=products&do=categories\" target=\"_top\">"._MENU_PRODUCT2."</a></li>",
	//"<li><a href=\"modules.php?f=products&do=color\" target=\"_top\">"._MENU_PRODUCT6."</a></li>",
	//"<li><a href=\"modules.php?f=products&do=size\" target=\"_top\">"._MENU_PRODUCT7."</a></li>",
	//"<li><a href=\"modules.php?f=products&do=company\" target=\"_top\">"._MENU_PRODUCT8."</a></li>",
	//"<li><a href=\"modules.php?f=products&do=national\" target=\"_top\">"._MENU_PRODUCT9."</a></li>",
	//"<li><a href=\"modules.php?f=products&do=age\" target=\"_top\">"._MENU_PRODUCT12."</a></li>",		
	//"<li><a href=\"modules.php?f=products&do=age\" target=\"_top\">"._MENU_PRODUCT12."</a></li>",		
	"<li><a href=\"modules.php?f=products&do=property\" target=\"_top\">"._MENU_PROPERTY_ADMIN."</a></li>",		
	"<li><a href=\"modules.php?f=products&do=value_standard\" target=\"_top\">"._MENU_PROPERTY_STAND."</a></li>",
	"<li><a href=\"modules.php?f=products&do=create_filter\" target=\"_top\">"._MENU_FILTER."</a></li>",
				
	//"<li><a href=\"modules.php?f=products&do=huongdan\" target=\"_top\">"._MENU_PRODUCT11."</a></li>",
	//"<li><a href=\"modules.php?f=products&do=taikhoan\" target=\"_top\">"._MENU_PRODUCT13."</a></li>"
);
?>