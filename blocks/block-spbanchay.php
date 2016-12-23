<?php
if (!defined('CMS_SYSTEM')) exit;

global $Default_Temp,$imgFold;

$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
	for ($h = 0; $h < count($bl_arr[$i]); $h++) {
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
			$correctArr = $temp;
			break;
		}
	}
}
global $urlsite;
$result = $db->sql_query_simple("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]' AND active=1");
list($showtitle) = $db->sql_fetchrow_simple($result);


$content = "";
global $imgFold, $currentlang, $prefix, $urlsite, $db,$urlsite,$path_upload;
echo "<section class=\"bg_sp\">";
    $result_prd_home = $db->sql_query_simple("SELECT id, prdcode, title, price1, priceold, text FROM {$prefix}_products WHERE  active='1' AND ptops=1  AND alanguage='$currentlang' order by rand() LIMIT 4");	
	if($db->sql_numrows($result_prd_home) > 0)
	{
		echo "<div class=\"box_sp_otther prd_block\">";
            if($showtitle==1){
                echo  "<div class=\"div-tblock_tt\"><h3>{$correctArr[1]}</h3></div>";
            }
			while(list($id, $prdcode, $title, $price1, $priceold, $text) = $db->sql_fetchrow_simple($result_prd_home))
			{
 				$text = strip_tags($text, '<a><b><u><i><strong><em>');
				$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id order by id asc");
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

				$url_prd =url_sid("index.php?f=products&do=detail&id=$id");
				?>
					<div class="item-doc col-sm-12 col-xs-12" style="border-bottom: 1px solid #dddddd;">
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
		echo  "</div>";
	}
echo  "</section>";	
?>