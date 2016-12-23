<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function AccCheck($nick,$acc="") {
	global $db, $prefix;
	if ((!$nick) || ($nick=="") || (preg_match("![^a-zA-Z0-9_-]!",$nick))) { $stop = ""._ERROR1.""; }
	elseif (strlen($nick) > 10) { $stop = ""._ERROR1.""; }
	elseif (strlen($nick) < 3) { $stop = ""._ERROR1.""; }
	elseif (strrpos($nick,' ') > 0) { $stop = ""._ERROR1.""; }
	elseif ($db->sql_numrows($db->sql_query_simple("SELECT adacc FROM ".$prefix."_admin WHERE adacc='$nick' AND adacc!='$acc'")) > 0) { $stop = ""._ERROR1_1.""; }
	else { $stop = ""; }
    return($stop);
}



?>