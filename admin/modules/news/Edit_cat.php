<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

$catid = intval($_GET['catid']);
$result = $db->sql_query_simple("SELECT title, active, onhome, homelinks, parent,gioithieu, title_seo, keyword_seo, description_seo,images FROM ".$prefix."_news_cat WHERE catid=$catid AND alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) {
    header("Location: modules.php?f=".$adm_modname."");
    die();
}
list($title, $active, $onhome, $homelinks, $parent,$gioithieu, $title_seo, $keyword_seo, $description_seo,$images) = $db->sql_fetchrow_simple($result);

$err_title = "";
$path_upload_img = "$path_upload/$adm_modname";
if (isset($_POST['subup']) && $_POST['subup'] == 1) {
    $title = $escape_mysql_string(trim($_POST['title']));
    $gioithieu = trim(stripslashes(resString($_POST['gioithieu'])));
    //    $gioithieu = "";
    $active = intval($_POST['active']);
    $onhome = intval($_POST['onhome']);
    $homelinks = intval($_POST['homelinks']);
    $parent = intval($_POST['parent']);
    $delpic = intval($_POST['delpic']);


    $title_seo = isset($_POST['title_seo']) ? nospatags($_POST['title_seo']):'';
    $keyword_seo = isset($_POST['keyword_seo']) ? nospatags($_POST['keyword_seo']):'';
    $description_seo = isset($_POST['keyword_seo']) ? trim(stripslashes(resString($_POST['description_seo']))):'';
    if($delpic == 1)
    {
        @unlink(RPATH."$path_upload_img/$images");
        @unlink(RPATH."$path_upload_img/thumb_".$images."");
        $images = "";
    }
    if (empty($title)) {
        $err_title = "<font color=\"red\">"._ERROR1."</font><br>";
        $err = 1;
    }else{
        $permalink=url_optimization(trim($title));
    }
    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        $upload = new Upload("userfile", $path_upload_img, $maxsize_up);
        $images_up = $upload->send();

    } else {
        $images_up = $images;
    }

    if (!$err) 
    {
        $guid="index.php?f=news&do=categories&id=$catid";
        $db->sql_query_simple("UPDATE ".$prefix."_news_cat SET title='$title',gioithieu='$gioithieu',permalink='$permalink',guid='$guid', active='$active', onhome='$onhome', homelinks='$homelinks', parent=$parent, title_seo = '$title_seo', keyword_seo = '$keyword_seo', description_seo = '$description_seo', images = '$images_up' WHERE catid='$catid'");
        fixweight_cat();
        updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT_NEWS_CAT);
        header("Location: modules.php?f=".$adm_modname."&do=categories");
    }
}


include_once("page_header.php");

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

echo "<form method=\"POST\" action=\"modules.php?f=news&do=edit_cat&catid=$catid\" onsubmit=\"return check(this);\" enctype='multipart/form-data'><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._MODTITLE." &raquo; "._EDITCAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"30%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";


if(!empty($images) && file_exists("../$path_upload_img/$images"))
{
    echo "<tr>\n";
    echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Xóa hình</b></td>\n";
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
 echo "<tr>\n";
 echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Giới Thiệu</b></td>\n";
 echo "<td class=\"row2\">";
 editor("gioithieu", $gioithieu,"",400);
 echo "</td>\n";
 echo "</tr>\n";
//hien thi tat ca cac cap danh muc tin tuc
$resultcat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_news_cat WHERE parent='0' AND catid!='$catid' AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0) {
    echo "<tr bgcolor=\"$scolor1\">\n";
    echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._IS_SUBCAT_OF."</b></td>\n";
    echo "<td class=\"row2\"><select name=\"parent\">";
    echo "<option name=\"catid\" value=\"0\">"._ROOT_CAT."</option>";
    $listcat ="";
    while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($resultcat)) {
        if($cat_id == $parent) {$seld =" selected"; }else{ $seld ="";}
        $listcat .= "<option value=\"$cat_id\"$seld>--$titlecat</option>";
        $listcat .= subcat($cat_id,"-",$parent, $catid);
    }
    echo $listcat;
    echo "</select></td>\n";
    echo "</tr>\n";
}
if($mod_seo == 1){
//=== tag SEO
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
//=== end tag SEO
}

echo "<tr bgcolor=\"#F7F7F7\">\n";
echo "<td align=\"right\" class=\"row1\"><b>"._SHOW."</b></td>\n";
if ($active == 1) {
    echo "<td class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
        ."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
    echo "</tr>\n";
} else {
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
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._HOMELINKS."</b></td>\n";
echo "<td class=\"row2\"><select name=\"homelinks\">\n";
for($i = 0; $i <= 10; $i ++) {
    $seld ="";
    if($i == $homelinks) { $seld =" selected"; }
    echo "<option value=\"$i\"".$seld.">$i</option>\n";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='".$adm_modname.".php?f=".$adm_modname."&do=categories'\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>