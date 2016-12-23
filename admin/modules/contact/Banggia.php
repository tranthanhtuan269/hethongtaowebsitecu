<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

$adm_pagetitle2 = "Bảng giá";

$result = $db->sql_query_simple("SELECT banggia FROM ".$prefix."_contact_add WHERE alanguage='$currentlang'");
list($banggia) = $db->sql_fetchrow_simple($result);

if(isset($_POST['submit']) && $_POST['submit'] !="") {
	$banggia = trim(stripslashes(resString($_POST['banggia'])));
	
	if($db->sql_numrows($result) > 0) {
		$db->sql_query_simple("UPDATE ".$prefix."_contact_add SET banggia='$banggia' WHERE alanguage='$currentlang'");
	}else{
		$db->sql_query_simple("INSERT INTO ".$prefix."_contact_add VALUES ('$banggia', '$currentlang')");
	}	
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, ""._SAVECHANGES." banggia");
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
	exit();
}	

include("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td class=\"header\">Bảng giá</td></tr>";
echo "<tr>\n";
echo "<td>";
editor("banggia",$banggia,"","");
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");

?>