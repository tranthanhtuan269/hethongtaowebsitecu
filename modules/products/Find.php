<?php
if (!defined('CMS_SYSTEM')) die();

global $keywords_page, $description_page, $title_page;
 
$price = intval(isset($_GET['price']) ? $_GET['price'] : (isset($_POST['price']) ? $_POST['price']:0));

$query = "";
 
switch($price){
case 0 : $query .= "AND 1"; break;
case 1 : $query .= "AND price1 BETWEEN 0 AND 100000"; break;
case 2 : $query .= "AND price1 BETWEEN 100000 AND 1000000";break;
case 3 : $query .= "AND price1 BETWEEN 1000000 AND 2000000";break;
case 4 : $query .= "AND price1 > 2000000";break;
case -1 : $query .= "AND price1 = 0";break;
}
 

include_once("header.php");
echo "<div class=\"page-home\">";
echo "<div class=\"product-view \">";
echo '<ol class="breadcrumb" style="margin-bottom: 5px;">
      
      <li class="active"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Tìm kiếm</li>
    </ol>';

$result_prd_home = $db->sql_query_simple("SELECT id,title,donvitinh,price1,priceold,text,pnews,ptops,psale FROM ".$prefix."_products WHERE active= 1 $query order by price1 asc ");

// if
if($db->sql_numrows($result_prd_home) > 0)

		{
			echo '<div class= "row">';
			while(list($id, $title, $donvitinh, $price1, $priceold, $text , $pnews, $ptops, $psale ) = $db->sql_fetchrow_simple($result_prd_home))
			{
 
				$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
				list($images) = $db->sql_fetchrow_simple($result_prd_img);
				$path_upload_img = "$path_upload/products";
				
			   if(file_exists("$path_upload_img/$images") && $images !="") 
			   
				{
					$new_goc=$urlsite."/".$path_upload_img."/".$images;
					$news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/208x208_$images" ,208,208);
					$news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
					$new_goc = "<img src=\"$new_goc\" alt=\"$title\" title=\"$title\" class=\"img-responsive\" />";
				} else 
				
					  {
					  $news_img ="";
					  $news_img = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_img/208x208_no_image.gif" ,208,208);
					  $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
					  }
	  			if($price1 !=0 )
				{
					$gia=dsprice($price1).'đ';
				}
				else
				{
					$gia='Liên hệ';
				}
					  
				$rwtitlelast = utf8_to_ascii(url_optimization($title));
		        $url_news_detail =url_sid("index.php?f=products&do=detail&id=$id");
				
				if($text != ""){
		        	$mota = cutText($text,100);
		        }else{
		        	$mota = 'Đang cập nhật...';
		        } 
				 
		         
					echo " <div class=\"col-xs-12 col-sm-6 col-md-3 col-lg-3  \" >";
 					echo " <div class=\"km-prd-new\" >";
			        echo "<div class=\"km-image\" id=\"box_hover\"><a href=\"$url_news_detail\"  alt=\"$title\" title=\"$title\" >$news_img";
			        echo "</a></div>";
			        echo "<a  href=\"$url_news_detail\"  alt=\"$title\" title=\"$title\" ><div  class=\"km-title\"><h3>".cutText($title,70)."</h3></div></a>";
		  			//echo "<div class='km-desc'>".$mota."</div>";
		  			//echo "<a class='xemthem' href=\"$url_news_detail\">Xem tiếp..</a>";
		  			echo "<div  class='giaprd'>".$gia."<br><span>".dsprice($priceold)."đ</span></div>";
		  			echo "<div class='cart1'> <a class='muahang_home' href=".url_sid("index.php?f=products&do=cart_add&id=$id")."> Mua hàng </a> </div>";
				    echo  "</div>";
				  echo  "</div>";
			 
			    

			}
			 echo "<div class=\"cl\"></div>"; 
				// if($total > $perpage) {
				// 	echo "<div class=\"pagging\">";
				// 		echo paging($total,$pageurl,$perpage,$page);
				// 	echo "</div>";
				// }
			 echo  "</div>";	
		}else{
			echo '<p>Không có sản phẩm nào!...</p>';
		}
// end if

echo "</div>";
echo "</div>";
include_once("footer.php");
?>