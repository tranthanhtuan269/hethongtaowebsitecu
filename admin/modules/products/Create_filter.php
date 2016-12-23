<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");

$text = $mota = $title = $age = $prdcode = $phukien = $mota = $tag_seo = $title_seo = $keyword_seo = $description_seo = $err_title = $err_cat = $err_national = $err_company = $ptops = $psale = $pnews = $err = $err_links = $images = $images_1  = $images_2  = $images_3  = $images_4 = "";

$active = 1;
$status = 1;
//$sex = 0;
//$shipping = 0;

$catid = isset($_GET['catid'])?intval($_GET['catid']):0;
$property_new_selected = isset($_GET['property_id_new'])?intval($_GET['property_id_new']):0;

$err_value_min = "";
$err_value_max = "";
$err_value_filter = "";
$err_filter = "";

if( isset($_POST['subup']) && $_POST['subup'] == 1) {

    //$title = nospatags($_POST['title']);



    $catid = intval($_POST['catid']);
    if(isset($_POST['property'])){
        $property_new_selected = intval($_POST['property']);
    }else{
        $property_new_selected = 0;
    }
    $filter_type = intval($_POST['filter_type']);
    switch ($filter_type){
        // Loai min
        case 0:
            $value = intval($_POST['value_min']);
            if($value <= 0) {
                $err_value_min = "<font color=\"red\">Phải là giá trị lớn hơn 0</font><br>";
                $err = 1;
            }
            $result_filter = $db->sql_query_simple("SELECT * FROM {$prefix}_filter WHERE property_id = ".$property_new_selected." AND cat_id = $catid AND type = 0 AND value=$value");
            if($db->sql_numrows($result_filter) > 0){
                $err_filter = "<font color=\"red\">Đã tồn tại bộ lọc</font><br>";
                $err = 1;
            }
            break;
        case 1:
            $value_min = intval($_POST['value_min']);
            $value_max = intval($_POST['value_max']);
            if($value_min <= 0) {
                $err_value_min = "<font color=\"red\">Phải là giá trị lớn hơn 0</font><br>";
                $err = 1;
            }
            if($value_max <= 0) {
                $err_value_max = "<font color=\"red\">Phải là giá trị lớn hơn 0</font><br>";
                $err = 1;
            }
            if($value_min > $value_max) {
                $err_value_max = "<font color=\"red\">Phải là giá trị lớn hơn min</font><br>";
                $err = 1;
            }
            $value = serialize(array('min'=>$value_min, 'max'=>$value_max));
            $result_filter = $db->sql_query_simple("SELECT * FROM {$prefix}_filter WHERE property_id = ".$property_new_selected." AND cat_id = $catid AND type = 1 AND value=$value");

            if($db->sql_numrows($result_filter) > 0){
                $err_filter = "<font color=\"red\">Đã tồn tại bộ lọc</font><br>";
                $err = 1;
            }
            break;
        // Loại max
        case 2:
            $value = intval($_POST['value_max']);
            if($value <= 0) {
                $err_value_max = "<font color=\"red\">Phải là giá trị lớn hơn 0</font><br>";
                $err = 1;
            }
            $result_filter = $db->sql_query_simple("SELECT * FROM {$prefix}_filter WHERE property_id = ".$property_new_selected." AND cat_id = $catid AND type = 2 AND value=$value");
            if($db->sql_numrows($result_filter) > 0){
                $err_filter = "<font color=\"red\">Đã tồn tại bộ lọc</font><br>";
                $err = 1;
            }
            break;
        case 3:
            //Loai custom
            if(isset($_POST['filter_value'])){
                $value = $_POST['filter_value'];
            }else{
                $value = array();
            }
            if(count($value) <= 0){
                $err_value_filter = "<font color=\"red\">Phải chọn ít nhất 1 giá trị</font><br>";
                $err = 1;
            }
            break;
    }

    if($catid == 0) {
        $err_cat = "<font color=\"red\">"._ERROR_CAT."</font><br>";
        $err = 1;
    }

    if(!$err) {
        if($filter_type == 3){
            /*
             * Delete all filter lien quan den thuoc tinh cua category
             */
            $db->sql_query_simple("DELETE FROM {$prefix}_filter WHERE property_id = $property_new_selected AND cat_id = $catid");
            if(is_array($value)){
                foreach ($value as $v){
                    $db->sql_query_simple("INSERT INTO ".$prefix."_filter (property_id, cat_id, value, type) VALUES ('$property_new_selected', '$catid', '$v', '$filter_type')");
                }
            }
        }else{
            $db->sql_query_simple("INSERT INTO ".$prefix."_filter (property_id, cat_id, value, type) VALUES ('$property_new_selected', '$catid', '$value', '$filter_type')");
        }
        //die("INSERT INTO ".$prefix."_filter (property_id, cat_id, value_min, value_max) VALUES ('$property', '$catid', '$value_min', '$value_max')");
        updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _FILTER);
        header("Location: modules.php?f=".$adm_modname."&do=$do&bf");
    }
}

//Get property of category
$result_property = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$catid");
$properties = array();
if($db->sql_numrows($result_property) > 0){
    $properties = $db->sql_fetchrow_array($result_property);
}
//Get relate property
get_relate_property($catid, $properties);


$page_url = "modules.php?f=$adm_modname&do=$do";

echo "<script language=\"javascript\">\n";
echo "  function check(f) {\n";
echo "      if(f.title.value =='') {\n";
echo "          alert('"._ERROR1_1."');\n";
echo "          f.title.focus();\n";
echo "          return false;\n";
echo "      }   \n";
echo "      \n";
echo "      if(f.prdcode.value =='') {\n";
echo "          alert('"._ERROR_CODE."');\n";
echo "          f.prdcode.focus();\n";
echo "          return false;\n";
echo "      }   \n";
echo "      \n";
echo "      if(f.catid.value == 0) {\n";
echo "          alert('"._ERROR2."');\n";
echo "          f.catid.focus();\n";
echo "          return false;\n";
echo "      }   \n";
echo "      \n";
echo "      if(f.nationalid.value == 0) {\n";
echo "          alert('"._ERROR_CHATLIEU."');\n";
echo "          f.nationalid.focus();\n";
echo "          return false;\n";
echo "      }   \n";
echo "      \n";
echo "      if(f.companyid.value == 0) {\n";
echo "          alert('"._ERROR_XUATXU."');\n";
echo "          f.companyid.focus();\n";
echo "          return false;\n";
echo "      }   \n";
echo "      \n";
echo "      f.submit.disabled = true;\n";
echo "      return true;    \n";
echo "  }   \n";
echo "</script> \n";

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onsubmit=\"return check(this);\" >";


echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._INCAT."</b></td>\n";
echo "<td class=\"row2\">$err_cat<select name=\"catid\" id=\"catid\" onchange = \"return update_form_filter(this,'".$page_url."')\" >\n";
$result_cat = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='0' AND alanguage='$currentlang' ORDER BY weight");
echo "<option name=\"catid\" value=\"0\" style=\"width:120px;\">"._INCAT0."</option>";
$listcat ="";
while(list($cat_id, $titlecat) = $db->sql_fetchrow_simple($result_cat)) {
    if($cat_id == $catid) {
        $seld =" selected";
    }else{ $seld ="";
    }
    $listcat .= "<option value=\"$cat_id\"$seld style=\"font-weight: bold\">|- $titlecat</option>";
    $listcat .= subcat($cat_id,"|",$catid, "");
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"40%\" align=\"right\" class=\"row1\"><b>"._STANDARD_PROPERTY."</b></td>\n";
echo "<td><select name=\"property\" style=\"width:120px;\" onchange = \"return update_property_filter(this,'".$page_url.'&catid='.$catid."')\">\n";

$listcat ="";
$listcat .= "<option value=\"0\" style=\"font-weight: bold\">Chọn thuộc tính</option>";
foreach($properties as $pro){
    if(!$pro['is_filter']){
        continue;
    }
    if($pro['id'] == $property_new_selected){
        $seld = "selected";
    }else{
        $seld = "";
    }
    $listcat .= "<option value=\"".$pro['id']."\"$seld style=\"font-weight: bold\">".$pro['name']."</option>";
}
echo $listcat;
echo "</select></td>\n";
echo "</tr>\n";
/*
 * Get property
*/
$result_property_new = $db->sql_query_simple("SELECT * FROM {$prefix}_property WHERE id = $property_new_selected");
if($db->sql_numrows($result_property_new) > 0){
    $property_new = $db->sql_fetchrow_simple($result_property_new);
}else{
    $property_new = null;
}
//Type property : 0: kieu so, 1: custom
if($property_new['type'] == 0){
    // echo "<tr>\n";
    // echo "<td width=\"40%\" align=\"right\" class=\"row1\">$err_filter<b>"._FILTER_TYPE."</b></td>\n";
    // echo "<td><select name=\"filter_type\" style=\"width:120px;\" onchange = \"return update_filter_value(this);\">\n";
    // $listcat ="";
    // foreach(array(0,1,2) as $type_filter){
    //     $listcat .= "<option value=\"".$type_filter."\" style=\"font-weight: bold\">".get_name_type_filter($type_filter)."</option>";
    // }
    // echo $listcat;
    // echo "</select></td>\n";
    // echo "</tr>\n";

    // echo "<tr id='value_min'>\n";
    // echo "<td width=\"40%\" align=\"right\" class=\"row1\">$err_value_min<b>Giá trị min</b></td>\n";
    // echo "<td class=\"row2\"><input type=\"text\" name=\"value_min\" value=\"\" size=\"50\"></td>\n";
    // echo "</tr>\n";
    // echo "<tr id='value_max' style=\"display:none;\">\n";
    // echo "<td width=\"40%\" align=\"right\" class=\"row1\">$err_value_max<b>Giá trị max</b></td>\n";
    // echo "<td class=\"row2\"><input type=\"text\" name=\"value_max\" value=\"\" size=\"50\"></td>\n";
    // echo "</tr>\n";
}else if($property_new['type'] == 1){
    //Check thuoc tinh da co filter chua
    $result_filter = $db->sql_query_simple("SELECT * FROM {$prefix}_filter WHERE property_id = ".$property_new['id']." AND cat_id = $catid");
    $values = array();
    if($db->sql_numrows($result_filter) > 0){
        $value_filter = $db->sql_fetchrow_array($result_filter);
        foreach($value_filter as $value){
            array_push($values, $value['value']);
        }
    }
    echo "<input type=\"hidden\" name=\"filter_type\" value=\"3\">";
    echo "<tr>";
    echo "<td width=\"20%\" align=\"right\" class=\"row1\">$err_value_filter<b>"._LIST_STANDARD."</b></td>\n";
    echo "<td class=\"row-car\" style=\"padding-top:7px;padding-bottom:0px;\">";
    echo "<div style=\"width:990px;float:left;\">";
    $result_standard = $db->sql_query_simple("SELECT id, name  FROM ".$prefix."_property_standard WHERE property_id = $property_new_selected AND status = 1");
    while(list($id, $name) = $db->sql_fetchrow_simple($result_standard)) {
        $seld = "";
        if(in_array($id, $values)){
            $seld = "checked";
        }
        echo "<input class=\"div-check\" type=\"checkbox\" $seld name=\"filter_value[]\" value=\"$id\">\n";
        echo "<div class=\"title-checkbox\" style=\"width:165px;\">".$name."</div>";
        echo "<div class=\"span-car\"></div>";
    }
    echo "</div>";
    echo "</td>\n";
    echo "</tr>\n";

}

echo "<input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "<tr><td colspan=\"2\" align=\"center\" class=\"row1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></td></tr>";
echo "</table></form>";

//if( isset($_POST['subup']) && $_POST['subup'] == 1) {
$property_select = intval(isset($_GET['property_id']) ? $_GET['property_id'] : (isset($_POST['property_id']) ? $_POST['property_id']:0));
//}

$perpage = 20;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;

if($property_select > 0){
    $result_filters = $db->sql_query_simple("SELECT * FROM {$prefix}_filter WHERE property_id = $property_select LIMIT $offset, $perpage");
    $countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_filter WHERE property_id = $property_select"));
}else{
    $result_filters = $db->sql_query_simple("SELECT * FROM {$prefix}_filter LIMIT $offset, $perpage");
    $countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_filter"));
}
$array_filters = array();
if($db->sql_numrows($result_filters) > 0){
    $array_filters = $db->sql_fetchrow_array($result_filters);
}

$total = $countf["COUNT(*)"];
$arr_property = array();
$result_property = $db->sql_query_simple("SELECT * FROM {$prefix}_property");

if($db->sql_numrows($result_property) > 0){
    $arr_property = $db->sql_fetchrow_array($result_property);
}

//== Search
echo "<form name=\"formSearch\" action=\"modules.php?f=$adm_modname&do=create_filter\" method=\"post\">";
echo "<table  border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
        <tr>
        <td><b>"._STANDARD_PROPERTY."</b></td>";

echo "<td><select name=\"property_id\" style=\"width:305px; height:20px;\">\n";
echo "<option name=\"id\" value=\"0\" style=\"width:120px;\">"._ALL_PROPERTY."</option>";
$listcat ="";
foreach ($arr_property as $property) {
    if($property['id'] == $property_select) {
        $seld =" selected";
    }else{ $seld ="";
    }
    $listcat .= "<option value=\"".$property['id']."\"$seld style=\"font-weight: bold\">|- ".$property['name']."</option>";
}
echo $listcat;
echo "</select></td>\n";

echo "</tr>
        <tr>
        <td align='right' colspan=\"2\" ><input type=\"submit\" value=\""._FILTER."\"/></td>
                </tr>
                </table></form>";

//=== end Search

/*
 * Get filter with property
 */


if($total > 0) {
    echo "<script language=\"javascript\" type=\"text/javascript\">\n";
    echo "function check_uncheck(){\n";
    echo "  var f=document.frm;\n";
    echo "  if(f.checkall.checked){\n";
    echo "      CheckAllCheckbox(f,'id[]');\n";
    echo "  }else{\n";
    echo "      UnCheckAllCheckbox(f,'id[]');\n";
    echo "  }           \n";
    echo "}\n";
    echo "  function checkQuick(f) {\n";
    echo "      if(f.fc.value =='') {\n";
    echo "          f.fc.focus();\n";
    echo "          return false;\n";
    echo "      }\n";
    echo "      f.submit.disabled = true; \n";
    echo "      return true;        \n";
    echo "  }   \n";
    echo "  function checkQuickId(f) {\n";
    echo "      if(f.id.value =='') {\n";
    echo "          f.id.focus();\n";
    echo "          return false;\n";
    echo "      }\n";
    echo "      f.submit.disabled = true; \n";
    echo "      return true;        \n";
    echo "  }   \n";
    echo "</script>\n";
    ajaxload_content();
    echo "<div id=\"".$adm_modname."_main\"><form action=\"modules.php?f=$adm_modname&do=create_filter&page=$page\" name=\"frm\" method=\"POST\" onsubmit=\"return checkQuick(this);\">";
    echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder\">\n";
    echo "<tr><td colspan=\"9\" class=\"header\">"._LIST_STANDARD."</td></tr>";
    echo "<tr>\n";
    echo "<td class=\"row1sd\"  width=\"10\">"._STANDARD_ORDER."</td>\n";
    echo "<td class=\"row1sd\">"._STANDARD_NAME."</td>\n";
    echo "<td class=\"row1sd\">"._STANDARD_PROPERTY." </td>\n";
    echo "<td class=\"row1sd\">"._PRD_CATEGORY." </td>\n";
    echo "<td class=\"row1sd\" align=\"center\" width=\"10px\">"._DELETE."</td>\n";
    echo "</tr>\n";
    $i =0;
    $a = 1;
    if($page > 1) {
        $a = $perpage*$page - $perpage + 1;
    }
    foreach($array_filters as $filter) {
        $id = $filter['id'];
        $property_id = $filter['property_id'];
        $value = $filter['value'];
        $property_name = "";
        $unit = "";
        $cat_name = "";

        foreach ($arr_property as $property){
            if($property_id == $property['id']){
                $property_name = $property['name'];
                $unit = $property['unit'];
                break;
            }
        }

        $result_cate = $db->sql_query_simple("SELECT title FROM {$prefix}_products_cat WHERE catid = ".$filter['cat_id']);
        if($db->sql_numrows($result_cate) > 0){
            $tmp = $db->sql_fetchrow_simple($result_cate);
            $cat_name = $tmp['title'];
        }
        /*
         * Filter type custom
         */
        if($filter['type'] == 3){
            $value = get_name_filter($filter);
            $value = 'Lọc sản phẩm có giá trị <b>"'.$value.'"</b>';
        }else{
            $value = get_name_filter($filter, $unit);
        }

        if($i%2 == 1) {
            $css = "row1";
        }   else {
            $css ="row3";
        }

        echo "<tr>\n";
        echo "<td align=\"center\" class=\"$css\">$a</td>";
        echo "<td class=\"$css\">$value</td>\n";
        echo "<td align=\"left\" class=\"$css\"><b>$property_name</b></td>\n";
        echo "<td align=\"left\" class=\"$css\"><b>$cat_name</b></td>\n";
        if($ajax_active == 1) {
            echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_filter&id=$id\" title=\""._DELETE."\" onclick=\"return aj_base_delete($id,'$adm_modname','"._DELETEASK1."','delete_filter','');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
        } else {
            echo "<td align=\"center\" width=\"30\" class=\"$css\"><a href=\"?f=".$adm_modname."&do=delete_filter&id=$id\" info=\""._DELETE."\" onclick=\"return confirm('"._DELETEASK."');\"><img border=\"0\" src=\"images/delete.png\"></a></td>\n";
        }
        echo "</tr>\n";
        $i ++;
        $a ++;
    }
    if($total > $perpage) {
        echo "<tr><td colspan=\"9\">";
        $pageurl = "modules.php?f=".$adm_modname."&do=$do&property_id=$property_select";
        echo paging($total,$pageurl,$perpage,$page);
        echo "</td></tr>";
    }
    echo "</table></div>";
}else{
    OpenDiv();
    echo "<center>"._NODATA."</center>";
    CLoseDiv();
}


include_once("page_footer.php");
?>