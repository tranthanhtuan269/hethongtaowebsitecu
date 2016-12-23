<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"3\" class=\"header\">"._INTRO_CURRENT_INTRO."</td></tr>\n";
echo "<tr>\n<td class=\"row1sd\">"._INTRO_TITLE."</td>\n";
echo "<td class=\"row1sd\" width=\"5%\">"._INTRO_EDIT."</td>\n";
echo "<td class=\"row1sd\" width=\"5%\">"._INTRO_DELETE."</td>\n";
echo "</tr>\n";

$db->sql_query_simple("SELECT id, title, body FROM {$prefix}_intro WHERE lang='$currentlang'");
while (list($introId, $introTitle) = $db->sql_fetchrow_simple()) {
	if($ajax_active == 1) {
		$tdId = " id=\"{$adm_modname}_title_edit_{$introId}\"";
		$title = "<a href=\"?f=$adm_modname&do=quick_title_intro&id=$introId\" info=\""._QUICK_EDIT."\" onclick=\"return show_edit_title($introId,'$introTitle','$adm_modname',20,'"._SAVECHANGES."','quick_title_intro');\">$introTitle</a>";
		// $icondel = "<a href=\"?f=$adm_modname&do=delete_intro&id=$introId\" onclick=\"return aj_base_delete('$introId','$adm_modname','"._INTRO_DELETE_INTRO_ASK."','delete_intro','');\" info=\""._INTRO_DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
	} else {
		$tdId = '';
		$title = $introTitle;
		$icondel = "<a href=\"?f=$adm_modname&do=delete_intro&id=$introId\" onclick=\"return confirm('"._INTRO_DELETE_INTRO_ASK."');\" info=\""._INTRO_DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
	}
	echo "<tr>\n";
	echo "<td class=\"row3\"$tdId>$title</td>\n";
	echo "<td align=\"center\" width=\"5%\" class=\"row3\"><a href=\"?f=".$adm_modname."&do=edit_intro&id=$introId\" info=\""._INTRO_EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
	echo "<td align=\"center\" width=\"5%\" class=\"row3\"></td>\n";
	echo "</tr>\n";
}

echo "</table>\n</div>\n";

include_once("page_footer.php");
?>