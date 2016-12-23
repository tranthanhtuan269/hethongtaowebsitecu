<?php
if (!defined('CMS_SYSTEM')) die();

function temp_news_cat_index($catid, $titlecat, $idhn, $titlehn, $hometexthn, $newshn_pic, $others) {
	OpenTab($titlecat);
	
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">";
	echo "<tr>";
	echo "<td>";
	echo "<div style=\"margin-bottom: 3px\" class=\"content\">$newshn_pic<a href=\"".url_sid("index.php?f=news&do=detail&id=$idhn")."\" class=\"titlecat\"><h3>$titlehn</h3></a></div>";
	echo "<div align=\"justify\" class=\"content\">$hometexthn</div>";
	echo "<div class=\"viewmore\" style=\"margin-top: 6px\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$idhn")."\" class=\"strong\">&raquo; "._READMORE."...</a></div>";
	echo "</td>";
	echo "</tr>";
	if($others) {
		echo "<tr><td style=\"padding-left: 8px; padding-top: 10px;\">$others</td></tr>";
	}
	echo "</table>";
	
	CloseTab();
}

function temp_newdetail($id, $title, $time, $hometext, $bodytext, $fattach, $othershow, $images, $others, $others2, $source) {
	global $module_name, $adm_mods_ar, $admin_fold, $url;

	echo "<table border=\"0\"><tr><td>";
	echo "<div class=\"content\"><h4 class=\"title2\">$title</h4></div>";
	echo "<div class=\"Author\" style=\"margin-bottom: 14px\">".NameDay($time).", ".ext_time($time,2)."</div>";
	echo "<div>$images <div align=\"justify\"><div style=\"margin-bottom: 5px\"><b>$hometext</b></div><span class=\"content\">$bodytext</span></div></div>";
	if($fattach !="") {
		echo "<div class=\"clearfix\" style=\"padding: 4px; margin-bottom:0px; padding-left: 20px; border-bottom:1px solid #cccccc\">";
		echo "<b>"._FILE_ATTACH.":</b> <img border=\"0\" src=\"images/file.gif\" align=\"absmiddle\">&nbsp;<a href=\"".url_sid("$url")."\" style=\"font: bold 12px arial; color: #007dba; text-decoration: underline\">$fattach</a> (".ext_time($time,2).")</div>";
		echo "</div>";
	}
	if($source !="") {
		echo "<div><div align=\"right\" style=\"margin-top: 20px\"><i><b>$source</i></b></div>";
	}
	if(defined('iS_SADMIN') || defined('iS_RADMIN') || (defined('iS_ADMIN') && in_array($module_name,$adm_mods_ar))) {
		echo "<div align=\"right\" style=\"margin-top: 3px\">[<a href=\"".$admin_fold."/modules.php?f=news&do=edit_news&id=$id\" target=\"mainFrame\">"._EDIT."</a> | <a href=\"".$admin_fold."/modules.php?f=news&do=delete_news&id=$id\" target=\"mainFrame\" onclick=\"return confirm('"._DELETEASK."');\">"._DELETE."</a>]</div>";
	}
	echo "</td></tr></table>";
	echo "<p><span style=\"float:right\"><a href=\"javascript:history.go(-1);\">[<b>"._BACK."</b>]</a> <a href=\"#\">[<b>"._TOP."</b>]</a></span><a href=\"".url_sid("index.php?f=news&do=print&id=".$id."")."\" target=\"_blank\">";
	echo "<img border=\"0\" src=\"images/print.gif\" width=\"32\" height=\"18\" title=\""._PRINT."\"></a> <a href=\"javascript:void(0)\" onclick=\"openNewWindow('".url_sid("index.php?f=news&do=email&id=".$id."")."',220,450)\">";
	echo "<img border=\"0\" src=\"images/email.gif\" width=\"30\" height=\"18\" title=\""._SENDFRIEND."\"></a></p>";
	if($othershow != 1)
	{
		if($others2) {
			echo "<p><b>"._OTHERNEW1.":</b><br>";
			echo "$others2</p>";
		}
		if($others) {
			echo "<p><b>"._OTHERNEW.":</b><br>";
			echo "$others</p>";
		}
	}
}

function temp_newcat_start($id, $title, $hometext, $images) {
	echo "<table style=\"margin-bottom: 8px; padding: 5px\"><tr><td>";
	echo "<div style=\"margin-bottom: 4px\">$images ";
	echo "<a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\"><h4 class=\"title2\">$title</h4></a></div>";
	echo "<div align=\"justify\">$hometext</div>";
	echo "<div class=\"viewmore\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" class=\"strong\">"._READMORE."</a></div>";
	echo "</td></tr></table>";
}

function temp_news_index($id, $title, $hometext, $newspic) {
	echo "<table><tr><td style=\"padding-right: 5px\">";
	echo "<div style=\"margin-bottom: 10px; padding-top: 10px\">";
	echo "<div align=\"justify\">$newspic <a href=\"".url_sid("index.php?f=news&do=detail&id=".$id."")."\"><h4 class=\"title2\">$title</h4></a></div><span align=\"justify\">$hometext</span></div>";
	echo "<div class=\"viewmore\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" class=\"strong\">"._READMORE."</a></div>";
	echo "</td></tr></table>";
}
?>