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

global $path_upload, $mod_name, $id, $default_temp,$urlsite;



// $result_newsindex = $db->sql_query_simple("SELECT id, title, images FROM {$prefix}_picture WHERE catid=90 and active =1 ORDER BY id DESC LIMIT 4");
// 			if($db->sql_numrows($result_newsindex) > 0) 
// 			{
				if($showtitle==1){
				echo  "<div class=\"div-tblock \">{$correctArr[1]} </div>";
				 
				}
				echo "<div class='divblock'>";
				echo  "<div class=\"div-block_imgqc\">";
				
				// while(list($idnewind, $titlesub, $images) = $db->sql_fetchrow_simple($result_newsindex)) 
				// {
					

				// 	$images = resizeImages("".$urlsite."/files/pictures/$images", "files/pictures/260x160_$images" ,260,160);
					echo advertising(41);
					?>
				 	
					<!-- <div class='block-new '>	
						<div class='img_block_pic'><img src="<?php echo $images; ?>" /></div>
					</div> -->
				 
				<?php
				// }
				echo "</div>";
				echo "</div>";	
			// }

		
?>