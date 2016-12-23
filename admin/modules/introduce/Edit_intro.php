<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

global $maxsize_up, $path_upload;

if (isset($_GET['id'])) {
	$introId = intval($_GET['id']);

	$err_parent = '';
	$err_title = '';
	$err_desc = '';
	$err_content = '';
	$err = 0;
	$content = '';
	$title = '';
	$desc = '';
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
			$delimg = isset($_POST['delimg']) ? intval($_POST['delimg']) : 0;
			$content = $escape_mysql_string($_POST['content']);
			$title = $escape_mysql_string($_POST['title']);
			$desc = $escape_mysql_string($_POST['desc']);
			$image = isset($_POST['image']) ? $escape_mysql_string(trim($_POST['image'])) : '';
			$imgtext = $escape_mysql_string(trim($_POST['imgtext']));
			$imgshow = intval($_POST['imgshow']);

			if (($delimg == 1) && (!empty($image))) {
				@unlink(RPATH."$path_upload/image/$image");
				@unlink(RPATH."$path_upload/image/thumb_$image");
				$image = "";
			}

			if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
				$upload = new Upload("userfile", "$path_upload/image", $maxsize_up, $adm_modname);
				$image1 = $upload->send();
				resizeImg($image1, "$path_upload/image", $intro_thumbsize);
				if (!empty($image1) && !empty($image)) {
					@unlink(RPATH."$path_upload/image/$image");
					@unlink(RPATH."$path_upload/image/thumb_$image");
					$image = "";
				}
			} else {
				$image1 = $image;
			}

			if(empty($image1) && empty($image)) { $imgtext = ''; }

			$query = "UPDATE {$prefix}_intro SET title='$title', body='$content', description='$desc', tag_seo = '$tag_seo', title_seo = '$title_seo', keyword_seo = '$keyword_seo', description_seo = '$description_seo', parent=$parent, image=";
			if (empty($image1) && empty($image)) $query .= "''";
			elseif (!empty($image1)) $query .= "'$image1'";
			else $query .= "'$image'";
			$query .= ", imgtext='$imgtext', imgshow=$imgshow WHERE id=$introId";
			$db->sql_query_simple($query);
			updateadmlog($admin_ar[0], $adm_modname, _INTRODUCE, _INTRO_UPDATE_INTRO);
			header("Location: modules.php?f=$adm_modname");
		} else {
			$content = $_POST['content'];
			$title = $_POST['title'];
			$desc = $_POST['desc'];
			$imgtext = $_POST['imgtext'];
			$imgshow = intval($_POST['imgshow']);
			$image = isset($_POST['image']) ? $_POST['image'] : '';
			$tag_seo = nospatags($_POST['tag_seo']);
			$title_seo = nospatags($_POST['title_seo']);
			$keyword_seo = nospatags($_POST['keyword_seo']);
			$description_seo = trim(stripslashes(resString($_POST['description_seo'])));
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

	$db->sql_query_simple("SELECT title, body, description, tag_seo, title_seo, keyword_seo, description_seo, parent, image, imgtext, imgshow FROM {$prefix}_intro WHERE id=$introId");
	if ($db->sql_numrows() < 1) {
		header("Location: modules.php?f=$adm_modname&do=create_intro");
	} else {
		if (!isset($_POST['subup'])) {
			list($title, $content, $desc, $tag_seo, $title_seo, $keyword_seo, $description_seo, $parent, $image, $imgtext, $imgshow) = $db->sql_fetchrow_simple();
		}
		echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$introId\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
		echo "<tr><td class=\"header\" colspan=\"2\">"._INTRODUCE."</td></tr>";
		echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_TITLE."</b></td>\n<td class=\"row2\">\n$err_title<input type=\"text\" id=\"title\" value=\"$title\" size=\"50\" name=\"title\">\n</td></tr>\n";
		echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_SUBCAT_OF."</b></td>\n<td class=\"row2\">\n$err_parent<select name=\"parent\" id=\"parent\">\n";
		echo "<option value=\"-1\">"._INTRO_CHOOSE_TOPIC."</option>\n";
		$db->sql_query_simple("SELECT id, title FROM {$prefix}_intro_cat WHERE alanguage = '$currentlang'");
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
		if (empty($image)) {
			echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_IMAGE."</b></td>\n";
			echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
			echo "</tr>\n";
		} else {
			echo "	<tr>\n";
			echo "		<td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_DELIMAGE."</b></td>\n";
			echo "		<td class=\"row2\"><input type=\"checkbox\" name=\"delimg\" value=\"1\">&nbsp;<a href=\"".RPATH."/$path_upload/image/$image\" target=\"_blank\" info=\""._INTRO_VIEW_IMAGE."\"><img border=\"0\" src=\"../images/img.gif\" align=\"absmiddle\"></a></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_CHANGE_IMAGE."</b></td>\n";
			echo "		<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"40\"></td>\n";
			echo "	</tr>\n";
			echo "<input type=\"hidden\" name=\"image\" value=\"$image\">";
		}
		$htmlImgText = '';
		if (!empty($imgtext)) $htmlImgText = $imgtext;
		echo "<tr><td class=\"row1\" width=\"20%\" align=\"right\"><b>"._INTRO_IMG_TEXT."</b></td>\n";
		echo "<td class=\"row2\"><input type=\"text\" name=\"imgtext\" size=\"70\" maxlength=\"255\" value=\"$htmlImgText\"></td>\n";
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
		echo "<input type=\"radio\" name=\"imgshow\" value=\"0\"$noSelected>"._NO."</td>\n";
		echo "</tr>\n";
		if($mod_seo == 1){
		//=== Tag SEO
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
		//=== end Tag SEO
		}
		echo '<input type="hidden" value="1" name="subup">';
		echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
		echo "</table></form><br>";
	}
	include_once("page_footer.php");
} else {
	header("Location: modules.php?f=$adm_modname&do=create_intro");
}
?>