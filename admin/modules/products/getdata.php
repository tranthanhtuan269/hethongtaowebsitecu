<?php
if (isset($_GET['number']) && (int)$_GET['number']) {
	$number = isset($_GET['number']) ? (int)$_GET['number'] : false;
	for ($i=1; $i <=$number ; $i++) { 
		?><div class="line_link">
			Links tải <?= $i ?> <input type="text" id="sl" name="sl[]">
			Ngôn ngữ úng dụng <?= $i ?> <input type="text" id="sl" name="sl[]">
		</div>
		
		<?php 
	}
}