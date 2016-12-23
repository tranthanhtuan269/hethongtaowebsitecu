<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");

$active = 1;
$special = 0;
$err_title = $err_links = $title = $links  = $err  = "";

if( isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$links = trim(stripslashes(resString($_POST['links'])));
	$special = isset($_POST['special']) ? intval($_POST['special']) : 0;
	$active = intval($_POST['active']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	
	if(!$err) {
		$weight = WeightMax("link");
		$db->sql_query_simple("INSERT INTO ".$prefix."_link (id, title, links, alanguage, active,special, weight) VALUES (NULL, '$title', '$links', '$currentlang', '$active', '$special', '$weight')");
		
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATECOLOR);
		header("Location: modules.php?f=".$adm_modname."");
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
echo "		if(f.links.value =='') {\n";
echo "			alert('"._ERROR_LINK."');\n";
echo "			f.links.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname\" method=\"POST\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECOLOR."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._URL."</b></td>\n";
echo "<td class=\"row2\">$err_links<input type=\"text\" name=\"links\" value=\"$links\" size=\"60\"></td>\n";
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
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SPECIAL."</b></td>\n";
if($special == 1) { $check = ' checked="checked"'; } else { $check = ""; }
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"special\" value=\"1\"$check></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

$resultlink = $db->sql_query_simple("SELECT id, title, links, active, special, weight  FROM ".$prefix."_link WHERE  alanguage='$currentlang' ORDER BY weight,id ASC");
if($db->sql_numrows($resultlink) > 0) {
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
	$listcat .= "<td width=\"1%\" align=\"center\" class=\"row1sd hidden-xs\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
	$listcat .= "<td class=\"row1sd\">"._TITLE."</td>\n";
	$listcat .= "<td align=\"center\" style =\"width:400px;\" class=\"row1sd hidden-xs\">"._LINK."</td>\n";
	$listcat .= "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
	$listcat .= "<td align=\"center\" width=\"60\" class=\"row1sd hidden-xs\">"._SPECIAL."</td>\n";
	$listcat .= "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
	$listcat .= "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
	$listcat .= "	</tr>\n";
	while(list($id, $title, $links, $active, $special, $weight) = $db->sql_fetchrow_simple($resultlink)) {
	
		if($ajax_active != 1) {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_link&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($id,0,'$adm_modname','status_link','id');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_link&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($id,1,'$adm_modname','status_link','id');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
		switch($active) {
			case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_link&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
			case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_link&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}
		
		if($ajax_active != 1) {
			switch($special) {
				case 1: $special = "<a href=\"modules.php?f=$adm_modname&do=status_special&id=$id&stat=0\" title=\""._DESPECIAL."\" onclick=\"return aj_base_status($id,0,'$adm_modname','status_special','id');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $special = "<a href=\"modules.php?f=$adm_modname&do=status_special&id=$id&stat=1\" title=\""._SPECIAL."\" onclick=\"return aj_base_status($id,1,'$adm_modname','status_special','id');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
		switch($special) {
			case 1: $special = "<a href=\"modules.php?f=$adm_modname&do=status_special&id=$id&stat=0\" info=\""._DESPECIAL."\"><img border=\"0\" src=\"images/true.gif\"></a>"; break;
			case 0: $special = "<a href=\"modules.php?f=$adm_modname&do=status_special&id=$id&stat=1\" info=\""._SPECIAL."\"><img border=\"0\" src=\"images/false.gif\"></a>"; break;
			}
		}

		$listcat .= "<tr>\n";
		$listcat .= "<td align=\"center\" class=\"row1 hidden-xs\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>\n";
		if($ajax_active == 1) {
			$listcat .= "		<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$id."\"><a href=\"?f=$adm_modname&do=edit_link&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$title','$adm_modname',30,'"._SAVECHANGES."','quick_title_link');\"><b>$title</b></a> </td>\n";
		} else {
			$listcat .= "<td class=\"row1\"><b>$title</b> </td>\n";
		}
		
		$listcat .= "<td align=\"left\" style=\"padding-left:15px;\" class=\"row1 hidden-xs\">$links</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"text\" name=\"poz[$id]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px\"></td>\n";		
		$listcat .= "<td align=\"center\" class=\"row1 hidden-xs\">$special</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\">$active</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=edit_link&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active != 1) {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_link&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK_COLOR."','delete_link','id');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_link&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK_COLOR."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		
	}
	echo $listcat;
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do_link\">";
	echo "<tr><td colspan=\"8\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	//echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
	echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
	echo "</table></form><br>";
	
}

echo "</div>\n";

include_once("page_footer.php");

?>