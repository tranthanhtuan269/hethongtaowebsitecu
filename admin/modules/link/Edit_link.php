<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);

$result = $db->sql_query_simple("SELECT title, active, special, links FROM ".$prefix."_link WHERE id='$id' AND alanguage='$currentlang'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=".$adm_modname."");
	die();
}

list($title, $active, $special, $links) = $db->sql_fetchrow_simple($result);
$err_title = $err_links = $err  = "";
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$links = trim(stripslashes(resString($_POST['links'])));
	$special = isset($_POST['special']) ? intval($_POST['special']) : 0;
	$active = intval($_POST['active']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	
	if(!$err) {
		$db->sql_query_simple("UPDATE ".$prefix."_link SET title='$title', links='$links', active='$active', special='$special' WHERE id='$id'");

		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDITCAT);
		header("Location: modules.php?f=".$adm_modname."");
	}
}

include("page_header.php");

ajaxload_content();

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if(f.links.value =='') {\n";
echo "			alert('"._ERROR_LINK."');\n";
echo "			f.links.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._EDITCOLOR."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._URL."</b></td>\n";
echo "<td class=\"row2\">$err_links<input type=\"text\" name=\"links\" value=\"$links\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ACTIVE."</b></td>\n";
if($active == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SPECIAL."</b></td>\n";
if($special == 1) { $check = ' checked="checked"'; } else { $check = ""; }
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"special\" value=\"1\"$check></td>\n";
echo "</tr>\n";

echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=".$adm_modname."&do=color'\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");
?>