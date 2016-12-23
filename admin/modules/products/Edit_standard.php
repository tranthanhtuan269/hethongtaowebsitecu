<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$id = intval($_GET['id']);
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$property_select = intval(isset($_GET['property_id']) ? $_GET['property_id'] : (isset($_POST['property_id']) ? $_POST['property_id']:1));

$result = $db->sql_query_simple("SELECT * FROM ".$prefix."_property_standard WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}

$standard = $db->sql_fetchrow_simple($result);
$err_title = $err = "";
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$name = trim(stripslashes(resString($_POST['name'])));
	$property_id = intval($_POST['property']);
	$status = intval($_POST['status']);

	/*
	 * Get name standard cua thuoc tinh dang chon
	 */
	
	$result_standard = $db->sql_query_simple("SELECT * FROM ".$prefix."_property_standard WHERE id='$id'");
	if($db->sql_numrows($result_standard) > 0){
		$standard_selected = $db->sql_fetchrow_simple($result_standard);
	}
	if(isset($standard_selected)){
		/*
		 * Check xem co thay doi name hay ko
		 */
		if($name != $standard_selected['name']){
			if(check_exist_standard($property, $name)){
				$err_title = "<font color=\"red\">Giá trị thuộc tính đã tồn tại</font><br>";
				$err = 1;
			}
		}
	}
	
	if($name =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query_simple("UPDATE ".$prefix."_property_standard SET property_id='$property_id', name='$name', status='$status' WHERE id='$id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT_STANDARD);
		header("Location: modules.php?f=".$adm_modname."&do=value_standard&page=$page&property_id=$property_select");
	}
}

include("page_header.php");
//Load list property
$result_properties = $db->sql_query_simple("SELECT id, name FROM ".$prefix."_property WHERE type=1");
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

echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=".$standard['id']."&page=$page&property_id=$property_select\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._EDIT_STANDARD."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._STANDARD_NAME."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"name\" value=\"".$standard['name']."\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr bgcolor=\"$scolor1\">\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._STANDARD_PROPERTY."</b></td>\n";
echo "<td class=\"row2\"><select name=\"property\">";
$listcat ="";
while(list($property_id, $name) = $db->sql_fetchrow_simple($result_properties)) {
	if($property_id == $standard['property_id']) {
		$seld =" selected";
	}else{ $seld ="";
	}
	$listcat .= "<option value=\"".$property_id."\"$seld style=\"font-weight: bold\">".$name."</option>";
}
echo $listcat;
echo "</select></td>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._STANDARD_STATUS."</b></td>\n";
if($standard['status'] == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"status\" value=\"1\" checked>"._ACTIVE." &nbsp;&nbsp;"
			."<input type=\"radio\" name=\"status\" value=\"0\">"._DEACTIVATE."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"status\" value=\"1\">"._YES." &nbsp;&nbsp;"
			."<input type=\"radio\" name=\"status\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=".$adm_modname."&do=value_standard&page=$page&property_id=$property_select'\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");
?>