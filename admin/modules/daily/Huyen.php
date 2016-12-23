<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$active = 1;
$title = $err_title = $err_t=$name="";
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err = 0;
	$title = $escape_mysql_string(trim($_POST['title']));
	$tid = intval($_POST['tid']);
	if(empty($title)) {
		$err_title = "<font color=\"red\">Nhập tên Huyện</font><br>";
		$err = 1;
	}


	if(!$err) {
		
		$db->sql_query_simple("INSERT INTO ".$prefix."_huyen (hid,tid, name) VALUES (NULL,'$tid', '$title')");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_NEWS_TOPIC);
		header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
	}
} else {
	$err_title = "";
	$title = "";
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('Nhập tên Tỉnh');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "</script>	\n";

ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" enctype=\"multipart/form-data\" method=\"POST\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Tên Quân/Huyện:</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Tỉnh</b></td>\n";
$result_cat1 = $db->sql_query_simple("SELECT tid, name FROM ".$prefix."_tinh ORDER BY tid");
echo "<td class=\"row2\">$err_t<select name=\"tid\">";
echo "<option name=\"tid\" value=\"0\">Chọn Tỉnh</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat1)) {
	if($cat_id == $tid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">- $titlecat</option>";
	//$listcat .= subcat($cat_id,"-",$tid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";

// echo "<tr>\n";
// echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
// echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"48\"></td>\n";
// echo "</tr>\n";
// echo "<tr>\n";
// echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Địa chỉ:</b><br></td>\n";
// echo "<td class=\"row2\">$err_links<textarea id=\"links\" name=\"links\"  cols=\"57\" rows=\"2\">$links</textarea></td>\n";
// echo "</tr>\n";
// echo "<tr>\n";
// echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Huyện:</b></td>\n";
// echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"huyen\" name=\"huyen\" value=\"$huyen\" size=\"60\"></td>\n";
// echo "</tr>\n";
// echo "<tr>\n";
// echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Tỉnh</b></td>\n";
// echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"tinh\" name=\"tinh\" value=\"$tinh\" size=\"60\"></td>\n";
// echo "</tr>\n";

// echo "<tr>\n";
// echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Số điện thoại:</b></td>\n";
// echo "<td class=\"row2\">$err_sdt<input type=\"text\" id=\"sdt\" name=\"sdt\" value=\"$sdt\" size=\"60\"></td>\n";
// echo "</tr>\n";

// echo "<tr>\n";
// echo "<td align=\"right\" class=\"row1\"><b>"._ACTIVE."</b></td>\n";
// if($active == 1) {
// 	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
// 	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
// } else {
// 	echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
// 	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
// }
// echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" id=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

////////////////////////////////////////////

$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query_simple("SELECT hid FROM {$prefix}_huyen"));
$result = $db->sql_query_simple("SELECT hid, name FROM {$prefix}_huyen ");

	ajaxload_content();

	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" name=\"frm\" method=\"POST\" >";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tbl-main\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._CURRENT_CATS."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"></td>\n";
	echo "<td class=\"row1sd\">Tên Tỉnh:</td>\n";



	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $title) = $db->sql_fetchrow_simple($result)) {
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";

		
		echo "<tr>\n";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";				
		 $titleLink = "<a href=\"".RPATH.url_sid("index.php?f=".$adm_modname."&do=detail&id=$id&t=".cv2urltitle($title)."")."\" info=\""._VIEW."\" target=\"_blank\">$title</a> ";		
		echo "<td class=\"$css\"><b>$titleLink</b></td>\n";

		
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit_huyen&page=$page&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active != 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_huyen&page=$page&id=$id\" title=\""._DELETE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=delete&page=$page&id=$id);\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_huyen&page=$page&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK1."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
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