<?php
if (!defined('CMS_SYSTEM')) die();
$snohf = isset($nohf);
@session_start();
$title_page ="Thanh toán giỏ hàng";
if (!$snohf) include_once("header.php");
if(isset($_SESSION['giohang']) && $_SESSION['giohang'] != "") {
	$giohangs1 = $_SESSION['giohang'];
	foreach ($giohangs1 as $key => $value) {
		@$chuoi .= $key.'-'.$value.',';
		
	}
	if(isset($_POST['gui']) && $_POST['gui'] == 1) {
		$hoten = trim($_POST['fullname']); 
		$sdt = intval($_POST['phone']);
		$email = trim($_POST['email']);
		$noidung = trim($_POST['note']);
		$timed=date('d/m/Y H:i:s');
		$query = ("INSERT INTO ".$prefix."_products_order (id, fullname, phone,mail,info,orderTime,chuoi) VALUES (NULL, '$hoten', '$sdt', '$email',  '$noidung', '$timed','$chuoi')");
		if($db->sql_query_simple($query)){
			?>
			<script type="text/javascript">
				window.alert('Gửi yêu cầu thành công. Chúng tôi sẽ sớm liên hệ với bạn');
				window.history.back();
			</script>
			<?php
		}
		 session_destroy();
	}
}
?>
<script type="text/javascript">
	function check_frm(){
		var fullname = document.getElementById('fullname').value;
		var email = document.getElementById('email').value;
		var phone = document.getElementById('phone').value;
		var kiemTraDT = isNaN(phone);
		if(fullname == ""){
			window.alert('Vui lòng nhập tên của bạn!.');
			document.getElementById('fullname').focus();
			return false;
		}
		var aCong=email.indexOf("@");
	    var dauCham = email.lastIndexOf(".");
	    if (email == "") {
	        alert("Email không được để trống");
	        return false;
	    }
	    else if ((aCong<1) || (dauCham<aCong+2) || (dauCham+2>email.length)) {
	          alert("Email của bạn không đúng định dạng");
	          return false;
	      }
		 
		if(phone == ""){
			window.alert('Vui lòng nhập số điện thoại của bạn!.');
			document.getElementById('phone').focus();
			return false;
		}
		 if (kiemTraDT == true) {
	          alert("Số điện thoại phải là dạng số!.");
	          return false;
	      }
		return true;
	}
</script>

	<section class="bg_title">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?= url_sid("index.php/") ?>" title="Trang chủ"><?= _HOMEPAGE ?> &raquo; </a></li>
                <li><a>Thanh toán giỏ hàng</a></li>
            </ol>
        </div>
    </section>

	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="home">
					<div class="thongtin">
						<div class='thongtinr'>
							<form method='post' active='' name='giohang'>
								<table class='table table-responsive' style="background:#FFF; font-size:12px;">
									<thead>
	          							<tr>
								            <th>Hình ảnh</th>
								            <th>Tên sản phẩm</th>
								            <th>Số lượng</th>
								            <th>Đơn vị tính (vnđ)</th>
								            <th>Tổng (vnđ)</th>
								            <th><a href=' '></a></th>
	          							</tr>
	        						</thead>
	        						<tbody>
	        						<?php 
	        						$totalprice = 0;
							        if(isset($_POST['ok1']) && $_POST['ok1'] == 1 ){
							        	foreach ($_POST['sl'] as $key => $value) {
							        		@$_SESSION['giohang'][$key] = $value;
							        	}
							        }
							        $soluong = @$_SESSION['giohang'] ; // san pham trong gio hang 1 
									$total = count($soluong); // so sp trong gio hang
									if($total > 0)
									{
										echo '<h3 class="h3trong" ><font>Có <b>'.$total.'</b> sản phẩm trong giỏ hàng.</font></h3>';
			 							foreach ($soluong as $key => $value) {
			 								@$mangID[] = $key;
										}
										sort($mangID);
										$manngIDN = implode(',', $mangID);
				 						$result_prd = $db->sql_query_simple("SELECT id, prdcode, title,price1,style FROM {$prefix}_products WHERE id in($manngIDN) ");
				 						while (list($id,$prdcode,$title,$price1,$style)=$db->sql_fetchrow_simple($result_prd)) 
				 						{
										 	$url_detail =url_sid("index.php?f=products&do=detail&id=$id");
										 	$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
					 						list($images) = $db->sql_fetchrow_simple($result_prd_img);
					 						$path_upload_img = "$path_upload/products";
					 						if(file_exists("$path_upload_img/$images") && $images !="")
					 						{
												$new_goc=$urlsite."/".$path_upload_img."/".$images;
											} 
											else 
											{
												$new_goc="";
											}
											$del=url_sid("index.php?f=products&do=cart_delete&id=$id");
											$totalprice += ($_SESSION['giohang'][$id]*$price1);
				 							?>
										 	<tr>
									            <td><img width='60px' src="<?= $new_goc; ?>" class='img-responsive'></td>
									            <td><span><a href='<?= $url_detail; ?>'><?=$title;?></a></span></td>
									            <td> <input style='width:40px;text-align:center;' type='number' min="1" max="10" name='sl[<?= $id?>]' value='<?= @$_SESSION['giohang'][$id] ?>' /></td>
									            <td><span><?= dsprice($price1);?></span></td>
									            <td><?= dsprice(@$_SESSION['giohang'][$id]*$price1) ?></td>
									            <td><a href='<?= $del ?>'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			          						</tr>
				 							<?php
				 						}	
										echo '<tr><td colspan="5"><input type="hidden" name="ok1" value="1" /><input type="submit" name="ok" value="Update" class="btn btn-primary"/></td>

										<td colspan="2" style="text-align: right;">Tổng tiền: <font style="color: #d40d0d;">'.dsprice($totalprice).' VNĐ</font></td>


										</tr>';
									// -----------------------------------------//	
									}
						        	else
						        	{
						        		echo '<h3 class="h3trong" >Không có sản phẩm nào trong giỏ hàng</h3>';
						        	} 
						        	?>
	        						</tbody>
		        				</table>
		        			</form>	
    					</div>
						<div class='thongtin_form' >
							<h3 class='h3trong'>Nhập thông tin thanh toán</h3>
							<div class="rowsss">
								<form method='post' active='' name='thongtindh' onsubmit="return check_frm()">
									<div class="form-group">
								        <label class="col-lg-4control-label">Họa và tên</label>
								        <div class="col-lg-8control-label">
								          <input type="text" name="fullname"  id='fullname' class="form-control" placeholder="Nhập họ và tên" style="width:90%;" value="">
								        </div>
								    </div>
								    <div class="form-group">
							            <label class="col-lg-4control-label">Email</label>
							            <div class="col-lg-8control-label">
							              <input type="text" class="form-control" id='email' name="email" placeholder="Địa chỉ Email" style="width:90%;" value="">
							            </div>
							        </div>
							        <div class="form-group">
										<label class="col-lg-4control-label">Số điện thoại</label>
										<div class="col-lg-8control-label">
											<input type="text" class="form-control" id='phone' name="phone" placeholder="Số điện thoại" style="width:90%;" value="">
										</div>
									</div>
									<div class="form-group">
							            <label class="col-lg-4control-label">Ghi chú</label>
							            <div class="col-lg-8control-label">
							              	<textarea class="form-control" name="note" rows="5" style="width: 507px; height: 177px;"></textarea>
							            </div>
							        </div>
							        <div class="form-group">
							            <label class="col-lg-4control-label">&nbsp;</label>
							            <div class="col-lg-8control-label">
							                <input type="hidden" name="gui" class="btn btn-primary" value="1">
							                <input type="submit" name="submit_step1" class="btn btn-primary" value="Gửi yêu cầu">
							            </div>
							        </div>
							    </form>
						    </div>  
						</div>
					</div>
				</div>
			</div>
<!-- 			<div class="col-sm-4 col-md-3">
				<div class="block_cskh">
					<div class="block_cskh_vanchuyen">
						<h6>Miễn phí vận chuyển</h6>
						<span>Cho hóa đơn trên 1.000.000 VNĐ</span>
					</div>
					<div class="cl"></div>
					<div class="block_cskh_chinhsach">
						<div class="cskh_chinhsach_camket">
							<span>Cam kết hàng chính hãng, uy tín chất lượng</span>
						</div>
						<div class="cskh_chinhsach_address">
							<span>Chỉ giao hàng tại Hà Nội & TP.Hồ Chí Minh</span>
						</div>
						<div class="cskh_chinhsach_doitra">
							<span>Đổi trả trong 30 ngày, thủ tục đơn giản</span>
						</div>
					</div>
					<div class="cl"></div>
					<div class="block_cskh_muahang">
						<div class="cskh_cuahang">
							<h6>Địa chỉ mua hàng</h6>
							<span>Số 352 Giải Phóng, Đống Đa, Hà Nội</span>
						</div>
					</div>
				</div>
			</div> -->
		</div>

</div>
<div class="cl"></div>
<?php
if (!$snohf) include_once("footer.php");
?>