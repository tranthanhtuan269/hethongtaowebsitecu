<?php
if (isset($_GET['number']) && (int)$_GET['number']) {
	$number = isset($_GET['number']) ? (int)$_GET['number'] : false;

	$soluong = isset($_GET['soluong']) ? (int)$_GET['soluong'] : false;
$c = $number - $soluong;
	$link = $_GET['link'];

	$link = explode('-+', $link);
	for ($i=1; $i <=($soluong*2) ; $i++) { 
	// for ($j=1; $j <= $sluong ; $j++) { 
	// 	# code...
	// if ($j == $i ) {
		# code...
	
	?>
		<div class="line_link_edit">
			<?php
				if ($i % 2 != 0) {
			?>
			<span>Links Tải <?= $i/2 + 0.5 ?> <input type="text" id="sl" name="sl[]" value="<?= $link[($i-1)] ?>"></span>
			<?php
				}
				else
				{
			?>
			<span>Ngôn ngữ ứng dụng<?= $i/2 ?> <input type="text" id="sl" name="sl[]" value="<?= $link[($i-1)] ?>"></span>
			<?php
				}
			?>
		</div>
	<?php
	// }
	// }
	}

	for ($i=1; $i <=$c ; $i++) { 
		?><div class="line_link">
			<div class="line_link_edit"><span>Links Tải <?= $i+$soluong ?> <input type="text" id="sl" name="sl[]"></span></div>
			<div class="line_link_edit"><span>Ngôn ngữ ứng dụng <?= $i+$soluong ?> <input type="text" id="sl" name="sl[]"></span></div>
		</div>
		
		<?php
	}
}