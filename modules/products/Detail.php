<?php
if (!defined('CMS_SYSTEM')) die();

$where="";
$where2="";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$c = isset($_GET['c']) ? $_GET['c'] : "";
$f = isset($_GET['f']) ? $_GET['f'] : "";

$catid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$do = isset($_GET['do']) ? $_GET['do'] : "";


if($id!=0)
	$where.="id=$id AND ";
if($t!="")
{
	$where.="permalink='$t' AND ";
}



global $keywords_page, $description_page, $title_page,$currentlang, $userInfo ,$urlsite,$yim_support;

$db->sql_query_simple("UPDATE ".$prefix."_products SET hits=hits+1 WHERE $where2 alanguage='$currentlang'");

// Cập nhật thông tin hoạt động của thành viên tại đây!.



$result_cat = $db->sql_query_simple("SELECT p.id, p.title, c.catid, c.title, c.parentid, p.permalink FROM ".$prefix."_products AS p,".$prefix."_products_cat AS c WHERE p.$where p.catid=c.catid AND p.alanguage='$currentlang'");

list($id, $title_prd, $catid, $cattitle, $parentid, $permalink) = $db->sql_fetchrow_simple($result_cat);

$result_prd_i = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id Limit 1");
list($images) = $db->sql_fetchrow_simple($result_prd_i);
$path_upload_img = "$path_upload/products";
$n_imgz = $path_upload_img."/".$images;

$catidnews = $catid;
$idsp=$id;

$dathang=$urlsite.'/index.php?f=products&do=cart&act=add&id='.$id.'';
$result_prd = $db->sql_query_simple("SELECT catid, prdcode, title,style, active, sex, shipping, nationalid, companyid, age, text, description, ptops, psale,pnews, tag_seo, title_seo, keyword_seo, description_seo, priceold, price, donvitinh, time , status, likes , hits, relation,  relationnews , counts,price1,link1, price2,link2, price3,link3, thongso FROM {$prefix}_products WHERE id=$id");
//if($db->sql_numrows($result_prd) != 1) header("Location: index.php?f=$module_name");

list($catid, $prdcode, $title,$style, $active, $sex, $shipping, $nationalid, $companyid, $age, $text, $mota, $ptops, $psale, $pnews, $tag_seo, $title_seo, $keyword_seo, $description_seo,$priceold, $price, $donvitinh, $time, $status, $likes, $hits, $relation, $relationnews,  $counts,$price1,$link1, $price2,$link2, $price3,$link3, $thongso) = $db->sql_fetchrow_simple($result_prd);
if($price1 != 0 )
{
    $gia = "<font>".dsprice($price1).' VNĐ</font>';
    
    if($priceold !=0 && $priceold > $price1){
        $gia1 = "".dsprice($priceold).' VNĐ';
    }
    else
    {
        $gia1 = "";
    }
}
else
{
    $gia = "<font>Liên hệ</font>";
    $gia1 = "";
}

if($keyword_seo == ""){$keywords_page = $title;}else{$keywords_page = $keyword_seo;}
if($description_seo == ""){$description_page = "";}else{$title_page = $description_seo;}
if($title_seo == ""){$title_page = $title;}else{$title_page = $title_seo;}
$tensp = $title;

if($parentid != 0) {
	$title_cat = page_tilecat($catid, $parentid, $cattitle);
	$title_home = "<li><a href=\"".url_sid("index.php/")."\" title=\""._HOMEPAGE."\">"._HOMEPAGE." &raquo; </a></li>";
	// $title_home .= "<a href=\"".url_sid("index.php?f=".$module_name."")."\" > "._MODTITLE." &raquo; </a></li>";
	$title_home .= "".$title_cat."";
	// $title_home .= "<li> &raquo; ".$title."</li>";
} else {
	$catname2 = "<li><a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" >$cattitle</a></li>";
	$title_home = "<li><a href=\"".url_sid("index.php/")."\"  title=\""._HOMEPAGE."\">"._HOMEPAGE." &raquo; </a></li>";
	// $title_home .= "<li><a href=\"".url_sid("index.php?f=".$module_name."")."\" > "._MODTITLE." &raquo; </a></li>";
	$title_home .= "".$catname2."";
	// $title_home .= "<li class=\"active\"> &raquo; ".$title."</li>";
}
include_once("header.php");
$parentgoc = showcatidgoc($catid,$parentid,"");
list($images_cat, $title_cat) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT images, title FROM adoosite_products_cat WHERE catid = $parentgoc LIMIT 1"));
$path_upload_img = "$path_upload/products";
if(file_exists("$path_upload_img/$images_cat") && $images_cat !="") 
{
    $news_img = "$path_upload_img/$images_cat";
    
} 
else 
{
    $news_img = ""; 
}
?>
<section class="box_content">
    <section class="bg_title">
        <div class="container">
            <ol class="breadcrumb">
                <?= $title_home ?>
            </ol>
        </div>
    </section>
    <section class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 left-col-main">
                <div class="boxhome_line">
                	<div class="row m10">
                        <div class="">
                            <div class="col-sm-12 col-md-6">
                                <link rel="stylesheet" href="<?php echo $urlsite ?>/templates/Adoosite/css/slick.min.css">
                                <link rel="stylesheet" href="<?php echo $urlsite ?>/templates/Adoosite/css/slick-theme.min.css">
                                <script src="<?php echo $urlsite ?>/js/slick.min.js"></script>
                                <div class="slideritem">
                                    <div class="row">
                                        <div id='id_img_chose1' class="image img-detail col-lg-12 col-md-12 p0">
                                            <?php show_img_thurm($idsp, $title); ?>
                                        </div>
                                        
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $('.slider-for').slick({
                                            slidesToShow: 1,
                                            slidesToScroll: 1,
                                            arrows: false,
                                            fade: false,

                                            //dots: true,
                                            //centerMode: true,
                                            prevArrow: '<button type="button" data-role="none" class="slick-prev slick-prev3" aria-label="Previous" tabindex="0" role="button">Previous</button>',
                                            nextArrow: '<button type="button" data-role="none" class="slick-next slick-next3" aria-label="Next" tabindex="0" role="button">Next</button>',
                                            focusOnSelect: true
                                        });
                                        $('.next3').click(function(e){
                                            e.preventDefault();
                                            $('.slick-next3').click();
                                        });
                                        $('.prev3').click(function(e){
                                            e.preventDefault();
                                            $('.slick-prev3').click();
                                        });
                                    });
                                </script>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="box_detail_left">
                                    <h1 class="product-name"><?= $title ?></h1>
                                    
                                    <div class="item-box-count">
                                        <div class="count-view" style="padding: 0;">
                                            <?= $gia ?>
                                        </div>
                                    </div>
                                   
                                    <div class="product-summary">
                                        <?= $text ?>
                                    </div>
                                    <div class="form-inline form-inline2">
                                        <form method="post" action="<?= url_sid("index.php?f=products&do=cart_add&id=$id") ?>">
                                            <div class="line_form">
                                                <input id="slnhap" type="number" class="form-control" value="1" name="soluongnhap" style="width: 80px;"/>
                                                <button class="btn btn-info" type="submit">Thêm vào giỏ</button>
                                                <a class="btn btn-info" href="<?= url_sid("index.php?f=products&do=cart_add&id=$id")  ?>">Mua hàng</a>
                                                <input id="prdid" type="hidden" name="" value="<?= $idsp ?>">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="item-box-count"><?= links_share() ?></div>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="boxhome_line">
                        <div class="row">
                            <div class="col-sm-4 col-md-2 col-lg-2 text-center ">
                                <div class="box_item_prd">
                                    <img src="<?= $urlsite ?>/images/_icon_camket.png" alt="_icon_camket">
                                    <p>Cam kết</p>
                                    <span><b>100% hàng chính hãng</b></span>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2 col-lg-2 text-center ">
                                <div class="box_item_prd">
                                    <img src="<?= $urlsite ?>/images/_icon_tang.png" alt="_icon_camket">
                                    <p><span><b>Hoàn tiền 200%</b></span></p>
                                    <p>nếu phát hiện hàng giả</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2 col-lg-2 text-center ">
                                <div class="box_item_prd">
                                    <img src="<?= $urlsite ?>/images/_icon_doitra.png" alt="_icon_camket">
                                    <p><span><b>Được đổi trả miễn phí</b></span></p>
                                    <p>trong vòng 7 ngày</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2 col-lg-2 text-center ">
                                <div class="box_item_prd">
                                    <img src="<?= $urlsite ?>/images/_icon_hoantra.png" alt="_icon_camket">
                                    <p><span><b>Tặng 4% giá trị đơn hàng</b></span></p>
                                    <p>nếu chuyển khoản trước</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2 col-lg-2 text-center ">
                                <div class="box_item_prd">
                                    <img src="<?= $urlsite ?>/images/_icon_hotro.png" alt="_icon_camket">
                                    <p><span>Được đội ngũ <b>chuyên gia</b></span></p>
                                    <p><b> tư vấn</b> tận tình, khoa học</p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2 col-lg-2 text-center ">
                                <div class="box_item_prd">
                                    <img src="<?= $urlsite ?>/images/_icon_vanchuyen.png" alt="_icon_camket">
                                    <p><span><b>Miễn phí giao hàng</b></span></p>
                                    <p>trên toàn quốc</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="row">
                    <div class="col-sm-12 col-md-9 col-lg-9 left-col-main col-md-push-3 col-lg-push-3">
                        <h5 class="product-name" style="color: #69b126;">Thông tin sản phẩm</h5>
                        <div class="clear"></div>
                        <?php echo $mota ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 right-col-block col-md-pull-9 col-lg-pull-9">
                        <?php blocks("left", $module_name); ?>
                    </div>
                </div>
            </div> 
        </div>
    </section>
    <section class="bg_sp spotther">
        <div class="container">
            <?php cungloai($idsp, $catid); ?>
        </div>
    </section>
</section>
<?php
include_once("footer.php");

function showtag($tag_seo){
	if($tag_seo != ""){
	$tags = explode(",", $tag_seo);
	echo "<div class=\"tags\">";
	for($i = 0; $i < sizeof($tags);$i ++){
		if($i == sizeof($tags)-1 ){
			echo "<li><a href=\"index.php?f=products&do=tags&key=$tags[$i]\" >".$tags[$i]."</a></li>";
		}else {
			echo "<li><a href=\"index.php?f=products&do=tags&key=$tags[$i]\">".$tags[$i]."</a></li> ";
		}	
	}
	echo "</div>";
	}
}
function cungloai($id,$catid){
global $currentlang,$db,$prefix,$path_upload, $urlsite ;
	$result_prd_home = $db->sql_query_simple("SELECT id,prdcode, title, donvitinh, price1, priceold, text, pnews, ptops, psale, hits,counts, link1 FROM {$prefix}_products WHERE  active='1' AND id != $id AND catid = $catid  AND alanguage='$currentlang' order by rand() LIMIT 5");
	if($db->sql_numrows($result_prd_home) > 0)
    {
        ?>
        <div class="title_sp">
            <h3 class="title_gt"><a title="<?= $title_cat ?>" href="<?= url_sid($guid) ?>">Sản phẩm liên quan</a></h3>
        </div>
        <div class="bg_box_sp">
            <div class="">
            <?php
        		while(list($id,$prdcode, $title, $donvitinh, $price1, $priceold, $text , $pnews, $ptops, $psale, $hits,$counts, $link1 ) = $db->sql_fetchrow_simple($result_prd_home))
        		{
         			$text = strip_tags($text, '<a><b><u><i><strong><em>');
        			$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
        			list($images) = $db->sql_fetchrow_simple($result_prd_img);
        			$path_upload_img = "$path_upload/products";
        			if(file_exists("$path_upload_img/$images") && $images !="")
        			{
                        $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/370x0_$images" ,370,0);
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
        			$url_detail =url_sid("index.php?f=products&do=detail&id=$id");
        			?>
        			    <div class="item-doc col-sm-3 col-xs-12" style="margin-bottom: 30px;">
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
        		}
                ?>
                </div>
            </div>
        <?php
	}
}
function show_document($id,$type){
global $currentlang,$db,$prefix,$path_upload, $urlsite ;
$result_prd_other = $db->sql_query_simple("SELECT id, title,price,price1, priceold, text,prdcode FROM ".$prefix."_products WHERE active='1'  AND id!='".$id."' ORDER BY $type DESC LIMIT 5");
	if($db->sql_numrows($result_prd_other) > 0) {
	    $a=1;
	    $t="";
	    while(list($idot, $titleot, $priceot,$price1,$priceold,$text,$prdcode) = $db->sql_fetchrow_simple($result_prd_other)) 
	    {
	        $url_news_detail =url_sid("index.php?f=products&do=detail&id=$idot");
	        if ($prdcode == "PDF" || $prdcode == "pdf") {
	        	$t = "<i class=\"fa fa-file-pdf-o text-danger\"></i>";
	        }
	        elseif ($prdcode == "DOC" || $prdcode == "doc" || $prdcode == "DOCX" || $prdcode == "docx") {
	        	$t = "<i class=\"fa fa-file-word-o text-primary\"></i>";
	        }
	        else
	        {
	        	$t = "<i class=\"fa fa-file-archive-o text-primary\"></i>";
	        }
	        ?>
	        	<li>
	        		<?= $t ?>
	        		<a target="_blank"  href="<?= $url_news_detail ?>"><?= $titleot ?></a>
	        	</li>
	        <?php  
	        $a++;       
	    }
 	}
} 
function show_img_chose($idsp,$title_prd){

    global $currentlang,$db,$prefix,$path_upload,$urlsite;
    $result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$idsp order by id asc");
    if ($db->sql_numrows($result_prd_img) > 0)
    {
        echo '<div class="slider-nav">';
        $k=1;
        while(list($images) = $db->sql_fetchrow_simple($result_prd_img))
        {   
            $path_upload_img = "$path_upload/products";
            $news_img = $urlsite."/".resizeImages("$path_upload_img/$images","$path_upload_img/348x0_$images" ,348,0);
            if ($k != 0) {
                ?>
                    <a class="spcts" title="<?= $title_prd ?>" style="cursor: pointer;">
                        <img src="<?= $urlsite."/".$path_upload_img."/".$images ?>" class="img-responsive mauto" alt="<?= $title_prd ?>"/>
                    </a>
                <?php
            }
            $k++;   
        }
        echo "</div>";
    }
}      

function show_img_thurm($idsp,$title_prd)
{
    global $currentlang,$db,$prefix,$path_upload,$urlsite;
    $result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$idsp order by id asc");
    if ($db->sql_numrows($result_prd_img) > 0)
    {
        echo '<div class="slider-for">';
        $k=1;
        while(list($images) = $db->sql_fetchrow_simple($result_prd_img))
        {   
            $path_upload_img = "$path_upload/products";
            $news_img = $urlsite."/".resizeImages("$path_upload_img/$images","$path_upload_img/348x0_$images" ,348,0);
            ?>
                <a class="spcts" title="<?= $title_prd ?>">
                    <img src="<?= $urlsite."/".$path_upload_img."/".$images ?>" class="img-responsive mauto" alt="<?= $title_prd ?>"/>
                </a>
            <?php
            $k++;   
        }
        echo "</div>";
    }
}
?>	



