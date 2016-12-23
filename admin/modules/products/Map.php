<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");


if( isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$catid = intval($_POST['catid']);
	$property = intval($_POST['property']);
	$status = intval($_POST['status']);

	// if(check_exist_standard($property, $name)){
	// 	$err_title = "<font color=\"red\">Giá trị thuộc tính đã tồn tại</font><br>";
	// 	$err = 1;
	// }

	// if($name =="") {
	// 	$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
	// 	$err = 1;
	// }
	if(!$err) {
		$db->sql_query_simple("INSERT INTO ".$prefix."_cat_property_mapping (id,  cat_id , property_id) VALUES ('', '$catid', '$property')");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_PROPERTY);
		header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
	}
}
//Load list property
$result_properties = $db->sql_query_simple("SELECT id, name FROM ".$prefix."_property WHERE type=1");

echo "<div id=\"".$adm_modname."_main\">\n";
ajaxload_content();

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
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Tạo sản phẩm lọc</td></tr>";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Chọn danh mục</b></td>\n";
echo "<td class=\"row2\"><select name=\"catid\" id=\"catid\" >\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE active='1' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\" style=\"width:120px;\">Chọn danh mục</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
	$listcat .= "<option value=\"$cat_id\" style=\"font-weight: bold\">|- $titlecat</option>";
	// $listcat .= subcat($cat_id,"|",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Thuộc tính</b></td>\n";
echo "<td><select name=\"property\" style=\"width:305px; height:20px;\">\n";
$listcat ="";
$arr_property = array();
echo "<option value=\"0\" style=\"width:120px;\">Chọn thuộc tính</option>";
while(list($property_id, $name) = $db->sql_fetchrow_simple($result_properties)) {
	$listcat .= "<option value=\"$property_id\" style=\"font-weight: bold\">$name</option>";
	array_push($arr_property, array('property_id' => $property_id, 'name' => $name));
}
echo $listcat;
echo "</select></td>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._STANDARD_STATUS."</b></td>\n";
echo "<td  class=\"row2\"> <input type=\"radio\" name=\"status\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
		."<input type=\"radio\" name=\"status\" value=\"0\">"._NO."</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";



$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_cat_property_mapping"));
$total = ($countf[0]) ? $countf[0] : 1;

$result = $db->sql_query_simple("SELECT * FROM {$prefix}_cat_property_mapping LIMIT $offset,$perpage");



if($db->sql_numrows($result) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f=document.frm;\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'id[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'id[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "	function checkQuick(f) {\n";
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "	function checkQuickId(f) {\n";
	echo "		if(f.id.value =='') {\n";
	echo "			f.id.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		f.submit.disabled = true; \n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	ajaxload_content();
	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=value_standard&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">Danh sách thuộc tính lọc</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\"  width=\"10\">"._STANDARD_ORDER."</td>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">Tên danh mục</td>\n";
	echo "<td class=\"row1sd\">Thuộc tính lọc</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i =0;
	$a = 1;
	if($page > 1) {
		$a = $perpage*$page - $perpage + 1;
	}
	while(list($id,$cat_id, $property_id) = $db->sql_fetchrow_simple($result)) {
		if($i%2 == 1) {
			$css = "row1";
		}	else {
			$css ="row3";
		}

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\">$a</td>";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";

		$result_title = $db->sql_query_simple("SELECT title FROM {$prefix}_products_cat WHERE catid='$cat_id' ");
		list($name_product) = $db->sql_fetchrow_simple($result_title);
		echo "<td align=\"left\" class=\"$css\"><b>$name_product</b></td>\n";

		$result_titleproperty = $db->sql_query_simple("SELECT name FROM {$prefix}_property WHERE id='$property_id' ");
		list($name_property) = $db->sql_fetchrow_simple($result_titleproperty);
		echo "<td align=\"left\" class=\"$css\"><b>$name_property</b></td>\n";

		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit_map&page=$page&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_map&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_map','');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_map&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		}
		echo "</tr>\n";
		$i ++;
		$a ++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$adm_modname."&do=value_standard&property_id=$property_select";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do_standard\">";
	echo "<tr><td colspan=\"9\" class=\"row3\"><select name=\"fc\">";
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