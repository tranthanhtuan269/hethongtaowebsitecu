<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}



define("PROPERTY_TYPE_INTEGER",0);
define("PROPERTY_TYPE_CUSTOM",1);


function getPropertyType($type){
	switch ($type){
		case PROPERTY_TYPE_INTEGER:
			return _PROPERTY_TYPE_INTEGER;
		case PROPERTY_TYPE_CUSTOM:
			return _PROPERTY_TYPE_CUSTOM;
	}
	return '';
}

$property_id = intval($_GET['property_id']);
$result = $db->sql_query_simple("SELECT * FROM ".$prefix."_property WHERE id='$property_id'");
if(empty($property_id) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}

list($property_id, $property_name, $property_type, $property_is_filter, $property_is_view, $property_unit, $property_order) = $db->sql_fetchrow_simple($result);
$err_title = $err = "";
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$property_name = trim(stripslashes(resString($_POST['property_name'])));
	$property_is_view = intval($_POST['is_view']);
	$property_is_filter = intval($_POST['is_filter']);
	$property_type = intval($_POST['property_type']);
	$property_unit = trim(stripslashes(resString($_POST['property_unit'])));
	$property_order = intval($_POST['property_order']);
	
	if($property_name =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query_simple("UPDATE ".$prefix."_property SET name='$property_name', type='$property_type', is_show='$property_is_view', is_filter='$property_is_filter', unit='$property_unit', weight='$property_order' WHERE id='$property_id'");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT_PROPERTY);
		header("Location: modules.php?f=".$adm_modname."&do=property");
	}
}

include("page_header.php");

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

echo "<form action=\"modules.php?f=$adm_modname&do=$do&property_id=$property_id\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._EDIT_PROPERTY."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_NAME."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"property_name\" value=\"$property_name\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr bgcolor=\"$scolor1\">\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_TYPE."</b></td>\n";
echo "<td class=\"row2\"><select name=\"property_type\">";
if($property_type == PROPERTY_TYPE_CUSTOM){
	$selected = "selected";
}else{
	$selected = "";
}
echo "<option name=\"type\" value=\"".PROPERTY_TYPE_INTEGER."\">"._PROPERTY_TYPE_INTEGER."</option>";
echo "<option name=\"type\" value=\"".PROPERTY_TYPE_CUSTOM."\"  $selected>"._PROPERTY_TYPE_CUSTOM."</option>";
echo "</select></td>\n";
echo "</tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PROPERTY_IS_VIEW."</b></td>\n";
if($property_is_view == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"is_view\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"is_view\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"is_view\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"is_view\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PROPERTY_IS_FILTER."</b></td>\n";
if($property_is_filter == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"is_filter\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"is_filter\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"is_filter\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"is_filter\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_UNIT."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"property_unit\" value=\"$property_unit\" size=\"10\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_ORDER."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"property_order\" value=\"".$property_order."\" size=\"10\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=".$adm_modname."&do=property'\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");
?>