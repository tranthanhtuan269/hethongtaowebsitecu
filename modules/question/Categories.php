<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");
echo "<div class=\"main-question\">";
	echo "<div class=\"main-question-left\">";
		include_once("blocks/In-danh_muc_hoi_dap.php");
	echo "</div>";	
	echo "<div class=\"main-question-content\">";
$catid = intval($_GET['id']);
$resul_cat_1 = $db->sql_query_simple("SELECT title FROM  ".$prefix."_question_cat   WHERE  catid = $catid");
list($titlecat_1) = $db->sql_fetchrow_simple($resul_cat_1);

echo "<div class=\"title_home\"><h2>".$titlecat_1."</h2></div>";
echo "<div class=\"div-home\">";

echo "<div class=\"add-question\">";	
	echo "<span class=\"add\"><a href=\"index.php?f=question&do=create\">"._ADD_QUESTION."</a></span>";
	echo "<img src=\"images/addition.gif\" >";
echo "</div>";

$perpage = 10;

$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_question WHERE alanguage='$currentlang'";
$result = $db->sql_query_simple($query);
list($total) = $db->sql_fetchrow_simple($result);
echo "<div class=\"question-answer\">";
$query = "SELECT id, title, content, time, name, email,hits FROM ".$prefix."_question WHERE active=1 AND catid= $catid AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage";
$resultn = $db->sql_query_simple($query);
if($db->sql_numrows($resultn) > 0) {
	while(list($id, $title, $content, $time, $name, $email,$hits) = $db->sql_fetchrow_simple($resultn)) {
		$rwtitle = utf8_to_ascii(url_optimization($title));
		$url_detail =url_sid("index.php?f=question&do=detail&id=$id&t=$rwtitle");
		
		echo "<div class=\"qtitle\"><a href=\"".url_sid("$url_detail")."\">$title</a></div>";
		echo "<div class=\"qname\">"._NGUOI_GUI."<b>$name</b> "._THOI_GIAN."".ext_time($time,2)."</div>";
		$resul_cat = $db->sql_query_simple("SELECT c.catid, c.title FROM ".$prefix."_question AS q INNER JOIN ".$prefix."_question_cat AS c ON c.catid = q.catid  WHERE  q.id = $id");
		list($catid, $titlecat) = $db->sql_fetchrow_simple($resul_cat);
		$rwtitlecat = utf8_to_ascii(url_optimization($titlecat));
		echo "<div class=\"qname\">"._THUOC_CHUYEN_MUC_1."<a href=\"".url_sid("index.php?f=question&do=categories&id=$catid&t=$rwtitlecat")."\"> $titlecat</a></div>";	
		$result_total = $db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_answer WHERE qid=$id AND active=1");
		list($total) = $db->sql_fetchrow_simple($result_total);		
		echo "<div class=\"qname\">"._TRA_LOI."($total) "._LUOT_XEM."($hits)</div>";
		echo "<div class=\"qname\"><b>"._NOI_DUNG."</b></div>";
		echo "<div class=\"qname\" align=\"justify\">$content</div>";
		echo "<div class=\"qname\" style=\"text-align:right;margin-top:5px;\"><a href=\"".url_sid("index.php?f=question&do=detail&id=$id&t=$rwtitle")."\"> "._TRA_LOI_CAU_HOI."</a></div>";
		echo "<div class=\"qrow\"></div>";
	}
	if($total > $perpage) {
		echo "<div>";
		$pageurl = "index.php?f=$module_name";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</div>";
	}
}
echo "</div>";

echo "</div>";
echo "</div>";
echo "</div>";
include_once("footer.php");

?>