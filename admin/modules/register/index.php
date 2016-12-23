<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

if (defined('iS_RADMIN')) {
	include("page_header.php");
	

	$perpage = 20;
	$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']) : 1);
	$offset = ($page-1) * $perpage;
	$total = $db->sql_numrows($db->sql_query_simple("SELECT*FROM ".$prefix."_register WHERE alanguage='$currentlang'"));

	$result = $db->sql_query_simple("SELECT  id,name,birthday,address,email,phone,cmnd,khuvuc,totnghiep,diemtn,hocba,diemhb,hedangky,hoidongthi,sbd,datedk,status   FROM ".$prefix."_register WHERE alanguage='$currentlang' ORDER BY id DESC LIMIT $offset, $perpage");
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
		echo "		if(f.f.value =='') {\n";
		echo "			f.f.focus();\n";
		echo "			return false;\n";
		echo "		}\n";
		echo "		f.submit.disabled = true; \n";
		echo "		return true;		\n";
		echo "	}	\n";
		echo "</script>\n";
		ajaxload_content();

		echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder table table-bordered\">\n";
		echo "<tr>\n";
		echo "<tr><td colspan=\"8\" class=\"header\">Danh sách học viên đăng ký</td></tr>";
		echo "<td class=\"row1sd\" width=\"10\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\"></td>\n";
		echo "<td class=\"row3sd\">Tên học viên</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"150\">email</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"150\">Địa chỉ</td>\n";
		echo "<td class=\"row3sd\" align=\"center\" width=\"100\">Ngày đăng ký</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"80\">"._STATUS."</td>\n";
		echo "<td class=\"row3sd\" align=\"center\" width=\"30\">"._VIEW."</td>\n";
		echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
		echo "</tr>\n";
		$cur_ar = array(_VND,_USD);
		$i =0;
		while(list($id, $name, $birthday, $address, $email,$phone, $cmnd,$khuvuc,$totnghiep,$diemtn,$hocba,$diemhb,$hedangky,$hoidongthi,$sbd,$datedk,$status) = $db->sql_fetchrow_simple($result)) {

			switch($status) {
				case 0: $status_o = "Chưa sử lý";  break;
				case 1: $status_o = "Đang xử lý"; break;
				case 2: $status_o = "Đã xử lý"; break;
			}

			echo "<tr>\n";
			echo "<td class=\"row1\"><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
			echo "<td class=\"row3\">\n";
			if($name) {
				echo "<a href=\"?f=$adm_modname&do=view_ct&id=$id\" info=\""._VIEW."\"><b>$name</b></a>\n";
			} else {
				echo "<a href=\"?f=$adm_modname&do=view_ct&id=$id\" info=\""._VIEW."\"><b>No name</b></a>\n";
			}
			echo "</td>\n";
			echo "<td align=\"center\" class=\"row1\"> $email</td>\n";
			echo "<td align=\"center\" class=\"row3\"> $address</td>\n";
			echo "<td align=\"center\" class=\"row3\"> $datedk</td>\n";
			echo "<td align=\"center\" class=\"row1\"><font color=\"red\">$status_o</font></td>\n";
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=$adm_modname&do=view_ct&id=$id\" info=\""._VIEW."\"><img border=\"0\" src=\"images/search.gif\"></a></td>\n";
			if($ajax_active == 1) {
				echo "<td align=\"center\" width=\"30\" class=\"row1\"><a href=\"?f=$adm_modname&do=delete_ct&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','Bạn có chắc xóa không','delete_ct','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
			} else {
				echo "<td align=\"center\" width=\"30\" class=\"row1\"><a href=\"?f=$adm_modname&do=delete_ct&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('Bạn có chắc xóa không');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
			}
			echo "</tr>\n";
			$i ++;
		}
		if($total > $perpage) {
			echo "<tr><td colspan=\"6\">";
			$pageurl = "".$adm_modname.".php";
			echo paging($total,$pageurl,$perpage,$page);
			echo "</td></tr>";
		}
		echo "<input type=\"hidden\" name=\"do\" value=\"quick_do\">";
		echo "<tr><td colspan=\"7\" class=\"row3\"><select name=\"v\">";
		echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
		echo "<option value=\"1\">&raquo; "._QUICKDO_1."</option>";
		echo "<option value=\"2\">&raquo; "._NOPROCESS."</option>";
		echo "<option value=\"3\">&raquo; "._PROCESSING."</option>";
		echo "<option value=\"4\">&raquo; "._PROCESSED."</option>";
		echo "</select> <input type=\"submit\" name=\"submit\" value=\""._DOACTION."\"></td></tr>";
		echo "</table></form></div><br>";

	}else{
		OpenDiv();
		echo "<center>"._NOCONTACT."</center>";
		CLoseDiv();
	}

	include_once("page_footer.php");
} else {
	header("Location: body.php");
}
?>