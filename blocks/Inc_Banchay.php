<?php
if (!defined('CMS_SYSTEM')) exit;
global $Default_Temp;
$content = "";
global $imgFold, $currentlang, $prefix,$path_upload,$db,$urlsite, $a3; 
 
echo  "<div class=\"div-block\" style=\"float:left; width:100%; margin:10px 0; \">";
 	echo "<div class=\"div-tblock\">Sản phẩm bán chạy</div>";
 	echo "<div class='banchay'>";

 	$result_prd_home = $db->sql_query_simple("SELECT id, title,price1,text,priceold, pnews, ptops FROM {$prefix}_products  WHERE psale='1' and   active='1'  AND alanguage='$currentlang' ORDER BY id DESC LIMIT 20");
 	if($db->sql_numrows($result_prd_home) > 0  )
		{
			$i=1;
			echo '<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">';
			echo ' <div class="carousel-inner" role="listbox">';
			while(list($id, $title,  $price1,$text,$priceold, $pnews,$ptops) = $db->sql_fetchrow_simple($result_prd_home)) 
			{
				$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
				list($images) = $db->sql_fetchrow_simple($result_prd_img);
				$path_upload_img = "$path_upload/products";
		
			   if(file_exists("$path_upload_img/$images") && $images !="") 
			   
				{
					$new_goc=$urlsite."/".$path_upload_img."/".$images;
					$news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/200x245_$images" ,200,245);
					$news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
					$new_goc = "<img src=\"$new_goc\" alt=\"$title\" title=\"$title\" class=\"img-responsive\" />";
				} else 
				
					  {
					  $news_img ="";
					  $news_img = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_img/200x245_no_image.gif" ,200,245);
					  $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
					  }
	  
				 

				$rwtitlelast = utf8_to_ascii(url_optimization($title));
		        $url_news_detail =url_sid("index.php?f=products&do=detail&id=$id");

			        //echo "<a href=".$url_news_detail.">$new_goc</a>";
		        	if($i==1){
		        		$active = 'active';
		        	}else{
		        		$active ='';
		        	}
				  	?>
				  	<!-- Wrapper for slides active -->
					 
					    <div class="item <?= $active ?> box_psale ">
					     <?php echo "<a href=".$url_news_detail.">$new_goc</a>"; ?>
					      <div class="carousel-caption title-prd"><?= $title; ?></div>
					    </div>
					 
				  	<?php
				$i++;
			}
			echo  "</div>"; 
			echo ' </div>';
			echo ' </div>';
		}

 		
 		 
 	echo "</div>";
echo  "</div>";
// 
?>
