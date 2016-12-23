<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$id = intval(isset($_GET['id']) ? $_GET['id'] : $_POST['id']);
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));

$query = "SELECT catid, title, time, hometext, bodytext, fattach, othershow, images, imgtext, tag_seo, title_seo, keyword_seo, description_seo, active, alanguage, source, imgshow, nstart, special, image_highlight";
if ($_GET['type'] == 'normal') {
	$table = "{$prefix}_news";
	$query .= ", time";
} else {
	$table = "{$prefix}_news_temp";
	$query .= ", UNIX_TIMESTAMP(timed)";
}
$query .= " FROM $table WHERE id=$id";
$result = $db->sql_query_simple($query);
if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
list($catid, $title, $time, $hometext, $bodytext, $fattach, $othershow, $images, $imgtext, $tag_seo, $title_seo, $keyword_seo, $description_seo, $active, $alang, $source, $imgshow, $startid, $special,$highlight, $time ) = $db->sql_fetchrow_simple($result);

if ($_GET['type'] == 'timed') {
	list($sec, $min, $hour, $day, $mon, $yr) = localtime($time);
	$mon++;
	$yr += 1900;
}

$get_path = get_path($time);
$path_upload_img = "$path_upload/news/$get_path";
$siteurl = "http://".$_SERVER['HTTP_HOST']."/Adoosite";
$path_upload_attach = "$path_upload/news/attachs";//path upload file attach
$err_title = $err_cat = "";
if (isset($_POST['submit'])) {
	$title = $escape_mysql_string(trim($_POST['title']));
	$catid = intval($_POST['catid']);
	$active = intval($_POST['active']);
	$startid = isset($_POST['startid']) ? intval($_POST['startid']):0;
	$special = isset($_POST['special']) ? intval($_POST['special']):0;
	$hometext = $escape_mysql_string(trim($_POST['hometext']));
	$bodytext = $escape_mysql_string(trim($_POST['bodytext']));
	$othershow = isset($_POST['othershow']) ? intval($_POST['othershow']) : 0;
	$imgtext = $escape_mysql_string(trim($_POST['imgtext']));
	$tag_seo =  isset($_POST['tag_seo']) ? nospatags($_POST['tag_seo']):'';
	$title_seo = isset($_POST['title_seo']) ? nospatags($_POST['title_seo']):'';
	$keyword_seo = isset($_POST['keyword_seo']) ? nospatags($_POST['keyword_seo']):'';
	$description_seo = isset($_POST['keyword_seo']) ? trim(stripslashes(resString($_POST['description_seo']))):'';
	$delimg = isset($_POST['delimg']) ? intval($_POST['delimg']):0;
	$delattach = isset($_POST['delattach']) ? intval($_POST['delattach']):0;
	$images = isset($_POST['images']) ? $escape_mysql_string(trim($_POST['images'])) : '';
	$source = isset($_POST['source']) ? $escape_mysql_string(trim($_POST['source'])) : '';
	$imgshow = intval($_POST['imgshow']);
	$highlight = isset($_POST['highlight']) ? intval($_POST['highlight']) : 0;
	$timed = isset($_POST['timed']) ? intval($_POST['timed']) : 0;
	$year = intval($_POST['year']);
	$month = intval($_POST['month']);
	$day = intval($_POST['day']);
	$hour = intval($_POST['hour']);
	$min = intval($_POST['min']);
	$sec = intval($_POST['sec']);

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
	// delete file attach
	if($delattach == 1) {
		@unlink(RPATH."$path_upload_attach/$fattach");
		$fattach = "";
	}
	if(!$err) {
		$postTime = mktime($hour, $min, $sec, $month, $day, $year);
		if ($timed) $newUploadPath = "$path_upload/news/".get_path($postTime);
		else $newUploadPath = $path_upload_img;
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$upload = new Upload("userfile", $newUploadPath, $maxsize_up);
			$images1 = $upload->send();
			resizeImg($images1, $newUploadPath, $sizenews);
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
		//upload file attach
		if (is_uploaded_file($_FILES['userattach']['tmp_name']))
		{
			$upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up);
			$fattach1 = $upload_attach->send();
			if(!empty($fattach1) && !empty($fattach))
			{
				@unlink(RPATH."$path_upload_attach/$fattach");
				$fattach="";
			}
		}
		else
		{
			$fattach1=$fattach;
		}
		if (empty($images1) && empty($images)) { $imgtext = "";}

		if ((($_GET['type'] == 'normal') && (!$timed)) || (($_GET['type'] == 'timed') && ($timed))) {
			$query = "UPDATE $table SET catid=$catid, title='$title', hometext='$hometext', bodytext='$bodytext', fattach='$fattach1', othershow='$othershow', images='$images1', imgtext='$imgtext', source='$source', imgshow=$imgshow, image_highlight=$highlight, tag_seo = '$tag_seo', title_seo = '$title_seo', keyword_seo = '$keyword_seo', description_seo = '$description_seo', nstart=$startid, special=$special";
			if ($timed) $query .= ", timed=FROM_UNIXTIME($postTime)";
			$query .= " WHERE id=$id";
			$db->sql_query_simple($query);
		} elseif (($_GET['type'] == 'normal') && ($timed)) {
			$db->sql_query_simple("DELETE FROM $table WHERE id=$id");
			$db->sql_query_simple("INSERT INTO {$prefix}_news_temp (catid, title, alanguage, hometext, bodytext, fattach, othershow, images, imgtext, tag_seo, title_seo, keyword_seo, description_seo, active, source, imgshow, image_highlight, hits, nstart, special, timed) VALUES ($catid, '$title', '$currentlang', '$hometext', '$bodytext', '$fattach', '$othershow', '$images', '$imgtext', '$tag_seo', '$title_seo', '$keyword_seo', '$description_seo',  $active, '$source', $imgshow, $highlight, 0, $startid, $special, FROM_UNIXTIME($postTime))");
		} elseif (($_GET['type'] == 'timed') && (!$timed)) {
			$db->sql_query_simple("DELETE FROM $table WHERE id=$id");
			$db->sql_query_simple("INSERT INTO {$prefix}_news (catid, title, alanguage, time, hometext, bodytext, fattach, othershow, images, imgtext, active, source, imgshow, image_highlight, tag_seo, title_seo, keyword_seo, description_seo, hits, nstart, special) VALUES ($catid, '$title', '$currentlang', ".time().", '$hometext', '$bodytext', '$fattach', '$othershow', '$images', '$imgtext', $active, '$source', $imgshow, $highlight, '$tag_seo', '$title_seo', '$keyword_seo', '$description_seo', 0, $startid, $special)");
		}
		if (($db->sql_affectedrows() > 0) && (!$timed)) {
			fixcount_cat();
			if($startid == 1) {
				if ($_GET['type'] == 'timed') {
					$db->sql_query_simple("SELECT LAST_INSERT_ID()");
					list($lastInsertId) = $db->sql_fetchrow_simple();
				} else {
					$lastInsertId = $id;
				}
				$db->sql_query_simple("UPDATE {$prefix}_news SET nstart=0 WHERE id!=$lastInsertId AND catid=$catid");
				$db->sql_query_simple("UPDATE {$prefix}_news_cat SET startid=$lastInsertId WHERE catid=$catid");
			}
		}


	    $guid="index.php?f=news&do=detail&id=$id";
	    $permalink=url_optimization(trim($title));

		$query = "UPDATE ".$prefix."_news  SET guid='$guid', permalink ='$permalink' WHERE id='$id'";
		$db->sql_query_simple($query);


		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_EDIT_NEWS);
		header("Location: modules.php?f=".$adm_modname."&sort=$sort&page=$page");
	}
}

$title = str_replace('"',"''",$title);

include_once("page_header.php");

echo "<form action=\"modules.php?f=$adm_modname&do=$do&sort=$sort&page=$page&id=$id&type={$_GET['type']}\" method=\"POST\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td class=\"header\" colspan=\"2\">"._EDITNEWS."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row3\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_news_cat WHERE parent='0' and alanguage='$currentlang' ");
echo "<td class=\"row3\">$err_cat<select name=\"catid\">";
echo "<option name=\"catid\" value=\"0\">"._INCAT0."</option>";
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
echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
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
echo "<td align=\"right\" class=\"row1\"><b>"._SPECIAL."</b></td>\n";
if($special == 1)
{
	echo "<td class=\"row3\"><input type=\"radio\" name=\"special\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"special\" value=\"0\">"._NO."</td>\n";
}
else
{
	echo "<td class=\"row3\"><input type=\"radio\" name=\"special\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"special\" value=\"0\"  checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWSTART."</b></td>\n";
if($startid == 1) { $check = ' checked="checked"'; } else { $check = ""; }
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"startid\" value=\"1\"$check></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._HOMETEXT."</b></td>\n";
echo "<td class=\"row3\">";
editorbasic("hometext",$hometext,"",200);
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._BODYTEXT."</b></td>\n";
echo "<td class=\"row3\">";
editor("bodytext",$bodytext,"",400);
echo "</td>\n";
echo "</tr>\n";
if($folder_site)  $url = !empty($fattach) ? "$siteurl/$folder_site/$path_upload_attach/$fattach" : '';
else $url = !empty($fattach) ? "$siteurl/$path_upload_attach/$fattach" : '';
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_URL."</b></td>\n";
$disabled="";
if (empty($fattach))
{
	$disabled="disabled=\"$disabled\"";
}
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delattach\" value=\"1\" $disabled>&nbsp;"._DELETE_FILE_ATTACH."<br><input type=\"text\" value=\"$url\" size=\"100\" readonly=\"readonly\" /></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_ATTACH_FILE."</b></td>\n";
echo "<td class=\"row3\"><input type=\"file\" name=\"userattach\" size=\"50\"></td>\n";
echo "</tr>\n";
if (empty($images)) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
	echo "<td class=\"row3\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
	echo "</tr>\n";
	$imgtext = "";
} else {
	echo "	<tr>\n";
	echo "		<td align=\"right\" class=\"row1\"><b>"._DELIMAGE."</b></td>\n";
	echo "		<td class=\"row3\"><input type=\"checkbox\" name=\"delimg\" value=\"1\">&nbsp;<a href=\"".RPATH."$path_upload_img/$images\" target=\"_blank\" info=\""._VIEWIMAGE."\"><img border=\"0\" src=\"../images/img.gif\" align=\"absmiddle\"></a></td>\n";
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
if($imgshow == 1) {
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._SHOWIMGDETAIL."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"radio\" name=\"imgshow\" value=\"1\" checked>"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"imgshow\" value=\"0\">"._NO."</td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._SHOWIMGDETAIL."</b></td>\n";
	echo "<td class=\"row2\"><input type=\"radio\" name=\"imgshow\" value=\"1\">"._YES." &nbsp;&nbsp;";
	echo "<input type=\"radio\" name=\"imgshow\" value=\"0\" checked>"._NO."</td>\n";
	echo "</tr>\n";
}
if($mod_seo == 1){
//=== Tag SEO
echo "<tr>\n";
echo "<td class=\"row1\" align=\"right\"><b>"._TAG_SEO."</b></td>\n";
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
//=== end Tag SEO
}
echo "<tr>\n";
$highlightChecked = '';
if (intval($highlight) == 1) $highlightChecked = "checked";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_IMAGE_HIGHLIGHT."</b></td>\n";
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"highlight\" value=\"1\"$highlightChecked></td>\n";
echo "</tr>\n";
$othershowChecked = '';
if (intval($othershow) == 1) $othershowChecked = ' checked="checked"';
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_OTHER_SHOW."</b><br><i>"._NEWS_OTHER_SHOW_DETAIL."</i></td>\n";
echo "<td class=\"row2\"><input type=\"checkbox\" name=\"othershow\" value=\"1\"$othershowChecked></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._SOURCE."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"source\" value=\"$source\" size=\"50\" maxlength=\"253\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._NEWS_TIMED."</b></td>\n";
echo "<td class=\"row2\">";
$timedChecked = '';
if ($_GET['type'] == 'timed') $timedChecked = ' checked="checked"';
echo "<input type=\"checkbox\" name=\"timed\" value=\"1\"$timedChecked>";
echo '<br /><select id="day" name="day">'."\n";
for ($i = 1; $i <= 31; $i++) {
	$daySelected = '';
	if (($_GET['type'] == 'timed') && ($i == $day)) $daySelected = ' selected="selected"';
	echo '<option value="'.$i."\"$daySelected>$i</option>\n";
}
echo "</select>\n".'<select id="month" name="month">'."\n";
for ($i = 1; $i <= 12; $i++) {
	$monSelected = '';
	if (($_GET['type'] == 'timed') && ($i == $mon)) $monSelected = ' selected="selected"';
	echo '<option value="'.$i."\"$monSelected>"._NEWS_MONTH." $i</option>\n";
}
echo "</select>\n".'<select id="year" name="year">'."\n";
$thisYear = localtime(time(), true);
for ($i = intval($thisYear['tm_year']) + 1900; $i <= (1900 + intval($thisYear['tm_year']) + 100); $i++) {
	$yrSelected = '';
	if (($_GET['type'] == 'timed') && ($i == $yr)) $yrSelected = ' selected="selected"';
	echo '<option value="'.$i."\"$yrSelected>$i</option>\n";
}
echo "</select>\n".'&nbsp;&nbsp;'._NEWS_HOUR.'&nbsp;<select id="hour" name="hour">'."\n";
for ($i = 0; $i < 24; $i++) {
	$hourSelected = '';
	if (($_GET['type'] == 'timed') && ($i == $hour)) $hourSelected = ' selected="selected"';
	echo '<option value="'.$i."\"$hourSelected>$i</option>\n";
}
echo "</select>\n"._NEWS_MINUTE.'&nbsp;<select id="min" name="min">'."\n";
for ($i = 0; $i < 60; $i++) {
	$minSelected = '';
	if (($_GET['type'] == 'timed') && ($i == $min)) $minSelected = ' selected="selected"';
	echo '<option value="'.$i."\"$minSelected>$i</option>\n";
}
echo "</select>\n"._NEWS_SECOND.'&nbsp;<select id="sec" name="sec">'."\n";
for ($i = 0; $i < 60; $i++) {
	$secSelected = '';
	if (($_GET['type'] == 'timed') && ($i == $sec)) $secSelected = ' selected="selected"';
	echo '<option value="'.$i."\"$secSelected>$i</option>\n";
}
echo "</select>\n";
echo "</td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form><br>";

include_once("page_footer.php");
?>