<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$ajax_call = intval(isset($_GET['ajax']) ? $_GET['ajax'] : (isset($_POST['ajax']) ? $_POST['ajax']:0));

$prdid = $id;
$prdid_1 = $id;

$result_prd = $db->sql_query_simple("SELECT catid, title, style, prdcode, active, sex, shipping, age, companyid, nationalid, text, description, images, relation, pnews, ptops, psale, onhome, tag_seo, title_seo, keyword_seo, description_seo, priceold, price, price1,link1, price2,link2, price3,link3, counts, thongso, trang1, trang2, trang3, trang4, trang5 FROM {$prefix}_products WHERE id=$id");
if($db->sql_numrows($result_prd) != 1) header("Location: modules.php?f=$adm_modname");

list($catid, $title ,$style, $prdcode, $active, $sex, $shipping, $age, $companyid, $nationalid, $text, $mota, $images, $relation, $pnews, $ptops, $psale, $onhome, $tag_seo, $title_seo, $keyword_seo, $description_seo, $priceold, $price,$price1,$link1, $price2,$link2, $price3,$link3, $sluong, $thongso, $trang1, $trang2, $trang3, $trang4, $trang5) = $db->sql_fetchrow_simple($result_prd);
if($ajax_call){
	$catid = intval(isset($_GET['catid']) ? $_GET['catid'] : 0);
}
//Get property of category
$result_property = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$catid");
$properties = array();
if($db->sql_numrows($result_property) > 0){
	$properties = $db->sql_fetchrow_array($result_property);
}
//Get relate property
get_relate_property($catid, $properties);

$path_upload_img = "$path_upload/$adm_modname";
$err_title = $err_cat = $err_national = $err_company = $err = $err_links = "";
$delpic = 0 ;
if( isset($_POST['subup']) && $_POST['subup'] == 1) {


	$title = nospatags($_POST['title']);
	$prdcode = nospatags($_POST['prdcode']);
    // $catid_new = implode(',', $_POST['catid']);//chọn nhiều danh mục
	$catid_new = intval($_POST['catid']);
	if($catid_new != $catid){
		//Update lai property
		$cat_old = $catid;
		$catid = $catid_new;
		$properties_old = $properties;
		//Get property of category
		$result_property = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$catid");
		$properties = array();
		if($db->sql_numrows($result_property) > 0){
			$properties = $db->sql_fetchrow_array($result_property);
		}
		//Get relate property
		get_relate_property($catid, $properties);
	}
	$pnews = intval($_POST['pnews']);
	$ptops = intval($_POST['ptops']);
	$psale = intval($_POST['psale']);
	$onhome = intval($_POST['onhome']);


	if(isset($_POST['sex'])){
		$sex = intval($_POST['sex']);
	}else{
		$sex = 0;
	}
	//$shipping = isset($_POST['shipping']) ? intval($_POST['shipping']):0;
	// $nationalid = intval($_POST['nationalid']);
	$companyid = intval($_POST['companyid']);
	// $counts = nospatags($_POST['counts']);
	 $mota = trim(stripslashes(resString($_POST['mota'])));
	 $trang1 = trim(stripslashes(resString($_POST['trang1'])));
	 $trang2 = trim(stripslashes(resString($_POST['trang2'])));
	 $trang3 = trim(stripslashes(resString($_POST['trang3'])));
	 $trang4 = trim(stripslashes(resString($_POST['trang4'])));
	 $trang5 = trim(stripslashes(resString($_POST['trang5'])));

	$text = trim(stripslashes(resString($_POST['text'])));
	$tag_seo = nospatags($_POST['tag_seo']);
	$title_seo = nospatags($_POST['title_seo']);
	$keyword_seo = nospatags($_POST['keyword_seo']);
	$description_seo = trim(stripslashes(resString($_POST['description_seo'])));
	$thongso = trim(stripslashes(resString($_POST['thongso'])));
	$active = intval($_POST['active']);
	$priceold = floatval(str_replace(',', '.', $_POST['priceold']));
	$price1 = floatval(str_replace(',', '.', $_POST['price1']));

	$permalink=url_optimization(trim($title));
	// $price1 = 0;

	$link1 = trim(stripslashes(resString($_POST['link1'])));
	// $link2 = trim(stripslashes(resString($_POST['link2'])));
	// $link3 = trim(stripslashes(resString($_POST['link3'])));
	// $tinhtrang = trim(stripslashes(resString($_POST['tinhtrang'])));
	// $baohanh = trim(stripslashes(resString($_POST['baohanh'])));

	foreach($properties as $property){
		$value = intval($_POST['property_'.$property['id']]);
		if($property['type'] == 1){
			if($value == 0){
				$property['error'] = "<font color=\"red\">Chưa chọn ".$property['name']."</font><br>";
				$err = 1;
			}
		}
		if($property['id'] == PRICE_ID){
			$price = $value;
		}
	}

	$submit1 = $_POST['submit1'];
	$submit2 = $_POST['submit2'];

	$err =  0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR_TITLE."</font><br>";
		$err = 1;
	}

	if($prdcode =="") {
		$err_prdcode = "<font color=\"red\">"._ERROR_CODE."</font><br>";
		$err = 1;
	}

	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR_CAT."</font><br>";
		$err = 1;
	}
    // if($companyid == 0) {
    //     $err_company = "<font color=\"red\">Vui lòng chọn phong cách</font><br>";
    //     $err = 1;
    // }


	if(!$err) {

		$result = $db->sql_query_simple("UPDATE {$prefix}_products SET catid = $catid, companyid = $companyid, title = '$title', prdcode = '$prdcode', permalink = '$permalink', active = $active,sex = $sex, shipping = $shipping, text = '$text', description='$mota' , thongso = '$thongso', tag_seo = '$tag_seo', title_seo = '$title_seo', keyword_seo = '$keyword_seo', description_seo = '$description_seo', pnews = '$pnews', ptops = '$ptops', psale = '$psale', onhome= '$onhome', images = '$images', priceold = $priceold, price = $price, price1= $price1 , price2 = $price2, price3= $price3, trang1 = '$trang1', trang2 = '$trang2', trang3 = '$trang3', trang4 = '$trang4', link1 = '$link1' WHERE id = $id");


		//Delete old value
		if(isset($cat_old)){
			foreach($properties_old as $property){
				delete_property_value($property['id'],$prdid);
			}
		}

		//Update value property của product
		foreach($properties as $property){
			$value = intval($_POST['property_'.$property['id']]);
			//Check exist property value
			$result_value = $db->sql_query_simple("SELECT id FROM {$prefix}_property_value WHERE property_id = ".$property['id']." AND product_id = $prdid AND category_id = $catid");
			if($db->sql_numrows($result_value) > 0){
				$result_update = $db->sql_query_simple("UPDATE {$prefix}_property_value SET value='$value' WHERE property_id = ".$property['id']." AND product_id = $prdid");
			}
			else{
				$db->sql_query_simple("INSERT INTO {$prefix}_property_value (id, property_id, product_id, category_id, value, text_view, unit) VALUES ('', '".$property['id']."', '$prdid', '$catid', '$value', '', '')");
			}
		}

		fixcount_cat();
	    fixweight_cat("products_cat","catid");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADDNEWS);

		if($submit1 == _SAVECHANGES){
			header("Location: modules.php?f=".$adm_modname."&sort=$sort&page=$page");
		}else if($submit2 == _SAVE_EDIT){
			header("Location: modules.php?f=".$adm_modname."&do=edit&sort=$sort&page=$page&id=$prdid");
		}
	}
}

$path_upload_img = "$path_upload/$adm_modname";
$delpic = $delpic_1 = $delpic_2 = $delpic_3 = $delpic_4 = 0 ;
if( isset($_POST['subup_img']) && $_POST['subup_img'] == 1) {

	if (is_uploaded_file($_FILES['images']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("images", $path_upload_img, $maxsize_up);
			$images = $upload->send();
			resizeImg($images, $path_upload_img, $prd_thumbsize);
			$result = $db->sql_query_simple("INSERT INTO {$prefix}_prd_images (title, prdid) VALUES ( '$images', $id)");
		}
		if (is_uploaded_file($_FILES['images_1']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("images_1", $path_upload_img, $maxsize_up);
			$images_1 = $upload->send();
			resizeImg($images_1, $path_upload_img, $prd_thumbsize);
			$result = $db->sql_query_simple("INSERT INTO {$prefix}_prd_images (title, prdid) VALUES ( '$images_1', $id)");
		}
		if (is_uploaded_file($_FILES['images_2']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("images_2", $path_upload_img, $maxsize_up);
			$images_2 = $upload->send();
			resizeImg($images_2, $path_upload_img, $prd_thumbsize);
			$result = $db->sql_query_simple("INSERT INTO {$prefix}_prd_images (title, prdid) VALUES ( '$images_2', $id)");

		}
		if (is_uploaded_file($_FILES['images_3']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("images_3", $path_upload_img, $maxsize_up);
			$images_3 = $upload->send();
			resizeImg($images_3, $path_upload_img, $prd_thumbsize);
			$result = $db->sql_query_simple("INSERT INTO {$prefix}_prd_images (title, prdid) VALUES ( '$images_3', $id)");
		}
		if (is_uploaded_file($_FILES['images_4']['tmp_name'])) {
			$path_upload_img = "$path_upload/$adm_modname";
			$upload = new Upload("images_4", $path_upload_img, $maxsize_up);
			$images_4 = $upload->send();
			resizeImg($images_4, $path_upload_img, $prd_thumbsize);
			$result = $db->sql_query_simple("INSERT INTO {$prefix}_prd_images (title, prdid) VALUES ( '$images_4', $id)");
		}
}



echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1_1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		if(f.prdcode.value =='') {\n";
echo "			alert('"._ERROR_CODE."');\n";
echo "			f.prdcode.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		if(f.catid.value == 0) {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		if(f.companyid.value == 0) {\n";
echo "			alert('Vui lòng chọn phong cách');\n";
echo "			f.companyid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";



echo "<form name=\"frm_edit\" action=\"modules.php?f=$adm_modname&do=$do&sort=$sort&page=$page&id=$id\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\">";


//// ========== TAB content
echo "<div id=\"tabcontent\">";
echo "<ul id=\"countrytabs\" class=\"shadetabs\">";
echo "<li><a href=\"#\" rel=\"thong_tin_sp\" class=\"selected\">"._THONG_TIN_SAN_PHAM."</a></li>";
echo "<div class=\"border-right\"></div>";
echo "<li><a href=\"#\" rel=\"thuoc_tinh\">"._THUOC_TINH."</a></li>";
echo "<div class=\"border-right\"></div>";
echo "<li><a href=\"#\" rel=\"seo\" >"._SEO."</a></li>";
echo "<div class=\"border-right\"></div>";
echo "<li><a href=\"#\" rel=\"pictures\">"._PICTURES."</a></li>";

echo "</ul>";
echo "</div>";

echo "<div class=\"\">";
echo "<div id=\"thong_tin_sp\" class=\"tabcontent\">"; //== thong tin san pham
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRDCODE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"prdcode\" value=\"$prdcode\" size=\"70\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Size</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"link1\" name=\"link1\" value=\"$link1\" style=\"width:240px;\">  ( nhiều size cách nhau bằng dấu ',' )</td>\n";
echo "</tr>\n";
/*
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Số tầng</b></td>\n";
echo "<td class=\"row2\">";
    echo "<select name=\"link2\">";
    echo "<option value=\"0\" style=\"width:120px;\">--[ Chọn số tầng  ]--</option>";
        for ($i=1; $i <= 20 ; $i++) {
            if ($i == $link2) {
                $seld = 'selected';
            }
            else
            {
                $seld = "";
            }
            ?><option value="<?= $i ?>"  <?= $seld ?> ><?= $i ?> tầng</option><?php
        }

    echo "</selct>";
echo "</td>\n";
echo "</tr>\n";
*/


// echo "<tr>\n";
// echo "<td width=\"15%\" align=\"right\" class=\"row1\"><b>Chiều rộng</b></td>\n";
// echo "<td class=\"row2\"><input type=\"text\" id=\"tinhtrang\" name=\"tinhtrang\">  (m)</td>\n";
// echo "</tr>\n";

// echo "<tr>\n";
// echo "<td width=\"15%\" align=\"right\" class=\"row1\"><b>Chiều dài</b></td>\n";
// echo "<td class=\"row2\"><input type=\"text\" id=\"baohanh\" name=\"baohanh\">  (m)</td>\n";
// echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"15%\" align=\"right\" class=\"row1\"><b>Giá cũ</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"priceold\" name=\"priceold\"  value=\"$priceold\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"15%\" align=\"right\" class=\"row1\"><b>Giá mới</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price1\" name=\"price1\"  value=\"$price1\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>Giới thiệu chung</b></td>\n";
echo "<td class=\"row2\">";
//echo "<textarea name=\"text\"  cols=\"100\" rows=\"5\">$text</textarea>";
editor("text", $text,"",400);
echo "</td>\n";

//editorbasic("text", $text,"",100);

echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Tổng quan</b></td>\n";
echo "<td class=\"row2\">";
editor("mota", $mota,"",400);
echo "</td>\n";
echo "</tr>\n";

// echo "<tr>\n";
// echo "<td align=\"right\" class=\"row1\"><b>Thành phần & Hàm lượng</b></td>\n";
// echo "<td class=\"row2\">";
// editor("mota", $mota,"",400);
// echo "</td>\n";
// echo "</tr>\n";



echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Tiện nghi</b></td>\n";
echo "<td class=\"row2\">";
editor("thongso", $thongso,"",400);
echo "</td>\n";
echo "</tr>\n";

// echo "<tr>\n";
// echo "<td align=\"right\" class=\"row1\"><b>Chống chỉ định</b></td>\n";
// echo "<td class=\"row2\">";
// editor("trang1", $trang1,"",400);
// echo "</td>\n";
// echo "</tr>\n";

// echo "<tr>\n";
// echo "<td align=\"right\" class=\"row1\"><b>Thận trọng</b></td>\n";
// echo "<td class=\"row2\">";
// editor("trang2", $trang2,"",400);
// echo "</td>\n";
// echo "</tr>\n";

// echo "<tr>\n";
// echo "<td align=\"right\" class=\"row1\"><b>Thông tin khác</b></td>\n";
// echo "<td class=\"row2\">";
// editor("trang3", $trang3,"",400);
// echo "</td>\n";
// echo "</tr>\n";



echo "</table>";
echo "</div>";


echo "<div id=\"thuoc_tinh\" class=\"tabcontent\">"; //== thuoc tinh
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
echo "<td class=\"row2\">$err_cat<select name=\"catid\" onchange = \" return update_edit_form_product(this,".$id.");\" >\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\" style=\"width:120px;\">"._INCAT0."</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">|- $titlecat</option>";
	$listcat .= subcat($cat_id,"|",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
//chọn nhiều danh mục
// echo "<tr>\n";
// echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
// echo "<td class=\"row2\">$err_cat<ul class=\"danhmuccat\">\n";
// $result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
// $listcat ="";
// while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
//         $tidsub = explode(',', $catid);
//         for ($i=0; $i < count(explode(',', $catid)) ; $i++) {
//             if ($cat_id == $tidsub[$i]) {
//             $seldt = "checked";
//             break;
//             }
//             else
//             {
//                 $seldt = "";
//             }
//         }
//     $listcat .= "<li><input type=\"checkbox\" name=\"catid[]\"  $seldt  value=\"".$cat_id."\"><span class=\"folder\">".$titlecat."</span></li>";
//     $listcat .= subcatcat($cat_id,"|",$catid, "");
// }
// echo $listcat;
// echo "</ul></td>\n";
// echo "</tr>\n";
//end chọn
// echo "<tr>\n";
// echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Phong cách</b></td>\n";
// echo "<td class=\"row2\">$err_company<select name=\"companyid\" id=\"companyid\">\n";
// $result_cat = $db->sql_query_simple("SELECT id, title FROM ".$prefix."_company WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
// echo "<option name=\"catid\" value=\"0\" style=\"width:120px;\">Chọn phong cách</option>";
// $listcat ="";
// while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
//     if($cat_id == $companyid) {$seld =" selected"; }else{ $seld ="";}
//     $listcat .= "<option value=\"$cat_id\" $seld style=\"font-weight: bold\">|- $titlecat</option>";
// }
// echo $listcat;
// echo "</select></td>\n";
// echo "</tr>\n";

//
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
if($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Nổi bật</b></td>\n";
if($ptops == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"ptops\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"ptops\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"ptops\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"ptops\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PRODUCT_SALE."</b></td>\n";
if($psale == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"psale\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"psale\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"psale\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"psale\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Mới</b></td>\n";
if($pnews == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"pnews\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"pnews\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"pnews\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"pnews\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Hiển thị trang chủ</b></td>\n";
if($onhome == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"onhome\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"onhome\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}



//echo "<tr>\n";
//echo "<td align=\"right\" class=\"row1\"><b>"._FREE_SHIPPING."</b></td>\n";

/*if($shipping == 1) { $check = ' checked="checked"'; } else { $check = ""; }
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"shipping\" value=\"1\"$check></td>\n";
echo "</tr>\n";
*/

foreach($properties as $pro){
    //0: int
    if($pro['type'] == 0){
        echo "<tr>\n";
        echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>".$pro['name']."</b></td>\n";
        echo "<td class=\"row2\"><input type=\"text\" id=\"property_".$pro['id']."\" name=\"property_".$pro['id']."\" value=\"".get_value_property($prdid, $pro['id'])."\" style=\"width:240px;\">&nbsp;(".$pro['unit'].")</td>\n";
        echo "</tr>\n";
    }else{//1: Gia tri nguoi dung tu dinh nghia
        if(isset($pro['error'])){
            $err_message = $pro['error'];
        }else{
            $err_message = "";
        }

        echo "<tr>\n";
        echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>".$pro['name']."</b></td>\n";
        echo "<td class=\"row2\">".$err_message."<select name=\"property_".$pro['id']."\">\n";
        echo "<option name=\"property_".$pro['id']."\" value=\"0\" style=\"width:120px;\">--[Chọn ".$pro['name']."]--</option>";
        $standard_id = get_value_property($prdid, $pro['id']);
        $result_standard = $db->sql_query_simple("SELECT id, name FROM ".$prefix."_property_standard WHERE property_id = ".$pro['id']." AND status=1");
        while(list($id, $name) = $db->sql_fetchrow_simple($result_standard)) {
            if($standard_id == $id) {$seld =" selected"; }else{ $seld ="";}
            echo "<option value=\"$id\" $seld style=\"font-weight: bold\">&nbsp; $name</option>";
        }
        echo "</select></td>\n";
        echo "</tr>\n";
    }
}

echo "</table>";
echo "</div>";

echo "<div id=\"seo\" class=\"tabcontent\">"; //== SEO

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\" >\n";
echo "<tr>\n";
echo "<td class=\"row1\" width=\"20%\" align=\"right\"><b>"._TAG_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"tag_seo\" value=\"$tag_seo\" size=\"80\">&nbsp;&nbsp;"._TAG_NOTE."</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._TITLE_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"title_seo\" value=\"$title_seo\" size=\"80\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"keyword_seo\" value=\"$keyword_seo\" size=\"80\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._DESCRIPTION_SEO."</b></td>\n";
echo "<td class=\"row2\"><textarea name=\"description_seo\"  cols=\"77\" rows=\"4\">$description_seo</textarea></td>\n";
echo "</tr>\n";
echo "</table>";

echo "</div>";

echo "</table>";


echo "<br>";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<input type=\"submit\" name=\"submit1\" value=\""._SAVECHANGES."\" class=\"button2\">&nbsp;&nbsp;";
echo "<input type=\"submit\" name=\"submit2\" value=\""._SAVE_EDIT."\" onclick=\"redirect_edit()\" class=\"button2\">";

echo "</form>";

echo "<div id=\"pictures\" class=\"tabcontent\">"; //== hinh anh============================
echo "<form name=\"frm_img\" action=\"modules.php?f=$adm_modname&do=$do&id=$prdid\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\">";

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\" >\n";
$result_img = $db->sql_query_simple("SELECT id, title FROM {$prefix}_prd_images WHERE prdid=$prdid");
if($db->sql_numrows($result_img) > 0) {
while(list($imgid, $images) = $db->sql_fetchrow_simple($result_img)){
	echo "<tr>\n";
	echo "<td  align=\"right\" class=\"row1\"></td>\n";
	echo "<td class=\"row2\" width=\"62%\"><a href=\"../$path_upload_img/$images\" target=\"_blank\"><img style=\"width:200px;\" border=\"0\" src=\"../$path_upload_img/$images\" align=\"absmiddle\"></a></b></td>\n";
	echo "<td align=\"center\" class=\"row2\" style=\"border-left:1px solid #CCCCCC;\"><a href=\"?f=".$adm_modname."&do=delete_img&prdid=$prdid&img=$imgid\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETE_IMG."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
	echo "</tr>\n";
	}
}
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ANH_1."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"images\" size=\"30\"></td>\n";
	echo "<td  align=\"right\" class=\"row2\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ANH_2."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"images_1\" size=\"30\"></td>\n";
	echo "<td  align=\"right\" class=\"row2\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ANH_3."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"images_2\" size=\"30\"></td>\n";
	echo "<td  align=\"right\" class=\"row2\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ANH_4."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"images_3\" size=\"30\"></td>\n";
	echo "<td  align=\"right\" class=\"row2\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ANH_5."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"images_4\" size=\"30\"></td>\n";
	echo "<td  align=\"right\" class=\"row2\"></td>\n";
	echo "</tr>\n";

echo "<input type=\"hidden\" name=\"subup_img\" value=\"1\">";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._SAVE_LUU."\" class=\"button2\"></td></tr>";
echo "</table>";
echo "</form>";
echo "</div>";

echo "</div>";

// ==== tab content
echo "<script type=\"text/javascript\">
	var countries=new ddtabcontent(\"countrytabs\")
	countries.setpersist(true)
	countries.setselectedClassTarget(\"link\")
	countries.init()
</script>";


include_once("page_footer.php");
?>