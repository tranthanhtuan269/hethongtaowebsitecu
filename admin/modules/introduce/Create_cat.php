<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

global $maxsize_up, $path_upload;

$err_title = '';
$err_desc = '';
$title = '';
$desc = '';
if (isset($_POST['subup'])) {
	$err = 0;
	$title = $escape_mysql_string($_POST['title']);
	$desc = $escape_mysql_string($_POST['desc']);
	if (empty($title)) {
		$err_title = "<font color=\"red\">"._INTRO_ERROR_TITLE."</font><br />";
		$err = 1;
	}
	if (empty($desc)) {
		$err_desc = "<font color=\"red\">"._INTRO_ERROR_DESC."</font><br />";
		$err = 1;
	}
	if (!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", "$path_upload/image", $maxsize_up, $adm_modname);
			$image = $upload->send();
			resizeImg($image, "$path_upload/image", $intro_thumbsize, '', '', true);
		} else $image = '';
		$db->sql_query_simple("INSERT INTO {$prefix}_intro_cat (title, description, image, alanguage) VALUES ('$title', '$desc', '$image', '$currentlang')");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _INTRO_CREATE_CAT);
		header("Location: modules.php?f=$adm_modname&do=categories");
	} else {
		$title = $_POST['title'];
		$desc = $_POST['desc'];
	}
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._INTRO_ERROR_TITLE."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;\n";
echo "	}\n";
echo "</script>\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\">\n<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._INTRO_CREATE_CAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INTRO_TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INTRO_DESC."</b></td>\n";
echo "<td class=\"row2\">$err_desc";
editor("desc", $desc, "", 400);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" id=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>\n";
echo "</table></form>\n";

include_once("page_footer.php");
?>