<?php
//define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/".$currentlang."/menu.php");
if(@in_array($listmenus,$file_menu)) {
		$seld =" checked";
	}
if(defined('iS_ADMIN')) 
{
    ?>
    <div class="sidebar" role="navigation">
        <div class="navbar-collapse">
            <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
                <div id="content-1" class="content">
                    <ul class="nav" id="side-menu">
                        <?php
                        $result = $db->sql_query_simple("SELECT menus FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'");
                        if(empty($admin_ar[0]) || $db->sql_numrows($result) != 1)
                        {
                            echo"error";
                        }
                        list($menus) = $db->sql_fetchrow_simple($result);
                        $auth_menus2 = @explode("|",$menus);
                        $i=0;
                        $resultmenu = $db->sql_query_simple("SELECT * FROM ".$prefix."_admin_menu where action = '1' ORDER BY weight");
                        //                    echo "<pre>";
                        //                    print_r($not_accept_mod);
                        //                    echo "</pre>";
                        while(list($mid, $file_menu, $weight, $action) = $db->sql_fetchrow_simple($resultmenu))
                        {
                            if (file_exists("menus/adm_".$file_menu.".php") && (defined('iS_RADMIN') || (checkPermAdm($file_menu)/* && !in_array($file_menu,$not_accept_mod)*/)))
                            {
                                if(@in_array($listmenus[$i],$auth_menus2) || defined('iS_RADMIN'))
                                {
                                    include("menus/adm_".$file_menu.".php");
                                    if($menu_main != "" && $submenu !="")
                                    {
                                        echo "<li><a href=\"#\"  rel=\"ddsubmenu$i\"><i class=\"fa fa-cogs nav_icon\"></i>$menu_main <span class=\"fa arrow\"></span></a>";
                                        echo "<ul id=\"ddsubmenu$i\" class=\"nav nav-second-level collapse ddsubmenustyle\">";
                                        for($a =0; $a < sizeof($submenu); $a ++)
                                            echo "".$submenu[$a]."";
                                        echo "</ul>";
                                        echo "</li>";
                                    }
                                    else
                                    {
                                        echo "<li><a href=\"".$menu_main_link."\" target=\"_top\" class=\"catmenu\"><i class=\"fa fa-cogs nav_icon\"></i>$menu_main</a></li>";
                                    }
                                }

                            }
                            $i++;
                        }
                        ?>
                    </ul>
                </div>

                <div class="clearfix"> </div>
                <!-- //sidebar-collapse -->
            </nav>
        </div>
    </div>


    <div class="sticky-header header-section ">
        <div class="header-left">
            <!--toggle button start-->
            <button id="showLeftPush"><i class="fa fa-bars"></i></button>
            <!--toggle button end-->
            <!--logo -->
            <div class="logo">
                <a href="index.html">
                    <h1>Thietkewebtamphat.com</h1>
                    <span>AdminPanel</span>
                </a>
            </div>
            <!--//logo-->
            <!--search-box-->
           <!-- <div class="search-box">
                <form class="input">
                    <input class="sb-search-input input__field--madoka" placeholder="Search..." type="search" id="input-31" />
                    <label class="input__label" for="input-31">
                        <svg class="graphic" width="100%" height="100%" viewBox="0 0 404 77" preserveAspectRatio="none">
                            <path d="m0,0l404,0l0,77l-404,0l0,-77z"/>
                        </svg>
                    </label>
                </form>
            </div>-->
            <!--//end-search-box-->
            <div class="clearfix"> </div>
        </div>
        <div class="header-right">
            <!--notifications of menu start -->
            <!--<div class="profile_details_left">

                <ul class="nofitications-dropdown">
                    <li class="dropdown head-dpdn">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">3</span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="notification_header">
                                    <h3>You have 3 new messages</h3>
                                </div>
                            </li>
                            <li><a href="#">
                                    <div class="user_img"><img src="images/1.png" alt=""></div>
                                    <div class="notification_desc">
                                        <p>Lorem ipsum dolor amet</p>
                                        <p><span>1 hour ago</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a></li>
                            <li class="odd"><a href="#">
                                    <div class="user_img"><img src="images/2.png" alt=""></div>
                                    <div class="notification_desc">
                                        <p>Lorem ipsum dolor amet </p>
                                        <p><span>1 hour ago</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a></li>
                            <li><a href="#">
                                    <div class="user_img"><img src="images/3.png" alt=""></div>
                                    <div class="notification_desc">
                                        <p>Lorem ipsum dolor amet </p>
                                        <p><span>1 hour ago</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a></li>
                            <li>
                                <div class="notification_bottom">
                                    <a href="#">See all messages</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown head-dpdn">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i><span class="badge blue">3</span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="notification_header">
                                    <h3>You have 3 new notification</h3>
                                </div>
                            </li>
                            <li><a href="#">
                                    <div class="user_img"><img src="images/2.png" alt=""></div>
                                    <div class="notification_desc">
                                        <p>Lorem ipsum dolor amet</p>
                                        <p><span>1 hour ago</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a></li>
                            <li class="odd"><a href="#">
                                    <div class="user_img"><img src="images/1.png" alt=""></div>
                                    <div class="notification_desc">
                                        <p>Lorem ipsum dolor amet </p>
                                        <p><span>1 hour ago</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a></li>
                            <li><a href="#">
                                    <div class="user_img"><img src="images/3.png" alt=""></div>
                                    <div class="notification_desc">
                                        <p>Lorem ipsum dolor amet </p>
                                        <p><span>1 hour ago</span></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </a></li>
                            <li>
                                <div class="notification_bottom">
                                    <a href="#">See all notifications</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown head-dpdn">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tasks"></i><span class="badge blue1">15</span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="notification_header">
                                    <h3>You have 8 pending task</h3>
                                </div>
                            </li>
                            <li><a href="#">
                                    <div class="task-info">
                                        <span class="task-desc">Database update</span><span class="percentage">40%</span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="progress progress-striped active">
                                        <div class="bar yellow" style="width:40%;"></div>
                                    </div>
                                </a></li>
                            <li><a href="#">
                                    <div class="task-info">
                                        <span class="task-desc">Dashboard done</span><span class="percentage">90%</span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="progress progress-striped active">
                                        <div class="bar green" style="width:90%;"></div>
                                    </div>
                                </a></li>
                            <li><a href="#">
                                    <div class="task-info">
                                        <span class="task-desc">Mobile App</span><span class="percentage">33%</span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="progress progress-striped active">
                                        <div class="bar red" style="width: 33%;"></div>
                                    </div>
                                </a></li>
                            <li><a href="#">
                                    <div class="task-info">
                                        <span class="task-desc">Issues fixed</span><span class="percentage">80%</span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="progress progress-striped active">
                                        <div class="bar  blue" style="width: 80%;"></div>
                                    </div>
                                </a></li>
                            <li>
                                <div class="notification_bottom">
                                    <a href="#">See all pending tasks</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"> </div>
            </div>-->
            <!--notification menu end -->
            <div class="profile_details">
                <ul>
                    <li class="dropdown profile_details_drop">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <div class="profile_img">
<!--                                <span class="prfil-img"><img src="images/a.png" alt=""> </span>-->
                                <div class="user-name">
                                    <p><?= $admin_ar[0] ?></p>
                                    <span>ThietkewebTamphat</span>
                                </div>
                                <i class="fa fa-angle-down lnr"></i>
                                <i class="fa fa-angle-up lnr"></i>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <ul class="dropdown-menu drp-mnu">
<!--                            <li> <a href="#"><i class="fa fa-cog"></i> Settings</a> </li>-->
<!--                            <li> <a href="#"><i class="fa fa-user"></i> Profile</a> </li>-->
                            <li> <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a> </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="clearfix"> </div>
        </div>
        <div class="clearfix"> </div>
    </div>
    <div id="page-wrapper">
        <div class="main-page">
    <?php

	//$not_accept_mod = array("blocks","modules","configuration","adminlog");



//	$not_accept_mod=array();
//	echo "<div class=\"div-header\">";
//	echo "<div class=\"div-banner\"><span class=\"version\">"._VERSION_ONECMS."<br>"._HELLO.": <b>$admin_ar[0]</b></span></div>";
//	echo "</div>";
//	echo "<div class=\"div-content\">";
//	echo "<div id=\"ddtopmenubar\" class=\"mattblackmenu\" style=\"float:left\">";
//	echo "<ul>";





	//tra ve mang menu duoc cap quyen truy cap












//	$result = $db->sql_query_simple("SELECT menus FROM ".$prefix."_admin WHERE adacc='$admin_ar[0]'");
//	if(empty($admin_ar[0]) || $db->sql_numrows($result) != 1)
//	{
//		echo"error";
//	}
//	list($menus) = $db->sql_fetchrow_simple($result);
//	$auth_menus2 = @explode("|",$menus);
//	$i=0;
//	$resultmenu = $db->sql_query_simple("SELECT * FROM ".$prefix."_admin_menu where action = '1' ORDER BY weight");
//	while(list($mid, $file_menu, $weight, $action) = $db->sql_fetchrow_simple($resultmenu))
//	{
//		if (file_exists("menus/adm_".$file_menu.".php") && (defined('iS_RADMIN') || (checkPermAdm($file_menu) && !in_array($file_menu,$not_accept_mod))))
//		{
//			if(@in_array($listmenus[$i],$auth_menus2) || defined('iS_RADMIN'))
//			{
//				include("menus/adm_".$file_menu.".php");
//				if($menu_main != "" && $submenu !="")
//				{
//					echo "<li><a href=\"#\"  rel=\"ddsubmenu$i\">$menu_main</a></li>";
//					echo "<ul id=\"ddsubmenu$i\" class=\"ddsubmenustyle\">";
//					for($a =0; $a < sizeof($submenu); $a ++)
//						echo "".$submenu[$a]."";
//					echo "</ul>";
//				}
//				else
//				{
//					echo "<li><a href=\"".$menu_main_link."\" target=\"_top\" class=\"catmenu\">$menu_main</a></li>";
//				}
//			}
//
//		}
//		$i++;
//	}

//	echo "<li><a href=\"logout.php\" target=\"_top\" title=\""._LOGOUT."\" onclick=\"return confirm('"._LOGOUTASK."');\" class=\"catmenu1\">"._LOGOUT."</a></li>";
//	echo "</ul>";
//	echo "</div >";
//	echo "<div  style=\"clear:left\"></div>";
//	echo "<script type=\"text/javascript\">";
//	echo "ddlevelsmenu.setup(\"ddtopmenubar\", \"topbar\") //ddlevelsmenu.setup(\"mainmenuid\", \"topbar|sidebar\")";
//	echo "</script>";
}else{
	header("Location: login.php");
}
?>
