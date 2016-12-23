<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
?>
    <style>
        @media screen and (max-width: 400px){
            .main-page form:nth-child(1) .table-bordered td{
                width: 100%;
                display: block;
            }
            .main-page form:nth-child(1) .table-bordered td:nth-child(1){
                text-align: left;
            }

        }

    </style>
<?php
$adm_pagetitle2 = _CTPART;

include("page_header.php");

$ds_title = $ds_email = "none";
$phone = $note = $address = '';
$title = $email ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	$email = nospatags($_POST['email']);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $note = nospatags($_POST['note']);

	if($title =="") {
		$ds_title = "";
		$err = 1;
	}

	if($db->sql_numrows($db->sql_query_simple("SELECT title FROM ".$prefix."_contact_part WHERE title='$title'")) > 0) {
		$ds_title = "";
		$err = 1;
	}

	if(!$err) {
		$db->sql_query_simple("INSERT INTO ".$prefix."_contact_part (id, title, email,alanguage,phone, address, note) VALUES (NULL, '$title', '$email', '$currentlang', '$phone','$address', '$note')");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._ADD." "._CTPART."");
		header("Location: modules.php?f=$adm_modname&do=$do&bf");
	}
}
ajaxload_content();

echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" ><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._CTPART."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Tên web</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_title, "title", _ERROR1)."<input type=\"text\" name=\"title\" value=\"$title\" size=\"40\"></td>\n";
echo "</tr>\n";

//echo "<tr>\n";
//echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Tên đại diện</b></td>\n";
//echo "<td class=\"row3\"><input type=\"text\" name=\"note\" value=\"$note\" size=\"40\"></td>\n";
//echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Số điện thoại</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"phone\" value=\"$phone\" size=\"40\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"email\" value=\"$email\" size=\"40\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Địa chỉ</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"address\" value=\"$address\" size=\"40\"></td>\n";
echo "</tr>\n";

echo "<input type=\"hidden\" name=\"subup\" value=\"1\">\n";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table></form>";

$result = $db->sql_query_simple("SELECT id, title, email,phone,address,note FROM ".$prefix."_contact_part WHERE alanguage='$currentlang' ORDER BY id DESC");
if($db->sql_numrows($result) > 0) {
	echo "<br><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" class=\"tableborder\">\n";
	echo "<tr>\n";
	echo "<td class=\"row1sd\">Tên web</td>\n";
//    echo "<td class=\"row1sd\" align=\"center\" width=\"200\">Tên đại diện</td>\n";
	echo "<td class=\"row1sd hidden-xs\" align=\"center\" width=\"300\">Số điện thoại</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"200\">Email</td>\n";
	echo "<td class=\"row1sd hidden-xs\" align=\"center\" width=\"400\">Địa chỉ</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._EDIT."</td>\n";
	echo "<td class=\"row1sd\" align=\"center\" width=\"30\">"._DELETE."</td>\n";
	echo "</tr>\n";
	$i =0;
	while(list($id, $title, $email,$phone,$address,$note) = $db->sql_fetchrow_simple($result)) {
		if($i%2 == 1) { $bgcolor = "#F7F7F7"; }	else { $bgcolor ="#FFFFFF"; }

		echo "<tr>\n";
		if($ajax_active == 1) {
			echo "<td class=\"row1\" id=\"".$adm_modname."_title_edit_".$id."\"><a href=\"?f=$adm_modname&do=edit_part&id=$id\" title=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($id,'$title','$adm_modname',30,'"._SAVECHANGES."','quick_title_part');\"><b>$title</b></a></td>\n";
		} else {
			echo "<td class=\"row1\"><a href=\"?f=$adm_modname&do=edit_part&id=$id\" info=\""._EDIT."\"><b>$title</b></a></td>\n";
		}

//		echo "<td align=\"center\" class=\"row3\">$note</td>\n";
        echo "<td align=\"center\" class=\"row3 hidden-xs\">$phone</td>\n";
		echo "<td align=\"center\" class=\"row3\">$email</td>\n";
		echo "<td align=\"center\" class=\"row3 hidden-xs\">$address</td>\n";
		echo "<td align=\"center\" width=\"30\" class=\"row1\"><a href=\"?f=$adm_modname&do=edit_part&id=$id\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
		if($ajax_active == 1) {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=$adm_modname&do=delete_part&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK."','delete_part','');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		} else {
			echo "<td align=\"center\" width=\"30\" class=\"row3\"><a href=\"?f=$adm_modname&do=delete_part&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></td>\n";
		}
		echo "</tr>\n";
		$i ++;
	}
	echo "</table></div>";
}

include_once("page_footer.php");

?>