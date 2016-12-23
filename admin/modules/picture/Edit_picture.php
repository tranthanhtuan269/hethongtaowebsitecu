<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
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
$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

$query = "SELECT catid, title, time, images, imgtext, active, alanguage FROM {$prefix}_picture WHERE id=$id";
$result = $db->sql_query_simple($query);
if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");

list($catid, $title, $time, $images, $imgtext, $active, $alang) = $db->sql_fetchrow_simple($result);


$get_path = get_path($time);
$path_upload_img = "$path_upload/pictures";
$siteurl = "http://".$_SERVER['HTTP_HOST']."/Adoosite";
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$catid = intval($_POST['catid']);
	$active = intval($_POST['active']);
	$special = isset($_POST['special']) ? intval($_POST['special']):0;
	$imgtext = $escape_mysql_string(trim($_POST['imgtext']));
	$delimg = isset($_POST['delimg']) ? intval($_POST['delimg']):0;
	$images = isset($_POST['images']) ? $escape_mysql_string(trim($_POST['images'])) : '';
	
	

	$err = 0;
	if($title =="") {
		$err_title = "<font color=\"red\">"._ERROR1."</font><br>";
		$err = 1;
	}

	if($catid == 0) {
		$err_cat = "<font color=\"red\">"._ERROR1_1."</font><br>";
		$err = 1;
	}
	// delete file images
	if($delimg == 1) {
		@unlink(RPATH."$path_upload_img/$images");
		@unlink(RPATH."$path_upload_img/thumb_".$images."");
		$images = "";
	}

	if(!$err) {
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $path_upload_img, $maxsize_up);
			$images1 = $upload->send();
			resizeImg($images1, $path_upload_img, $pic_size);
			if(!empty($images1) && !empty($images)) {
				@unlink(RPATH."$path_upload_img/$images");
				@unlink(RPATH."$path_upload_img/thumb_".$images);
				$images = "";
			}
		}
		else 
		{
			if ((($timed) && (!empty($images))) || ((!$timed) && ($_GET['type'] == 'timed'))) {
				if (!is_dir(RPATH."$newUploadPath")) mkdir(RPATH."$newUploadPath");
				@copy(RPATH."$path_upload_img/$images", RPATH."$newUploadPath/$images");
				@copy(RPATH."$path_upload_img/thumb_$images", RPATH."$newUploadPath/$images");
				@unlink(RPATH."$path_upload_img/$images");
				@unlink(RPATH."$path_upload_img/thumb_".$images);
			}
			$images1 = $images;
		}
		

		
		$query = "UPDATE {$prefix}_picture SET catid=$catid, title='$title', images='$images1', imgtext='$imgtext' WHERE id=$id";
		$db->sql_query_simple($query);		
		
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_NEWS);
		header("Location: modules.php?f=".$adm_modname."&sort=$sort&page=$page");
	}
}

$title = str_replace('"',"''",$title);

include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do&sort=$sort&page=$page&id=$id\" method=\"POST\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">Sửa</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Thuộc danh mục</b></td>\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_picture_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
echo "		<td class=\"row3\">$err_cat<select name=\"catid\">";
echo "<option name=\"catid\" value=\"0\">Thuộc danh mục</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
	if($cat_id == $catid) {$seld =" selected"; }else{ $seld ="";}
	$listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">- $titlecat</option>";
	$listcat .= subcat($cat_id,"-",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Hiển thị</b></td>\n";
if($active == 1) 
{
	echo "<td class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
} 
else 
{
	echo "<td class=\"row3\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"active\" value=\"0\"  checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";

if (empty($images)) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row3\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
	echo "</tr>\n";
	$imgtext = "";
} else {
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._DELIMAGE."</b></td>\n";
	echo "		<td class=\"row3\"><input type=\"checkbox\" name=\"delimg\" value=\"1\">&nbsp;<a href=\"".RPATH."$path_upload_img/$images\" target=\"_blank\" info=\""._VIEWIMAGE."\"><img border=\"0\" width='100px' src=\"".RPATH."$path_upload_img/$images\" align=\"absmiddle\"></a></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._CHANGE_IMAGE."</b></td>\n";
	echo "		<td class=\"row3\"><input type=\"file\" name=\"userfile\" size=\"40\"></td>\n";
	echo "	</tr>\n";
	echo "<input type=\"hidden\" name=\"images\" value=\"$images\">";
}
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMGTEXT."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"70\" maxlength=\"253\"></td>\n";
echo "</tr>\n";

echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form><br>";

include_once("page_footer.php");
?>