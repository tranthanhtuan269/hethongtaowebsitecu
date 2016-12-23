<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

global $maxsize_up, $path_upload;

$err_parent = $err_title = $err_desc = $err_content = $content = $tag_seo = $title_seo = $keyword_seo = $description_seo = $title = $desc = $imgtext = '';
$err = $imgshow = 0;
$parent = -1;
if(isset($_POST['subup'])) {
	$parent = intval($_POST['parent']);
	if ($parent == -1) {
		$err_parent = "<font color=\"red\">"._INTRO_ERROR_TOPIC."</font><br>";
		$err = 1;
	}
	if (empty($_POST['title'])) {
		$err_title = "<font color=\"red\">"._INTRO_ERROR_TITLE."</font><br>";
		$err = 1;
	}
	if (empty($_POST['content'])) {
		$err_content = "<font color=\"red\">"._INTRO_ERROR_CONTENT."</font><br>";
		$err = 1;
	}
	if (empty($_POST['desc'])) {
		$err_desc = "<font color=\"red\">"._INTRO_ERROR_DESC."</font><br>";
		$err = 1;
	}
	if (!$err) {
		$content = $escape_mysql_string($_POST['content']);
		$title = $escape_mysql_string($_POST['title']);
		$desc = $escape_mysql_string($_POST['desc']);
		$imgtext = $escape_mysql_string(trim($_POST['imgtext']));
		$imgshow = intval($_POST['imgshow']);
		$tag_seo = nospatags($_POST['tag_seo']);
		$title_seo = nospatags($_POST['title_seo']);
		$keyword_seo = nospatags($_POST['keyword_seo']);
		$description_seo = trim(stripslashes(resString($_POST['description_seo'])));

		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", "$path_upload/image", $maxsize_up, $adm_modname);
			$image = $upload->send();
			resizeImg($image, "$path_upload/image", $intro_thumbsize);
		} else $image = '';

		if (empty($image)) $imgtext = '';
		
		$db->sql_query_simple("INSERT INTO {$prefix}_intro (title, body, description, tag_seo, title_seo, keyword_seo, description_seo, parent, image, imgtext, imgshow, lang) VALUES ('$title', '$content', '$desc', '$tag_seo', '$title_seo', '$keyword_seo', '$description_seo', $parent, '$image', '$imgtext', $imgshow, '$currentlang')");
		updateadmlog($admin_ar[0], $adm_modname, _INTRODUCE, _INTRO_CREATE_INTRO);
		header("Location: modules.php?f=$adm_modname");
	} else {
		$content = $_POST['content'];
		$title = $_POST['title'];
		$desc = $_POST['desc'];
		$imgtext = $_POST['image'];
		$imgshow = intval($_POST['imgshow']);
	}
}

include_once("page_header.php");

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if (f.title.value == '') {\n";
echo "			alert('"._INTRO_ERROR_TITLE."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if (f.parent.value == -1) {\n";
echo "			alert('"._INTRO_ERROR_TOPIC."');\n";
echo "			f.parent.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;\n";
echo "	}\n";
echo "</script>\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._INTRODUCE."</td></tr>";
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_TITLE."</b></td>\n<td class=\"row2\">\n$err_title<input type=\"text\" id=\"title\" value=\"$title\" size=\"50\" name=\"title\">\n</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_SUBCAT_OF."</b></td>\n<td class=\"row2\">\n$err_parent<select name=\"parent\" id=\"parent\">\n";
echo "<option value=\"-1\">"._INTRO_CHOOSE_TOPIC."</option>\n";
$db->sql_query_simple("SELECT id, title FROM {$prefix}_intro_cat WHERE alanguage='$currentlang' ");
while (list($catId, $catTitle) = $db->sql_fetchrow_simple()) {
	$parentSelected = '';
	if ($catId == $parent) $parentSelected = ' selected="selected"';
	echo "<option value=\"$catId\"$parentSelected>$catTitle</option>\n";
}
echo "</select></td><tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_DESC."</b></td>\n";
echo "<td class=\"row2\">\n$err_desc";
editor("desc", $desc, "", 400);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_CONTENT."</b></td>\n";
echo "<td class=\"row2\">\n$err_content";
editor("content", $content, "", 400);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_IMG_TEXT."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"70\" maxlength=\"255\"></td>\n";
echo "</tr>\n";
if ($imgshow == 1) {
	$yesSelected = ' checked="checked"';
	$noSelected = '';
} else {
	$yesSelected = '';
	$noSelected = ' checked="checked"';
}
echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_SHOW_IMG_DETAIL."</b></td>\n";
echo "<td class=\"row2\"><input type=\"radio\" name=\"imgshow\" value=\"1\"$yesSelected>"._YES." &nbsp;&nbsp;";
echo "<input type=\"radio\" name=\"imgshow\" value=\"0\" checked=\"checked\"$noSelected>"._NO."</td>\n";
echo "</tr>\n";
if($mod_seo == 1){
//=== tag SEO
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._TAG_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"tag_seo\" value=\"$tag_seo\" size=\"80\">&nbsp;&nbsp;"._TAG_NOTE."</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._TITLE_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title_seo\" value=\"$title_seo\" size=\"80\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"keyword_seo\" value=\"$keyword_seo\" size=\"80\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._DESCRIPTION_SEO."</b></td>\n";
echo "<td class=\"row2\"><textarea name=\"description_seo\"  cols=\"77\" rows=\"4\">$description_seo</textarea></td>\n";
echo "</tr>\n";
//=== end tag SEO
}
echo '<input type="hidden" value="1" name="subup">';
echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table></form><br>";

include_once("page_footer.php");
?>