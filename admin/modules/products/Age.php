<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");

$active = 1;
$title = $err_title = $err = "";

if( isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$active = intval($_POST['active']);
	//$parentid = intval($_POST['parentid']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}

	if(!$err) {
		$weight = WeightMax("age");
		$db->sql_query_simple("INSERT INTO ".$prefix."_age (id, parentid, title, alanguage, active, weight) VALUES (NULL, '0', '$title', '$currentlang', '$active', '$weight')");

		fixweight_cat("age","id");
		//fixsubcat("age","id");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATECOLOR);
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
echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECOLOR."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
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
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

$resultcat = $db->sql_query_simple("SELECT id, parentid, title, active, weight  FROM ".$prefix."_age WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight,id ASC");
if($db->sql_numrows($resultcat) > 0) {
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
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">"._CURRENTCOLOR."</td></tr>";
	$listcat ="";
	$listcat .= "	<tr>\n";
	$listcat .= "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
	$listcat .= "		<td class=\"row1sd\">"._TITLE."</td>\n";
	$listcat .= "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
	$listcat .= "<td align=\"center\" width=\"60\" class=\"row1sd\">"._COUNTS."</td>\n";
	$listcat .= "		<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
	$listcat .= "	</tr>\n";
	while(list($catid, $parentid, $title, $active, $weight) = $db->sql_fetchrow_simple($resultcat)) {
		if($ajax_active != 1) {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_age&catid=$catid&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($catid,0,'$adm_modname','status_cat','catid');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_age&catid=$catid&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($catid,1,'$adm_modname','status_cat','catid');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_age&catid=$catid&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_age&catid=$catid&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		$listcat .= "	<tr>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"catid[]\" value=\"$catid\"></td>\n";
		if($ajax_active == 1) {
			$listcat .= "		<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$catid."\"><a href=\"?f=$adm_modname&do=edit_age&catid=$catid\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($catid,'$title','$adm_modname',30,'"._SAVECHANGES."','quick_title_age');\"><b>$title</b></a> </td>\n";
		} else {
			$listcat .= "		<td class=\"row1\"><b>$title</b> </td>\n";
		}

		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"text\" name=\"poz[$catid]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px\"></td>\n";
		$count_prd = $db->sql_query_simple("SELECT count(*) FROM {$prefix}_products_age WHERE  ageid=$catid");
		list($counts) = $db->sql_fetchrow_simple($count_prd);
		$listcat .= "<td align=\"center\" class=\"row1\">$counts</td>\n";
		$listcat .= "		<td align=\"center\" class=\"row1\">$active</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=edit_age&catid=$catid\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active != 1) {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_age&catid=$catid\" title=\""._DELETE."\" onclick=\"return aj_base_delete($catid,'$adm_modname','"._DELETEASK_COLOR."','delete_cat','catid');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_age&catid=$catid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK_COLOR."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		
	}
	echo $listcat;
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do_age\">";
	echo "<tr><td colspan=\"8\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
	echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
	echo "</table></form><br>";
	
}

echo "</div>\n";

include_once("page_footer.php");

?>