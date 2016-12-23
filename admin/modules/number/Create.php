`<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$ngay = $bachthu = $songthu = $xien2 = $ngay2 = $ngay3 = $err =  "";

if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	
	$ngay = nospatags($_POST['ngay']);
	$bachthu = nospatags($_POST['bachthu']);
	$active = 0;
	$songthu = nospatags($_POST['songthu']);
	$xien2 = nospatags($_POST['xien2']);
	$ngay2 = nospatags($_POST['ngay2']);
	$ngay3 = nospatags($_POST['ngay3']);	
	
	$submit1 = $_POST['submit1'];

	if(!$err) {		
		
	$result = $db->sql_query_simple("INSERT INTO {$prefix}_number_date (id, date ,bachthu, songthu, xien2, khung2,khung3, active) VALUES ('', '$ngay' ,'$bachthu',  '$songthu', '$xien2', '$ngay2', '$ngay3', 0)");

	
		updateadmlog($admin_ar[0], $adm_modname, _MODngay, _ADDNEWS);	
		
		$guid="index.php?f=products&do=detail&id=$prdidmax";
		
		if($submit1 == _ADD){
			header("Location: modules.php?f=".$adm_modname."");
		}
	}
}

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\">";




echo "<div class=\"container\">";

echo "<div id=\"thong_tin_sp\" class=\"tabcontent\">"; //== thong tin san pham

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
//echo "<tr><td colspan=\"2\" class=\"header\">"._ADDNEWS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Ngày</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"ngay\" value=\"$ngay\" size=\"70\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Bạch thủ</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"bachthu\" value=\"$bachthu\" size=\"70\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Song thủ</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"songthu\" name=\"songthu\" value=\"$songthu\" style=\"width:240px;\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Xiên 2</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"xien2\" name=\"xien2\" value=\"$xien2\" style=\"width:240px;\"></td>\n";
echo "</tr>\n";



echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Loto khung 2 ngày</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"ngay2\" name=\"ngay2\" value=\"$ngay2\" style=\"width:100px;\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Loto khung 3 ngày</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"ngay3\" name=\"ngay3\" value=\"$ngay3\" style=\"width:100px;\"></td>\n";
echo "</tr>\n";

echo "</table>";
echo "</div>";


echo "</div>";


echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<input type=\"submit\" name=\"submit1\" value=\""._ADD."\" class=\"button2\">&nbsp;&nbsp;";

echo "</form>";

include_once("page_footer.php");
?>