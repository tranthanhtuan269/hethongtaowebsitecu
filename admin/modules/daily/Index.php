<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$active = 1;
$title = $err_title =$images =$links = $err_links = $err_sdt=$sdt=$huyen=$tinh=$err_t=$err_h=$mota=$chucvu="";
if(isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$err = 0;
	$title = $escape_mysql_string(trim($_POST['title']));
	$links = $_POST['links'];
	$active = intval($_POST['active']);
	$sdt = $_POST['sdt'];
	$chucvu = $_POST['chucvu'];
	// $hid = intval($_POST['hid']);
	// $tid = intval($_POST['tid']);
	$mota = $escape_mysql_string(trim($_POST['mota']));
	if(empty($title)) {
		$err_title = "<font color=\"red\">"._ERROR11."</font><br>";
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
		$db->sql_query_simple("INSERT INTO ".$prefix."_nhacungcap (id, tennhacc, diachi, images, alanguage, active, sdt, mota, chucvu) VALUES (NULL, '$title', '$links', '$images', '$currentlang', '$active', '$sdt', '$mota', '$chucvu')");
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
echo "			alert('"._ERROR11."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if(f.links.value =='') {\n";
echo "			alert('"._ERROR_LINKSs."');\n";
echo "			f.links.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		if(f.tid.value == 0) {\n";
echo "			alert('Chọn Tên Tỉnh');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
?>
		<script type="text/javascript">
			function check(){
				var sdt = document.getElementById('sdt').value;
				 
				 
				var kiemTraDT = isNaN(sdt);
				
				 
				if(sdt == ""){
					window.alert('Nhập số điện thoại.');
					document.getElementById('sdt').focus();
					return false;
				}
				 if (kiemTraDT == true) {
		              alert("Điện thoại phải để ở định dạng số");
		              return false;
		         }
		        
				return true;
			}
		</script>
<?php
ajaxload_content();

echo "<div id=\"{$adm_modname}_main\">";
echo "<form action=\"modules.php?f=$adm_modname\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Tên:</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"48\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Chức vụ:</b><br></td>\n";
echo "<td class=\"row2\">$err_links<textarea id=\"chucvu\" name=\"chucvu\"  cols=\"57\" rows=\"2\">$chucvu</textarea></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Tóm tắt lời nói</b></td>\n";
echo "<td class=\"row2\">";
editor("mota", $mota,"",400);
echo "</td>\n";
echo "</tr>\n";



echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Email:</b><br></td>\n";
echo "<td class=\"row2\">$err_links<textarea id=\"links\" name=\"links\"  cols=\"57\" rows=\"2\">$links</textarea></td>\n";
echo "</tr>\n";


// echo "<tr>\n";
// echo "<td align=\"right\" class=\"row1\"><b>Huyện</b></td>\n";
// $result_cat11 = $db->sql_query_simple("SELECT hid, name FROM ".$prefix."_huyen ORDER BY hid");
// echo "<td class=\"row2\">$err_h<select name=\"hid\">";
// echo "<option name=\"hid\" value=\"0\">Chọn Huyện</option>";
// $listcatt ="";
// while(list($cat_idh, $titlecath) = $db->sql_fetchrow_simple($result_cat11)) {
// 	if($cat_idh == $hid) {$seldd =" selected"; }else{ $seldd ="";}
// 	$listcatt .= "<option value=\"$cat_idh\"$seldd style=\"font-weight: bold\">- $titlecath</option>";
// 	//$listcat .= subcat($cat_id,"-",$tid, "");
// }
// echo $listcatt;
// echo "</select></td>\n";
// echo "</tr>\n";


// echo "<tr>\n";
// echo "<td align=\"right\" class=\"row1\"><b>Tỉnh</b></td>\n";
// $result_cat1 = $db->sql_query_simple("SELECT tid, name FROM ".$prefix."_tinh ORDER BY tid");
// echo "<td class=\"row2\">$err_t<select name=\"tid\">";
// echo "<option name=\"tid\" value=\"0\">Chọn Tỉnh</option>";
// $listcat ="";
// while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat1)) {
// 	if($cat_id == $tid) {$seld =" selected"; }else{ $seld ="";}
// 	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">- $titlecat</option>";
// 	//$listcat .= subcat($cat_id,"-",$tid, "");
// }
// echo $listcat;
// echo "</select></td>\n";
// echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Số điện thoại:</b></td>\n";
echo "<td class=\"row2\">$err_sdt<input type=\"text\" id=\"sdt\" name=\"sdt\" value=\"$sdt\" size=\"60\"></td>\n";
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
$total = $db->sql_numrows($db->sql_query_simple("SELECT id FROM {$prefix}_nhacungcap WHERE active='1'"));
$result = $db->sql_query_simple("SELECT id, tennhacc, active,diachi FROM {$prefix}_nhacungcap WHERE alanguage='$currentlang' LIMIT $offset, $perpage");

	ajaxload_content();

	echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&page=$page\" name=\"frm\" method=\"POST\" >";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tbl-main\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._CURRENT_CATS."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"10\"></td>\n";
	echo "<td class=\"row1sd\">Tên:</td>\n";
	echo "<td align=\"center\" class=\"row1sd\">Email</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";

	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i = 0;
	if($page > 1) { $a = $perpage * $page - $perpage + 1;}
	while(list($id, $title, $active,$diachi) = $db->sql_fetchrow_simple($result)) {
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
		echo "<td align=\"center\" class=\"$css\">$diachi</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		
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