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

global $path_upload, $mod_name, $id, $default_temp, $db, $prefix, $currentlang, $path_upload;

if ($currentlang == 'vietnamese') 
{
    $catid = 8;
}
else
{
    $catid = 3;
}

echo "<div class=\"div-block\">";
	echo "<div class=\"box_block\">";
		$result_newsindex = $db->sql_query_simple("SELECT id, title, hometext, time,images  FROM {$prefix}_news WHERE  active =1 AND special = 1 AND catid = $catid ORDER BY time DESC LIMIT 5");
		if($db->sql_numrows($result_newsindex) > 0) 
		{
			if($showtitle==1){
			echo  "<div class=\"div-tblock_tt\"><h3>{$correctArr[1]}</h3></div>";
			}
			echo "<div class='divblock'>";
				while(list($idnewind, $titlesub,$hometextind,$time, $imagesind) = $db->sql_fetchrow_simple($result_newsindex)) 
				{
					$url_news_detail =url_sid("index.php?f=news&do=detail&id=$idnewind");
					$hometextind = strip_tags($hometextind, '<a><b><u><i><strong><span>');
					$get_path = get_path($time);
					$path_upload_img = "$path_upload/news/$get_path";
					if(file_exists("$path_upload_img/$imagesind") && $imagesind !="") 
					{

						$news_img1 = $urlsite."/".resizeImages("$path_upload_img/$imagesind","$path_upload_img/90x0_$imagesind" ,90,0);
						$news_img1 = "<img src=\"$news_img1\" alt=\"$titlesub\" title=\"$titlesub\" />";
						 
					} else {
						$news_img1 = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_img/90x0_no_image.gif" ,90,0);
						$news_img1 = "<img src=\"$news_img1\" alt=\"$titlesub\" title=\"$titlesub\" />";
					}
					
					?>
				 
					<div class='block-new '>
						<div class="block_l">
							<?= $news_img1 ?>
						</div>
						<div class="block_r">
							<div class='title_block'><a href="<?= $url_news_detail ?>"><?php echo $titlesub; ?></a></div>
							<div class="des_blr">
								<?= CutString(strip_tags($hometextind),200) ?>
							</div>
						</div>
					</div>
				 
				<?php
				}
			echo "</div>";	
		}
	echo "</div>";
echo "</div>";		
?>