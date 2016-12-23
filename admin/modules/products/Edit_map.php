<?php

if(!defined('CMS_ADMIN')) {
    die("Illegal File Access");
}

$id = intval($_GET['id']);
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$result = $db->sql_query_simple("SELECT cat_id, property_id FROM ".$prefix."_cat_property_mapping WHERE id='$id'");
if(empty($id) || $db->sql_numrows($result) != 1) {
    header("Location: ".$adm_modname.".php?f=products&do=map");
    die();
}
list($catid, $property_id_cat ) = $db->sql_fetchrow_simple($result);
include_once("page_header.php");
$err = 0;
if( isset($_POST['subup'])&& $_POST['subup'] == 1) {
    $catid = intval($_POST['catid']);
    $property = intval($_POST['property']);
    $status = intval($_POST['status']);

    // if(check_exist_standard($property, $name)){
    //  $err_title = "<font color=\"red\">Giá trị thuộc tính đã tồn tại</font><br>";
    //  $err = 1;
    // }

    // if($name =="") {
    //  $err_title = "<font color=\"red\">"._ERROR1."</font><br>";
    //  $err = 1;
    // }
    if(!$err) {
        $db->sql_query_simple("UPDATE ".$prefix."_cat_property_mapping SET  cat_id = '$catid', property_id = '$property' WHERE id = $id");
        updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Sửa thuộc tính lọc");
        header("Location: modules.php?f=".$adm_modname."&do=map");
    }
}

echo "<div>\n";
ajaxload_content();


echo "<form action=\"#\" method=\"POST\" enctype=\"multipart/form-data\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">Tạo sản phẩm lọc</td></tr>";

echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>Chọn sản phẩm</b></td>\n";
echo "<td class=\"row2\"><select name=\"catid\" id=\"catid\" >\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE active='1' AND parentid = 0 AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\" style=\"width:120px;\">"._INCAT0."</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
    if ($cat_id == $catid) {
       $seld = "selected";
    }
    else{ $seld ="";}
    $listcat .= "<option value=\"$cat_id\" $seld style=\"font-weight: bold\">|- $titlecat</option>";
    // $listcat .= subcat($cat_id,"|",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>Thuộc tính</b></td>\n";
echo "<td><select name=\"property\" style=\"width:305px; height:20px;\">\n";
$listcat ="";
$result_properties = $db->sql_query_simple("SELECT id, name FROM ".$prefix."_property WHERE type=1");
$arr_property = array();
echo "<option value=\"0\" style=\"width:120px;\">Chọn thuộc tính</option>";
while(list($property_id, $name) = $db->sql_fetchrow_simple($result_properties)) {
    if ($property_id == $property_id_cat) {
       $seld = "selected";
    }
    else{ $seld ="";}
    $listcat .= "<option value=\"$property_id\" $seld style=\"font-weight: bold\">$name</option>";
    array_push($arr_property, array('property_id' => $property_id, 'name' => $name));
}
echo $listcat;
echo "</select></td>\n";
echo "<tr>\n";

echo "<td align=\"right\" class=\"row1\"><b>"._STANDARD_STATUS."</b></td>\n";
echo "<td  class=\"row2\"> <input type=\"radio\" name=\"status\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
        ."<input type=\"radio\" name=\"status\" value=\"0\">"._NO."</td>\n";
echo "</tr>\n";
echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"></td></tr>";
echo "</table></form>";



include_once("page_footer.php");