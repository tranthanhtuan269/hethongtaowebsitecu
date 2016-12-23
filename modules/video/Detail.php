
<?php
if (!defined('CMS_SYSTEM')) die();

$where="";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$c = isset($_GET['c']) ? $_GET['c'] : "";
if($id!=0)
    $where.="n.id=$id AND ";
if($t!="")
    $where.="n.permalink='$t' AND ";


$result = $db->sql_query_simple("SELECT n.id, n.title FROM ".$prefix."_video AS n WHERE $where n.alanguage='$currentlang'");

if($db->sql_numrows($result) != 1) {
    header("Location: ".url_sid("index.php/")."");
}
list($id, $title) = $db->sql_fetchrow_simple($result);

$db->sql_query_simple("UPDATE ".$prefix."_video SET hits=hits+1 WHERE id='$id'");

$result_video = $db->sql_query_simple("SELECT id, title, links, hits FROM {$prefix}_video WHERE id = '$id'");
list($ids, $title, $links, $hits) = $db->sql_fetchrow_simple($result_video);

	$str1 = substr($links, 0, 15);
	$str2 = substr($links, 15, 3);
	$str2 = 520;
	$str3 = substr($links, 18, 10);
	$str4 = substr($links, 28, 3);
	$str4 = 350;
	$str5 = substr($links, 31, 200);
	$str = $str1.$str2.$str3.$str4.$str5;

$page_title = $title;
$description_page = $title;
$title_page = $title;
include_once("header.php");
?>
<section style="padding:0px;">
    <div class="boxhome_ banner">
        <?php banner_logo(53); ?>
    </div>
</section>
<section class="box_content">
    <section class="bg_title">
        <ol class="breadcrumb">
            <li><a href="<?= url_sid("index.php/") ?>" title="Trang chủ"><?= _HOMEPAGE ?> &raquo; </a></li>
            <li><a>Video</a></li>
        </ol>
    </section>
        <div class="boxhome_line">
            <div class="box_title">
                <h1><?= $title ?></h1>
            </div>
            <?php
            echo "<div class=\"div-home\" style=\"padding-bottom:23px;\" >";
                echo "<div class=\"play-video\">".$str."</div>";
                echo "<div class=\"play-hits\">"._VIEW.": ".$hits."</div>";
            echo "</div>";
            $perpage = 9;
            $page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
            $offset = ($page-1) * $perpage;
            $countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_video WHERE active=1 AND id != $ids "));
            $total = ($countf[0]) ? $countf[0] : 1;

            echo "<div class=\"content_otther\">";
                echo "<div class=\"row\">";
                $result = $db->sql_query_simple("SELECT id,title,images,links FROM ".$prefix."_video WHERE active=1 AND alanguage='$currentlang' AND id != $ids ORDER BY rand() DESC LIMIT $offset, $perpage");
                if($db->sql_numrows($result) > 0)
                {
                    $i=1;
                    while(list($id, $title,$images, $links_video) = $db->sql_fetchrow_simple($result))
                    {
                        $url_news_kh = url_sid("index.php?f=video&do=detail&id=$id");
                		$path_upload_img = "$path_upload/video";
                		if(file_exists("$path_upload_img/$images") && $images !="")
                        {
                            $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/300x0_$images" ,300,0);
                            // $news_img = $urlsite."/".$path_upload_img."/".$images;
                            $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
                        } else {
                            // $news_img = $urlsite."/"."images/no_image.gif";
                            $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/300x0_no_image.gif" ,300,0);
                            $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                        }
                		?>
                            <div class="post_duan col-sm-3 col-xs-6 video_widt">
                                <div class="box_img_duan" style="padding: 0;">
                                    <?= $news_img ?>
                                </div>
                                <h3><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h3>
                            </div>
                        <?php
                        $i++;
                    }
                    echo "<div class=\"cl\"></div>";
                    if($total > $perpage) {
                    	echo "<div style=\"float:right;padding-right:10px;\">";
                    	$pageurl = "index.php?f=".$module_name."";
                    	echo paging($total,$pageurl,$perpage,$page);
                    	echo "</div>";
                    }
                }
                echo "</div>";
            echo "</div>";
            ?>
        </div>
    </section>
</section>
<?php
include_once("footer.php");
?>