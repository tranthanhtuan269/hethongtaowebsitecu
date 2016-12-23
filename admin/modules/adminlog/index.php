<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

if(isset($_POST['delete']) && $_POST['delete'] !="") {
	$idx = $_POST['id'];
	foreach ($idx as $id) {
		$db->sql_query_simple("DELETE FROM ".$prefix."_admin_log WHERE id='$id'");	
	}		
	header("Location: modules.php?f=$adm_modname");
	exit;
}	

if(isset($_POST['deleteall']) && $_POST['deleteall'] !="") {
	$db->sql_query_simple("TRUNCATE TABLE ".$prefix."_admin_log");	
	header("Location: modules.php?f=$adm_modname");
	exit;
}	

include("page_header.php");

$perpage = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) :1);
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query_simple("SELECT*FROM ".$prefix."_admin_log"));
$result = $db->sql_query_simple("SELECT  id, adname, dateline, area, title, action, ip_add, alanguage  FROM ".$prefix."_admin_log ORDER BY dateline DESC LIMIT $offset, $perpage");
if($db->sql_numrows($result) > 0) {
echo "<script language=\"javascript\" type=\"text/javascript\">\n";
echo "function check_uncheck(){\n";
echo "	var f= document.frm;\n";
echo "	if(f.checkall.checked){\n";
echo "		CheckAllCheckbox(f,'id[]');\n";
echo "	}else{\n";
echo "	UnCheckAllCheckbox(f,'id[]');\n";
echo "	}\n";
echo "}\n";
echo "</script>\n";
ajaxload_content();
	
echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" name=\"frm\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr>\n";
echo "<tr><td colspan=\"8\" class=\"header\">"._MODTITLE."</td></tr>";
echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
echo "<td class=\"row1sd\">Administrators</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._DATELINE."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"80\">Files</td>\n";
echo "<td class=\"row1sd\" align=\"center\">"._AREA."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"200\">"._ACTION."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._LANGUAGE."</td>\n";
echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
echo "</tr>\n";
$cur_ar = array(_VND,_USD);
$i =0;
while(list($id, $adname, $time, $area, $title, $action, $ip_add, $alanguage) = $db->sql_fetchrow_simple($result)) {
if($i%2 == 1) { $css = "row1"; }	else { $css ="row3"; }	
echo "<tr>\n";
echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
echo "<td class=\"$css\"><a href=\"modules.php?f=authors&do=edit&acc=$adname\"><b>$adname</b></a></td>\n";
echo "<td class=\"$css\" align=\"center\">".ext_time($time, 2)."</td>\n";
echo "<td class=\"$css\" align=\"center\">".$area.".php</td>\n";
echo "<td class=\"$css\">$title</td>\n";
echo "<td class=\"$css\"><font color=\"red\">$action</font></td>\n";
echo "<td class=\"$css\" align=\"center\">$alanguage</td>\n";
if($ajax_active == 1) {
	echo "<td class=\"$css\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=delete&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK."','','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
} else {
	echo "<td class=\"$css\" align=\"center\" width=\"30\"><a href=\"?f=$adm_modname&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
}	
echo "</tr>\n";
$i ++;	
}
if($total > $perpage) {
	echo "<tr><td colspan=\"8\">";	
	$pageurl = "modules.php?f=".$adm_modname."";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}	
echo "<tr><td colspan=\"8\"> <input type=\"submit\" name=\"delete\" value=\""._QUICKDO_1."\"> <input type=\"submit\" name=\"deleteall\" value=\""._DELETEALL."\"></td></tr>";
echo "</table></form><br></div>";
	
}else{
	echo "<br>";
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}		

include_once("page_footer.php");

?>