
<?php

if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
?>
<style>
    @media screen and (max-width: 400px){
        #side-menu {
            max-height: 300px;
            overflow-y: scroll;
        }
        #news_main form:nth-child(1) .table-bordered td{
            width: 100%;
            display: block;
        }
        #news_main form:nth-child(1) .table-bordered td:nth-child(1){
            text-align: left;
        }

    }

</style>
<?php
$active = 1;
$onhome = 0;
$homelinks = 3;
$title = $err_title = $gioithieu= $title_seo = $keyword_seo = $description_seo ="";
if(isset($_POST['subup'])&& $_POST['subup'] == 1) 
{
    $err = 0;
    $title = $escape_mysql_string(trim($_POST['title']));
    $gioithieu = trim(stripslashes(resString($_POST['gioithieu'])));
    $active = intval($_POST['active']);
    $onhome = intval($_POST['onhome']);
    $homelinks = intval($_POST['homelinks']);
    $parentid = intval($_POST['parent']);
    $parent = ($parentid >= 0) ? $parentid : 'NULL';

    $title_seo = isset($_POST['title_seo']) ? nospatags($_POST['title_seo']):'';
    $keyword_seo = isset($_POST['keyword_seo']) ? nospatags($_POST['keyword_seo']):'';
    $description_seo = isset($_POST['keyword_seo']) ? trim(stripslashes(resString($_POST['description_seo']))):'';

    if(empty($title)) {
        $err_title = "<font color=\"red\">"._ERROR1."</font><br>";
        $err = 1;
    }else{
        $permalink=url_optimization(trim($title));
    }





    if(!$err) {
        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $path_upload_img = "$path_upload/$adm_modname";
            $upload = new Upload("userfile", $path_upload_img, $maxsize_up);
            $images = $upload->send();
            resizeImg($images, $path_upload_img, $prd_thumbsize);
        }

        //$result_maxcatid = $db->sql_query
        $result_maxcatid = $db->sql_query_simple("SELECT max(catid) FROM ".$prefix."_news_cat");
        list($catid_max)=$db->sql_fetchrow_simple($result_maxcatid);
        $catid_max = $catid_max+1;

        $guid="index.php?f=news&do=categories&id=$catid_max";
        //
        $weight = WeightMax("news_cat", $parentid, '', 'parent');

        $db->sql_query_simple("INSERT INTO ".$prefix."_news_cat (catid, title,gioithieu,permalink,guid, alanguage, active, weight, onhome, homelinks, parent, counts, startid, title_seo, keyword_seo, description_seo,images) VALUES ('$catid_max', '$title','$gioithieu','$permalink','$guid', '$currentlang', '$active', '$weight', '$onhome', '$homelinks', $parent, 0, 0, '$title_seo', '$keyword_seo', '$description_seo','$images')");
        fixweight_cat();
        updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CREATE_NEWS_TOPIC);
        header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
    }
} else {
    $err_title = "";
    $title = "";
}

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
ajaxload_content();

echo "<div id=\"{$adm_modname}_main\"><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" enctype='multipart/form-data'><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CREATECAT."</td></tr>";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" size=\"50\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Giới Thiệu</b></td>\n";
echo "<td class=\"row2\">";
editor("gioithieu", $gioithieu,"",400);
echo "</td>\n";
echo "</tr>\n";


echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"37\"></td>\n";
echo "</tr>\n";



//hien thi tat ca cac cap danh muc tin tuc

$resultcat = $db->sql_query_simple("SELECT catid, title FROM {$prefix}_news_cat WHERE parent=0 AND alanguage='$currentlang' ORDER BY weight");
if($db->sql_numrows($resultcat) > 0)
{
    echo "<tr><td align=\"right\" class=\"row1\"><b>"._IS_SUBCAT_OF."</b></td>\n";
    echo "<td class=\"row2\">";
    echo '<select id="parent" name="parent">'."\n";
    echo '<option value="0">'._ROOT_CAT."</option>\n";
    $listcat ="";
    while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($resultcat))
    {
        if ($cat_id == $parent)
        {
            $seld =" selected";
        }
        else
        {
            $seld ="";
        }
        $listcat .= "<option value=\"$cat_id\" $seld>--$titlecat</option>";
        $listcat .= subcat($cat_id,"-",$catid, "");
    }
    echo $listcat;
    echo "</select></td></tr>";
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

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ACTIVE."</b></td>\n";
if($active == 1) {
    echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
        ."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td>\n";
} else {
    echo "<td  class=\"row2\"><input type=\"radio\" name=\"active\" value=\"1\">"._YES." &nbsp;&nbsp;"
        ."<input type=\"radio\" name=\"active\" value=\"0\" checked>"._NO."</td>\n";
}
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ONHOME."</b></td>\n";
if($onhome == 1) {
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
    if($i == $homelinks) { $seld =" selected=\"selected\""; }
    echo "<option value=\"$i\"".$seld.">$i</option>\n";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" id=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

function listCat($catArr, $newArr, $tdClass, $pad = '') {
    global $adm_modname, $scolor1, $ajax_active;

    foreach ($newArr as $key => $val) {
        for ($i = 0; $i < count($catArr); $i++) {
            if (intval($catArr[$i]['id']) == $key) $tempArr = $catArr[$i];
        }

        switch (intval($tempArr['active'])) {
            case 1: $active = "<a href=\"?f=".$adm_modname."&do=status_cat&catid={$tempArr['id']}&stat=0\" info=\""._DEACTIVATE."\"><img border=\"0\" src=\"images/view.png\"></a>"; break;
            case 0: $active = "<a href=\"?f=".$adm_modname."&do=status_cat&catid={$tempArr['id']}&stat=1\" info=\""._ACTIVE."\"><img border=\"0\" src=\"images/viewo.png\"></a>"; break;
        }

        switch (intval($tempArr['onhome'])) {
            case 1: $onhome = "<a href=\"?f=".$adm_modname."&do=status_cat_home&catid={$tempArr['id']}&stat=0\" info=\""._NOTHOME."\"><img border=\"0\" src=\"../images/home.gif\"></a>"; break;
            case 0: $onhome = "<a href=\"?f=".$adm_modname."&do=status_cat_home&catid={$tempArr['id']}&stat=1\" info=\""._ONHOME."\"><img border=\"0\" src=\"../images/nhome.gif\"></a>"; break;
        }

        if(intval($tempArr['counts']) > 0) {
            $counts1 = "<a href=\"".RPATH."index.php?f=".$adm_modname."&do=categories&catid={$tempArr['id']}\" target=\"_blank\" info=\""._VIEWCOUNTS."\">{$tempArr['counts']} <img border=\"0\" src=\"images/search.gif\" align=\"absmiddle\"></a>";
            if(intval($tempArr['startid']) == 0) {
                $startids = "<a href=\"".$adm_modname.".php?f=".$adm_modname."&do=cat_newstart&catid={$tempArr['id']}\">"._CHOOSE."</a>";
            } else {
                $startids = "<a href=\"".$adm_modname.".php?f=".$adm_modname."&do=cat_newstart&catid={$tempArr['id']}\">"._CHANGE."</a>";
            }
        } else {
            $counts1 = 0;
            $startids = "<a href=\"".$adm_modname.".php?f=".$adm_modname."&do=create&catid={$tempArr['id']}\">"._NONEWS."</a>";
        }

        if ($ajax_active == 1) {
            $tdId = " id=\"{$adm_modname}_title_edit_{$tempArr['id']}\"";
            $title = "<a href=\"?f=".$adm_modname."&do=edit_cat&catid={$tempArr['id']}\" title=\""._QUICK_EDIT."\" onclick=\"\">{$tempArr['title']}</a> <a href=\"../".url_sid("index.php?f=".$adm_modname."&do=categories&id={$tempArr['id']}")."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','".url_sid("index.php?f=$adm_modname&do=categories&id={$tempArr['id']}")."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
            $icondel = "<a href=\"?f=$adm_modname&do=delete_cat&catid={$tempArr['id']}\" onclick=\"return aj_base_delete('{$tempArr['id']}','$adm_modname','"._DELETEASK."','delete_cat','catid');\" info=\""._DELETE."\"><img border=\"0\" src=\"images/delete.png\"></a>";
        } else {
            $tdId = '';
            $title = $tempArr['title'];
            $icondel = "<a href=\"?f=".$adm_modname."&do=delete_cat&catid={$tempArr['id']}\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a>";
        }

        echo "	<tr>\n";
        echo "<td align=\"center\" class=\"{$tdClass}\"><input type=\"checkbox\" name=\"catid[]\" value=\"{$tempArr['id']}\"></td>\n";
        echo "		<td class=\"{$tdClass}\"$tdId>{$pad}";
        if (!empty($pad)) echo " ";
        if ($tdClass == "row1") {
            $bgcolor = "";
            echo "<b>$title</b>";
        } else {
            $bgcolor = "; background-color: $scolor1";
            echo $title;
        }
        echo "</a></td>\n";
        echo "<td align=\"center\" class=\"{$tdClass}\"><input type=\"text\" name=\"poz[{$tempArr['id']}]\" value=\"{$tempArr['weight']}\" maxlength=\"2\" style=\"text-align: center; width: 30px$bgcolor\"></td>\n";
        echo "<td align=\"center\" class=\"{$tdClass} hidden-xs\">$counts1</td>\n";
        echo "<td align=\"center\" class=\"{$tdClass} hidden-xs\">$startids</td>\n";
        echo "		<td align=\"center\" class=\"{$tdClass} hidden-xs\">{$tempArr['onhome']}</td>\n";
        echo "		<td align=\"center\" class=\"{$tdClass}\">{$tempArr['active']}</td>\n";
        echo "		<td align=\"center\" class=\"{$tdClass} hidden-xs\">{$tempArr['homelinks']}</td>\n";
        echo "<td align=\"center\" class=\"{$tdClass}\"><a href=\"?f=".$adm_modname."&do=edit_cat&catid={$tempArr['id']}\" info=\""._EDIT."\"><img border=\"0\" src=\"images/edit.png\"></a></td>\n";
        echo "<td align=\"center\" class=\"{$tdClass}\">$icondel</td></tr>\n";

        if (is_array($val)) {
            listCat($catArr, $val, "row3", "$pad---");
        }
    }
}

$resultcat = $db->sql_query_simple("SELECT catid, title, active, weight, counts, startid, onhome, homelinks, parent FROM {$prefix}_news_cat WHERE alanguage='$currentlang' ORDER BY weight, catid ASC ");
if($db->sql_numrows($resultcat) > 0) {
    echo "<script language=\"javascript\" type=\"text/javascript\">\n";
    echo "function check_uncheck(){\n";
    echo "	var f=fetch_object('frm');\n";
    echo "	if(f.checkall.checked){\n";
    echo "		CheckAllCheckbox(f,'catid[]');\n";
    echo "	}else{\n";
    echo "		UnCheckAllCheckbox(f,'catid[]');\n";
    echo "	}			\n";
    echo "}\n";
    echo "</script>\n";
    echo "<br><form id=\"frm\" action=\"modules.php?f=$adm_modname&do=quick_do_cat\" method=\"POST\">";
    echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
    echo "<tr><td colspan=\"10\" class=\"header\">";
    echo _CURRENT_CATS;
    echo "</td></tr>";
    echo "	<tr>\n";
    echo "<td width=\"1%\" align=\"center\" class=\"row1sd\"><input type=\"checkbox\" name=\"checkall\" onclick=\"javascript:check_uncheck();\" title=\""._CHECKALL."\"></td>\n";
    echo "		<td class=\"row1sd\">"._TITLE."</td>\n";
    echo "<td align=\"center\" width=\"50\" class=\"row1sd\">"._WEIGHT."</td>\n";
    echo "<td align=\"center\" width=\"60\" class=\"row1sd hidden-xs\">"._COUNTS."</td>\n";
    echo "<td align=\"center\" width=\"60\" class=\"row1sd hidden-xs\">"._NEWSTART."</td>\n";
    echo "		<td align=\"center\" width=\"50\" class=\"row1sd hidden-xs\"><b>"._HOMEP."</b></td>\n";
    echo "		<td align=\"center\" width=\"50\" class=\"row1sd \"><b>"._SHOW."</b></td>\n";
    echo "		<td align=\"center\" width=\"50\" class=\"row1sd hidden-xs\"><b>"._HLINKS."</b></td>\n";
    echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._EDIT."</td>\n";
    echo "<td align=\"center\" width=\"30\" class=\"row1sd\">"._DELETE."</td>\n";
    echo "	</tr>\n";
    $catArr = array();
    $i = 0;
    while (list($catArr[$i]['id'], $catArr[$i]['title'], $catArr[$i]['active'], $catArr[$i]['weight'], $catArr[$i]['counts'], $catArr[$i]['startid'], $catArr[$i]['onhome'], $catArr[$i]['homelinks'], $catArr[$i]['parent']) = $db->sql_fetchrow_simple($resultcat)) { $i++; }
    $newArr = array();
    Common::buildTree($catArr, $newArr);
    listCat($catArr, $newArr, "row1");
    echo "<tr><td colspan=\"8\"><select name=\"fc\">";
    echo "<option value=\"\">&raquo; "._QUICKDO."</option>";
    echo "<option value=\"2\">&raquo; "._QUICKDO_2."</option>";
    echo "<option value=\"3\">&raquo; "._QUICKDO_3."</option>";
    echo "<option value=\"4\">&raquo; "._QUICKDO_4."</option>";
    echo "</select> <input type=\"submit\" value=\""._DOACTION."\"></td></tr>";
    echo "</table></form>";
    echo "<br />";
    OpenDiv();
    echo "* "._NOTES."";
    CloseDiv();
    echo "</div>";
}
include_once("page_footer.php");
?>