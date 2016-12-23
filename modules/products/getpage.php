<?php 
$conn=mysql_connect("localhost","root","")
or die("Can not connect database");
mysql_select_db("metro",$conn);

if (isset($_GET['number']) && (int)$_GET['number']) {
	$number = isset($_GET['number']) ? (int)$_GET['number'] : false;
	$id = isset($_GET['id']) ? (int)$_GET['id'] : false;
	$urlsite = $_GET['urlsite'];
	$list = "";
	if ($number == 5) {
		for ($i=1; $i <= $number ; $i++) { 
			$list .= "trang".$i.",";
		}
		$result_prd = mysql_query("SELECT $list id FROM adoosite_products WHERE id=$id Limit 1", $conn);
		if(mysql_num_rows($result_prd) > 0 ) {
			while($row = mysql_fetch_array($result_prd)){
				?>
				<ul class="document-detail">
					<?php for ($i=1; $i <= $number ; $i++) { 
						$page = "trang".$i."";
						?>
							<li>
					         	<div class="doc-view">
					          		<div class="mark"></div>
					         		<div class="pf w0 h0">
					           			<div class="border">
					            			<?= $row[$page] ?>
					           			</div>            						
					          		</div>
					         	</div>
					        </li>
						<?php
					} ?>
			    </ul>
			    <a href="#signin1" data-toggle="modal" data-animation="animated animate10 fadeIn" class="btn download-btn-big dasdjfiwerhugysfdfw" onclick="checkuser();">Tải về</a>
			    <?php
			}
		}
	}
	elseif ($number < 5) {
		for ($i=1; $i <= $number ; $i++) { 
			$list .= "trang".$i.",";
		}
		$result_prd = mysql_query("SELECT $list id FROM adoosite_products WHERE id=$id Limit 1", $conn);
		if(mysql_num_rows($result_prd) > 0 ) {
			while($row = mysql_fetch_array($result_prd)){
				?>
				<ul class="document-detail">
					<?php for ($i=1; $i <= $number ; $i++) { 
						$page = "trang".$i."";
						?>
							<li>
					         	<div class="doc-view">
					          		<div class="mark"></div>
					         		<div class="pf w0 h0">
					           			<div class="border">
					            			<?= $row[$page] ?>
					           			</div>            						
					          		</div>
					         	</div>
					        </li>
						<?php
					} ?>
					<input type="hidden" id="numberpage" value="<?= $number+1 ?>">
					<button id="clickpage" class="btn download-btn-big" onclick="loadpage();">Xem thêm (1 trang)</button>
			    </ul>
			    <?php
			}
		}
	}
}

?>
