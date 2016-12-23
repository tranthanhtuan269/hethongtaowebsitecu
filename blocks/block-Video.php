<?php
if (!defined('CMS_SYSTEM')) exit;
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



$content ="";

global $path_upload, $mod_name, $id, $default_temp;


echo "<div class=\"div-block\">";
    echo "<div class=\"box_block\">";
        $result_newsindex = $db->sql_query_simple("SELECT id, title, images, links  FROM {$prefix}_video WHERE  active =1 AND alanguage='$currentlang' ORDER BY hits desc LIMIT 1");
        if($db->sql_numrows($result_newsindex) > 0)
        {
        	// if($showtitle==1){
        	// echo  "<div class=\"div-tblock\"><h3><img src=\"".$urlsite."/images/icon_video.png\" alt=\"member\">{$correctArr[1]}</h3></div>";
        	// }
            $i = 1;
            ?><div class="block-video"><?php
    		while(list($id, $title,$images,$links) = $db->sql_fetchrow_simple($result_newsindex))
    		{
                $url_detail_video = url_sid("index.php?f=video&do=detail&id=$id");
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
    			if ($i == 1) {
                    ?>
                        <a href="<?= $url_detail_video ?>" title="<?= $title ?>"><div class="bl_video"><?= $news_img ?></div></a>
                        
                    <?php
                }
                else
                {
                    ?>
                    <div class="title_video" style="border-bottom: 1px dashed rgb(204, 204, 204);"><h4><a href="<?= $url_detail_video ?>" title="<?= $title ?>"><?= $title ?></a></h4></div>
                    <div class="title_video"><h5><a href="<?= $url_detail_video ?>" title="<?= $title ?>"><?= $title ?></a></h5></div><?php
                }

            $i++;
    		}
            ?></div><?php
        }
    echo "</div>";
echo "</div>";
?>