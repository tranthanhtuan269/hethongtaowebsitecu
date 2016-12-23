<?php
if (!defined('CMS_SYSTEM')) die();

$tag = isset($_GET['tag']) ? $_GET['tag'] : $_POST['tag'];
$tagurl = $tag;
$tag=str_ireplace("-"," ",$tag);
$result = $db->sql_query_simple("SELECT title FROM ".$prefix."_news where tag_seo LIKE '%$tag%'");
//if($db->sql_numrows($result) != 1) header("Location: index.php");

list($catname) = $db->sql_fetchrow_simple($result);

//$page_title .= "$tag";
//title
if($tag!=""){$page_title = $tag;} // if($tag!=""){$page_title = $tag." - ".$sitename;}
//keywords
if($tag!=""){$keywords_site ="$tag, ".utf8_to_ascii($tag);}	
else{$header_page_keyword = $tag.",".utf8_to_ascii($tag);}	
//description
$description_site = $tag." - ".$description_site;

$rwcatname= utf8_to_ascii(url_optimization($catname));
include_once("header.php");
$title_home = "<a href=\"".url_sid("index.php")."\">"._HOMEPAGE."</a>  - <a href=\"".url_sid("index.php?f=news&do=tags&tag=".$_GET['tag']."")."\">$tag</a>";
echo "<div class=\"div-home\">";
echo '<ol class="breadcrumb" style="margin-bottom: 5px;">
      <li class="active">'.$tag.'</li>
    </ol>';
echo "<div class=\"_padding\"></div>";
echo "<div class=\"tabbox\">";

$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_news WHERE alanguage='$currentlang' AND (";
$query .= "tag_seo LIKE '%$tag%' OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ')';
$result = $db->sql_query_simple($query);
list($total) = $db->sql_fetchrow_simple($result);

$query = "SELECT id, catid, title, hometext, time, images FROM ".$prefix."_news WHERE alanguage='$currentlang' AND (";
$query .= "tag_seo LIKE '%$tag%' OR ";
$query = substr($query, 0, strlen($query) - 4);
$query .= ") ORDER BY time DESC LIMIT $offset, $perpage";
$resultn = $db->sql_query_simple($query);
if($db->sql_numrows($resultn) > 0) {
	while(list($id,$catid, $title, $hometext, $time, $images) = $db->sql_fetchrow_simple($resultn)) {
		$url_news_detail =url_sid("index.php?f=news&do=detail&id=$id");
		$hometext = strip_tags($hometext, '<a><b><u><i>');
		$get_path = get_path($time);
		$path_upload_img = "$path_upload/news/$get_path";
		if(file_exists("$path_upload_img/$images") && $images !="" && file_exists("$path_upload_img/thumb_".$images."")) {			
			$images = "thumb_".$images."";	
			$news_img = $urlsite."/".$path_upload_img."/".$images;	
			$news_img ="<img src=".$news_img." />";	
		} else {
			$news_img ="";
		}
		temp_news_index($id, $title, $hometext, $news_img, $url_news_detail);
	}
	if($total > $perpage) {		
		echo "<div class=\"page\">";
			echo "<div style=\"float:right;\">";
				$pageurl = "index.php?f=$module_name&do=tags&tag=$tagurl";
				echo paging($total,$pageurl,$perpage,$page);
			echo "</div>";
		echo "</div>";
	}
}

echo "</div>";
echo "</div>";

include_once("footer.php");

?>