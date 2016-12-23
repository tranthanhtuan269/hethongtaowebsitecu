<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval($_GET['id']);
global $urlsite;
include_once("page_header.php");
$username = $admin_ar[0];
if (isset($_POST['submit'])) {
	$status = intval($_POST['status']);
	$why = $_POST['txt_why'];
	$timed=date('d/m/Y H:i:s');
	$db->sql_query_simple("UPDATE {$prefix}_products_order SET status=$status,date_update='$timed', username='".$username."', why='$why' WHERE id=$id");
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _SET_ORDER_STATUS);
	header("Location: modules.php?f=$adm_modname&do=orders");
	exit();
}

$result = $db->sql_query_simple("SELECT title, fullname, mail, phone, address, info, orderList, orderTime, status, why ,chuoi FROM {$prefix}_products_order WHERE id=$id");
if($db->sql_numrows($result) > 0) {
	list($title, $fullname, $mail, $phone, $address, $info, $orderList, $orderTime, $status, $why,$chuoi) = $db->sql_fetchrow_simple($result);
	//------------------------------------------------------------------// bang gio hang 
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="4" class="tbl-main">
		
		<tbody>
		<thead><h3 class="header">Thông Tin Đơn Đặt Hàng</h3></thead>
		<tr>
			<td class="row1">STT</td>
			<td class="row1">Hình Ảnh</td>
			<td class="row1">Thông Tin Sản Phẩm</td>
			<td class="row1">Đơn Giá</td>
			<td class="row1">Số Lượng</td>
			<td class="row1">Thành Tiền</td>
		</tr>
		<?php
		$chuoi=substr($chuoi, 0 ,strlen($chuoi)-1);

		$chuoim = explode(',', $chuoi);
		sort($chuoim);
		//print_r($chuoim);
		echo '<br>';
		$keys = "";
		//array_search ($value,$array)
		$tong = "";
		foreach ($chuoim as $key => $value) {
			$m = explode('-', $value);
			$result_prd = $db->sql_query_simple("SELECT id, prdcode, title,price1,style FROM {$prefix}_products WHERE id = $m[0] ");
			list($id,$prdcode,$title,$price1,$style)=$db->sql_fetchrow_simple($result_prd);
			$url_detail =url_sid("index.php?f=products&do=detail&id=$id");
			$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
			list($images) = $db->sql_fetchrow_simple($result_prd_img);
			$path_upload_img = $urlsite."/files/products/".$images;
			if($images !=""){
				$new_goc=$path_upload_img;
			} else {

			}
			  
			?>
			<tr>
			<td class="row1"><?= $key +1?></td>
			<td class="row1 hover-img">
				<li><img class='show_img' src="<?= $new_goc ?>" width='150px' class="img-responsive" ></li>
				<img src="<?= $new_goc ?>" width='30px' class="img-responsive"></td>
			<td class="row1"><a href='<?= $url_detail ?>'><?= $title ?></a></td>
			<td class="row1"><?= dsprice($price1) ?></td>
			<td class="row1"><?= $m[1] ?></td>
			<td class="row1"><?=  dsprice($price1*$m[1]) ?></td>
			</tr>
			<?php
			$tong += $price1*$m[1];
		}
			?>
			<tr>
				<td class="row1"></td>
				<td class="row1"></td>
				<td class="row1"></td>
				<td class="row1"></td>
				<td class="row1"></td>
				<td class="row1">Tổng Tiền : <b><?= dsprice($tong).' vnđ'; ?></b></td>
			</tr>
			<?php
		
		?>
		</tbody>
	</table>
	<?php
	//------------------------------------------------------------------//het bang gio hang 
	$id1 = intval($_GET['id']);
	echo "<br />\n";
	echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id1\" name=\"frm\" method=\"POST\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tbl-main\">\n";
	echo "<tr><td colspan=\"2\" class=\"header\">"._PRD_VIEW_ORDER."</td></tr>";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_NAME."</td>\n";
	
	echo "<td class=\"row2\">$fullname</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_EMAIL."</td>\n";
	echo "<td class=\"row2\">$mail</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_PHONE."</td>\n";
	echo "<td class=\"row2\">0".dsprice($phone)."</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_CUSTOMER_ADDRESS."</td>\n";
	echo "<td class=\"row2\">".$address."</td></tr>\n";
	echo "<tr> </tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_INFO."</td>\n";
	echo "<td class=\"row2\">$info</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_ORDER_TIME."</td>\n";
	echo "<td class=\"row2\">$orderTime</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">"._PRD_ORDER_STATUS."</td>\n";
	echo "<td class=\"row2\">";
	echo "<select name=\"status\">";
	$status0 = $status1 = '';
	if ($status == '0') $status0 = ' selected="selected"';
	elseif ($status == '1') $status1 = ' selected="selected"';
	echo "<option value=\"0\"$status0>"._PRD_ORDER_UNPROCESSED."</option>";
	echo "<option value=\"1\"$status1>"._PRD_ORDER_PROCESSED."</option>";
	echo "</select>";
	echo "&nbsp;&nbsp;&nbsp;Ghi ch&uacute;  x&#7917; l&yacute; <input type='text' name='txt_why' value='$why' style='width:350px; padding: 3px 2px; border:1px solid #d8d8d8'/>";
	echo "</td></tr>\n";
	echo "<tr><td colspan=\"2\" class=\"row3\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"></form></td></tr>";
	echo "</table>";
} else {
	header("Location: modules.php?f=$adm_modname&do=orders");
}

include_once("page_footer.php");
?>