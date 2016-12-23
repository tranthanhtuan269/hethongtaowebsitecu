<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");


if( isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$name = trim(stripslashes(resString($_POST['standard_name'])));
	$property = intval($_POST['property']);
	$status = intval($_POST['status']);

	if(check_exist_standard($property, $name)){
		$err_title = "<font color=\"red\">Giá trị thuộc tính đã tồn tại</font><br>";
		$err = 1;
	}

	if($name =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	if(!$err) {
		$db->sql_query_simple("INSERT INTO ".$prefix."_property_standard (property_id, name, status) VALUES ('$property', '$name', '$status')");
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
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATE_STANDARD."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Tên thuộc tính lọc</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"standard_name\" value=\"\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Của thuộc tính</b></td>\n";
echo "<td><select name=\"property\" style=\"width:305px; height:20px;\">\n";
$listcat ="";
$arr_property = array();
while(list($property_id, $name) = $db->sql_fetchrow_simple($result_properties)) {
	$listcat .= "<option value=\"$property_id\"$seld style=\"font-weight: bold\">$name</option>";
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

$query = "1";
//if( isset($_POST['subup']) && $_POST['subup'] == 1) {
$name = isset($_GET['name']) ? $_GET['name'] : (isset($_POST['name']) ? $_POST['name']:"");
$property_select = intval(isset($_GET['property_id']) ? $_GET['property_id'] : (isset($_POST['property_id']) ? $_POST['property_id']:0));
if($property_select == 0){
	$query = " 1 ";
}else{$query = " property_id=$property_select";
}
if($name == ""){
	$query .= " AND 1";
}else{$query .= " AND name LIKE '%$name%' ";
}
//}

$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_property_standard WHERE $query"));
$total = ($countf[0]) ? $countf[0] : 1;

$result = $db->sql_query_simple("SELECT * FROM {$prefix}_property_standard WHERE $query LIMIT $offset,$perpage");

//== Search
echo "<form name=\"formSearch\" action=\"modules.php?f=$adm_modname&do=value_standard\" method=\"post\">";
echo "<table  border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
		<tr>
		<td><b>"._STANDARD_PROPERTY."</b></td>";

echo "<td><select name=\"property_id\" style=\"width:305px; height:20px;\">\n";
echo "<option name=\"id\" value=\"0\" style=\"width:120px;\">"._ALL_PROPERTY."</option>";
$listcat ="";
foreach ($arr_property as $property) {
	if($property['property_id'] == $property_select) {
		$seld =" selected";
	}else{ $seld ="";
	}
	$listcat .= "<option value=\"".$property['property_id']."\"$seld style=\"font-weight: bold\">|- ".$property['name']."</option>";
}
echo $listcat;
echo "</select></td>\n";

echo "</tr>
		<tr>
		<td align='right' colspan=\"2\" ><input type=\"submit\" value=\""._FILTER."\"/></td>
				</tr>
				</table></form>";

//=== end Search

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
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._LIST_STANDARD."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\"  width=\"10\">"._STANDARD_ORDER."</td>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">"._STANDARD_NAME."</td>\n";
	echo "<td class=\"row1sd\">"._STANDARD_PROPERTY." </td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i =0;
	$a = 1;
	if($page > 1) {
		$a = $perpage*$page - $perpage + 1;
	}
	while(list($id, $property_id, $name, $active) = $db->sql_fetchrow_simple($result)) {
		if($i%2 == 1) {
			$css = "row1";
		}	else {
			$css ="row3";
		}

		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=standard_status&page=$page&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=standard_status&page=$page&id=$id&stat=0&load_hf=1','ajaxload_container', 'products_main'); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=standard_status&page=$page&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=standard_status&page=$page&id=$id&stat=1&load_hf=1','ajaxload_container', 'products_main'); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=standard_status&page=$page&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=standard_status&page=$page&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\">$a</td>";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		if($ajax_active == 1) {
			echo "<td class=\"$css\" id=\"".$adm_modname."_title_edit_".$id."\"><b><a href=\"modules.php?f=".$adm_modname."&do=standard_edit&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$name','$adm_modname',40,'"._SAVECHANGES."','quick_name_standard');\">$name</a></b> </td>\n";
		} else {
			echo "<td class=\"$css\"><b><a href=\"modules.php?f=".$adm_modname."&do=edit_standard&id=$id\" info=\""._VIEW."\">$name</a></b> </td>\n";
		}
		$result_titleproperty = $db->sql_query_simple("SELECT name FROM {$prefix}_property WHERE id='$property_id' ");
		list($name_property) = $db->sql_fetchrow_simple($result_titleproperty);
		echo "<td align=\"left\" class=\"$css\"><b>$name_property</b></td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit_standard&page=$page&id=$id&property_id=$property_select\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_standard&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_standard','');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_standard&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
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