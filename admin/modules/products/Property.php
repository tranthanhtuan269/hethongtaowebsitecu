<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");

$property_is_filter = 1;
$property_is_view = 0;
$property_name = $err_title ="";

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

if( isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$property_name = trim(stripslashes(resString($_POST['property_name'])));
	$property_is_view = intval($_POST['is_view']);
	$property_is_filter = intval($_POST['is_filter']);
	$property_type = intval($_POST['property_type']);
	$property_unit = $_POST['property_unit'];
	$property_order = intval($_POST['property_order']);
	
	$err = 0;
	if($property_name =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query_simple("INSERT INTO ".$prefix."_property (name, type, is_show, is_filter, unit, weight) VALUES ('$property_name', '$property_type', '$property_is_view', '$property_is_filter', '$property_unit', $property_order)");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_PROPERTY);
		header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
	}
}

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
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATE_PROPERTY."</td></tr>";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_NAME."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"property_name\" value=\"$property_name\" size=\"50\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_TYPE."</b></td>\n";
echo "<td class=\"row2\"><select name=\"property_type\">";
echo "<option name=\"type\" value=\"".PROPERTY_TYPE_INTEGER."\">"._PROPERTY_TYPE_INTEGER."</option>";
echo "<option name=\"type\" value=\"".PROPERTY_TYPE_CUSTOM."\">"._PROPERTY_TYPE_CUSTOM."</option>";
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
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
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_UNIT."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"property_unit\" value=\"\" size=\"10\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._PROPERTY_ORDER."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"property_order\" value=\"".get_last_order_property()."\" size=\"10\"></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

/*
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price\" name=\"price\" value=\"$price\"  disabled style=\"width:240px;\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";*/

$result_property = $db->sql_query_simple("SELECT *  FROM ".$prefix."_property");
if($db->sql_numrows($result_property) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f= document.frm;\n";
	echo "	if(f.checkall.checked){\n";
	echo "		CheckAllCheckbox(f,'catid[]');\n";
	echo "	}else{\n";
	echo "		UnCheckAllCheckbox(f,'catid[]');\n";
	echo "	}			\n";
	echo "}\n";
	echo "	function checkQuick(f) {\n";
	echo "		if(f.fc.value =='') {\n";
	echo "			f.fc.focus();\n";
	echo "			return false;\n";
	echo "		}\n";
	echo "		return true;		\n";
	echo "	}	\n";
	echo "</script>\n";
	echo "<br><form name=\"frm\" action=\"modules.php?f={$adm_modname}\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">"._CURRENT_PROPERTY."</td></tr>";
	$listcat ="";
	$listcat .= "	<tr>\n";
	$listcat .= "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
	$listcat .= "		<td class=\"row1sd\">"._PROPERTY_NAME."</td>\n";
	$listcat .= "<td align=\"center\" width=\"50\" class=\"row1sd\">"._PROPERTY_TYPE."</td>\n";
	$listcat .= "<td align=\"center\" width=\"60\" class=\"row1sd\">"._PROPERTY_IS_VIEW."</td>\n";
	$listcat .= "		<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._PROPERTY_IS_FILTER."</b></td>\n";
	$listcat .= "		<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._PROPERTY_ORDER."</b></td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
	$listcat .= "	</tr>\n";
	while(list($property_id, $property_name, $property_type, $property_is_filter, $property_is_view, $property_unit, $property_order) = $db->sql_fetchrow_simple($result_property)) {
		if($ajax_active == 1) {
			switch($property_is_view) {
				case 1: $property_is_view = "<a href=\"modules.php?f=$adm_modname&do=property_show&property_id=$property_id&stat=0\" title=\""._PROPERTY_NO_VIEW."\" onclick=\"return aj_base_status($property_id,0,'$adm_modname','property_show','property_id');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $property_is_view = "<a href=\"modules.php?f=$adm_modname&do=property_show&property_id=$property_id&stat=1\" title=\""._PROPERTY_IS_VIEW."\" onclick=\"return aj_base_status($property_id,1,'$adm_modname','property_show','property_id');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			switch($property_is_filter) {
				case 1: $property_is_filter = "<a href=\"modules.php?f=$adm_modname&do=property_filter&property_id=$property_id&stat=0\" title=\""._PROPERTY_NO_FILTER."\" onclick=\"return aj_base_status($property_id,0,'$adm_modname','property_filter','property_id');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $property_is_filter = "<a href=\"modules.php?f=$adm_modname&do=property_filter&property_id=$property_id&stat=1\" title=\""._PROPERTY_IS_FILTER."\" onclick=\"return aj_base_status($property_id,1,'$adm_modname','property_filter','property_id');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($property_is_view) {
				case 1: $property_is_view = "<a href=\"modules.php?f=$adm_modname&do=property_show&property_id=$property_id&stat=0\" info=\""._PROPERTY_NO_VIEW."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $property_is_view = "<a href=\"modules.php?f=$adm_modname&do=property_show&property_id=$property_id&stat=1\" info=\""._PROPERTY_IS_VIEW."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
			switch($property_is_filter) {
				case 1: $property_is_filter = "<a href=\"modules.php?f=$adm_modname&do=property_filter&property_id=$property_id&stat=0\" info=\""._PROPERTY_NO_FILTER."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $property_is_filter = "<a href=\"modules.php?f=$adm_modname&do=property_filter&property_id=$property_id&stat=1\" info=\""._PROPERTY_IS_FILTER."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		$listcat .= "	<tr>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"property_id[]\" value=\"$property_id\"></td>\n";
		if($ajax_active == 1) {
			$listcat .= "		<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$property_id."\"><a href=\"?f=$adm_modname&do=edit_property&property_id=$property_id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($property_id,'$property_name','$adm_modname',30,'"._SAVECHANGES."','quick_name_property');\"><b>$property_name</b></a> </td>\n";
		} else {
			$listcat .= "		<td class=\"row1\"><b>$property_name</b> </td>\n";
		}

		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"text\" name=\"poz[$property_id]\" value=\"".getPropertyType($property_type)."\" maxlength=\"2\" style=\"text-align: center; width: 120px\"></td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\">$property_is_view</td>\n";
		$listcat .= "		<td align=\"center\" class=\"row1\">$property_is_filter</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\">$property_order</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=edit_property&property_id=$property_id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active == 1) {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_property&property_id=$property_id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($property_id,'$adm_modname','"._DELETEASK."','delete_property','property_id');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_property&property_id=$property_id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
	}
	echo $listcat;
	echo "</table></form><br>";
	OpenDiv();
	echo "* "._NOTES."";
	CloseDiv();
}

echo "</div>\n";

include_once("page_footer.php");

?>