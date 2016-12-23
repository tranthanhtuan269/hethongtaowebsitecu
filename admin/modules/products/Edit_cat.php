<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

?>
    <style>
        @media screen and (max-width: 400px){
            .main-page .table-bordered td{
                width: 100%;
                display: block;
            }
            .main-page td:nth-child(1){
                text-align: left;
            }

        }

    </style>
<?php

$catid = intval($_GET['catid']);
$path_upload_img = "$path_upload/$adm_modname";
$result = $db->sql_query_simple("SELECT title,gioithieu, active, onhome, parentid ,images, seo_keyword, seo_description,relation FROM ".$prefix."_products_cat WHERE catid='$catid' AND alanguage='$currentlang'");


if(empty($catid) || $db->sql_numrows($result) != 1) {
	header("Location: ".$adm_modname.".php");
	die();
}

list($title,$gioithieu, $active, $onhome, $parentid, $images, $seo_keyword, $seo_description,$relation) = $db->sql_fetchrow_simple($result);


//$err_title = $err_relation = "";

$err_title = $err  = $err_news =  "";


if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$title = trim(stripslashes(resString($_POST['title'])));
	$active = intval($_POST['active']);
	$onhome = intval($_POST['onhome']);
	$parentid = intval($_POST['parentid']);
	$relation = intval($_POST['relation']);
	$delpic = isset($_POST['delpic']) ? intval($_POST['delpic']):0;
	$properties = $_POST['property'];
	$gioithieu = trim(stripslashes(resString($_POST['gioithieu'])));
	$seo_keyword = nospatags($_POST['seo_keyword']);
	$seo_description = trim(stripslashes(resString($_POST['seo_description'])));


	$permalink=url_optimization(trim($title));

	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}
	if($delpic == 1) {
		@unlink("../$path_upload_img/$images");
		@unlink("../$path_upload_img/thumb_".$images);
		$images = "";
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images_up = $upload->send();
			if($images_up) {
				resizeImg($images_up, $path_upload_img, $prd_thumbsize);
				@unlink("../$path_upload_img/$images");
				@unlink("../$path_upload_img/thumb_".$images);
			} else {
				$images_up = $images;
			}
		} else {
			$images_up = $images;
		}
$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET title='$title',gioithieu='$gioithieu', images='$images_up', active='$active', onhome='$onhome', parentid='$parentid', seo_keyword = '$seo_keyword', seo_description = '$seo_description' , relation = '$relation' WHERE catid='$catid'");

	//  $db->sql_query_simple("INSERT INTO {$prefix}_products_cat (seo_keyword, seo_description ) VALUES ('$seo_keyword', '$seo_description')");

		//fixweight_cat("relation","catid");

		fixweight_cat("products_cat","catid");
		fixsubcat("products_cat","catid");
		update_property($catid, $properties);


		$guid="index.php?f=products&do=categories&id=$catid";
	    $permalink=url_optimization(trim($title));

		$query = "UPDATE ".$prefix."_products_cat SET guid='$guid', permalink ='$permalink' WHERE catid='$catid'";
		$db->sql_query_simple($query);

		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDITCAT);
		header("Location: modules.php?f=".$adm_modname."&do=categories");
	}
}

include("page_header.php");

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR1."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do&catid=$catid\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._EDITCAT."</td></tr>";


echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Giới Thiệu</b></td>\n";
echo "<td class=\"row2\">";
editor("gioithieu", $gioithieu,"",400);
echo "</td>\n";
echo "</tr>\n";

if(!empty($images) && file_exists("../$path_upload_img/$images"))
{
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._DEL_IMAGE."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic\" value=\"1\"> <a href=\"../$path_upload_img/$images\" target=\"_blank\"><img border=\"0\" src=\"../images/img.gif\" align=\"absmiddle\"></a></td>\n";
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
//if($parentid != 0) {
	$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE catid!='$catid' AND parentid='0' AND alanguage='$currentlang' ORDER BY weight");
	if($db->sql_numrows($result_cat) > 0) {
		echo "<tr bgcolor=\"$scolor1\">\n";
		echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
		echo "<td class=\"row2\"><select name=\"parentid\">";
		echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
		$listcat ="";
		while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
			if($cat_id == $parentid) {$seld =" selected"; }else{ $seld ="";}
			$listcat .= "<option value=\"$cat_id\"$seld>--$titlecat</option>";
			$listcat .= subcat($cat_id,"|",$catid, $catid);
		}
		echo $listcat;
		echo "</select></td>\n";
		echo "</tr>\n";
	}

/////////////////////////////////
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Tin liên quan</b></td>\n";
echo "<td class=\"row2\">$err_news<select name=\"relation\" >\n";

$result_news = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_news_cat WHERE alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"relation\" value=\"0\" style=\"width:120px;\">Danh mục</option>";

while(list($relation_id, $titlenational) = $db->sql_fetchrow_simple($result_news))
 {
	if($relation_id == $relation) {$seld =" selected"; }else{ $seld ="";}
	echo "<option value=\"$relation_id\" $seld style=\"font-weight: bold\">&nbsp; $titlenational</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
/////////////////////////////////


echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._KEYWORD_SEO."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"seo_keyword\" value=\"$seo_keyword\" size=\"80\"></td>\n";
echo "</tr>\n";


echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._DESCRIPTION_SEO."</b></td>\n";
echo "<td class=\"row2\"><textarea name=\"seo_description\"  value=\"$seo_description\" cols=\"77\" rows=\"4\">$seo_description</textarea></td>\n";
echo "</tr>\n";



echo "<tr bgcolor=\"#F7F7F7\">\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
if($active == 1) {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
}else {
	echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ONHOME."</b></td>\n";
if ($onhome == 1) {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\">"._NO."</td>\n";
} else {
	echo "<td  class=\"row2\"><input type=\"radio\" name=\"onhome\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"onhome\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";


echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=".$adm_modname."&do=categories'\"></td></tr>";
echo "</table></form>";
include_once("page_footer.php");
?>