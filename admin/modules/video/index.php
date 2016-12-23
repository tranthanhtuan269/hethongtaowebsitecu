<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$active = 1;
$title = $err_title = $links = $err_links = "";
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err = 0;
	$title = $escape_mysql_string(trim($_POST['title']));
	$links = $_POST['links'];
	$active = intval($_POST['active']);

	if(empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	if(empty($links)) {
		$err_links = "<font color=\"red\">"._ERROR_LINKS."</font><br>";
		$err = 1;
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images = $upload->send();
			resizeImg($images, $path_upload_img, $prd_thumbsize);
		}
		$db->sql_query_simple("INSERT INTO ".$prefix."_video (id, title, links, images, alanguage, active, hits) VALUES (NULL, '$title', '$links', '$images', '$currentlang', '$active', 0)");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_NEWS_TOPIC);
		header("Location: modules.php?f=".$adm_modname."");
	}
} else {
	$err_title = "";
	$title = "";
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if(f.links.value =='') {\n";
echo "			alert('"._ERROR_LINKS."');\n";
echo "			f.links.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><form action=\"\" enctype=\"multipart/form-data\" method=\"POST\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"48\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._LINKS."</b><br>"._NOTE_VIDEO."</td>\n";
echo "<td class=\"row2\">$err_links<textarea id=\"links\" name=\"links\"  cols=\"57\" rows=\"2\">$links</textarea></td>\n";
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
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" id=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

////////////////////////////////////////////

$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query_simple("SELECT id FROM {$prefix}_video WHERE active='1'"));
$result = $db->sql_query_simple("SELECT id, title, active, hits FROM {$prefix}_video WHERE alanguage='$currentlang' LIMIT $offset, $perpage");

	ajaxload_content();

	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" name=\"frm\" method=\"POST\" >";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._CURRENT_CATS."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"></td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._VIEW."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $title, $active, $hits) = $db->sql_fetchrow_simple($result)) {
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";

		if($ajax_active != 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&page=$page&id=$id&stat=0&load_hf=1','ajaxload_container', 'video_main'); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&page=$page&id=$id&stat=1&load_hf=1','ajaxload_container', 'video_main'); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}			
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&page=$page&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}
		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";				
		 $titleLink = "<a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=detail&id=$id&t=".cv2urltitle($title)."")."\" info=\""._VIEW."\" target=\"_blank\">$title</a> ";		
		echo "<td class=\"$css\"><b>$titleLink</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" class=\"$css\">$hits</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit&page=$page&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active != 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&page=$page&id=$id\" title=\""._DELETE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=delete&page=$page&id=$id&load_hf=1','ajaxload_container', 'video_main'); return false; aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_video','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&page=$page&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		echo "</tr>\n";
		$i++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$adm_modname."";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "</table><br /></div>";
	
include_once("page_footer.php");
?>