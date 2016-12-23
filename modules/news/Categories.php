<?php
if (!defined('CMS_SYSTEM')) die();
global $Default_Temp, $urlsite;
//$catid = intval($_GET['id']);
$where=$idbuc="";
$catid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$n = isset($_GET['n']) ? $_GET['n'] : "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$t=trim($t);
if($catid!=0)
	$where.="catid=$catid AND ";
if($n!=0)
	$where.="catid=$n AND ";
if($t!="")
	$where.="permalink='$t' AND ";
$result = $db->sql_query_simple("SELECT catid, title, startid, parent, gioithieu, id_adv FROM ".$prefix."_news_cat WHERE $where alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) header("Location: index.php");
list($catid, $catname, $startid, $parent, $gioithieu, $bnid) = $db->sql_fetchrow_simple($result);
$catidnews = $catid;

$result = $db->sql_query_simple("SELECT catid, parent FROM {$prefix}_news_cat WHERE $where alanguage='$currentlang' ORDER BY weight, catid ASC");
if ($db->sql_numrows($result) > 0) {
	$i = 0;
	$tempArr = array();
	while ($rows = $db->sql_fetchrow_simple($result)) {
		list($tempArr[$i]['id'], $tempArr[$i]['parent']) = $rows;
		$i++;
	}
}
$newArr = array();
Common::buildTree($tempArr, $newArr);
$searchArray = Common::recursiveArrayKeyExists($catid, $newArr);
if ($searchArray === false) {
	//header("Location: index.php");
}
$kList = '';
if (is_array($searchArray[$catid])) Common::findAllKeys($searchArray[$catid], $kList);
else $kList = strval($catid);
if (substr($kList, -1) == ':') $kList = substr($kList, 0, strlen($kList) - 1);
$kList = explode(':', $kList);

$title_page = $catname;
if($page!=0)
	$title_page.= " - "._PAGE." ".$page;
//keywords
if($catname!="")
	$keywords_site =$catname.", ".utf8_to_ascii($catname);
else
	$header_page_keyword = $hometext;
//description
$description_site = $catname." ".$description_site;
if($page!=0)
	$description_site.= " - "._PAGE." ".$page;

if($parent != 0) {
    $title_cat = page_tilecat($catid, $parent, $catname);
    $title_home = " <li><a href=\"".url_sid("index.php/")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE." &raquo; </a></li>";
    // $title_home .= "<li><a href=\"".url_sid("index.php?f=".$module_name."")."\" > "._MODTITLE." </a></li>";
    $title_home .= "".$title_cat."";
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
                    <div class="bg_sp_home" style="margin-bottom: 20px;">
                        <div class="title_home_cat"><h1><span><?= $catname ?></span></h1></div>
                    </div>
                    <div class="clear"></div>
                    <?php
                    if ($parentgoc == 98888) {
                        if ($parent == 0) {
                            $result_newskh = $db->sql_query_simple("SELECT catid, title, guid FROM {$prefix}_news_cat WHERE active=1 AND alanguage = '$currentlang' AND parent = $catid  ORDER BY weight asc");
                            if($db->sql_numrows($result_newskh) > 0)
                            {
                                while(list($catid, $title_cat, $guid) = $db->sql_fetchrow_simple($result_newskh))
                                {
                                    ?>
                                    <div class="box_title">
                                        <h2><?= $title_cat ?></h2>
                                        <a href="<?= url_sid($guid) ?>" title="Xem thêm"> Xem thêm &raquo;</a>
                                    </div>
                                    <div class="row">
                                        <div class="box_duan_cat">
                                            <?php  show_duan($catid, 8); ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        else
                        {
                             ?>
                                <div class="row">
                                    <div class="box_duan_cat">
                                        <?php
                                            $perpage=6;
                                            $page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
                                            $offset = ($page-1) * $perpage;
                                            $query = "SELECT COUNT(*) FROM {$prefix}_news WHERE alanguage='$currentlang' AND (";
                                            $query .= "catid=$catid OR ";
                                            for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
                                            $query = substr($query, 0, strlen($query) - 4);
                                            $query .= ')';
                                            $result = $db->sql_query_simple($query);
                                            list($total) = $db->sql_fetchrow_simple($result);

                                            $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images FROM {$prefix}_news WHERE active=1 AND catid = $catid ORDER BY time DESC LIMIT $offset, $perpage");
                                            if($db->sql_numrows($result_newskh) > 0)
                                            {
                                               $i=1;
                                                while(list($id, $title, $hometext,$timea, $images) = $db->sql_fetchrow_simple($result_newskh))
                                                {
                                                    $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
                                                    $get_path = get_path($timea);
                                                    $path_upload_img = "$path_upload/news/$get_path";
                                                    if(file_exists("$path_upload_img/$images") && $images !="")
                                                    {
                                                        // $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/300x0_$images" ,300,0);
                                                        $news_img = $urlsite."/".$path_upload_img."/".$images;
                                                        $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
                                                    } else {
                                                        $news_img = $urlsite."/"."images/no_image.gif";
                                                        // $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/300x0_no_image.gif" ,300,0);
                                                        $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                                                    }
                                                    ?>
                                                        <div class="post_duan col-sm-12 col-md-3 col-lg-3">
                                                            <div class="box_img_duan" style="padding: 0px;">
                                                                <?= $news_img ?>
                                                            </div>
                                                            <h3 style="text-align: left;"><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h3>
                                                            <div class="des_duan" style="text-align: left;"><?= CutString(strip_tags($hometext),210) ?></div>
                                                        </div>
                                                        <?php
                                                    $i++;
                                                }
                                                if($total > $perpage) {
                                                    $pageurl = "index.php?f=$module_name&do=categories&id=$catid";
                                                    echo paging($total,$pageurl,$perpage,$page);
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                    else
                    {
                        $perpage=6;
                        $page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
                        $offset = ($page-1) * $perpage;
                        $query = "SELECT COUNT(*) FROM {$prefix}_news WHERE alanguage='$currentlang' AND (";
                        $query .= "catid=$catid OR ";
                        for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
                        $query = substr($query, 0, strlen($query) - 4);
                        $query .= ')';
                        $result = $db->sql_query_simple($query);
                        list($total) = $db->sql_fetchrow_simple($result);

                        $query = "SELECT id, title, hometext, time, images, hits, source FROM ".$prefix."_news WHERE active=1 AND alanguage='$currentlang' AND (";
                        $query .= "catid=$catid OR ";
                        for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
                        $query = substr($query, 0, strlen($query) - 4);
                        $query .= ") ORDER BY time DESC LIMIT $offset, $perpage";
                        //die($query);
                        $resultn = $db->sql_query_simple($query);
                        if($db->sql_numrows($resultn) > 0) {
                            while(list($id, $title, $hometext, $time, $images, $hits, $source) = $db->sql_fetchrow_simple($resultn))
                             {
                                $url_news_detail =url_sid("index.php?f=news&do=detail&id=$id");
                                $hometext = strip_tags($hometext, '<a><b><u><i><strong><span>');
                                $get_path = get_path($time);
                                $path_upload_img = "$path_upload/news/$get_path";
                                if(file_exists("$path_upload_img/$images") && $images !="")
                                {
                                    $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/215x0_$images" ,215,0);
                                    //$news_img = $urlsite."/".$path_upload_img."/".$images;
                                    $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
                                } else {
                                    //$news_img ="";
                                    $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/215x0_no_image.gif" ,215,0);
                                    $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                                }
                                temp_newcat_start($id, $catid, $source, $title, $hometext, $news_img,$time, $hits, $url_news_detail);
                            }
                            if($total > $perpage) {
                                $pageurl = "index.php?f=$module_name&do=categories&id=$catid";
                                echo paging($total,$pageurl,$perpage,$page);
                            }
                        }
                    }
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