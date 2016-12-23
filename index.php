<?php

if (!file_exists("config.php")) exit();
define('CMS_SYSTEM', true);
@require_once("config.php");
//echo 1; die;
global $disable_site,$disable_message;
if($disable_site == 1 && defined('CMS_ADMIN')){
	include_once("disable_site.php");
}
else
{
    if(isset($_GET['f']) || isset($_POST['f'])) {
    	$home = 0;
    	$f = trim(isset($_POST['f']) ? $_POST['f'] : $_GET['f']);
    	if(isset($_GET['do']) || isset($_POST['do'])) {
    		$do = trim(isset($_POST['do']) ? $_POST['do'] : $_GET['do']);
    		$do = ucfirst($do);
    	} else {
    		$do = "index";
    	}
    	if (preg_match("![^a-zA-Z0-9_]!", $do)) {

    	//info_exit(_FILENOTFOUND." $f/$do");

    	 }

    	if(isset($_GET['op']) || isset($_POST['op'])) {
    		$op = trim(isset($_POST['op']) ? $_POST['op'] : $_GET['op']);
    	} else {
    		$op = "";
    	}
    	if (preg_match("![^a-zA-Z0-9_]!", $op)) {
    	   info_exit(_FUNCTIONNOTFOUND);
    	}
    }
    else
    {
    	$f = $Home_Module;
    	$home = 1;
    	$do = "index";
    	$op = "";
    }


    if (preg_match("![^a-zA-Z0-9_]!", $f)) {
        info_exit(_FUNCTIONNOTFOUND);
    }

    $resultloadmod = $db->sql_query_simple("SELECT * FROM ".$prefix."_modules WHERE title='".addslashes($f)."' AND alanguage='$currentlang'");
    $rowloadmod = $db->sql_fetchrow_simple($resultloadmod);
    if(!$rowloadmod) {

    	//info_exit(_PROBLEMMOD);

    	}
    $page_title = $rowloadmod['custom_title'];
    if ($home == 1) { $page_title = _HOMEPAGE; }

    $module_active = intval($rowloadmod['active']);
    $module_view = intval($rowloadmod['view']);
    $module_title = $rowloadmod['title'];
    $index = intval($rowloadmod['mindex']);

    if (($module_active != 1) AND !defined('iS_ADMIN')) {
    //info_exit(_MODULENOTACTIVE);
    }

    $module_path = "modules/$f/$do.php";

    if(file_exists($module_path)) {
    	getlangmod($f);
    	if (defined('_MODTITLE') && $home == 0) {
    		$page_title = _MODTITLE;
    	}
        
    	$module_name = $f;
    	if(file_exists(DATAFOLD."/config_".$module_name.".php")) {
    		require_once(DATAFOLD."/config_".$module_name.".php");
    	}
    	if(file_exists("templates/".$Default_Temp."/module_".$module_name.".php")) {
    		include("templates/".$Default_Temp."/module_".$module_name.".php");
    	}
    	if(file_exists("modules/".$module_name."/Functions.php")) {
    		include("modules/".$module_name."/Functions.php");
    	}
    	if ($module_view == 0) { include($module_path); }
    	elseif ($module_view == 1 && defined('iS_ADMIN')) { include($module_path); }
    	elseif ($module_view == 2 && (defined('iS_ADMIN') || defined('iS_CUS'))) { include($module_path); }
    	else {

    	info_exit(_MODULENOTACTIVE);

    	 }
    } else {
    	info_exit(_FILENOTFOUND);
    }

}
?>