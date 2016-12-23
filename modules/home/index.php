<?php
if (!defined('CMS_SYSTEM')) die();
global $Default_Temp, $time, $urlsite;
$imgFold = "$urlsite/templates/{$Default_Temp}/images";

if (file_exists(DATAFOLD."/config_introduce.php")) {
	include_once(DATAFOLD."/config_introduce.php");
}
if (file_exists(DATAFOLD."/config_products.php")) {
	include_once(DATAFOLD."/config_products.php");
}
include_once("header.php");
global $imgFold, $currentlang, $prefix,$path_upload,$db,$urlsite, $a3, $urlsite;

?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-9 col-lg-9 left-col-main">
            <?php  
                $result_newskh = $db->sql_query_simple("SELECT catid, title, gioithieu, guid FROM {$prefix}_news_cat WHERE active=1 AND alanguage='$currentlang' AND onhome = 1 AND parent = 99 ORDER BY weight ASC LIMIT 6");
                if($db->sql_numrows($result_newskh) > 0)
                {
                    while(list($catid, $title, $gioithieu, $guid) = $db->sql_fetchrow_simple($result_newskh))
                    {
                        ?>
                            <div class="title_home_cat"><h3><a href="<?= url_sid($guid) ?>"><?= $title ?></a></h3></div>
                            <div class="box_des_cat">
                                <?= $gioithieu ?>
                            </div>
                        <?php
                    }
                }
            ?>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 right-col-block">
            <?php blocks("right", $module_name); ?>
        </div>
    </div>
</div>
<?php
include_once("footer.php");
function show_tintuckhac($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite , $imgFold;
    $cat_sub = listparent("news",$catid, "");
    $cat_sub = trim($cat_sub,",");
    $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images, hits FROM {$prefix}_news WHERE active=1 AND alanguage='$currentlang' AND catid in ($cat_sub) /*AND special != 1 */ ORDER BY time DESC LIMIT 6");
    if($db->sql_numrows($result_newskh) > 0)
    {
        ?>
        <div class="box_tintuckhac">
            <div class="box_noidung">        
            <?php
                $j=1;
                while(list($id, $title, $hometext,$timea, $images, $hits) = $db->sql_fetchrow_simple($result_newskh))
                {
                    $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
                    ?>
                        <div class="post_tintuckhac">
                            <h5><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h5>
                        </div>
                    <?php
                    $j++;
                }
            ?>
            </div>
        </div>
        <?php
    }
}
function show_duanhome($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite , $imgFold;
    $cat_sub = listparent("news",$catid, "");
    $cat_sub = trim($cat_sub,",");
    $result_newskh = $db->sql_query_simple("SELECT catid, title, gioithieu FROM {$prefix}_news_cat WHERE active=1 AND alanguage='$currentlang' AND catid = $catid  ORDER BY weight asc LIMIT 1");
    if($db->sql_numrows($result_newskh) > 0)
    {
        while(list($catid, $title_cat, $gioithieu) = $db->sql_fetchrow_simple($result_newskh))
        {
            $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images FROM {$prefix}_news WHERE active=1 AND alanguage='$currentlang' AND catid in ($cat_sub)  ORDER BY time DESC");
            if($db->sql_numrows($result_newskh) > 0)
            {
                ?>
                <div class="box_title_dichvu">
                    <a href="#" class="prev"><img src="<?= $urlsite ?>/images/otther_prev.png" alt="icon_prev"></a>
                    <h2><?= $title_cat ?></h2>
                    <a href="#" class="next"><img src="<?= $urlsite ?>/images/otther_next.png" alt="icon_next"></a>
                </div>
                <div class="des_cat"><?= $gioithieu ?></div>
                <?php
                ?><div class="row"><div class="owl-carouseldv"><?php
                $j=1;
                while(list($id, $title, $hometext, $timea, $images) = $db->sql_fetchrow_simple($result_newskh))
                {
                    $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
                    $get_path = get_path($timea);
                    $path_upload_img = "$path_upload/news/$get_path";
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
                    $a = explode(' ', $title);
                    $b = count($a);
                    $k = '';
                    for($i = 0; $i < $b; $i++)
                    {
                        if ($b == 3) {
                            if ($i == 0) {
                                $c .= '<br/>';
                            }
                            else
                            {
                                $c = ' ';
                            }
                        }
                        elseif ($b == 4) {
                            if ($i == 1) {
                                $c .= '<br/>';
                            }
                            else
                            {
                                $c = ' ';
                            }
                        }
                        elseif ($b > 4) {
                            if ($i == 2) {
                                $c .= '<br/>';
                            }
                            else
                            {
                                $c = ' ';
                            }
                        }
                        
                        $k .= $a[$i].$c;
                    }
                    ?>
                        <div class="post_newsotther p15">
                                <div class="title_top">
                                    <span>0<?= $j ?></span>
                                    <h4><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $k ?></a></h4>
                                </div>
                                
                                <div class="box_dichvu_img">
                                    <?= $news_img ?>
                                    <div class="box_content_noithat button">
                                        <div style="width: 70%; margin: 0 auto;background: #222;padding: 5px;">
                                            <a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= _READMORE ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    $j++;
                }
                ?></div>
                <script type="text/javascript">
                (function($) {
                    $(".owl-carouseldv").each(function(){
                        var my = $(this);
                        my.owlCarousel({
                        items : 5, itemsDesktop : [1000,4],
                        itemsTablet: [600,2],
                        itemsMobile : true,
                        autoPlay: true
                    });
                    my.parent().parent().find('a[href^="#"]').click(function(ev){
                        ev.preventDefault();
                        my.trigger('owl.'+$(this).attr('class')); });
                    });
                })(jQuery);
            </script>
                </div><?php
            }
        }
    }
}

function show_doanhnhiep_special($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
    $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images, hits FROM {$prefix}_news WHERE active=1 AND catid = $catid ORDER BY time DESC LIMIT 1");
    if($db->sql_numrows($result_newskh) > 0)
    {
       $i=1;
       while(list($id, $title, $hometext, $timea, $images, $hits) = $db->sql_fetchrow_simple($result_newskh))
        {
            $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
            $get_path = get_path($timea);
            $path_upload_img = "$path_upload/news/$get_path";
            if(file_exists("$path_upload_img/$images") && $images !="")
            {
                $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/385x0_$images" ,385,0);
                // $news_img = $urlsite."/".$path_upload_img."/".$images;
                $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
            } else {
                // $news_img = $urlsite."/"."images/no_image.gif";
                $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/385x0_no_image.gif" ,385,0);
                $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
            }
            ?>
                <div class="row">
                    <div class="box_img_doanhnghiep col-sm-6 col-xs-12"><?= $news_img ?></div>
                    <div class="content_doanhnghiep col-sm-6 col-xs-12">
                        <div>
                            <h3><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h3>
                            <div class="des_doanhnghiep">
                                <?= $hometext ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            $i++;
        }
    }
}
function show_tintuc(){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
    $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images, hits FROM {$prefix}_news WHERE active=1 AND special = 1 ORDER BY time DESC LIMIT 3");
    if($db->sql_numrows($result_newskh) > 0)
    {
       $i=1;
       ?>
            <div class="box_title_dan"><h2><a>Tin tức nổi bật</a></h2></div>
            <ul class="box_tintucnoibat">
            <?php
                while(list($id, $title, $hometext,$timea, $images, $hits) = $db->sql_fetchrow_simple($result_newskh))
                {
                    $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
                    $get_path = get_path($timea);
                    $path_upload_img = "$path_upload/news/$get_path";
                    if(file_exists("$path_upload_img/$images") && $images !="")
                    {
                        $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/190x0_$images" ,190,0);
                        // $news_img = $urlsite."/".$path_upload_img."/".$images;
                        $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
                    } else {
                        // $news_img = $urlsite."/"."images/no_image.gif";
                        $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/190x0_no_image.gif" ,190,0);
                        $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                    }
                        ?>
                            <li>
                                <div class="post_tintuc">
                                    <div class="box_img_tintuc">
                                        <?= $news_img ?>
                                    </div>
                                    <h3><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h3>
                                    <p><?= CutString(strip_tags($hometext), 350) ?></p>
                                </div>
                            </li>
                        <?php
                    $i++;
                }
            ?>
            </ul>
        <?php
    }
}
function show_news($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
    $result_newskh = $db->sql_query_simple("SELECT catid, title, guid FROM {$prefix}_news_cat WHERE active=1 AND alanguage = '$currentlang' AND catid = $catid  ORDER BY weight asc LIMIT 1");
    if($db->sql_numrows($result_newskh) > 0)
    {
        while(list($catid, $title_cat, $guid) = $db->sql_fetchrow_simple($result_newskh))
        {
            ?>
                <h4 class="title_news" style="margin-bottom: 15px;"><a href="<?= url_sid($guid) ?>" title="<?= $title_cat ?>"><?= $title_cat ?></a></h4>
                <div class="row">
                <div class="owl-carouselnews">
                <?php

                    $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images, hits FROM {$prefix}_news WHERE active=1 AND catid = $catid ORDER BY time DESC LIMIT 10");
                    if($db->sql_numrows($result_newskh) > 0)
                    {
                        $i=1;
                        while(list($id, $title, $hometext,$timea, $images, $hits) = $db->sql_fetchrow_simple($result_newskh))
                        {
                            $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
                            $get_path = get_path($timea);
                            $path_upload_img = "$path_upload/news/$get_path";
                            if(file_exists("$path_upload_img/$images") && $images !="")
                            {
                                $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/290x0_$images" ,290,0);
                                // $news_img = $urlsite."/".$path_upload_img."/".$images;
                                $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
                            } else {
                                // $news_img = $urlsite."/"."images/no_image.gif";
                                $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/290x0_no_image.gif" ,290,0);
                                $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                            }
                                ?>
                                    <div class="item_news_home">
                                        <div class="img_newspecial"><?= $news_img ?></div>
                                        <div class="box_title_new">
                                            <span><?= date('d', $timea).'<br>'.date('M',$timea) ?></span>
                                            <h2><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h2>
                                            <p>Lượt xem: <?= $hits ?> Lượt xem</p>
                                        </div>
                                        <div class="des_img_newspecial"><?= CutString(strip_tags($hometext),250) ?></div>
                                    </div>
                                <?php
                            $i++;
                        }
                    }
                ?>
                </div>
                <script type="text/javascript">
                    (function($) {
                        "use strict";
                        $(".owl-carouselnews").each(function(){
                            var my = $(this);
                            my.owlCarousel({
                            items : 2, itemsDesktop : [1000,2],
                            itemsTablet: [600,2],
                            itemsMobile : false,
                            autoPlay: true
                        });
                        my.parent().find('a[href^="#"]').click(function(ev){
                            ev.preventDefault();
                            my.trigger('owl.'+$(this).attr('class')); });
                        });
                    })(jQuery);
                </script>
                </div>
            <?php
        }
    }
}
function show_news_star($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite, $imgFold;
    $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images, hits FROM {$prefix}_news WHERE active=1 AND catid = $catid AND special =1  ORDER BY time desc LIMIT 4");
    if($db->sql_numrows($result_newskh) > 0)
    {
        ?><div class="row" style="margin: 0 -15px !important;"><?php
        $i=1;
        while(list($id, $title, $hometext, $timea, $images, $hits) = $db->sql_fetchrow_simple($result_newskh))
        {
            $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
            $get_path = get_path($timea);
            $path_upload_img = "$path_upload/news/$get_path";
            if(file_exists("$path_upload_img/$images") && $images !="")
            {
                $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/370x0_$images" ,370,0);
                // $news_img = $urlsite."/".$path_upload_img."/".$images;
                $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
            } else {
                // $news_img = $urlsite."/"."images/no_image.gif";
                $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/370x0_no_image.gif" ,370,0);
                $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
            }
            ?>
                <div class="post_tintuc_special col-sm-6 col-md-6 col-lg-6">
                    <div class="box_img_special">
                       <?= $news_img ?> 
                    </div>
                    <div class="tintuc_special">
                        <h3><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h3>
                        <div class="box_bor_special">
                            <?= CutString(strip_tags($hometext), 220) ?> 
                        </div>
                    </div>
                </div>
                <?php
            $i++;
        }
        ?></div><?php
    }
}

function catprdhome()
{
global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite, $userInfo ;

    $result_prdcat = $db->sql_query_simple("SELECT catid, title, guid, gioithieu, images FROM {$prefix}_products_cat WHERE  active='1' AND onhome = 1 AND parentid = 0 AND alanguage='$currentlang' order by weight ASC limit 4");
    if($db->sql_numrows($result_prdcat) > 0)
    {
        $i = 2;
        while(list($catid,$title_cat, $guid, $gioithieu, $images_cat) = $db->sql_fetchrow_simple($result_prdcat))
        {
            $path_upload_img = "$path_upload/products";
            if(file_exists("$path_upload_img/$images_cat") && $images_cat !="")
            {
                $images_cat = $urlsite."/"."$path_upload_img/$images_cat";
                $images_cat = "<img src=\"$images_cat\" alt=\"$title_cat\" title=\"$title_cat\"  />";
            }
            else
            {
                $images_cat ="";
            }
            ?>
            <section class="bg_sp">
                <div class="title_sp l<?= $i ?>">
                    <h3 class="title_gt"><a title="<?= $title_cat ?>" href="<?= url_sid($guid) ?> "><?= $title_cat ?></a></h3>
                </div>
                <div class="bg_box_sp">
                    <div class="row">
                        <?php
                        if ($i % 2 != 0) {
                            echo "<div class=\"item-doc col-sm-3 col-xs-12\">".$images_cat."</div>";
                        }
                    	$result_prd_home = $db->sql_query_simple("SELECT id, prdcode, title, donvitinh, price1, priceold, text, pnews, ptops, psale, hits,counts, link1 FROM {$prefix}_products WHERE  active='1' AND catid = $catid AND alanguage='$currentlang' order by id DESC LIMIT 3");
                        if($db->sql_numrows($result_prd_home) > 0)
                        {
                            $j = 1;
                
                            while(list($id,$prdcode, $title, $donvitinh, $price1, $priceold, $text , $pnews, $ptops, $psale, $hits,$counts, $link1 ) = $db->sql_fetchrow_simple($result_prd_home))
                            {
                                $text = strip_tags($text, '<a><b><u><i><strong><em>');
                                $result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
                                list($images) = $db->sql_fetchrow_simple($result_prd_img);
                                $path_upload_img = "$path_upload/products";
                                if(file_exists("$path_upload_img/$images") && $images !="")
                                {
                                    $news_img = $urlsite."/"."$path_upload_img/$images";
                                    // $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/370x0_$images" ,370,0);
                                    $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
                                }
                                else
                                {
                                    $news_img ="";
                                    $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/370x0_no_image.gif" ,370,0);
                                    $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                                }
                                if($price1 != 0 )
                                {
                                    $gia = "".dsprice($price1).' đ';
                                    
                                    if($priceold !=0 && $priceold > $price1){
                                        $gia1 = "".dsprice($priceold).' đ';
                                    }
                                    else
                                    {
                                        $gia1 = "";
                                    }
                                }
                                else
                                {
                                    $gia = "Liên hệ";
                                    $gia1 = "";
                                }
                                $url_prd =url_sid("index.php?f=products&do=detail&id=$id");
                                ?>
                                <div class="item-doc col-sm-3 col-xs-12">
                                    <div class="item">
                                        <div class="item-box-img">
                                            <a href="<?= $url_prd ?>" title="<?= $title ?>"><?= $news_img ?></a>
                                        </div>
                                        <div class="item-box-info">
                                            <a href="<?= $url_prd ?>" title="<?= $title ?>"><?= $title ?></a>
                                        </div>
                                        <div class="item-box-count">
                                            <div class="count-view">
                                                <span><font style="color:#f00"><?= $gia ?></font></span>
                                            </div>
                                        </div>
                                        <div class="item-box-des">
                                            <a class="btn btn-info" href="<?= $url_prd ?>" title="<?= $title ?>"> Chi tiết</a>
                                            <a class="btn btn-info" href="<?= url_sid("index.php?f=products&do=cart_add&id=$id") ?>" title="Mua ngay"> Mua ngay</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $j++;
                            }
                        }
                        if ($i % 2 == 0) {
                            echo "<div class=\"item-doc col-sm-3 col-xs-12\">".$images_cat."</div>";
                        }
                        $i++;
                    ?>
                </div>
            </div>
        </section>
        <?php
        }
    }
}

function show_gt($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite, $imgFold ;

    $result_newsindex = $db->sql_query_simple("SELECT id, title, hometext, bodytext FROM {$prefix}_news WHERE catid='$catid' ORDER BY id DESC LIMIT 1");
    if($db->sql_numrows($result_newsindex) > 0)
    {
        $i=1;
        while(list($idnewind, $titlenewind, $hometextind,$body) = $db->sql_fetchrow_simple($result_newsindex)){
            ?>
            <div class="box-home-intro">
                <?= $body ?>
            </div>
        <?php
        $i++;
        }
    }
}





function home_gt(){
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite, $imgFold ;
	echo "<div class=\"box_cat \">";// new nhe -------------------------------------------
	 	echo "<div class=\"box_news\">";// box home
	 		echo "<div class=\"box_box\">";
	 	$result = $db->sql_query_simple("SELECT id, title FROM {$prefix}_intro_cat WHERE alanguage='$currentlang' ORDER BY id DESC LIMIT 1");
			if($db->sql_numrows($result) > 0)
			{
				while(list($idintrocat, $title_catintro) = $db->sql_fetchrow_simple($result)){
					$result_newsindex = $db->sql_query_simple("SELECT id, title, description, body, image FROM {$prefix}_intro WHERE parent='$idintrocat' ORDER BY id DESC LIMIT 1");
					if($db->sql_numrows($result_newsindex) > 0)
					{
						$i=1;
						while(list($idnewind, $titlenewind, $hometextind,$body, $images) = $db->sql_fetchrow_simple($result_newsindex)){
							?>
							<div class="box-home-intro">
								<div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <img src="<?= $urlsite."/files/image/".$images ?>" alt="<?= $titlenewind ?>">
                                    </div>
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="box_body_intro">
                                           <?= $body ?>
                                        </div>
                                    </div>
								</div>
							</div>
						<?php
						$i++;
						}
					}
				}
			}
			echo "</div>";
		echo "</div>";
	echo "</div>";// het new nhe -------------------------------------------------------------
}
function video(){
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;

	$result = $db->sql_query_simple("SELECT id, title, images, links FROM {$prefix}_video WHERE active=1  ORDER BY hits DESC LIMIT 1");
	if($db->sql_numrows($result) > 0)
	{
		echo "<div class=\"box-video\">";
		echo "<div class=\"title_colhome\"><h2>Video</h2></div>";
			echo "<div class=\"box-video-home\">";
				$i=1;
				while(list($id, $title, $images, $links) = $db->sql_fetchrow_simple($result))
				{
					$url_video = url_sid("index.php?f=video&do=detail&id=$id");
					$images = resizeImages("files/video/$images", "files/video/575x0_$images" ,575,0);

					?>
                        <div class="img-video-home">
                            <img src="<?=$images?>" title="<?= $title ?>"/>
                            <div class="button" id="playvideo">
                                
                            </div>
                        </div>

                        <div id="iframevideo" style="display: none;"><?= $links ?></div>
					<?php
				}
			echo "</div>";
		echo "</div>";
	}
}
function nhahang($catid){
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
	$result_newskh = $db->sql_query_simple("SELECT catid, title, gioithieu FROM {$prefix}_news_cat WHERE active=1 AND catid = $catid  ORDER BY weight DESC LIMIT 1");
	if($db->sql_numrows($result_newskh) > 0)
	{
		while(list($catid, $title_cat, $gioithieu) = $db->sql_fetchrow_simple($result_newskh))
		{
			$result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, bodytext, images FROM {$prefix}_news WHERE active=1 AND catid = $catid AND nstart = 1  ORDER BY time DESC LIMIT 1");
			if($db->sql_numrows($result_newskh) > 0)
			{
			    $i=1;
			    while(list($id, $title, $hometext,$timea, $bodytext, $images) = $db->sql_fetchrow_simple($result_newskh))
				{   
                    $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
                    $get_path = get_path($timea);
                    $path_upload_img = "$path_upload/news/$get_path";
                    if(file_exists("$path_upload_img/$images") && $images !="")
                    {
                        $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/570x0_$images" ,570,0);
                        // $news_img = $urlsite."/".$path_upload_img."/".$images;
                        $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
                    } else {
                        // $news_img = $urlsite."/"."images/no_image.gif";
                        $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/570x0_no_image.gif" ,570,0);
                        $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                    }
					$url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
					?>
                        <div class="item-doc col-sm-6 col-xs-12">
                            <a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $news_img ?></a>
                        </div>
                        <div class="item-doc col-sm-6 col-xs-12">
    						<div class="post">
    							<h4><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?=  $title  ?></a></h4>
    							<div class="ttmsgplace">
    								<?= ($hometext) ?>
    							</div>
                                <div class="item-box-count">
                                    <a class="chitiet" href="<?= $url_news_kh ?>" title=<?= _READMORE ?>""><?= _READMORE ?></a>
                                </div>
    		                </div>
                        </div>
					    <?php
					$i++;
				}
			}
		}
	}
}

function khachhang1(){
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
	$result_newskh = $db->sql_query_simple("SELECT catid, title, gioithieu FROM {$prefix}_news_cat WHERE active=1 AND catid = 98  ORDER BY weight DESC LIMIT 1");
	if($db->sql_numrows($result_newskh) > 0)
	{
		while(list($catid, $title_cat, $gioithieu) = $db->sql_fetchrow_simple($result_newskh))
		{
			$result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, bodytext, images FROM {$prefix}_news WHERE active=1 AND catid = $catid AND nstart = 1  ORDER BY time DESC LIMIT 1");
			if($db->sql_numrows($result_newskh) > 0)
			{
			   $i=1;
			   while(list($id, $title, $hometext, $timea, $bodytext, $images) = $db->sql_fetchrow_simple($result_newskh))
				{
                    
					$url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
					?>
						<div class="post_line2">
							<h3><?=  $title  ?></h3>
		                    <?= $bodytext ?>
		                </div>
					    <?php
					$i++;
				}
			}
		}
	}
}
function show_khachhang($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite, $imgFold;
    $result_newskh = $db->sql_query_simple("SELECT catid, title, guid FROM {$prefix}_news_cat WHERE active=1 AND alanguage = '$currentlang' AND catid = $catid  ORDER BY weight asc LIMIT 5");
    if($db->sql_numrows($result_newskh) > 0)
    {
        while(list($catid, $title_cat, $guid) = $db->sql_fetchrow_simple($result_newskh))
        {
            $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images, source FROM {$prefix}_news WHERE active=1 AND catid = $catid   ORDER BY time ");
            if($db->sql_numrows($result_newskh) > 0)
            {
                ?>
                <div class="box_title_dichvu">
                    <a href="#" class="prev"><img src="<?= $urlsite ?>/images/otther_prev.png" alt="icon_prev"></a>
                    <h2><?= $title_cat ?></h2>
                    <a href="#" class="next"><img src="<?= $urlsite ?>/images/otther_next.png" alt="icon_next"></a>
                </div>
                <?php
                ?><div class="khachhang"><div class="row"><div class="owl-carouselkh"><?php
                $i=1;
                while(list($id, $title, $hometext,$timea, $images, $source) = $db->sql_fetchrow_simple($result_newskh))
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
                        <div class="post_line2">
                            <div class="box_bor">
                                <div class="box_nd">
                                    <?= CutString(strip_tags($hometext), 350) ?> 
                                </div>
                                <p><a href="<?= $url_news_kh ?>" title="Xem chi tiết">[Xem chi tiết]</a></p>
                                <div class="box_img">
                                   <?= $news_img ?> 
                                </div>
                            </div>
                            <h3><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><?= $title ?></a></h3>
                            <p><?= $source ?></p>
                        </div>
                        <?php
                    $i++;
                }
                ?></div>
                </div><?php
                ?>
                </div>
                <script type="text/javascript">
                    (function($) {
                        "use strict";
                        $(".owl-carouselkh").each(function(){
                            var my = $(this);
                            my.owlCarousel({
                            items : 4, itemsDesktop : [1000,3],
                            itemsTablet: [600,2],
                            itemsMobile : false,
                            autoPlay: true
                        });
                        my.parent().find('a[href^="#"]').click(function(ev){
                            ev.preventDefault();
                            my.trigger('owl.'+$(this).attr('class')); });
                        });
                    })(jQuery);
                </script>
                <?php
            }
        }
    }
}
?>