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

	$result_newsindex = $db->sql_query_simple("SELECT  id, title, images  FROM {$prefix}_picture WHERE  active =1 ORDER BY id asc");
			if($db->sql_numrows($result_newsindex) > 0) 
			{
				if($showtitle==1){
				echo  "<div class=\"div-tblock-sphot\">{$correctArr[1]} </div>";
				}
				?>
				<div id="main" role="main">
				<section class="slider">
					<div class="flexslider">
						<ul class="slides">
						<?php
						while(list($id, $title,$images) = $db->sql_fetchrow_simple($result_newsindex)) 
						{
							$path_upload_img = "$path_upload/pictures";
							if(file_exists("$path_upload_img/$images") && $images !="") 
							{
								$news_img1 = $urlsite."/"."$path_upload_img/$images";
								$news_img1 = "<img src=\"$news_img1\" alt=\"$titlesub\" title=\"$titlesub\" />";
								 
							} else {
								$news_img1 = $urlsite."/"."images/no_image.gif";
								$news_img1 = "<img src=\"$news_img1\" alt=\"$titlesub\" title=\"$titlesub\" />";
							}
							?>
							<li>
								<?= $news_img1 ?>
							</li>
						<?php
						}
						?>
						</ul>
					</div>

				</section>
				</div>
				<?php
			}

		
?>

