<?php
if (!defined('CMS_SYSTEM')) die();
include_once("header.php");
 






echo "<div class='box_new'>";
	echo '<ol class="breadcrumb" style="margin: 0px;"><li class="active"><a title="" href="'.url_sid("index.php/").'">Trang Chủ</a> / Hệ Thống Đại Lý</li></ol>';

	echo "<div class='box_catnew'>";

$op = intval(isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op']:0));
$opp = intval(isset($_GET['opp']) ? $_GET['opp'] : (isset($_POST['opp']) ? $_POST['opp']:0));
	$pageURL = 'http';
    if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    //echo $num;
    $query2  ="";
    $query1 ="";
    $op = explode('=',$pageURL,2);
    $num = count($op);
    if($num ==1){
	   $query = "";
	}
	if($num ==2){
		 
		 $query = "and tinh like '%$op[1]%'";

		 	$oppp = explode('?',$op[1]);
		    $numss = count($op);
		    if($numss ==1){
			   $query2 = "";
			}
			if($numss ==2){
				 $query1="";
				 $query2 = "and tinh like '%$oppp[0]%'";
				 $op[0]=$oppp[0];
				 $query="";
				 	$opp = explode('=',$op[1],3);
				    $nums = count($opp);
				    if($nums ==1){
					   $query1 = "";
					}
					if($nums ==2){
						 
						 $query1 = "and huyen like '%$opp[1]%'";
						 $opp = $opp[1];
					}
			}
	}
	

	


	
	 //echo $opp[0];
	 //echo $oppp[0];
		?>
		<div class="tieude_daily">
			<div class="hotrodly">Bạn Cần hỗ trợ điểm bán hàng gần nhất vui lòng liên hệ theo số: 098653214</div>
			<div class="prd_search" >
				Tỉnh/TP <select name="prd_search" id="prd_search" onchange="window.location='<?php echo "http://localhost/fobic/dai-ly.html"; ?>?op='+this.value">
					<option value="0">Chọn Tỉnh</option>
					<?php
					$result_cat1 = $db->sql_query_simple("SELECT tid, name FROM ".$prefix."_tinh ORDER BY tid");
						
						while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat1)) {
							?>
							<option value="<?= $cat_id ?>" <?php if($op[0] ==$cat_id){echo "selected='selected'";} ?> ><?= $titlecat ?></option>
							<?php	
						}
					?>
				</select>
			</div>
			<div class="prd_search_huyen" >
				Quận/Huyện <select name="prd_search" id="prd_search" onchange="window.location='<?php echo $pageURL; ?>?opp='+this.value">
					<option value="0">Chọn Huyện</option>
					<?php
					$result_cat1 = $db->sql_query_simple("SELECT hid, name FROM ".$prefix."_huyen WHERE tid like '%$op[0]%' ORDER BY hid");
						while(list($cat_idd, $titlecatd) = $db->sql_fetchrow_simple($result_cat1)) {
							?>
							<option value="<?= $cat_idd ?>" <?php if($opp[0] ==$cat_idd){echo "selected='selected'";} ?> ><?= $titlecatd ?></option>
							<?php	
						}
					?>
				</select>
			</div>
		</div>
		<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
			<!-- <caption>table title and/or explanatory text</caption> -->
			<thead>
				<tr>
					<th class="daily_img"></th>
					<th class="daily_name"><b>Tên Đại lý</b></th>
					<th class="daily_dc"><b>Địa Chỉ</b></th>
					<th class="daily_sdt"><b>Số điện thoại</b></th>
				</tr>
			</thead>
			<tbody>
				
		
		<?php

		$result_prd_home = $db->sql_query_simple("SELECT id, tennhacc, diachi, huyen,tinh, images, sdt FROM {$prefix}_nhacungcap WHERE  active='1' AND alanguage='$currentlang' $query $query2 $query1");
		if($db->sql_numrows($result_prd_home) > 0)

		{
			while(list($id, $name, $diachii,$huyen,$tinh, $images, $sdt) = $db->sql_fetchrow_simple($result_prd_home))
			{
				$re = $db->sql_query_simple("select name from {$prefix}_tinh WHERE tid=$tinh");
				list($namet) = $db->sql_fetchrow_simple($re);

				$res = $db->sql_query_simple("select name from {$prefix}_huyen WHERE hid=$huyen");
				list($nameth) = $db->sql_fetchrow_simple($res);
				$path_upload_img = "$path_upload/daily";
				
			   if(file_exists("$path_upload_img/$images") && $images !="") 
			   
				{
					$new_goc=$urlsite."/".$path_upload_img."/".$images;
					$news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/100x70_$images" ,100,70);
					$news_img = "<img src=\"$news_img\" alt=\"$name\" title=\"$name\"/>";
					$new_goc = "<img src=\"$new_goc\" alt=\"$name\" title=\"$name\" class=\"img-responsive\" />";
				} 
				else 
				{
					
					$news_img = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_img/100x70_no_image.gif" ,100,70);
					$news_img = "<img title=\"$name\" alt=\"$name\" src=\"$news_img\"/>";
				}
				?>
					<tr class="detail"><td class="daily_img"><?= $news_img?></td>
					<td class="daily_name"><?= $name?></td>
					<td class="daily_dc"><?= $diachii.', '.$nameth.', '.$namet?></td>
					<td class="daily_sdt"><?= $sdt?></td></tr>
				<?php

			}


		}
		?>
				</tbody>
			</table>
		<?php



	echo "</div>";
echo "</div>";






















include_once("footer.php");
?>