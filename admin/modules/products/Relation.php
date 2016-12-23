<?php

echo "<link rel=\"stylesheet\" href=\"styles/styles.css\" />\n";

$prdid =  intval(isset($_GET['pid']) ? $_GET['pid'] :0);

$search = "1";
if( isset($_POST['subup']) && $_POST['subup'] == 1) {

$name = isset($_GET['name']) ? $_GET['name'] : (isset($_POST['name']) ? $_POST['name']:"");

$cat = intval(isset($_GET['cat']) ? $_GET['cat'] : (isset($_POST['cat']) ? $_POST['cat']:0));

if($cat == 0){$search = " 1 ";}else{$search = " catid=$cat";}
if($search == ""){$search .= " AND 1";}else{$search .= " AND title LIKE '%$name%' ";}

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
$perpage = 13;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;

$result_prd = $db->sql_query_simple("SELECT relation FROM {$prefix}_products WHERE id=$prdid");
list($relation) = $db->sql_fetchrow_simple($result_prd);
$query = "";
if($relation != ""){
	$rel_id = explode(",", $relation);	
	for($i = 0; $i < sizeof($rel_id);$i ++){
		if($rel_id[$i] != ""){
			$query .= "id != $rel_id[$i] AND ";
		}
	}
}

$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_products WHERE alanguage='$currentlang' AND $search"));
$total = ($countf[0]) ? $countf[0] : 1;

$result = $db->sql_query_simple("SELECT id, title, time, active, hits FROM {$prefix}_products WHERE id!=$prdid AND $query alanguage='$currentlang' AND $search $sortby LIMIT $offset,$perpage");
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
	
		//== Search 
	echo "<form name=\"formSearch\" action=\"modules.php?f=$adm_modname&do=relation&pid=$prdid\" method=\"post\">";
	echo "<table  border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
		<tr>
			<td width=\"80px\"><b>"._TEN_SAN_PHAM."</b></td>
			<td align='left'><input type=\"text\" size=\"25\" name=\"name\" style=\"width:300px;\"></td>
		</tr>		
		<tr>
			<td><b>"._DANH_MUC."</b></td>";
	
		echo "<td><select name=\"cat\" style=\"width:305px; height:20px;\">\n";
		$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
		echo "<option name=\"catid\" value=\"0\" style=\"width:120px;\">"._ALL_CAT."</option>";
		$listcat ="";
		while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
			if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">|- $titlecat</option>";
			$listcat .= subcat($cat_id,"|",$catid, "");
		}
		echo $listcat;
		echo "</select></td>\n";
		
		echo "</tr>
		<tr>			
			<input type=\"hidden\" name=\"subup\" value=\"1\">
			<td align='right' colspan=\"2\" ><input type=\"submit\" value=\""._SEARCH."\"/></td>
		</tr>	
	</table></form>";
	
	
	//=== end Search

	echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=do_relation&pid=$prdid&sort=$sort&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"95%\" style=\"margin-left:30px;margin-top:10px;\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"9\" class=\"header\">"._PRD_LIST."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">".sortBy("modules.php?f=$adm_modname&do=relation&pid=$prdid",1)."</td>\n";
	echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">"._TITLE."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100px\">"._TIMEUP." ".sortBy("modules.php?f=$adm_modname&do=relation&pid=$prdid",3)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"70\">"._VIEW." ".sortBy("modules.php?f=$adm_modname&do=relation&pid=$prdid",5)."</td>\n";
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

		if($ajax_active == 1) {
			switch($active) {
				case 1: $active = "<a href=\"?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=0&load_hf=1','ajaxload_container', 'products_main'); return false;\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
				case 0: $active = "<a href=\"?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"ajaxinfoget('modules.php?f=".$adm_modname."&do=status&sort=$sort&page=$page&id=$id&stat=1&load_hf=1','ajaxload_container', 'products_main'); return false;\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
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
		if($ajax_active == 1) {
			echo "<td class=\"$css\" id=\"".$adm_modname."_title_edit_".$id."\"><b><a href=\"modules.php?f=".$adm_modname."&do=edit&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$title','$adm_modname',40,'"._SAVECHANGES."','quick_title');\">$title</a></b> ".getlink($adm_modname,"detail&id=$id&t=".cv2urltitle($title)."")."</td>\n";
		} else {
			echo "<td class=\"$css\"><b><a href=\"modules.php?f=".$adm_modname."&do=edit&id=$id\" info=\""._VIEW."\">$title</a></b> ".getlink($adm_modname,"detail&id=$id&t=".cv2urltitle($title)."")."</td>\n";
		}
		echo "<td align=\"center\" class=\"$css\">".ext_time($time, 2)."</td>\n";
		echo "<td align=\"center\" class=\"$css\">$active</td>\n";
		echo "<td align=\"center\" class=\"$css\">$hits</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=edit&sort=$sort&page=$page&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK1."','','');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		}
		echo "</tr>\n";
		$i ++;
		$a ++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"9\">";
		$pageurl = "modules.php?f=".$adm_modname."&do=relation&sort=$sort&pid=$prdid";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<input type=\"hidden\" name=\"do\" value=\"do_relation\">";
	echo "<input type=\"hidden\" name=\"pid\" value=\"$prdid\">";
	echo "<tr><td colspan=\"9\" class=\"row3\">";
	echo "<input type=\"submit\" name=\"submit_relation\" value=\""._THEM."\"></form></td></tr>";	
	echo "</table></div>";
}else{
	//OpenDiv();
	echo "<center>"._NODATA."</center>";
	//CLoseDiv();
}

echo "<br>";
?>