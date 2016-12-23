<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
?>
    <style>
        input[type^="text"]{
            width: 200px;
        }
        form[name^="frm2"]{
            padding: 10px;
        }
        input[name^="btn_timsdt"]{
            margin-left: -1px;
        }

    </style>
<?php
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
switch($sort) {
	default: $sortby = "ORDER BY id DESC"; break;
	case 0: $sortby = "ORDER BY id DESC"; break;
	case 1: $sortby = "ORDER BY fullname ASC"; break;
	case 2: $sortby = "ORDER BY fullname DESC"; break;
	case 3: $sortby = "ORDER BY phone ASC"; break;
	case 4: $sortby = "ORDER BY phone DESC"; break;
	case 5: $sortby = "ORDER BY orderTime ASC"; break;
	case 6: $sortby = "ORDER BY orderTime DESC"; break;
}

$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page - 1) * $perpage;
$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_products_order"));
$total = ($countf[0]) ? $countf[0] : 1;
if(isset($_REQUEST["btn_timsdt"]))
{
	if($_POST["phone"]!="") header("Location: modules.php?f={$adm_modname}&do=orders&phone=".$_POST["phone"]);
	else header("Location: modules.php?f={$adm_modname}&do=orders");
}
if(@$_REQUEST["phone"]!="")
{
	$result = $db->sql_query_simple("SELECT id, fullname, title1, mail, orderTime, status,username, why,date_update,  phone, orderList FROM {$prefix}_products_order where phone like '%".@$_REQUEST["phone"]."%' $sortby LIMIT $offset,$perpage");
}
else
{
	$result = $db->sql_query_simple("SELECT id, fullname,title1, mail, orderTime, status,username,why,date_update, phone, orderList FROM {$prefix}_products_order $sortby LIMIT $offset,$perpage");
}
if($db->sql_numrows($result) > 0) {
	echo "<script language=\"javascript\" type=\"text/javascript\">\n";
	echo "function check_uncheck(){\n";
	echo "	var f= document.frm;\n";
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

	echo "<div id=\"{$adm_modname}_main\"><form name=\"frm2\" action=\"\" method=\"POST\"><div style='line-height:30px;display: flex; font-size:13px;'>S&#7889; &#273;i&#7879;n tho&#7841;i&nbsp;&nbsp;<input type='text' name='phone' style=\"height: 30px;margin-right: 10px;\"><input type='submit' name='btn_timsdt' class=\"btn btn-submit\" value='T&igrave;m'></div></form>
	<form name=\"frm\" action=\"modules.php?f={$adm_modname}&do=quick_do_order\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
	echo "<tr><td colspan=\"11\" class=\"header\">"._PRD_ORDER_LIST."</td></tr>";
	echo "<tr>\n";
	echo "<td class=\"row1sd\" width=\"20\" align=\"center\">"._PRD_ORDER_ID."</td>\n";
	echo "<td class=\"row1sd hidden-xs\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
	echo "<td class=\"row1sd\">"._PRD_ORDER_CUSTOMER_NAME." ".sortBy("modules.php?f=$adm_modname&do=orders",1)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"100\">Phone</td>\n";
	echo "<td class=\"row1sd hidden-xs\" align=\"center\" width=\"200\">Xử lý ngày</td>\n";
	echo "<td class=\"row1sd hidden-xs\" align=\"center\" width=\"140\">"._PRD_ORDER_ORDER_TIME." ".sortBy("modules.php?f=$adm_modname&do=orders",5)."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"60\">"._STATUS."</td>\n";
	echo "<td class=\"row1sd hidden-xs\" align=\"center\" width=\"100\">Ng&#432;&#7901;i x&#7917; l&yacute;</td>\n";
	echo "<td class=\"row1sd hidden-xs\" align=\"center\" width=\"150\">Ghi ch&uacute;</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._VIEW."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$t = 1;
	
	$link_delete="";
	$link_delete2="";
	while(list($orderId, $fullname,$title1, $mail, $orderTime, $status,$username,$why,$date_update, $phone, $orderlist) = $db->sql_fetchrow_simple($result)) {
	
		$query2 = "SELECT title FROM {$prefix}_products WHERE";
		// $orderlist = explode(',', $orderlist);
		// $newOrderList = array();
		// for ($i = 0; $i < count($orderlist); $i++) {
		// 	$orderList[$i] = explode('x', $orderlist[$i]);
		// 	$query2 .= " id={$orderList[$i][1]} OR";
		// 	$newOrderList[$orderlist[$i][1]] = intval($orderlist[$i][0]);
		// }
		// $query2 = substr($query2, 0, strlen($query2) - 3);
		// $shoesname=@mysql_query($query2);
		// //echo $query2."<br />";
		// $shoes_name="";
		// while($row = @mysql_fetch_row($shoesname))
		// {
		// 	$shoes_name.=$row[0]."<br/>";
		// }
		
		if($i % 2 == 1) $css = "row3";
		else $css ="row1";
		
		switch (intval($status)) {
			case 1:
				$statusText = _PRD_ORDER_PROCESSED;
				break;
			case 0:
				$statusText = "<font color=\"red\">"._PRD_ORDER_UNPROCESSED."</font>";
				break;
		}

		//if ($ajax_active == 1) $delete = "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_order&id=$orderId\" title=\""._DELETE."\" onclick=\"return aj_base_delete($orderId,'$adm_modname','"._DELETEASK2."','delete_order','');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		//else $delete = "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_order&id=$orderId\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK2."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
		
		if($admin_ar[0]=="admin" || $admin_ar[0]=="tuongdd")
		{
			$link_delete="<a href=\"?f=".$adm_modname."&do=delete_order&id=$orderId\" title=\""._DELETE."\" onclick=\"return aj_base_delete($orderId,'$adm_modname','"._DELETEASK2."','delete_order','');\"><img border=\"0\" src=\"images/delete.png\"></a>";
			$link_delete2="<a href=\"?f=".$adm_modname."&do=delete_order&id=$orderId\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK2."');\"><img border=\"0\" src=\"images/delete.png\"></a>";
		}
		else
		{
			$link_delete="<img border=\"0\" src=\"images/delete.png\" style='opacity:0.5'>";
			$link_delete2="<img border=\"0\" src=\"images/delete.png\" style='opacity:0.5'>";
		}
		if ($ajax_active == 1) $delete = "<td align=\"center\" width=\"30\" class=\"$css\">$link_delete</td>\n";
		else $delete = "<td align=\"center\" width=\"30\" class=\"$css\">$link_delete2</td>\n";
		
		echo "<tr>\n";
		echo "<td align=\"center\" class=\"$css\" width=\"20\">$t</td>";
		echo "<td class=\"$css hidden-xs\" width=\"10\"><input type=\"checkbox\" name=\"id[]\" value=\"$orderId\"></td>";
		echo "<td class=\"$css\"><a href=\"?f=".$adm_modname."&do=view_order&id=$orderId\" info=\""._VIEW."\"><b>$fullname</b></a></td>\n";		
		echo "<td align=\"center\" class=\"$css\" width=\"100\">0$phone</td>\n";
		echo "<td align=\"center\" class=\"$css hidden-xs\" width=\"200\"> $date_update</td>\n";
		echo "<td align=\"center\" class=\"$css hidden-xs\" width=\"140\">$orderTime</td>\n";
		echo "<td align=\"center\" class=\"$css\" width=\"60\">$statusText</td>\n";
		echo "<td align=\"center\" class=\"$css hidden-xs\" width=\"100\">$username</td>\n";
		echo "<td align=\"center\" class=\"$css hidden-xs\" width=\"150\">$why</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=view_order&id=$orderId\" info=\""._VIEW."\"><img border=\"0\" src=\"./images/search.gif\"></a></td>\n";
		echo $delete;
		echo "</tr>\n";
		$t++;
	}
	if($total > $perpage) {
		echo "<tr><td colspan=\"11\">";
		$pageurl = "modules.php?f=".$adm_modname."&sort=$sort&do=orders";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</td></tr>";
	}
	echo "<tr><td colspan=\"11\" class=\"row3\"><select name=\"fc\">";
	echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
	echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
	echo "</select>&nbsp;<input type=\"submit\" class=\"btn btn-submit\" name=\"submit\" value=\""._DOACTION."\"></form></td></tr>";
	echo "</table></div>";
} else {
	OpenDiv();
	echo "<center>"._NODATA."</center>";
	CLoseDiv();
}

include_once("page_footer.php");
?>