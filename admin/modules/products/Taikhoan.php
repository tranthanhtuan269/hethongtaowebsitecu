<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = _ADDRESSMNG;

$result = $db->sql_query_simple("SELECT money, atm, banks, unions, address, store, zip FROM ".$prefix."_taikhoan WHERE alanguage='$currentlang'");
list($money, $atm, $banks, $unions, $address, $store, $zip) = $db->sql_fetchrow_simple($result);

if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$money = trim(stripslashes(resString($_POST['money'])));
	$atm = trim(stripslashes(resString($_POST['atm'])));
	$banks = trim(stripslashes(resString($_POST['banks'])));
	$unions = trim(stripslashes(resString($_POST['unions'])));
	$address = trim(stripslashes(resString($_POST['address'])));
	$store = trim(stripslashes(resString($_POST['store'])));
	$zip = trim(stripslashes(resString($_POST['zip'])));
	
	if($db->sql_numrows($result) > 0) {
		$db->sql_query_simple("UPDATE ".$prefix."_taikhoan SET money='$money', atm='$atm', banks='$banks', unions='$unions', address='$address', store='$store', zip='$zip'  WHERE alanguage='$currentlang'");
	}else{
		$db->sql_query_simple("INSERT INTO ".$prefix."_taikhoan (money, atm, banks, unions, address, store, zip, alanguage) VALUES ('$money', '$atm','$banks', '$unions','$address', '$store','$zip', '$currentlang')");
	}	
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._SAVECHANGES." tai khoan");
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
	exit();
}	

include("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._TT_TAI_KHOAN."</td></tr>";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\" width=\"30%\"><b>"._MONEY."</b></td>\n";
echo "<td class=\"row2\">";
editbasic("money",$money,"",100);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>"._ATM."</b></td>\n";
echo "<td class=\"row2\">";
editbasic("atm",$atm,"",100);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>"._BANK."</b></td>\n";
echo "<td class=\"row2\">";
editbasic("banks",$banks,"",100);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>"._UNION."</b></td>\n";
echo "<td class=\"row2\">";
editbasic("unions",$unions,"",100);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>"._ADDRESS_1."</b></td>\n";
echo "<td class=\"row2\">";
editbasic("address",$address,"",100);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>"._STORE."</b></td>\n";
echo "<td class=\"row2\">";
editbasic("store",$store,"",100);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>"._ZIP."</b></td>\n";
echo "<td class=\"row2\">";
editbasic("zip",$zip,"",100);
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td align=\"center\"  colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");

?>