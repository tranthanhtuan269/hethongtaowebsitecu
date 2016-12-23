<?php
if (!defined('CMS_SYSTEM')) die();
global $urlsite;
$where="";
	$title_page='';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$c = isset($_GET['c']) ? $_GET['c'] : "";
if($id!=0)
	$where.="n.id=$id AND ";
if($t!="")
	$where.="n.permalink='$t' AND ";
if($c!="")
	$where.="c.permalink='$c' AND ";

$result = $db->sql_query_simple("SELECT n.id, n.catid, c.title, c.parent, n.title, n.othershow, n.time, n.hometext, n.bodytext, n.title_seo, n.description_seo, n.keyword_seo, n.tag_seo, n.fattach, n.news_type, n.images, n.imgtext, n.source, n.imgshow, n.hits FROM ".$prefix."_news AS n,".$prefix."_news_cat AS c WHERE $where n.catid=c.catid AND n.alanguage='$currentlang'");

if($db->sql_numrows($result) != 1) {
	header("Location: ".url_sid("index.php")."");
}

list($id, $catid, $catname, $parent, $title, $othershow, $time, $hometext, $bodytext, $title_seo, $description_seo, $keyword_seo, $tag_seo, $fattach, $news_type, $images, $imgtext, $source, $imgshow,$hits ) = $db->sql_fetchrow_simple($result);
$catidnews = $catid ;

$get_path = get_path($time);
$path_upload_img = "$path_upload/news/$get_path";

$imgz = $urlsite."/".$path_upload_img."/".$images;


$db->sql_query_simple("UPDATE ".$prefix."_news SET hits=hits+1 WHERE id='$id'");
//title
if($title_seo!="")
	$title_page = "$title_seo ";
else
	$title_page .= "$title";
//keywords

if($tag_seo!="")
	$tag_seo_key =", ".$tag_seo;
else
	$tag_seo_key = $tag_seo;

if($keyword_seo!="")
	$keywords_site =$keyword_seo.$tag_seo_key;
else
	$header_page_keyword = $hometext.$tag_seo_key;
//description
if($description_seo!="")
	$description_site =$description_seo;
else
	if($hometext!=""){ $description_site =$hometext;}

//link title

if($parent != 0) {
    $title_cat = page_tilecat($catid, $parent, $catname);
    $title_home = " <li><a href=\"".url_sid("index.php/")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE." &raquo; </a></li>";
    // $title_home .= "<li><a href=\"".url_sid("index.php?f=".$module_name."")."\" > "._MODTITLE." </a></li>";
    $title_home .= "<li>".$title_cat."</li>";
} else {
    $catname2 = "<li><a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$catname</a></li>";
    $title_home = "<li><a href=\"".url_sid("index.php/")."\" title=\""._HOMEPAGE."\">"._HOMEPAGE." &raquo; </a></li> ";
    // $title_home .= "<li><a href=\"".url_sid("index.php?f=".$module_name."")."\" > "._MODTITLE." </a></li>";
    $title_home .= "".$catname2."";
}

include_once("header.php");
$parentgoc = showcatidgoc($catid,$parent,"");
?>
<section class="box_content" style="margin-bottom: 30px;">
    <section class="bg_title">
        <div class="container">
            <ol class="breadcrumb">
                <?= $title_home ?>
            </ol>
        </div>
    </section>
    <section class="container">
        <div class="row">
            <div class="col-sm-12 col-md-9 col-lg-9 left-col-main">
                <div class="boxhome_line">
                <?php
                    $hometext = strip_tags($hometext, '<a><b><u><i><strong><em>');
                    $path_upload_attach = "$path_upload/news/attachs";
                    $siteurl = "http://".$_SERVER['HTTP_HOST']."";
                    if($folder_site)  $url = !empty($fattach) ? "$siteurl/$folder_site/$path_upload_attach/$fattach" : '';
                    else $url = !empty($fattach) ? "$siteurl/$path_upload_attach/$fattach" : '';

                    if(file_exists("$path_upload_img/$images") && $images !="" && $imgshow == 1) {
                    	$news_img = "<img alt=\"$title\" src=\"$urlsite/".resizeImages("$path_upload_img/$images", "$path_upload_img/80x60_$images" ,80,60)."\">";
                    } else {
                    	$news_img ="";
                    }
                    $new_others = "";
                    $result_others = $db->sql_query_simple("SELECT id, title, time, hits FROM ".$prefix."_news WHERE id != '$id' AND catid='$catid' ORDER BY time DESC LIMIT $news_ccd");
                    if($db->sql_numrows($result_others) > 0) {

                    	while(list($idot, $titleot, $timeot, $hitsot) = $db->sql_fetchrow_simple($result_others)) {
                    		$new_others .= "<a href=\"".url_sid("index.php?f=$module_name&do=detail&id=$idot")."\" class=\"hometext\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>  $titleot <span>( ".$hitsot." "._VIEW." )</span></a>";
                    	}
                    }
                    $new_others2 = "";
                    $result_others2 = $db->sql_query_simple("SELECT id, title, time, images, hometext FROM ".$prefix."_news WHERE id!='$id' AND catid = '$catid' ORDER BY time ASC LIMIT $news_ccd");
                    if($db->sql_numrows($result_others2) > 0) {
                    	while(list($idot2, $titleot2, $timeot2, $imagesot2, $hometextot) = $db->sql_fetchrow_simple($result_others2)) {
                            $ul = url_sid("index.php?f=$module_name&do=detail&id=$idot2");
                            $get_path = get_path($timeot2);
                            $path_upload_img = "$path_upload/news/$get_path";
                            if(file_exists("$path_upload_img/$imagesot2") && $imagesot2 !="")
                            {
                                // $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/300x0_$images" ,300,0);
                                $news_img = $urlsite."/".$path_upload_img."/".$imagesot2;
                                $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
                            } else {
                                $news_img = $urlsite."/"."images/no_image.gif";
                                // $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/300x0_no_image.gif" ,300,0);
                                $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                            }
                            $new_others2 .= '<div class="post_duan col-sm-12 col-xs-12">
                                                <div class="box_img_duan" style="padding: 0px;">
                                                    '.$news_img.'
                                                </div>
                                                <h3 style="text-align: left;"><a href="'.$ul.' ?>" title="'.$title.'">'.$title.'</a></h3>
                                                <div class="des_duan" style="text-align: left;">'.CutString(strip_tags($hometextot),210).'</div>
                                            </div>';
                    	}
                    }

                    $news_tid ="";
                    if(!empty($tid)) {
                    	$result_tid = $db->sql_query_simple("SELECT id, title FROM ".$prefix."_news WHERE tid='$tid' AND id!='$id' ORDER BY time DESC LIMIT $nums_tid");
                    	if($db->sql_numrows($result_tid) > 0) {
                    		$news_tid .= "<div style=\"border: 1px solid #CCCCCC; padding: 5px; background-color: #F0F0F0\">";
                    		$news_tid .= "<font color=\"red\"><b>"._NEWSTID.":</b></font><br/>";
                    		while(list($idtid, $titletid) = $db->sql_fetchrow_simple($result_tid)) {
                    			$rwtitletid=utf8_to_ascii(url_optimization($titletid));
                    			$news_tid .= "&raquo; <a href=\"../".url_sid($module_name.".php?do=detail&id=$idtid&t=$rwtitletid")."\" class=\"tittahom\">$titletid</a></br>";
                    		}
                    		if($db->sql_numrows($db->sql_query_simple("SELECT*FROM ".$prefix."_news WHERE tid='$tid' AND id!='$id'")) > $nums_tid) {
                    			$news_tid .= "<div align=\"right\"><a href=\"../".url_sid("".$module_name.".php?do=topics&topic_id=$tid")."\"><b>["._VIEWALLNEWS."]</b></a></div>";
                    		}
                    		$news_tid .= "</div>";
                    	}
                    }
                    $tags="";
                    if(!empty($tag_seo))
                    {
                    	$tag_seo_arr = @explode(",", $tag_seo);

                    	for ($i = 0; $i < sizeof($tag_seo_arr); $i++)
                    	{
                    		$tagurl=trim($tag_seo_arr[$i]);
                    		$tagurl=str_ireplace(" ","-",$tagurl);
                    		$tags .= "<a href=\"".url_sid("index.php?f=news&do=tags&tag=$tagurl")."\">".$tag_seo_arr[$i]."</a>,\n";
                    	}
                    }

                    $comment_content = "";
                    $comment="";

                    	$pageURL = 'http';
                    	    if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}}
                    	    $pageURL .= "://";
                    	    if ($_SERVER["SERVER_PORT"] != "80") {
                    	        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
                    	    } else {
                    	        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                    	    }
                    temp_newdetail($id, $title, $time, $hometext, $bodytext, $fattach, $othershow, $news_img, $imgtext, $new_others, $new_others2, $source, $news_tid, $title_seo, $description_seo, $keyword_seo, $tags, $hits, $comment, $comment_content,$pageURL, $parentgoc);
                    ?>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 right-col-block">
                <?php blocks("right", $module_name); ?>
            </div>
        </div>
    </section>
</section>
<?php
include_once("footer.php");
?>