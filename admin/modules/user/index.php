 <?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort'] : 0));
switch($sort) {
	case 1: $sortby ="ORDER BY id ASC"; break;
	case 2: $sortby ="ORDER BY id DESC"; break;
	case 3: $sortby ="ORDER BY fullname ASC"; break;
	case 4: $sortby ="ORDER BY fullname DESC"; break;
	case 5: $sortby ="ORDER BY email ASC"; break;
	case 6: $sortby ="ORDER BY email DESC"; break;
	default: $sortby ="ORDER BY registrationTime DESC"; break;
}

$db->sql_query_simple("SELECT COUNT(id) FROM {$prefix}_user");
list($total) = $db->sql_fetchrow_simple();
$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page'] : 1));
$offset = ($page - 1) * $perpage;

ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"7\" class=\"header\">"._MODTITLE."</td></tr>\n";
echo "<tr>\n<td class=\"row1sd hidden-xs\" width=\"8%\" align=\"center\">"._USER_ID." <a href=\"modules.php?f=$adm_modname&sort=1\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"modules.php?f=$adm_modname&sort=2\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
echo "<td class=\"row1sd\" align=\"center\">"._USER_FULLNAME."  <a href=\"modules.php?f=$adm_modname&sort=3\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"modules.php?f=$adm_modname&sort=4\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
echo "<td class=\"row1sd hidden-xs\" align=\"center\">"._USER_EMAIL."  <a href=\"modules.php?f=$adm_modname&sort=5\" info=\""._SORTUP."\"><img border=\"0\" src=\"images/sup.gif\" align=\"absmiddle\"></a> <a href=\"modules.php?f=$adm_modname&sort=6\" info=\""._SORTDOWN."\"><img border=\"0\" src=\"images/sdown.gif\" align=\"absmiddle\"></a></td>\n";
echo "<td align=\"center\" width=\"120\" class=\"row1sd\"><b>Số dư tài khoản</b></td>\n";
echo "<td align=\"center\" width=\"50\" class=\"row1sd\"><b>"._SHOW."</b></td>\n";
echo "<td class=\"row1sd\" width=\"5%\" align=\"center\">"._EDIT."</td>\n";
echo "<td class=\"row1sd\" width=\"5%\" align=\"center\">"._DELETE."</td>\n";
echo "</tr>\n";

$result = $db->sql_query_simple("SELECT id, fullname, email, actives, money FROM {$prefix}_user $sortby LIMIT $offset, $perpage");
if ($db->sql_numrows() > 0) {
	$i = 0;
	while (list($id, $tname, $temail, $active, $money) = $db->sql_fetchrow_simple($result)) {
		if($ajax_active == 1) {	
	switch($active) {
		case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" title=\""._DEACTIVATE."\" onclick=\"return aj_base_status($mid,0,'$adm_modname','','');\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
		case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" title=\""._ACTIVE."\" onclick=\"return aj_base_status($mid,1,'$adm_modname','','');\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
	}	
} else {
	switch($active) {
		case 1: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
		case 0: $active = "<a href=\"?f=$adm_modname&do=status&id=$id&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
	}	
}
		if (($i % 2) == 1) $css = "row1";
		else $css ="row3";

		if ($ajax_active == 1) {
			$tdId = " id=\"{$adm_modname}_title_edit_$id\"";
			$fullname = "<a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" onclick=\"return show_edit_title($id,'$tname','$adm_modname',20,'"._SAVECHANGES."','quick_edit_name')\" info=\""._QUICK_EDIT."\">$tname</a>";
			$tdId2 = " id=\"email_title_edit_$id\"";
			$email = "<a href=\"modules.php?f=$adm_modname&do=edit&id=$id\" onclick=\"return show_edit_title2($id,'$temail','$adm_modname','email',20,'"._SAVECHANGES."','quick_edit_email','email_title_edit_$id')\" info=\""._QUICK_EDIT."\">$temail</a>";
						
			$delete = "<a href=\"modules.php?f=$adm_modname&do=delete&id=$id\" onclick=\"return aj_base_delete('$id','$adm_modname','"._USER_DELETEASK."','delete','id');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		} else {
			$tdId = $tdId2= '';
			$fullname = $tname;
			$email = $temail;
			$delete = "<a href=\"modules.php?f=$adm_modname&do=delete&id=$id\" onclick=\"return confirm('"._USER_DELETEASK."')\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
		}

		echo "<tr>\n<td class=\"$css hidden-xs\" width=\"5%\" align=\"center\">$id</td>\n";
		echo "<td class=\"$css\" align=\"left\"$tdId>$fullname</td>\n";
		echo "<td class=\"$css hidden-xs\" align=\"left\"$tdId2>$email</td>\n";
		// $result_check = $db->sql_query_simple("SELECT id FROM {$prefix}_products_order WHERE userid=$id");
		// if($db->sql_numrows($result_check) > 0){
		// 	echo "<td align=\"center\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=orders&userid=$id\" info=\""._VIEW_ORDER."\"><img border=\"0\" src=\"./images/search.gif\"></a></td>\n";
		// }else {
			echo "<td align=\"center\" class=\"$css\"><font color=\"red\">".dsprice($money)." VNĐ</font></td>\n";
		// }
		echo "<td align=\"center\" class=\"row1\">$active</td>\n";
		echo "<td class=\"$css\" align=\"center\"><a href=\"modules.php?f=$adm_modname&do=edit&id=$id\"><img border=\"0\" src=\"../images/edit.gif\"></a></td>\n";
		echo "<td class=\"$css\" align=\"center\">$delete</td>\n</tr>";
	}
}

if($total > $perpage) {
	echo "<tr><td colspan=\"5\">";
	$pageurl = "modules.php?f=$adm_modname&sort=$sort";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</td></tr>";
}

echo "</table>\n</div>\n";

include_once("page_footer.php");
?>
