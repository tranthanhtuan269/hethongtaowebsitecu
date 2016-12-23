<?php
if (!defined('CMS_SYSTEM')) die();

function temp_products_detail($title, $description, $prd_pic, $other, $addToCartLink) {
	echo "<div class=\"hotel-detail\">";
	//echo "<h3>$title</h3>";	
	echo "<center><div class=\"hotel-detail-image\">$prd_pic</div></center>";
	echo "<div class=\"hotel-detail-des\" align=\"justify\">$description</div>";
	echo "<div class=\"hotel-detail-cart\" align=\"right\">$addToCartLink</div>";
	echo "</div>";
	if ($other) echo "<div class=\"hotel-detail-other\">".$other."</div>";
}

?>