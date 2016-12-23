<?php
if(!defined('CMS_ADMIN') || !defined('iS_RADMIN')) die('Stop');

include("page_header.php");
if (isset($_GET['mid'])) {
	$mid = intval($_GET['mid']);
	$stat = intval($_GET['stat']);
	$db->sql_query_simple("UPDATE ".$prefix."_admin_menu SET action='$stat' WHERE mid='$mid'");
}
if (isset($_POST['submit'])) {
	$poz = $_POST['poz'];
	foreach ($poz as $idx => $weight) {
		$idx = intval($idx);
		$weight = intval($weight);
		$db->sql_query_simple("UPDATE ".$prefix."_admin_menu SET weight='$weight' WHERE mid='$idx'");
	}

	$sql = "SELECT mid FROM ".$prefix."_admin_menu ORDER BY weight";
	$result = $db->sql_query_simple($sql);
	$xweight = 1;
	while ($row = $db->sql_fetchrow_simple($result)) {
		$db->sql_query_simple("UPDATE ".$prefix."_admin_menu SET weight = '$xweight'  WHERE mid = '$row[mid]'");
		$xweight++;
	}
}

$dir = @opendir("menus");
$setmodules = 1;
$admin_list_menu = array();
while( $filemn = readdir($dir) ){
	if (preg_match("/^adm\_(.+)\.php/i", $filemn, $matches)) {
		$file_ar = @explode(".",$filemn);
		$filearr = $file_ar[0];
		$file_arr = @explode("_",$filearr);
		$filemn = $file_arr[1];
		$admin_list_menu[] = $filemn;
	}
}
@closedir($dir);
sort($admin_list_menu);

$admin_list_menu_data = array();
$ml2result = $db->sql_query_simple("SELECT mid, file_menu  FROM ".$prefix."_admin_menu");
while($rowmod = $db->sql_fetchrow_simple($ml2result)) {
	$admin_list_menu_data[] = $rowmod['file_menu'];
	if (!in_array($rowmod['file_menu'], $admin_list_menu)) {
		$db->sql_query_simple("DELETE FROM ".$prefix."_admin_menu  WHERE mid='$rowmod[mid]'");
	}
}
sort($admin_list_menu_data);

for ($i=0; $i < sizeof($admin_list_menu); $i++) {
	if($admin_list_menu[$i] != "" AND !@in_array($admin_list_menu[$i],$admin_list_menu_data)) {
		$result = $db->sql_query_simple("SELECT max(weight) as xweight FROM ".$prefix."_admin_menu");
		list($xweight) = $db->sql_fetchrow_simple($result);
		$weight = $xweight + 1;
		$db->sql_query_simple("INSERT INTO ".$prefix."_admin_menu (file_menu, weight, action) VALUES ('$admin_list_menu[$i]', '$weight', '1')");
	}
}

$result = $db->sql_query_simple("SELECT * FROM ".$prefix."_admin_menu ORDER BY weight");
$total = $db->sql_numrows($result);
if($db->sql_numrows($result) > 0) {

	ajaxload_content();
	echo "<div id=\"".$adm_modname."_main\"><br><form action=\"modules.php?f=$adm_modname&do=$do\" name=\"frm\"  method=\"POST\">";
	echo "<table border=\"0\" width=\"50%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">Admin menus</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">"._POSITION."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "</tr>\n";

	while(list($mid, $file_menu, $weight, $action) = $db->sql_fetchrow_simple($result)) {
		if(file_exists("menus/adm_".$file_menu.".php")) {
			include("menus/adm_".$file_menu.".php");
		}
		if($ajax_active == 1) {
			switch($action) {
				case 1: $action = "<a href=\"modules.php?f=$adm_modname&mid=$mid&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($mid,0,'$adm_modname','','mid');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $action = "<a href=\"modules.php?f=$adm_modname&mid=$mid&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($mid,1,'$adm_modname','','mid');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($action) {
				case 1: $action = "<a href=\"modules.php?f=$adm_modname&mid=$mid&stat=0\" title=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $action = "<a href=\"modules.php?f=$adm_modname&mid=$mid&stat=1\" title=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		echo "<tr>\n";
		echo "<td class=\"row3\"><b>$menu_main</b></td>\n";
		echo "<td align=\"center\" class=\"row3\"><input type=\"text\" name=\"poz[$mid]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px\"></td>\n";
		echo "<td align=\"center\" class=\"row1\"><font color=\"red\">$action</font></td>\n";
		echo "</tr>\n";
		$i ++;
	}
	echo "<tr><td colspan=\"9\" align=\"center\" class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	echo "</table><br></div>";
}

include_once("page_footer.php");

?>