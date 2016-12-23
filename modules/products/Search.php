<?php
if (!defined('CMS_SYSTEM')) die();

global $keywords_page, $description_page, $title_page;

$show = intval(isset($_GET['show']) ? $_GET['show'] : (isset($_POST['show']) ? $_POST['show']:1));
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));

	switch($sort) {
		default: $sortby = "ORDER BY time DESC"; break;
		case 1: $sortby = "ORDER BY hits DESC"; break;
		case 2: $sortby = "ORDER BY price ASC"; break;
		case 3: $sortby = "ORDER BY price DESC"; break;		
		case 4: $sortby = "ORDER BY time DESC"; break;
	}

$national = intval(isset($_GET['national']) ? $_GET['national'] : (isset($_POST['national']) ? $_POST['national']:0));
$company = intval(isset($_GET['company']) ? $_GET['company'] : (isset($_POST['company']) ? $_POST['company']:0));
$color = intval(isset($_GET['color']) ? $_GET['color'] : (isset($_POST['color']) ? $_POST['color']:0));
$age = intval(isset($_GET['age']) ? $_GET['age'] : (isset($_POST['age']) ? $_POST['age']:0));
$price = intval(isset($_GET['price']) ? $_GET['price'] : (isset($_POST['price']) ? $_POST['price']:0));

$query = "";
if($national == 0){ $query .= " AND 1 ";}else {$query .= " AND p.nationalid = $national ";}
if($company == 0){ $query .= " AND 1 ";}else {$query .= " AND p.companyid = $company ";}
if($color == 0){ $query .= " AND 1 ";}else if($color == -1) {$query .= " AND p.color = 1 ";} else {$query .= " AND c.colorid = $color ";}
if($age == 0){ $query .= " AND 1 ";}else if($age == -1) {$query .= " AND p.age = 1 ";} else {$query .= " AND s.ageid = $age ";}

switch($price){
case 0 : $query .= "AND 1"; break;
case 1 : $query .= "AND price <= 100000 AND price != 0"; break;
case 2 : $query .= "AND price > 100000 AND price <= 150000";break;
case 3 : $query .= "AND price > 150000 AND price <= 200000";break;
case 4 : $query .= "AND price > 200000 AND price <= 250000";break;
case 5 : $query .= "AND price > 250000 AND price <= 300000";break;
case 6 : $query .= "AND price > 300000 AND price <= 350000";break;
case 7 : $query .= "AND price > 350000 AND price <= 400000";break;
case 8 : $query .= "AND price > 400000 AND price <= 450000";break;
case 9 : $query .= "AND price > 450000 AND price <= 500000";break;
case 10 : $query .= "AND price > 500000";break;
case -1 : $query .= "AND price = 0";break;
}

$result = $db->sql_query_simple("SELECT title FROM ".$prefix."_national WHERE id ='$national' ");
$number = $db->sql_numrows($result);
if($number == 1){ 
	list($title) = $db->sql_fetchrow_simple($result);	
	$keywords_page .= $title.", ";
	$title_page .= $title.", ";
}
$result = $db->sql_query_simple("SELECT title FROM ".$prefix."_company WHERE id='$company'");
$number = $db->sql_numrows($result);
if($number == 1){ 
	list($title) = $db->sql_fetchrow_simple($result);	
	$keywords_page .= $title.", ";
	$title_page .= $title.", ";
}
$result = $db->sql_query_simple("SELECT title FROM ".$prefix."_color WHERE id='$color'");
$number = $db->sql_numrows($result);
if($number == 1){ 
	list($title) = $db->sql_fetchrow_simple($result);	
	$keywords_page .= $title.", ";
	$title_page .= $title.", ";
}
$result = $db->sql_query_simple("SELECT title FROM ".$prefix."_age WHERE id='$age'");
$number = $db->sql_numrows($result);
if($number == 1){ 
	list($title) = $db->sql_fetchrow_simple($result);	
	$keywords_page .= $title;
	$title_page .= $title;
}

include_once("header.php");

$perpage = $prd_perpage;

$pageurl = "index.php?f=$module_name&do=search&national=$national&company=$company&color=$color&age=$age&price=$price"; //
$url = "index.php?f=$module_name&do=search&national=$national&company=$company&color=$color&age=$age&price=$price";
// phan trang
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$table = "{$prefix}_products AS p LEFT JOIN {$prefix}_products_color AS c ON p.id = c.prdid LEFT JOIN {$prefix}_products_age AS s ON p.id = s.prdid ";

$query_1 = "SELECT DISTINCT p.id FROM $table WHERE p.active='1' $query ";
$total = $db->sql_numrows($db->sql_query_simple($query_1));

if($total > 0){
echo "<div class=\"category-product\">";
	echo "<div class=\"category-product-list\">";
echo "<div class=\"title_home\"><h2>"._RESULT_SEARCH."</h2></div>";
echo "<div class=\"top-row\">";
echo "<div style=\"font-weight:bold; padding:5px;width:230px;float:left\">"._CO."<span style=\"color:red;\">".$total."</span> "._SAN_PHAM_1."</div>";

echo "<div class=\"sort\">";		
	?>
	<Script language= "JavaScript">
	function goto(form) { 
	var index=form.select.selectedIndex;
	location=form.select.options[index].value;}
	</SCRIPT>
	<?php
	echo "<FORM NAME=\"form1\">";
	echo "<select name=\"select\" style=\"width:150px;\" ONCHANGE=\"goto(this.form)\" SIZE=\"1\">";	
		if(!isset($_GET['sort']) || $key==0){echo "<option value=\"$url&show=$show&sort=0\" selected=\"selected\">"._SORT."</option>";}		
		if($sort == 1){echo "<option value=\"$url&sort=1\" selected=\"selected\">"._XEM_NHIEU."</option>";}
		else{echo "<option value=\"$url&sort=1\">"._XEM_NHIEU."</option>";}
		if($sort == 2){echo "<option value=\"$urlsort=2\" selected=\"selected\">"._GIA_TANG_DAN."</option>";}
		else{echo "<option value=\"$url&sort=2\">"._GIA_TANG_DAN."</option>";}
		if($sort == 3){echo "<option value=\"$url&sort=3\" selected=\"selected\">"._GIA_GIAM_DAN."</option>";}
		else{echo "<option value=\"$url&sort=3\">"._GIA_GIAM_DAN."</option>";}
		if($sort == 4){echo "<option value=\"$url&sort=4\" selected=\"selected\">"._MOI_CAP_NHAT."</option>";}
		else{echo "<option value=\"$url&sort=4\">"._MOI_CAP_NHAT."</option>";}
	echo "</select>";
	echo "</FORM>";
echo "</div>";

echo "</div>";
				
$result_products = $db->sql_query_simple("SELECT DISTINCT p.id, p.title,  p.price, p.pnews, p.ptops, p.psale FROM $table WHERE p.active='1' $query $sortby LIMIT $offset, $perpage");
if($db->sql_numrows($result_products) > 0) {	
	$i=$offset;
	$alimit = $perpage;
	while(list($id, $title,$price, $pnews, $ptops, $psale) = $db->sql_fetchrow_simple($result_products)) {	
		$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
		list($images) = $db->sql_fetchrow_simple($result_prd_img);
		if($images !="" && file_exists("$path_upload/$module_name/$images")) {
			$images = resizeImages("$path_upload/$module_name/$images", "$path_upload/$module_name/160x160_$images" ,160,160);			
			$images = "<img src=\"$images\">";
		}					
		$i++;	
			echo "<div class=\"km-prd2\"><a href=\"".url_sid("index.php?f=products&do=detail&id=$id")."\">";			
					echo "<div class=\"km-image\">$images </div>";			
					//echo "<div class=\"title\">";						
						echo "<div class=\"km-title\">$title</div>";							
						echo "<div class=\"km-price\">".dsprice($price)." VND</div>";
				//	echo "</div>";
				echo "</a></div>";					
			if($i%5==0){echo "<div class=\"cl\"></div>";}	
	}
	echo "<div class=\"cl\"></div>";
}

}


if($total > $perpage) {	
	$pageurl = $pageurl."&show=$show&sort=$sort";
	echo "<div style=\"float:left;width:100%;\">";
	echo "<div style=\"float:right;margin-top:5px;\">";
		echo paging($total,$pageurl,$perpage,$page);
	echo "</div>";
	echo "</div>";
}

	echo "</div>";


echo "</div>";
include_once("footer.php");
?>