<?php
if (!defined('CMS_SYSTEM')) die();

global $keywords_page, $description_page, $title_page;

@$tag = isset($_GET['tag']) ? $_GET['tag'] : $_POST['tag'];
@$catid = isset($_GET['catid']) ? $_GET['catid'] : $_POST['catid'];
if ($catid != "") {
	$query12 = " AND catid = $catid";
}
else
{
	$query12 =  "";
}
$pema_tag = url_optimization($tag);
$keywords_page = $tag;
$description_page = $tag;
$title_page = $tag;
include_once("header.php");

$perpage = 24;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$pageurl = "index.php?f=$module_name&do=tags&tag=$tag&catid=$catid";

$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_products WHERE title LIKE '%$tag%' OR permalink like '%$pema_tag%' OR  tag_seo LIKE '%$tag%' OR  text LIKE '%$tag%' $query12 ";
list($total) = $db->sql_fetchrow_simple($db->sql_query_simple($query));

?>
<section class="box_content">
	<section class="bg_title">
        <div class="container">
	        <ol class="breadcrumb">
	            <li><a href="<?= url_sid("index.php/") ?>" title="Trang chủ">Trang chủ &raquo; </a></li>
	            <li><a>Tìm kiếm &raquo;</a></li>
	            <li>Từ khóa: <?= $tag ?></li>
	        </ol>
        </div>
	</section>
	<section class="container">

		<?php
		$query = "SELECT id, title, text, price1, priceold, prdcode FROM {$prefix}_products WHERE title LIKE '%$tag%' OR permalink like '%$pema_tag%' OR  tag_seo LIKE '%$tag%' OR  text LIKE '%$tag%' $query12 ORDER BY time DESC LIMIT $offset, $perpage";			
		$result_products = $db->sql_query_simple($query);
		if($db->sql_numrows($result_products) > 0) 
		{
			echo "<div class=\"row\">";
			$i = 1;
			while(list($id, $title, $text, $price1,$priceold, $prdcode) = $db->sql_fetchrow_simple($result_products)) 
				{
					$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id order by id asc LIMIT 1");
					list($images) = $db->sql_fetchrow_simple($result_prd_img);
					$path_upload_img = "$path_upload/products";
				   	if(file_exists("$path_upload_img/$images") && $images !="")
					{
		                $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/470x0_$images" ,470,0);
						$news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
					}
					else
					{
						$news_img ="";
		                $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/470x0_no_image.gif" ,470,0);
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
					$i++;
				} 
			echo "</div>";
		}else {
			echo "<div class=\"clear\"></div>";
		echo "<div style=\"margin: 30px auto; font-weight:bold;width:400px;\">";
			echo "Không tìm thấy dữ liệu mà bạn cần tìm!</font>";
		echo "</div>";
	}

	if($total > $perpage) {	
		echo "<div style=\"float:left;width:100%;\">";
			echo "<div style=\"float:right;margin-top:5px;\">";
				echo paging($total,$pageurl,$perpage,$page);
			echo "</div>";
		echo "</div>";
	}
	?>
	</section>
</section>
<?php
include_once("footer.php");

?>