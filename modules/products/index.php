<?php
if (!defined('CMS_SYSTEM')) die();
global $Default_Temp, $urlsite,$prefix;
 
include_once("header.php");
echo "<div class=\"page-home\" >";
echo "<div class='box_new'>";
echo "<div class=\"title_newss\"><i class=\"fa fa-newspaper-o\"></i>Danh Mục Sản phẩm</div>";
$result_cat = $db->sql_query_simple("SELECT catid, title,sub_id FROM {$prefix}_products_cat WHERE  active='1'  AND onhome='1' AND alanguage='$currentlang' AND parentid = 0 LIMIT 8");
if($db->sql_numrows($result_cat) > 0){
		$lv = 1;
	while(list($catidc, $titlecat,$sub_id ) = $db->sql_fetchrow_simple($result_cat)){
		$lv++;
		$sub_id_1 = $sub_id;
		$sub_id = explode("|", $sub_id);
		echo "<div class=\"box_cat_home \">";// titlehomehot -------------------------------------------
		
		{
			
			// echo "<div class=\"box_cat_title \"><h3><a href =".$url_cat." ><span class=\"glyphicon glyphicon-th-list\"></span> $titlecat</a><a class=\"title_link_no\" rel=\"nofollow\" title='Xem tất cả' href=".$url_cat."><span class=\"glyphicon glyphicon-eye-open\"></span> Xem tất cả</a></h3></div>";// titlehomehot -----------------------------
			// tab sp
			 // bat dau tab dau
			?><ul class="nav nav-tabs custontab"><?php
			?> <li class="active"><a href="#home<?=$lv?>" data-toggle="tab"><i class="fa fa-paper-plane"></i><?=$titlecat ?></a></li><?php

			$result_cat_1 = $db->sql_query_simple("SELECT catid, title FROM {$prefix}_products_cat WHERE  active='1'  AND onhome='1' AND alanguage='$currentlang' AND parentid = $catidc LIMIT 8");
			if($db->sql_numrows($result_cat_1) > 0){
				$i=1;
				while (list($catidc1,$titlecat1) = $db->sql_fetchrow_simple($result_cat_1)) {
				?><li><a href="#info<?=$i.''.$lv?>" data-toggle="tab"><?=$titlecat1 ?></a></li><?php
				$i++;
				}
			}
			?></ul><?php
			// end tab dau

			?><div class="tab-content"><?php
			$listID = $catidc."|".$sub_id_1;
			$listID = explode("|", $listID);
			$kk = 0;
			foreach ($listID as $k => $val) {
				if($kk == 0){$active = "active"; $idhome = "home$lv";}else{$active = ""; $idhome ="info$kk"."$lv";}
				 echo "<div class=\"tab-pane $active\" id=\"$idhome\">";
				 // bat dau vong lap
				 if ($kk==0) {
				 	$result_prd_home = $db->sql_query_simple("SELECT id, title, donvitinh, price1, priceold, text, pnews, ptops, psale FROM {$prefix}_products WHERE  active='1'  AND alanguage='$currentlang' order by id desc");
				 }if($kk>0){
				  $result_prd_home = $db->sql_query_simple("SELECT id, title, donvitinh, price1, priceold, text, pnews, ptops, psale FROM {$prefix}_products WHERE  active='1' AND catid = $val AND alanguage='$currentlang'");
				  }
				  if($db->sql_numrows($result_prd_home) > 0){
				  	//-===========================================================================
				  	echo "<div class=\"ox_dm_prd\">";// box home
					// echo '<div class= "row">';
					while(list($id, $title, $donvitinh, $price1, $priceold, $text , $pnews, $ptops, $psale ) = $db->sql_fetchrow_simple($result_prd_home))
					{
		 
						$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
						list($images) = $db->sql_fetchrow_simple($result_prd_img);
						$path_upload_img = "$path_upload/products";
						
					   if(file_exists("$path_upload_img/$images") && $images !="") 
					   
						{
							$new_goc=$urlsite."/".$path_upload_img."/".$images;
							$news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/245x230_$images" ,245,230);
							$news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
							$new_goc = "<img src=\"$new_goc\" alt=\"$title\" title=\"$title\"   />";
						} else 
						
						  {
						  $news_img ="";
						  $news_img = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_img/245x230_no_image.gif" ,245,230);
						  $new_goc = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
						  $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
						  }

			  			if($price1 !=0 )
						{
							$gia=dsprice($price1).'VNĐ';
							$gia1= dsprice($priceold)."VNĐ";
						}
						else
						{
							$gia='Liên hệ';
						}
						$dem = $priceold - $price1;
						if($dem > 0)  {
							$sale = ($dem % $priceold)/1000;
							$show_sale = "<div class='sale'><span class='km'>-$sale%</span></div>";
						}else{
							$sale = "";
							$show_sale = "";
						}
						$rwtitlelast = utf8_to_ascii(url_optimization($title));
				        $url_news_detail =url_sid("index.php?f=products&do=detail&id=$id");
						
						if($text != ""){
				        	$mota = cutText($text,100);
				        }else{
				        	$mota = 'Đang cập nhật...';
				        } 
						 
				         ?>
				         	<div id="prd_home" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<div class="prd_home">
									<div class="img_prd_home">
										<a href="<?= $url_news_detail?>"><?= $news_img ?></a>
									
									</div>
									<div class="title_prd_home"><h3><a href=" <?= $url_news_detail?>" title="<?= $title?> "><?= CutString($title,50)?></a></h3></div>
									<div class="gia_pro"><font><?= $gia ?></font><strike><?php if($price1 !=0 ){ echo $gia1; } ?></strike></div>
									<div class="gia_prd_home"><?= CutString($mota,180);  ?></div>
									<div class="div_cart">
										<form action = "<?= url_sid('index.php?f=products&do=cart_add&id='.$id.'')?>" method='post' name='frm_dathang' class='frm_dathang' >
											<input type='hidden' name='ip_dathang' value = '1' />
											<button class='muahang' type='submit' /></button>
										</form>
										
										<a class="view_prd" href="" title="<?= $url_news_detail ?>">view</a>
									</div>
								</div>
								
							</div>


				         <?php
							// echo " <div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4 km-prd-new1 \" >";
		 				// 	echo " <div class=\"km-prd-new\" >";
		 				// 	echo $show_sale ;
					  //       echo "<div class=\"km-image\" id=\"box_hover\"><a href=\"$url_news_detail\"  alt=\"$title\" title=\"$title\" >$news_img";
					  //       echo "</a></div>";
					  //       echo "<a  href=\"$url_news_detail\"  alt=\"$title\" title=\"$title\" ><div  class=\"km-title\"><h3>".cutText($title,70)."</h3></div></a>";
				  	// 		//echo "<div class='km-desc'>".$mota."</div>";
				  	// 		//echo "<a class='xemthem' href=\"$url_news_detail\">Xem tiếp..</a>";
				  	// 		//echo "<div  class='giaprd'>".$gia."<br></div>";//<span>".dsprice($priceold)."đ</span>
				  	// 		echo "<span class='giaprd1'>".$gia."</span>";
				  	// 		// echo "<div class='cart1'><div class='bbgr'> <span class=\"glyphicon glyphicon-shopping-cart\" aria-hidden=\"true\"></span><a class='muahang_home' href=".url_sid("index.php?f=products&do=cart_add&id=$id").">  Giỏ hàng </a> </div></div>";
						 //    echo  "</div>";
						 //  echo  "</div>";
					 
					    

					}
					 
					 echo  "</div>";
					 $url_cat =url_sid("index.php?f=products&do=categories&id=$val");
					 
					// echo "</div>";//het box home ----------------------------
				  	//-===========================================================================
				  }else{
				  	echo "<p style='  padding: 10px 0;'>Thông tin đang được cập nhật...</p>";
				  }
				 // ket thuc vong lap
				 echo "</div>";
				$kk++;
			}
			// die();
				?></div><?php

			// het tab sp
			
		}


		echo "</div>";
	} 
	
}
































// echo "<div class='title_home'>Sản phẩm</div>"; 
// code tim kie
// 	$result_cat = $db->sql_query_simple("SELECT catid, title FROM {$prefix}_products_cat WHERE  active='1'   AND alanguage='$currentlang' LIMIT 8");
// if($db->sql_numrows($result_cat) > 0){
// 	while(list($catidc, $titlecat ) = $db->sql_fetchrow_simple($result_cat)){
		 
// 		 $result_prd_home = $db->sql_query_simple("SELECT id, title, donvitinh, price1, priceold, text, pnews, ptops, psale FROM {$prefix}_products WHERE  active='1' AND catid=$catidc AND alanguage='$currentlang' LIMIT 4");
		
// 		 if($db->sql_numrows($result_prd_home) > 0){
// 			 $url_cat =url_sid("index.php?f=products&do=categories&id=$catidc");
// 			echo "<div class=\"title_prd_home1\"> <h3><a href=\"$url_cat\">  $titlecat </a></h3> </div>";
// 			echo "<div class=\"box_cat_home\">";// box home
			 
// 			while(list($id, $title, $donvitinh, $price1, $priceold, $text , $pnews, $ptops, $psale ) = $db->sql_fetchrow_simple($result_prd_home))
// 			{
 
// 				$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
// 				list($images) = $db->sql_fetchrow_simple($result_prd_img);
// 				$path_upload_img = "$path_upload/products";
				
// 			   if(file_exists("$path_upload_img/$images") && $images !="") 
			   
// 				{
// 					$new_goc=$urlsite."/".$path_upload_img."/".$images;
// 					$news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/189x171_$images" ,189,171);
// 					$news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
// 					$new_goc = "<img src=\"$new_goc\" alt=\"$title\" title=\"$title\"   />";
// 				} else 
				
// 				  {
// 				  $news_img ="";
// 				  $news_img = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_img/189x171_no_image.gif" ,189,171);
// 				  $new_goc = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
// 				  $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
// 				  }
// 	  			if($price1 !=0 )
// 				{
// 					$gia=dsprice($price1).'vnđ';
// 				}
// 				else
// 				{
// 					$gia='Liên hệ';
// 				}
// 				$url_prd =url_sid("index.php?f=products&do=detail&id=$id");
				?>
				<!-- line1 -->
				<!-- <div id="prd_home" class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
				<div class="prd_home">
					<div class="img_prd_home"><a href=" <?= $url_prd?>"><?= $new_goc ?></a></div>	
					<div class="title_prd_home"><h3><a href=" <?= $url_prd?>" title="<?= $title?> "><?= $title?></a></h3></div>
					<div class="gia_prd_home"> <font><?=  $gia?></font></div>
				</div>
				</div> -->
				<!-- het line 1 -->
				<?php

	// 		}
	// 		echo "</div>";

	// 	}

		 
	// }
// }					  



echo "</div></div>";
include_once("footer.php");
 
?>