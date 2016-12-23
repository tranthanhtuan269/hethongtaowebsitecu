<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$text = $mota = $title = $age = $prdcode = $phukien = $onhome= $mota = $tag_seo = $title_seo = $keyword_seo = $description_seo = $err_title = $err_cat = $err_national = $err_company = $ptops = $psale = $pnews = $err = $err_links = $images = $images_1  = $images_2  = $images_3  = $images_4 = $thongso = $link1 = $link2 = $link3 = $baohanh = $tinhtrang ="";

$active = 1;
$status = 1;
$sex = 0;
$shipping = 0;
$catid = isset($_GET['catid'])?$_GET['catid']:0;
//Get property of category
// $result_property = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$catid");
// $properties = array();
// if($db->sql_numrows($result_property) > 0){
// 	$properties = $db->sql_fetchrow_array($result_property);
// }
//Get relate property
// get_relate_property($catid, $properties);

if( isset($_POST['subup']) && $_POST['subup'] == 1) {

	$title = nospatags($_POST['title']);


	$prdcode = nospatags($_POST['prdcode']);
	$catid = intval($_POST['catid']);
    // $catid = implode(',', $_POST['catid']);
	$pnews = intval($_POST['pnews']);
	$ptops = intval($_POST['ptops']);
	$onhome = intval($_POST['onhome']);
    $companyid = 0;
	$psale = intval($_POST['psale']);
	// $mota = trim(stripslashes(resString($_POST['mota'])));

	$text = trim(stripslashes(resString($_POST['text'])));
	$tag_seo = nospatags($_POST['tag_seo']);
	$title_seo = nospatags($_POST['title_seo']);
	$keyword_seo = nospatags($_POST['keyword_seo']);
	$description_seo = trim(stripslashes(resString($_POST['description_seo'])));
	$thongso = trim(stripslashes(resString($_POST['thongso'])));
	$active = intval($_POST['active']);
	$shipping = isset($_POST['shipping']) ? intval($_POST['shipping']) : 0;
	$priceold = intval($_POST['priceold']);
	$price1 = intval($_POST['price1']);

	$price = 0;
    $permalink=url_optimization(trim($title));

	$link1 = trim(stripslashes(resString($_POST['link1'])));

	$link3 = "";


	// $counts = 0;

	$submit1 = $_POST['submit1'];
	$submit2 = $_POST['submit2'];

 //    $result_property = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$catid");
 //    $properties = array();
 //    if($db->sql_numrows($result_property) > 0){
 //        $properties = $db->sql_fetchrow_array($result_property);
 //    }
 //    //Get relate property
 //    get_relate_property($catid, $properties);

	// foreach($properties as $property){
 //        $value = intval($_POST['property_'.$property['id']]);
 //        if($property['type'] == 1){
 //            if($value == 0){
 //                $property['error'] = "<font color=\"red\">Chưa chọn ".$property['name']."</font><br>";
 //                $err = 1;
 //            }
 //        }
 //    }

    // $tinhtrang = intval($_POST['property_405']);
    // $baohanh = intval($_POST['property_401']);
    // $link2 = intval($_POST['property_399']);

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR_TITLE."</font><br>";
		$err = 1;
	}

	// if($prdcode =="") {
	// 	$err_prdcode = "<font color=\"red\">"._ERROR_CODE."</font><br>";
	// 	$err = 1;
	// }


	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR_CAT."</font><br>";
		$err = 1;
	}
    // if($companyid == 0) {
    //     $err_company = "<font color=\"red\">Vui lòng chọn phong cách</font><br>";
    //     $err = 1;
    // }
	if(!$err) {

	$result = $db->sql_query_simple("INSERT INTO {$prefix}_products (catid,companyid, title, prdcode, permalink , alanguage, time, active, sex, shipping, text, thongso, tag_seo, title_seo, keyword_seo, description_seo, pnews, ptops, psale, onhome, images, priceold, price1, price, buyCount,hits, link1, link3) VALUES ($catid, $companyid, '$title' ,'$prdcode','$permalink', '$currentlang', '".time()."', $active, $sex, $shipping,'$text', '$thongso', '$tag_seo', '$title_seo', '$keyword_seo', '$description_seo', '$pnews', '$ptops', '$psale', '$onhome', '$images', $priceold,$price1, $price,0,0,'$link1', '$link3')");

		$result_prdid = $db->sql_query_simple("SELECT max(id)  FROM ".$prefix."_products WHERE 1");
		list($prdidmax) = $db->sql_fetchrow_simple($result_prdid);

		//Update value property của product

		// foreach($properties as $property){
		// 	$value = intval($_POST['property_'.$property['id']]);
		// 	//Check exist property value
		// 	$result_value = $db->sql_query_simple("SELECT id FROM {$prefix}_property_value WHERE property_id = ".$property['id']." AND product_id = $prdidmax");
		// 	if($db->sql_numrows($result_value) > 0){
		// 		$result_update = $db->sql_query_simple("UPDATE {$prefix}_property_value SET value=$value WHERE property_id = ".$property['id']." AND product_id = $prdidmax");
		// 	}
		// 	else{
		// 		$db->sql_query_simple("INSERT INTO {$prefix}_property_value (id, property_id, product_id, category_id, value, text_view, unit) VALUES ('','".$property['id']."', '$prdidmax', '$catid', '$value', '', '')");
		// 	}
		// }

		fixcount_cat();

		fixweight_cat("products_cat","catid");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADDNEWS);

		$guid="index.php?f=products&do=detail&id=$prdidmax";
		$query = "UPDATE {$prefix}_products SET guid='$guid' WHERE id='$prdidmax'";
		$db->sql_query_simple($query);
		//$permalink = url_optimization(trim($title));
		//$result = $db->sql_query_simple("INSERT INTO {$prefix}_products (permalink, guid ) VALUES ('$permalink', '$guid')");



		if($submit1 == _ADD){
			header("Location: modules.php?f=".$adm_modname."");
		}else if($submit2 == _ADD_EDIT){
			header("Location: modules.php?f=".$adm_modname."&do=edit&sort=0&page=1&id=$prdidmax");
		}
	}
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('Nhập tiêu đề');\n";
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
echo "			alert('Vi lòng chọn phong cách');\n";
echo "			f.companyid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\">";


//// ========== TAB content
echo "<div id=\"tabcontent\">";
echo "<ul id=\"countrytabs\" class=\"shadetabs\">";
echo "<li><a href=\"#\" rel=\"thong_tin_sp\" class=\"selected\">Thông số</a></li>";
echo "<div class=\"border-right\"></div>";
echo "<li><a href=\"#\" rel=\"thuoc_tinh\">"._THUOC_TINH."</a></li>";
echo "<div class=\"border-right\"></div>";
echo "<li><a href=\"#\" rel=\"seo\" >"._SEO."</a></li>";
echo "<div class=\"border-right\"></div>";
echo "<li><a href=\"#\" rel=\"pictures\">"._PICTURES."</a></li>";

echo "</ul>";
echo "</div>";


echo "<div class=\"container\">";

echo "<div id=\"thong_tin_sp\" class=\"tabcontent\">"; //== thong tin san pham

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
//echo "<tr><td colspan=\"2\" class=\"header\">"._ADDNEWS."</td></tr>";
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
            ?><option value="<?= $i ?>"><?= $i ?> tầng</option><?php
        }
    echo "</selct>";
echo "</td>\n";
echo "</tr>\n";
*/

// echo "<tr>\n";
// echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Màu sắc</b></td>\n";
// echo "<td class=\"row2\"><input type=\"text\" id=\"link3\" name=\"link3\" value=\"$link3\" style=\"width:240px;\"></td>\n";
// echo "</tr>\n";

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
echo "<td class=\"row2\"><input type=\"text\" id=\"priceold\" name=\"priceold\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"15%\" align=\"right\" class=\"row1\"><b>Giá mới</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price1\" name=\"price1\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"center\"><b>Mô tả ngắn</b></td>\n";
echo "<td class=\"row2\">";
//echo "<textarea name=\"text\"  cols=\"100\" rows=\"5\">$text</textarea>";
editor("text", $text,"",400);
echo "</td>\n";

//editorbasic("text", $text,"",100);

// echo "</td>\n";
// echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Tổng quan</b></td>\n";
echo "<td class=\"row2\">";
editor("mota", $mota,"",400);
echo "</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Tiện Nghi</b></td>\n";
echo "<td class=\"row2\">";
editor("thongso", $thongso,"",400);
echo "</td>\n";
echo "</tr>\n";




echo "</table>";
echo "</div>";


echo "<div id=\"thuoc_tinh\" class=\"tabcontent\">"; //== thuoc tinh
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
echo "<td class=\"row2\">$err_cat<select name=\"catid\" id=\"catid\" onchange = \" return update_form_product(this);\">\n";
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
//     // if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
//     $listcat .= "<li><input type=\"checkbox\" name=\"catid[]\" value=\"".$cat_id."\"><span class=\"folder\">".$titlecat."</span></li>";
//     $listcat .= subcatcat($cat_id,"|",$catid, "");
// }
// echo $listcat;
// echo "</ul></td>\n";
// echo "</tr>\n";
// end chọn
// echo "<tr>\n";
// echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Phong cách</b></td>\n";
// echo "<td class=\"row2\">$err_company<select name=\"companyid\" id=\"companyid\">\n";
// $result_cat = $db->sql_query_simple("SELECT id, title FROM ".$prefix."_company WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
// echo "<option name=\"catid\" value=\"0\" style=\"width:120px;\">Chọn phong cách</option>";
// $listcat ="";
// while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
//     $listcat .= "<option value=\"$cat_id\" style=\"font-weight: bold\">|- $titlecat</option>";
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



/*echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._FREE_SHIPPING."</b></td>\n";
if($shipping == 1) { $check = ' checked="checked"'; } else { $check = ""; }
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"shipping\" value=\"1\"$check></td>\n";
echo "</tr>\n";*/


// foreach($properties as $pro){
//     //0: int
//     if($pro['type'] == 0){
//         echo "<tr>\n";
//         echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>".$pro['name']."</b></td>\n";
//         echo "<td class=\"row2\"><input type=\"text\" id=\"property_".$pro['id']."\" name=\"property_".$pro['id']."\" value=\"\" style=\"width:240px;\">&nbsp;(".$pro['unit'].")</td>\n";
//         echo "</tr>\n";
//     }else{//1: Gia tri nguoi dung tu dinh nghia
//         if(isset($pro['error'])){
//             $err_message = $pro['error'];
//         }else{
//             $err_message = "";
//         }

//         echo "<tr>\n";
//         echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>".$pro['name']."</b></td>\n";
//         echo "<td class=\"row2\">".$err_message."<select name=\"property_".$pro['id']."\">\n";
//         echo "<option name=\"property_".$pro['id']."\" value=\"0\" style=\"width:120px;\">--[Chọn ".$pro['name']."]--</option>";
//         // $standard_id = get_value_property($prdid, $pro['id']);
//         $result_standard = $db->sql_query_simple("SELECT id,name FROM ".$prefix."_property_standard WHERE property_id = ".$pro['id']." AND status=1");
//         while(list($id, $name) = $db->sql_fetchrow_simple($result_standard)) {
//             // if($standard_id == $id) {$seld =" selected"; }else{ $seld ="";}
//             echo "<option value=\"$id\" style=\"font-weight: bold\">&nbsp; $name</option>";
//         }
//         echo "</select></td>\n";
//         echo "</tr>\n";
//     }
// }


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


echo "<div id=\"pictures\" class=\"tabcontent\">"; //== hinh anh
echo "<div style=\"padding:10px;padding-bottom:0px;\">";
echo "<b>"._NOTE_SP_PICTURE."</b>";
echo "</div>";
echo "</div>";


echo "</div>";

// ==== tab content
echo "<script type=\"text/javascript\">
	var countries=new ddtabcontent(\"countrytabs\")
	countries.setpersist(true)
	countries.setselectedClassTarget(\"link\")
	countries.init()
</script>";

echo "<br>";

echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<input type=\"submit\" name=\"submit1\" value=\""._ADD."\" class=\"button2\">&nbsp;&nbsp;";
echo "<input type=\"submit\" name=\"submit2\" value=\""._ADD_EDIT."\" onclick=\"redirect_edit();\" class=\"button2\">";

echo "</form>";

include_once("page_footer.php");
?>