<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: index.php?f=user&do=login");

$page_title = _USER_AD_PRODUCT;


include_once('header.php');

global $module_name;
//OpenTab(_USER_EDIT_PROFILE);
function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query_simple("SELECT catid, weight FROM ".$prefix."_products_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow_simple($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query_simple("SELECT catid, counts FROM ".$prefix."_products_cat");
	 $i =0;
	 while(list($catid, $counts) = $db->sql_fetchrow_simple($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query_simple("SELECT*FROM ".$prefix."_products WHERE catid='$catid'"));
	 	if($counts != $numsnew) {
	 		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET counts='$numsnew' WHERE catid='$catid'");
	 	}
	 	$i ++;
	 }
}

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='$catid' AND catid!='$catseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2) = $db->sql_fetchrow_simple($result)) {
			if($catcheck) {
				if($cat_id == $catcheck) {
					$seld = " selected";
				}else{
					$seld ="";
				}	
			}
			$treeTemp .= "<option value=\"$cat_id\"$seld>$text-- $title2</option>";
			$treeTemp .= subcat($cat_id,$text, $catcheck, $catseld);
		}	
	}
	return $treeTemp;	
}

function fixsubcat() {
	global $db, $prefix;
	$result = $db->sql_query_simple("SELECT catid FROM ".$prefix."_products_cat WHERE parentid='0' ORDER BY weight ASC");
	while(list($catid) = $db->sql_fetchrow_simple($result)) {
	 	fixsubcat_rec($catid);
	 }
}	

function fixsubcat_rec($catid) {
	global $db, $prefix;
	$result = $db->sql_query_simple("SELECT catid FROM ".$prefix."_products_cat WHERE parentid='$catid'");
	$sub_id_ar ="";
	if($db->sql_numrows($result) > 0) {
		while(list($catid2) = $db->sql_fetchrow_simple($result)) {
			$sub_id_ar[] = $catid2;
			fixsubcat_rec($catid2);	
		}	
		$sub_id = @implode("|",$sub_id_ar);
		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET sub_id='$sub_id' WHERE catid='$catid'");
	}else{
		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET sub_id='' WHERE catid='$catid'");
	}	
}


$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	default: $sortby = "ORDER BY time DESC"; break;
	case 1: $sortby = "ORDER BY id ASC"; break;
	case 2: $sortby = "ORDER BY id DESC"; break;
	case 3: $sortby = "ORDER BY time ASC"; break;
	case 4: $sortby = "ORDER BY time DESC"; break;
	case 5: $sortby = "ORDER BY hits ASC"; break;
	case 6: $sortby = "ORDER BY hits DESC"; break;
}
$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_products WHERE userid={$userInfo['id']} AND alanguage='$currentlang'"));
$total = ($countf[0]) ? $countf[0] : 1;
$result = $db->sql_query_simple("SELECT id, title, time, active, hits FROM {$prefix}_products WHERE userid={$userInfo['id']} AND alanguage='$currentlang' $sortby LIMIT $offset,$perpage");
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

	echo "<div id=\"".$module_name."_main\"><form action=\"modules.php?f=$module_name&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._PRD_LIST."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">".sortBy("modules.php?f=$module_name",1)."</td>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100px\">"._TIMEUP." ".sortBy("modules.php?f=$module_name",3)."</td>\n";
	//echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"70\">"._VIEW." ".sortBy("modules.php?f=$module_name",5)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i =0;
	$a = 1;
	if($page > 1) { $a = $perpage*$page - $perpage + 1;}
	while(list($id, $title, $time, $active, $hits) = $db->sql_fetchrow_simple($result)) {
		if($i%2 == 1) {
			$css = "row1";
		}	else {
			$css ="row3";
		}

		/*if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$module_name."&do=status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($id,0,'$module_name','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$module_name."&do=status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($id,1,'$module_name','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		} else {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$module_name."&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$module_name."&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
			}
		}*/

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\">$a</td>";
		echo "<td class=\"$css\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		if($ajax_active == 1) {
			echo "<td class=\"$css\" id=\"".$module_name."_title_edit_".$id."\"><b><a href=\"modules.php?f=".$module_name."&do=edit&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$title','$module_name',40,'"._SAVECHANGES."','quick_title');\">$title</a></b> ".getlink($module_name,"detail&id=$id&t=".cv2urltitle($title)."")."</td>\n";
		} else {
			echo "<td class=\"$css\"><b><a href=\"modules.php?f=".$module_name."&do=editproduct&id=$id\" info=\""._VIEW."\">$title</a></b> ".getlink($module_name,"detail&id=$id&t=".cv2urltitle($title)."")."</td>\n";
		}
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		//echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" class=\"$css\">$hits</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$module_name."&do=editproduct&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$module_name."&do=delete&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$module_name','"._DELETEASK1."','','');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$module_name."&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		}
		echo "</tr>\n";
		$i ++;
		$a ++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$module_name."&sort=$sort";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
	echo "<tr><td colspan=\"9\" class=\"row3\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	echo "</table></div>";
}else{
	//OpenDiv();
	echo "<center>"._NODATA."</center>";
	//CLoseDiv();
}
CloseTab();
include_once('footer.php');
?>