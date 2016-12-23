<?php
if (!defined('CMS_SYSTEM')) die();

global $keywords_page, $description_page, $title_page,$currentlang;

if(!isset($_GET['page'])){
	$key=trim(stripslashes(resString($_POST['key'])));
}else{
	$key = trim(stripslashes(resString($_GET['key'])));
}


$query_search = "";

if($key==_INPUT_SEARCH){$query_search .= "";}else{$query_search .= " AND title LIKE '%$key%' ";}


$page_title = _RESULT_SEARCH." - ".$key;
$title_page = _RESULT_SEARCH." - ".$key;

include_once("header.php");


echo "<div class=\"div-home\" style=\"padding:0px 10px 40px 10px;width:658px;\">";
echo "<div class=\"title_home\">"._RESULT_SEARCH."</div>";

$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_news WHERE  active = 1 $query_search AND alanguage='$currentlang'";
$result = $db->sql_query_simple($query);
list($total) = $db->sql_fetchrow_simple($result);

$query = "SELECT id, title, hometext, time, images FROM ".$prefix."_news WHERE active = 1 $query_search AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage";
$resultn = $db->sql_query_simple($query);
if($db->sql_numrows($resultn) > 0) {
	while(list($id, $title, $hometext, $time, $images) = $db->sql_fetchrow_simple($resultn)) {
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		if(file_exists("$path_upload_img/$images") && $images !="") {
			$size_pic = getimagesize("$path_upload_img/$images");
			if(file_exists("$path_upload_img/thumb_".$images."")) {
				$images = "thumb_".$images."";
			}
			$sizepic = $sizenews+4;
			if($size_pic[0] > $sizenews) {
				$news_img = "<div style=\"float: ".$pic_align_cat."; width: $sizepic; margin-right: 8px; border: 2px solid #FFF\"><a href=\"".fetch_urltitle("index.php?f=$module_name&do=detail&id=$id",$title)."\" title=\""._DETAIL."\"><img border=\"0\" src=\"$path_upload_img/$images\" width=\"$sizenews\"></a></div>";
			} else {
				$news_img = "<div style=\"float: ".$pic_align_cat."; width: {$size_pic[0]}; margin-right: 8px; border: 2px solid #FFF\"><a href=\"".fetch_urltitle("index.php?f=$module_name&do=detail&id=$id",$title)."\" title=\""._DETAIL."\"><img border=\"0\" src=\"$path_upload_img/$images\" width=\"{$size_pic[0]}\"></a></div>";
			}
		} else {
			$news_img ="";
		}
		temp_news_index($id, $title, $hometext, $news_img);
	}
	if($total > $perpage) {
		echo "<div>";
		$pageurl = "index.php?f=$module_name&do=search&key=$key";
		echo paging($total,$pageurl,$perpage,$page);
		echo "</div>";
	}
}
else {echo _NOT_FOUND;}


echo "</div>";
include_once("footer.php");

?>