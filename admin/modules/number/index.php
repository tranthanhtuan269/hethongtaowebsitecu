<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$query = "1";
//if( isset($_POST['subup']) && $_POST['subup'] == 1) {
$name = isset($_GET['name']) ? $_GET['name'] : (isset($_POST['name']) ? $_POST['name']:"");
$cat = intval(isset($_GET['cat']) ? $_GET['cat'] : (isset($_POST['cat']) ? $_POST['cat']:0));
if($cat == 0){$query = " 1 ";}else{$query = " catid=$cat";}
if($name == ""){$query .= " AND 1";}else{$query .= " AND title LIKE '%$name%' ";}
//}

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	default: $sortby = "ORDER BY id DESC"; break;
}
$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_number_date"));
$total = ($countf[0]) ? $countf[0] : 1;
$result = $db->sql_query_simple("SELECT id, date, bachthu,songthu, xien2, khung2, khung3, active FROM {$prefix}_number_date  $sortby LIMIT $offset,$perpage");
if($db->sql_numrows($result) > 0) {
	echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('Nhập tiêu đề');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";

echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";
	ajaxload_content();
	

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	
	
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"12\" class=\"header\">Danh sách số đã tạo</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">".sortBy("modules.php?f=$adm_modname",1)."</td>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">Ngày</td>\n";
	echo "<td class=\"row1sd\">Bạch thủ</td>\n";
	echo "<td class=\"row1sd\" align=\"center\">Song thủ</td>\n";
	echo "<td class=\"row1sd\" align=\"center\">Xiên 2</td>\n";
	echo "<td class=\"row1sd\" align=\"center\">Khung 2 ngày</td>\n";
	echo "<td class=\"row1sd\" align=\"center\">Khung 3 ngày</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i =0;
	$a = 1;
	if($page > 1) { $a = $perpage*$page - $perpage + 1;}
	while(list($id, $date, $bachthu, $songthu, $xien2, $khung2, $khung3, $active) = $db->sql_fetchrow_simple($result)) {
		if($i%2 == 1) {
			$css = "row1";
		}	else {
			$css ="row3";
		}

		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=0&load_hf=1','ajaxload_container', 'number_main'); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=1&load_hf=1','ajaxload_container', 'number_main'); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\">$a</td>";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		echo "<td class=\"$css\" id=\"".$adm_modname."_title_edit_".$id."\"><b>$date</b></td>\n";

		echo "<td align=\"left\" class=\"$css\"><b>$bachthu</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">$songthu</td>\n";
		echo "<td align=\"center\" class=\"$css\">$xien2</td>\n";
		echo "<td align=\"center\" class=\"$css\">$khung2</td>\n";
		echo "<td align=\"center\" class=\"$css\">$khung3</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','Bạn thực sự muốn xóa bản tin này!.','','');\"><img border=\"0\" src=\"images/delete.png\"> </a></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('Bạn thực sự muốn xóa bản tin này!.');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		}
		echo "</tr>\n";
		$i ++;
		$a ++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"12\">";
		$pageurl = "modules.php?f=".$adm_modname."&sort=$sort&name=$name&cat=$cat";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<tr><td colspan=\"10\" class=\"row3\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	//echo "<option value=\"4\">&raquo; "._QUICKDO_PRD1."</option>";
	//echo "<option value=\"5\">&raquo; "._QUICKDO_PRD2."</option>";
	echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	echo "</table></div>";
}else{
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}

include_once("page_footer.php");
?>