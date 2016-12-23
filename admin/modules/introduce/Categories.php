<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

ajaxload_content();

echo "<div id=\"".$adm_modname."_main\">";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"3\" class=\"header\">";
echo _INTRO_CURRENT_CATS;
echo "</td></tr>";
echo "	<tr>\n";
echo "<td class=\"row1sd\">"._INTRO_TITLE."</td>\n";
echo "<td width=\"5%\" align=\"center\" class=\"row1sd\">"._INTRO_EDIT."</td>\n";
echo "<td width=\"5%\" align=\"center\" class=\"row1sd\">"._INTRO_DELETE."</td>\n";
echo "</tr>\n";

$db->sql_query_simple("SELECT * FROM {$prefix}_intro_cat WHERE alanguage='$currentlang' ");
while (list($catid, $title) = $db->sql_fetchrow_simple()) {
	if ($ajax_active == 1) {
		$tdId = " id=\"{$adm_modname}_title_edit_{$catid}\"";
		$title = "<a href=\"?f=$adm_modname&do=quick_title_cat&id=$catid\" info=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($catid,'$title','$adm_modname',20,'"._SAVECHANGES."','quick_title_cat');\"><b>$title</b></a> <a href=\"../".url_sid("index.php?f=".$adm_modname."&do=categories&catid={$catid}&t=".cv2urltitle($catid)."")."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','".url_sid("index.php?f=$adm_modname&do=categories&catid={$catid}&t=".cv2urltitle($title)."")."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
		$icondel = "<a href=\"?f=$adm_modname&do=delete_cat&id=$catid\" onclick=\"return aj_base_delete('$catid','$adm_modname','"._INTRO_DELETE_CAT_ASK."','delete_cat','');\" info=\""._INTRO_DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
	} else {
		$tdId = '';
		$icondel = "<a href=\"?f=$adm_modname&do=delete_cat&id=$catid\" onclick=\"return confirm('"._INTRO_DELETE_CAT_ASK."');\" info=\""._INTRO_DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
	}
	echo "	<tr>\n";
	echo "<td class=\"row3\"$tdId>$title</td>\n";
	echo "<td width=\"5%\" align=\"center\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=edit_cat&id=$catid\" info=\""._INTRO_EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
	echo "<td width=\"5%\" align=\"center\" class=\"row3\">$icondel</td>\n";
	echo "</tr>\n";
}
echo "</table>";
echo "<br />";
OpenDiv();
echo "* "._INTRO_NOTES."";
CloseDiv();
echo "</div>";
include_once("page_footer.php");
?>