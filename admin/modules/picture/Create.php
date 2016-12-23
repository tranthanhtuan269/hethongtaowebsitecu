<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

foldcreate("news");

include("page_header.php");

$active = 1;
$title = $catid =    $imgtext =   $startid = $special =  $err_cat = $err_title = $images = '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
    $err = 0;
    $title = nospatags($_POST['title']);
    $catid = intval($_POST['catid']);
    $active = intval($_POST['active']);


    $othershow = isset($_POST['othershow']) ? intval($_POST['othershow']) : 0;
    $imgtext = nospatags($_POST['imgtext']);

    $startid = isset($_POST['startid']) ? intval($_POST['startid']) : 0;
    $special = isset($_POST['special']) ? intval($_POST['special']) : 0;

    $highlight = isset($_POST['highlight']) ? intval($_POST['highlight']) : 0;


    if (empty($title)) {
        $err_title = "<font color=\"red\">Nhập tiêu đề</font><br>";
        $err = 1;
    }

    if ($catid == 0) {
        $err_cat = "<font color=\"red\">Chọn danh mục</font><br>";
        $err = 1;
    }

    if (!$err) {

        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

            $path_upload_img = "$path_upload/pictures";    //"$path_upload/news/$get_path";
            $upload = new Upload("userfile", $path_upload_img, $maxsize_up);
            $images = $upload->send();
            resizeImg($images, $path_upload_img, $sizenews);
        }
        //upload file attach
        if (is_uploaded_file($_FILES['userattach']['tmp_name']))
        {
            $path_upload_attach = "$path_upload/news/attachs";
            $upload_attach = new Upload("userattach", $path_upload_attach, $maxsize_up);
            $fattach = $upload_attach->send();
        }
        //upload image in body text
        if (empty($images)) { $imgtext = ""; $highlight = 0; }
        list ($xid) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT max(id) AS xid FROM ".$prefix."_picture"));
        if ($xid == "-1") { $id = 1; } else { $id = $xid + 1; }
        if ($timed) $insertIntoTable = "{$prefix}_picture_temp";
        else $insertIntoTable = "{$prefix}_picture";

        $query = "INSERT INTO $insertIntoTable (id, catid, title, alanguage,   fattach, othershow, images, imgtext, active,    image_highlight, hits, nstart, special";
        if ($timed) $query .= ', timed';
        else $query .= ', time';
        $query .= ") VALUES ($id, $catid, '$title', '$currentlang',    '$fattach', '$othershow', '$images', '$imgtext', $active,    $highlight, 0, $startid, $special";
        if ($timed) $query .= ", FROM_UNIXTIME($postTime)";
        else $query .= ", ".time();
        $query .= ')';
        $result = $db->sql_query_simple($query);

        if (($db->sql_affectedrows() > 0) && (!$timed)) {
            fixcount_cat();
            if($startid == 1) {
                $db->sql_query_simple("UPDATE {$prefix}_picture SET nstart=0 WHERE id!=$id AND catid=$catid");
                $db->sql_query_simple("UPDATE ".$prefix."_picture_cat SET startid=$id WHERE catid=$catid");
            }
        }
        updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NEWS_CREATE_NEWS);
        header("Location: modules.php?f=".$adm_modname."");
    }
}

echo "<script language=\"javascript\">\n";
echo "	function check(f) {\n";
echo "		if(f.title.value =='') {\n";
echo "			alert('"._ERROR4."');\n";
echo "			f.title.focus();\n";
echo "			return false;\n";
echo "		}	\n";
echo "		\n";
echo "		if(f.catid.value == 0) {\n";
echo "			alert('"._ERROR1_1."');\n";
echo "			f.catid.focus();\n";
echo "			return false;\n";
echo "		}\n";
echo "		f.submit.disabled = true;\n";
echo "		return true;	\n";
echo "	}	\n";
echo "</script>	\n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" enctype=\"multipart/form-data\" onsubmit=\"return check(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Thêm mới</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
echo "<td class=\"row2\">$err_title<input type=\"text\" name=\"title\" value=\"$title\" size=\"70\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Thuộc danh mục</b></td>\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_picture_cat WHERE parent='0' and alanguage='$currentlang' ORDER BY weight");
echo "		<td class=\"row2\">$err_cat<select name=\"catid\">";
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

echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMAGE."</b></td>\n";
echo "<td class=\"row2\"><input type=\"file\" name=\"userfile\" size=\"50\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._IMGTEXT."</b></td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"imgtext\" value=\"$imgtext\" size=\"70\" maxlength=\"253\"></td>\n";
echo "</tr>\n";

echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\""._ADD."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>