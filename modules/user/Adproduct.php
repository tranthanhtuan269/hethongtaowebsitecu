<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: index.php?f=user&do=login");

$page_title = _USER_AD_PRODUCT;


include_once('header.php');

global $module_name;
//OpenTab(_USER_EDIT_PROFILE);
function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query_simple("SELECT catid, weight FROM ".$prefix."_products_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow_simple($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query_simple("SELECT catid, counts FROM ".$prefix."_products_cat");
	 $i =0;
	 while(list($catid, $counts) = $db->sql_fetchrow_simple($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query_simple("SELECT*FROM ".$prefix."_products WHERE catid='$catid'"));
	 	if($counts != $numsnew) {
	 		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET counts='$numsnew' WHERE catid='$catid'");
	 	}
	 	$i ++;
	 }
}

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='$catid' AND catid!='$catseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2) = $db->sql_fetchrow_simple($result)) {
			if($catcheck) {
				if($cat_id == $catcheck) {
					$seld = " selected";
				}else{
					$seld ="";
				}	
			}
			$treeTemp .= "<option value=\"$cat_id\"$seld>$text-- $title2</option>";
			$treeTemp .= subcat($cat_id,$text, $catcheck, $catseld);
		}	
	}
	return $treeTemp;	
}

function fixsubcat() {
	global $db, $prefix;
	$result = $db->sql_query_simple("SELECT catid FROM ".$prefix."_products_cat WHERE parentid='0' ORDER BY weight ASC");
	while(list($catid) = $db->sql_fetchrow_simple($result)) {
	 	fixsubcat_rec($catid);
	 }
}	

function fixsubcat_rec($catid) {
	global $db, $prefix;
	$result = $db->sql_query_simple("SELECT catid FROM ".$prefix."_products_cat WHERE parentid='$catid'");
	$sub_id_ar ="";
	if($db->sql_numrows($result) > 0) {
		while(list($catid2) = $db->sql_fetchrow_simple($result)) {
			$sub_id_ar[] = $catid2;
			fixsubcat_rec($catid2);	
		}	
		$sub_id = @implode("|",$sub_id_ar);
		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET sub_id='$sub_id' WHERE catid='$catid'");
	}else{
		$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET sub_id='' WHERE catid='$catid'");
	}	
}


$text = $description = $title = $err_title = $err_cat = $ptops = $psale="";
$active = 0;
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	
	$title = nospatags($_POST['title']);
	$text = trim(stripslashes(resString($_POST['text'])));
	$description = trim(stripslashes(resString($_POST['description'])));
	//$active = intval($_POST['active']);
	//$ptops = intval($_POST['ptops']);
	//$psale = intval($_POST['psale']);
	$userid = intval($userInfo['id']);
	$catid = intval($_POST['catid']);
	$priceold = floatval(str_replace(',', '.', $_POST['priceold']));
	$price = floatval(str_replace(',', '.', $_POST['price']));

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1_1."</font><br>";
		$err = 1;
	}

	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR2."</font><br>";
		$err = 1;
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$path_upload_img = "$path_upload/products";
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images = $upload->send();
			resizeImg($images, $path_upload_img, $prd_thumbsize);
		}
		if (is_uploaded_file($_FILES['userfile_01']['tmp_name'])) {
			$path_upload_img = "$path_upload/products";
			$upload = new Upload("userfile_01", $path_upload_img, $maxsize_up);
			$images_01 = $upload->send();
		}
		if (is_uploaded_file($_FILES['userfile_02']['tmp_name'])) {
			$path_upload_img = "$path_upload/products";
			$upload = new Upload("userfile_02", $path_upload_img, $maxsize_up);
			$images_02 = $upload->send();
		}
		if (is_uploaded_file($_FILES['userfile_03']['tmp_name'])) {
			$path_upload_img = "$path_upload/products";
			$upload = new Upload("userfile_03", $path_upload_img, $maxsize_up);
			$images_03 = $upload->send();
		}
		$result = $db->sql_query_simple("INSERT INTO {$prefix}_products (catid, title, alanguage, time, text, description, active, ptops, psale, images, images_01,images_02,images_03, priceold, price, buyCount, userid) VALUES ($catid, '$title', '$currentlang', ".time().", '$text', '$description', 0, 0, 0, '$images', '$images_01', '$images_02', '$images_03', $priceold, $price, 0, $userid)");
		fixcount_cat();
		updateadmlog($admin_ar[0], $module_name, _MODTITLE, _ADDNEWS);
		header("Location: index.php?f=".$module_name."");
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
echo "		if(f.catid.value == 0) {\n";
echo "			alert('"._ERROR2."');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"index.php?f=$module_name&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._ADDNEWS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
echo "<td class=\"row2\">$err_cat<select name=\"catid\">\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">|- $titlecat</option>";
	$listcat .= subcat($cat_id,"|",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._TEXT_DESC."</b></td>\n";
echo "<td class=\"row2\">";
editorbasic("text",$text,"",150);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._DESCRIPTION."</b></td>\n";
echo "<td class=\"row2\">";
editorbasic("description",$description,"",400);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile_01\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile_02\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile_03\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE_OLD."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"priceold\" name=\"priceold\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price\" name=\"price\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table></form>";
CloseTab();
include_once('footer.php');
?>