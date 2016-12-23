<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$result = $db->sql_query_simple("SELECT title, active, images, links, guid, permalink FROM ".$prefix."_video WHERE id=$id AND alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}
list($title, $active, $images, $links, $guid, $permalink) = $db->sql_fetchrow_simple($result);
$err_title = "";
$err_links = "";
$err = "";
$path_upload_img = "$path_upload/$adm_modname";
if (isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$active = intval($_POST['active']);
	$links=$_POST['links'];
	//$images = isset($_POST['images']) ? $escape_mysql_string(trim($_POST['images'])) : '';
    $guid="index.php?f=video&do=detail&id=$id";
	if (empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
    else
    {
        $permalink=url_optimization(trim($title));
    }
	if(empty($links)) {
		$err_links = "<font color=\"red\">"._ERROR_LINKS."</font><br>";
		$err = 1;
	}
	if($images == 1) {
		@unlink("../$path_upload_img/$images");
		//@unlink("../$path_upload_img/thumb_".$images);
		$images = "";
	}

	if (!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images_up = $upload->send();
			if(!empty($images_up) && !empty($images))  {
				//resizeImg($images_up, $path_upload_img, $prd_thumbsize);
				@unlink("../$path_upload_img/$images");
				//@unlink("../$path_upload_img/thumb_".$images);
			} else {
				$images_up = $images;
			}
		} else {
			$images_up = $images;
		}
		$db->sql_query_simple("UPDATE ".$prefix."_video SET title='$title', permalink = '$permalink', guid = '$guid', active='$active', images='$images_up', links='$links' WHERE id='$id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT_NEWS_CAT);
		header("Location: modules.php?f=".$adm_modname."&page=$page");
	}
}



include_once("page_header.php");

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form method=\"POST\" action=\"modules.php?f=".$adm_modname."&do=edit&id=$id\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._EDITCAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";

if(!empty($images) && file_exists("../$path_upload_img/$images")) {
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._DEL_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic\" value=\"1\"> <a href=\"../$path_upload_img/$images\" target=\"_blank\"><img border=\"0\" src=\"../images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"48\"></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"48\"></td>\n";
	echo "</tr>\n";
}

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._LINKS."</b><br>"._NOTE_VIDEO."</td>\n";
echo "<td class=\"row2\">$err_links<textarea id=\"links\" name=\"links\"  cols=\"57\" rows=\"2\">$links</textarea></td>\n";
echo "</tr>\n";

echo "<tr bgcolor=\"#F7F7F7\">\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ACTIVE."</b></td>\n";
if ($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}

echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=".$adm_modname."&page=$page'\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>