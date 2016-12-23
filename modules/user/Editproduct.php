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


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result_prd = $db->sql_query_simple("SELECT catid, title, text, description, active, ptops, psale, images, images_01, images_02, images_03, priceold, price FROM {$prefix}_products WHERE userid={$userInfo['id']} AND id=$id");
if ($db->sql_numrows($result_prd) != 1) header("Location: index.php?f=$module_name");

list($catid, $title, $text, $description, $active, $ptops, $psale, $images, $images_01, $images_02, $images_03, $priceold, $price) = $db->sql_fetchrow_simple($result_prd);

$path_upload_img = "$path_upload/products";
$err_title = $err_cat ="";
if (isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = nospatags($_POST['title']);
	$text = trim(stripslashes(resString($_POST['text'])));
	$description = trim(stripslashes(resString($_POST['description'])));
	//$active = intval($_POST['active']);
	//$ptops = intval($_POST['ptops']);
	//$psale = intval($_POST['psale']);
	$catid = intval($_POST['catid']);
	$delpic = isset($_POST['delpic'])? intval($_POST['delpic']) : 0;
	$delpic2 = isset($_POST['delpic2'])? intval($_POST['delpic2']) : 0;
	$delpic3 = isset($_POST['delpic3'])? intval($_POST['delpic3']) : 0;
	$delpic4 = isset($_POST['delpic4'])? intval($_POST['delpic4']) : 0;
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

	if($delpic == 1) {
		@unlink("../$path_upload_img/$images");
		//@unlink("../$path_upload_img/thumb_".$images);
		$images = "";
	}
	if($delpic2 == 1) {
		@unlink("../$path_upload_img/$images_01");
		//@unlink("../$path_upload_img/thumb_".$images_01);
		$images_01 = "";
	}
	if($delpic3 == 1) {
		@unlink("../$path_upload_img/$images_02");
		//@unlink("../$path_upload_img/thumb_".$images_02);
		$images_02 = "";
	}
	if($delpic4 == 1) {
		@unlink("../$path_upload_img/$images_03");
		//@unlink("../$path_upload_img/thumb_".$images_03);
		$images_03 = "";
	}
	//if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images_up = $upload->send();
			if($images_up) {
				//resizeImg($images_up, $path_upload_img, $prd_thumbsize);
				@unlink("../$path_upload_img/$images");
				//@unlink("../$path_upload_img/thumb_".$images);
			} else {
				$images_up = $images;
			}
		} else {
			$images_up = $images;
		}
		
		if (is_uploaded_file($_FILES['userfile2']['tmp_name'])) {
			$upload = new Upload("userfile2", $path_upload_img, $maxsize_up);
			$images_up2 = $upload->send();
			if($images_up2) {
				//resizeImg($images_up2, $path_upload_img, $prd_thumbsize);
				@unlink("../$path_upload_img/$images_01");
				//@unlink("../$path_upload_img/thumb_".$images_01);
			} else {
				$images_up2 = $images_01;
			}
		} else {
			$images_up2 = $images_01;
		}
		
		if (is_uploaded_file($_FILES['userfile3']['tmp_name'])) {
			$upload = new Upload("userfile3", $path_upload_img, $maxsize_up);
			$images_up3 = $upload->send();
			if($images_up3) {
				//resizeImg($images_up3, $path_upload_img, $prd_thumbsize);
				@unlink("../$path_upload_img/$images_02");
				//@unlink("../$path_upload_img/thumb_".$images_02);
			} else {
				$images_up3 = $images_02;
			}
		} else {
			$images_up3 = $images_02;
		}
		
		if (is_uploaded_file($_FILES['userfile4']['tmp_name'])) {
			$upload = new Upload("userfile4", $path_upload_img, $maxsize_up);
			$images_up4 = $upload->send();
			if($images_up4) {
				//resizeImg($images_up4, $path_upload_img, $prd_thumbsize);
				@unlink("../$path_upload_img/$images_03");
				//@unlink("../$path_upload_img/thumb_".$images_03);
			} else {
				$images_up4 = $images_03;
			}
		} else {
			$images_up4 = $images_03;
		}
		
		$result = $db->sql_query_simple("UPDATE {$prefix}_products SET catid=$catid, title='$title', text='$text', description='$description', active=$active, ptops=$ptops, psale=$psale, images='$images_up', images_01='$images_up2', images_02='$images_up3', images_03='$images_up4', priceold=$priceold, price=$price WHERE userid={$userInfo['id']} AND id=$id");
		fixcount_cat();
		//updateadmlog($admin_ar[0], $module_name, _MODTITLE, _ADDNEWS);
		header("Location: index.php?f=".$module_name."&do=product");
	//}
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

echo "<form action=\"index.php?f=$module_name&do=$do&id=$id\" method=\"POST\" onsubmit=\"return check(this);\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._EDITNEWS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
echo "<td class=\"row2\"><select name=\"catid\">\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
$listcat = "";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">|- $titlecat</option>";
	$listcat .= subcat($cat_id,"|",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr><td align=\"right\" class=\"row1\" valign=\"top\"><b>"._TEXT_DESC."</b></td>\n";
echo "<td class=\"row2\">";
editorbasic("text",$text,"",150);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._DESCRIPTION."</b></td>\n";
echo "<td class=\"row2\">";
editor("description",$description,"",400);
echo "</td>\n";
echo "</tr>\n";
if(!empty($images) && file_exists("$path_upload_img/$images")) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DEL_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic\" value=\"1\"> <a href=\"../$path_upload_img/$images\" target=\"_blank\"><img border=\"0\" src=\"images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"30\"></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"30\"></td>\n";
	echo "</tr>\n";
}

if(!empty($images_01) && file_exists("$path_upload_img/$images_01")) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DEL_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic2\" value=\"1\"> <a href=\"../$path_upload_img/$images_01\" target=\"_blank\"><img border=\"0\" src=\"images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile2\" size=\"30\"></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile2\" size=\"30\"></td>\n";
	echo "</tr>\n";
}

if(!empty($images_02) && file_exists("$path_upload_img/$images_02")) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DEL_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic3\" value=\"1\"> <a href=\"../$path_upload_img/$images_02\" target=\"_blank\"><img border=\"0\" src=\"images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile3\" size=\"30\"></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile3\" size=\"30\"></td>\n";
	echo "</tr>\n";
}

if(!empty($images_03) && file_exists("$path_upload_img/$images_03")) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DEL_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic4\" value=\"1\"> <a href=\"../$path_upload_img/$images_03\" target=\"_blank\"><img border=\"0\" src=\"images/img.gif\" align=\"absmiddle\"></a></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile4\" size=\"30\"></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"file\" name=\"userfile4\" size=\"30\"></td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE_OLD."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"priceold\" name=\"priceold\" value=\"$priceold\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._PRODUCT_PRICE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" id=\"price\" name=\"price\" value=\"$price\">&nbsp;("._THOUSAND_VND.")</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";
CloseTab();
include_once('footer.php');
?>