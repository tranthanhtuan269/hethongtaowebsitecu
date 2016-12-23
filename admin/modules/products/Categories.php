<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include("page_header.php");

$active = 1;
$onhome = 0;
$title = $err_title =$gioithieu="";

$err_news   = $seo_keyword = $seo_description ="";


if( isset($_POST['subup'])&& $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$active = intval($_POST['active']);
	$onhome = intval($_POST['onhome']);
	$parentid = intval($_POST['parentid']);
	$seo_keyword = trim(stripslashes(resString($_POST['seo_keyword'])));
	$gioithieu = trim(stripslashes(resString($_POST['gioithieu'])));
	$permalink=url_optimization(trim($title));
	$seo_description = trim(stripslashes(resString($_POST['seo_description'])));


	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images = $upload->send();
			resizeImg($images, $path_upload_img, $prd_thumbsize);
		}
		$weight = WeightMax("products_cat");

		$result_maxcatid = $db->sql_query_simple("SELECT max(catid) FROM ".$prefix."_products_cat ");
		list($catid_max)=$db->sql_fetchrow_simple($result_maxcatid);
		$catid_max=$catid_max + 1;
		$guid="index.php?f=products&do=categories&id=$catid_max";
		//
		$db->sql_query_simple("INSERT INTO ".$prefix."_products_cat (catid, parentid,guid, title,gioithieu, permalink, images, seo_keyword, seo_description, alanguage, active, onhome, weight ) VALUES ('$catid_max', '$parentid','$guid', '$title', '$gioithieu','$permalink', '$images', '$seo_keyword', '$seo_description', '$currentlang', '$active', '$onhome', '$weight' )");

		fixweight_cat("products_cat","catid");
		fixsubcat("products_cat","catid");

		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATECAT);
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
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Giới Thiệu</b></td>\n";
echo "<td class=\"row2\">";
editor("gioithieu", $gioithieu,"",400);
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"37\"></td>\n";
echo "</tr>\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($result_cat) > 0) {
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
	echo "<td class=\"row2\"><select name=\"parentid\">";
	echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
	$listcat ="";
	while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
		if($cat_id == $parentid) {$seld =" selected"; }else{ $seld ="";}
		$listcat .= "<option value=\"$cat_id\"$seld>--$titlecat</option>";
		$listcat .= subcat($cat_id,"|",$catid, "");
	}
	echo $listcat;
	echo "</select></td>\n";
	echo "</tr>\n";
}


/////////////////////////////////



/*echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"seo_keyword\"  name=\"seo_keyword\"  value=\"$seo_keyword\" size=\"80\"></td>\n";
echo "</tr>\n";*/

echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\">$seo_keyword<input type=\"text\" name=\"seo_keyword\" value=\"$seo_keyword\" size=\"50\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._DESCRIPTION_SEO."</b></td>\n";
echo "<td class=\"row2\"><textarea name=\"seo_description\"  cols=\"77\" rows=\"4\">$seo_description</textarea></td>\n";
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
echo "<td align=\"right\" class=\"row1\"><b>"._ONHOME."</b></td>\n";
if($onhome == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

$resultcat = $db->sql_query_simple("SELECT catid, parentid, title, active, weight, counts  FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight,catid ASC");
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
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"10\" class=\"header\">"._CURRENTCATS."</td></tr>";
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
	$a=0;
	while(list($catid, $parentid, $title, $active, $weight, $counts) = $db->sql_fetchrow_simple($resultcat)) {
		$a++;
		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$catid&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($catid,0,'$adm_modname','status_cat','catid');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$catid&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($catid,1,'$adm_modname','status_cat','catid');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$catid&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$catid&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}

		$listcat .= "	<tr>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"checkbox\" name=\"catid[]\" value=\"$catid\"></td>\n";
		if($ajax_active == 1) {
			$listcat .= "		<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$catid."\"><a href=\"?f=$adm_modname&do=edit_cat&catid=$catid\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($catid,'$title','$adm_modname',30,'"._SAVECHANGES."','quick_title_cat');\"><b>$title</b></a> ".getlink($adm_modname,"categories&id=$catid")."</td>\n";
		} else {
			$listcat .= "		<td class=\"row1\"><b>$title</b> ".getlink($adm_modname,"categories&id=$catid")."</td>\n";
		}

		$listcat .= "<td align=\"center\" class=\"row1\"><input type=\"text\" name=\"poz[$catid]\" value=\"$weight\" maxlength=\"2\" style=\"text-align: center; width: 30px\"></td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\">$counts</td>\n";
		$listcat .= "		<td align=\"center\" class=\"row1\">$active</td>\n";
		$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=edit_cat&catid=$catid\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active == 1) {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_cat&catid=$catid\" title=\""._DELETE."\" onclick=\"return aj_base_delete($catid,'$adm_modname','"._DELETEASK."','delete_cat','catid');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			$listcat .= "<td align=\"center\" class=\"row1\"><a href=\"modules.php?f=$adm_modname&do=delete_cat&catid=$catid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}

		$listcat .= childcat($catid);
	}
	echo $listcat;
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do_cat\">";
	echo "<tr><td colspan=\"8\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
	echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
	echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
	echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
	echo "</table></form><br>";
	OpenDiv();
	echo "* "._NOTES."";
	CloseDiv();
}

echo "</div>\n";

function childcat($catid, $text="-") {
	global $db, $prefix, $adm_modname, $scolor1, $ajax_active;
	$treeTemp ="";
	$result = $db->sql_query_simple("SELECT catid, title, weight, active, counts FROM ".$prefix."_products_cat WHERE parentid='$catid' ORDER BY weight,catid ASC");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2, $weight2, $active, $counts) = $db->sql_fetchrow_simple($result)) {
			if($ajax_active == 1) {
				switch($active) {
					case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$cat_id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($cat_id,0,'$adm_modname','status_cat','catid');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
					case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$cat_id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($cat_id,1,'$adm_modname','status_cat','catid');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
				}
			} else {
				switch($active) {
					case 1: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$cat_id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
					case 0: $active = "<a href=\"modules.php?f=$adm_modname&do=status_cat&catid=$cat_id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
				}
			}

			$treeTemp .= "<tr>\n";
			$treeTemp .= "<td class=\"row3\" align=\"center\"><input type=\"checkbox\" name=\"catid[]\" value=\"$cat_id\"></td>\n";
			if($ajax_active == 1) {
				$treeTemp .= "<td class=\"row3\" id=\"".$adm_modname."_title_edit_".$cat_id."\"><a href=\"?f=$adm_modname&do=edit_cat&catid=$catid\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($cat_id,'$title2','$adm_modname',30,'"._SAVECHANGES."','quick_title_cat');\">$text- $title2</a> ".getlink($adm_modname,"categories&id=$cat_id")."</td>\n";
			} else {
				$treeTemp .= "<td class=\"row3\">$text- $title2 ".getlink($adm_modname,"categories&id=$cat_id")."</td>\n";
			}
			$treeTemp .= "<td align=\"center\" class=\"row3\"><input type=\"text\" name=\"poz[$cat_id]\" value=\"$weight2\" maxlength=\"2\" style=\"text-align: center; width: 30px; background-color: $scolor1\"></td>\n";
			$treeTemp .= "<td align=\"center\" class=\"row3\">$counts</td>\n";
			$treeTemp .= "		<td class=\"row3\" align=\"center\"><b>$active</b></td>\n";
			$treeTemp .= "<td align=\"center\" class=\"row3\"><a href=\"modules.php?f=$adm_modname&do=edit_cat&catid=$cat_id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
			$treeTemp .= "<td align=\"center\" class=\"row3\"><a href=\"modules.php?f=$adm_modname&do=delete_cat&catid=$cat_id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
			$treeTemp .= "	</tr>\n";
			$treeTemp .= childcat($cat_id, $text);
		}
	}
	return $treeTemp;
}

include_once("page_footer.php");

?>