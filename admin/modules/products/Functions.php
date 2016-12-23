<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function fixweight_cat($table,$id) {
	global $db, $prefix, $currentlang;
	$result = $db->sql_query_simple("SELECT ".$id.", weight FROM ".$prefix."_".$table." WHERE alanguage='$currentlang' order by weight ASC");
	$weight = 0;
	while($row = $db->sql_fetchrow_simple($result)) {
		$catid = $row[$id];
		$weight++;
		$catid = intval($catid);
		$db->sql_query_simple("UPDATE ".$prefix."_".$table." SET weight='$weight' WHERE ".$id."='$catid'");
	}
}

function fixcount_cat() {
	global $prefix, $db;
	$result = $db->sql_query_simple("SELECT catid, counts FROM ".$prefix."_products_cat");
	$i =0;
	while(list($catid, $counts) = $db->sql_fetchrow_simple($result)) {
		$numsnew = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_products WHERE catid='$catid'"));
		if($counts != $numsnew) {
			$db->sql_query_simple("UPDATE ".$prefix."_products_cat SET counts='$numsnew' WHERE catid='$catid'");
		}
		$i ++;
	}
}

function fixcount_company() {
	global $prefix, $db;
	$result = $db->sql_query_simple("SELECT id, counts FROM ".$prefix."_company");
	$i =0;
	while(list($companyid, $counts) = $db->sql_fetchrow_simple($result)) {
		$numsnew = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_products WHERE companyid='$companyid'"));
		if($counts != $numsnew) {
			$db->sql_query_simple("UPDATE ".$prefix."_company SET counts='$numsnew' WHERE id='$companyid'");
		}
		$i ++;
	}
}

function get_name_filter($filter, $unit = ""){
	global $db,$prefix;
	switch($filter['type']){
		case 0:
			$name = " < ".$filter['value']." $unit";
			break;
		case 1:
			$arr = unserialize($filter['value']);
			$name = $arr['min']." $unit - ".$arr['max']." $unit";
			break;
		case 2:
			$name = " > ".$filter['value']." $unit";
			break;
		case 3:
			//Get name starndard
			$result = $db->sql_query_simple("SELECT name FROM ".$prefix."_property_standard WHERE id = ".$filter['value']." AND property_id = ".$filter['property_id']);

			if($db->sql_numrows($result) > 0 ) {
				$value = $db->sql_fetchrow_simple($result);
			}else{
				$value = array('name' => "");
			}
			$name = $value['name'];
			break;
	}
	return $name;
}

function fixcount_national() {
	global $prefix, $db;
	$result = $db->sql_query_simple("SELECT id, counts FROM ".$prefix."_national");
	$i =0;
	while(list($nationalid, $counts) = $db->sql_fetchrow_simple($result)) {
		$numsnew = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_products WHERE nationalid='$nationalid'"));
		if($counts != $numsnew) {
			$db->sql_query_simple("UPDATE ".$prefix."_national SET counts='$numsnew' WHERE id='$nationalid'");
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
			$seld = "";
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

function subcatcat($catid, $text="", $catcheck="", $catseld="") {
    global $db, $prefix;
    $treeTemp ="";
    $result = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='$catid' AND catid!='$catseld' order by weight asc");
    if($db->sql_numrows($result) > 0 ) {
        $text = "$text--";
        while(list($cat_id, $title2) = $db->sql_fetchrow_simple($result)) {
            $seldt = "";
            $tidsub = explode(',', $catcheck);
            for ($i=0; $i < count(explode(',', $catcheck)) ; $i++) {
                if ($cat_id == $tidsub[$i]) {
                $seldt = "checked";
                break;
                }
                else
                {
                    $seldt = "";
                }
            }
            $treeTemp .= "<li><input type=\"checkbox\" name=\"catid[]\" $seldt value=\"".$cat_id."\"><span class=\"folder\">$text-- ".$title2."</span></li>";
            $treeTemp .= subcatcat($cat_id,$text, $catcheck, $catseld);
        }
    }
    return $treeTemp;
}
function fixsubcat($table,$id) {
	global $db, $prefix;
	$result = $db->sql_query_simple("SELECT ".$id." FROM ".$prefix."_".$table." WHERE parentid='0' ORDER BY weight ASC");
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

function delete_map($id){
    global $db,$prefix;
    $result = $db->sql_query_simple("SELECT cat_id, property_id FROM ".$prefix."_cat_property_mapping WHERE id=$id");
    list($catid, $property_id_cat ) = $db->sql_fetchrow_simple($result);
    // //Delete all value of standard
    $dele = "DELETE FROM ".$prefix."_cat_property_mapping WHERE id='$id' ";
    if ($db->sql_query_simple($dele)) {
    //     $result1 = $db->sql_query_simple("SELECT id, property_id FROM ".$prefix."_property_standard WHERE  property_id='".$standard['property_id']."'");
    //     $standard1 = $db->sql_fetchrow_simple($result1);
        $db->sql_query_simple("DELETE FROM ".$prefix."_property_standard WHERE property_id='".$property_id_cat."'");
        $db->sql_query_simple("DELETE FROM ".$prefix."_filter WHERE property_id='".$property_id_cat."' AND cat_id = '".$catid."'");
    }
}

/*
 * Delete standard, Khi do phai delete bang gia tri lien quan
 */
function delete_standard($id){
    global $db,$prefix;
    $result = $db->sql_query_simple("SELECT id, property_id FROM ".$prefix."_property_standard WHERE id=$id");
    $standard = $db->sql_fetchrow_simple($result);
    //Delete all value of standard
    $db->sql_query_simple("DELETE FROM ".$prefix."_filter WHERE filter='".$standard['id']."' and property_id = ".$standard['property_id']);
    $db->sql_query_simple("DELETE FROM ".$prefix."_property_standard WHERE id='$id'");
}


/*
 * Lay property cua cac category parent
 */
function get_relate_property($cat_id, &$properties){
	global $db,$prefix;
	//Get parent ID;
	$result_cat = $db->sql_query_simple("SELECT catid,parentid FROM ".$prefix."_products_cat WHERE catid=$cat_id");
	if($db->sql_numrows($result_cat) > 0 ) {
		while(list($id, $parentid) = $db->sql_fetchrow_simple($result_cat)) {
			$result = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$parentid");
			if($db->sql_numrows($result) > 0){
				$properties = array_merge($properties,$db->sql_fetchrow_array($result));
				get_relate_property($parentid,$properties);
			}
		}
	}
	return $properties;
}

/*
 * update lai cac property tuong ung voi cac category
 */
function update_property($cat_id, $properties){
	global $db,$prefix;
	//Delete exist mapping property with cat
	$db->sql_query_simple("DELETE FROM ".$prefix." WHERE cat_id='$cat_id'");
	//Add new mapping
	$result_property = $db->sql_query_simple("SELECT id FROM ".$prefix."_property where id in (".implode(',', $properties).")");
	while(list($property_id) = $db->sql_fetchrow_simple($result_property)) {
		$result = $db->sql_query_simple("INSERT INTO {$prefix}_cat_property_mapping (cat_id, property_id) VALUES ($cat_id, $property_id)");
	}
}
/*
 * Check exist standard
 */
function check_exist_standard($property_id, $name){
	global $db,$prefix;
	$result = $db->sql_query_simple("SELECT id FROM ".$prefix."_property_standard WHERE property_id='$property_id' AND name = '$name'");
	if($db->sql_numrows($result) > 0){
		return true;
	}else{
		return false;
	}
}

/*
 * Lay gia tri thuoc tinh cua 1 product
 */
function get_value_property($product_id,$property_id){
	global $db,$prefix;
	//Kieu gia tri la so
	$result_value = $db->sql_query_simple("SELECT * FROM ".$prefix."_property_value WHERE product_id = ".$product_id." AND property_id = $property_id");
	if($db->sql_numrows($result_value) > 0 ) {
		$value = $db->sql_fetchrow_simple($result_value);
		return $value['value'];
	}else{
		return 0;
	}
}

/*
 * Xoa thuoc tinh, khi do se fai xoa theo cac bang standard, value va mapping voi category
 */
function delete_property($property_id){
	global $db,$prefix;
	$result = $db->sql_query_simple("SELECT id FROM ".$prefix."_property WHERE id='$property_id'");
	if($db->sql_numrows($result) > 0) {
		//Delete all standard and value of property
		$db->sql_query_simple("DELETE FROM ".$prefix."_property_standard WHERE property_id='$property_id'");
		$db->sql_query_simple("DELETE FROM ".$prefix."_property_value WHERE property_id='$property_id'");
		$db->sql_query_simple("DELETE FROM ".$prefix."_map WHERE property_id='$property_id'");
		$db->sql_query_simple("DELETE FROM ".$prefix."_property WHERE id='$property_id'");
	}

}

function delete_property_value($property_id,$product_id){
	global $db,$prefix;
	//Delete all value of product with property
	$db->sql_query_simple("DELETE FROM ".$prefix."_property_value WHERE property_id='$property_id' AND product_id = '$product_id'");

}

/*
 * Lay gia tri thu tu lon nhat cua thuoc tinh
 */
function get_last_order_property(){
	global $db,$prefix;
	$last_order = 0;
	$result = $db->sql_query_simple("SELECT weight FROM ".$prefix."_property ORDER BY weight DESC LIMIT 1");
	if($db->sql_numrows($result) > 0) {
		$property = $db->sql_fetchrow_simple($result);
		$last_order = $property['weight'];
	}
	return $last_order;
}

function get_name_type_filter($type){
	$type_filter_name = "";
	switch($type){
		case 0:
			$type_filter_name = "Có hoặc không";
			break;
		case 1:
			$type_filter_name = "Text giá trị";
			break;
		// case 2:
		// 	$type_filter_name ="Lớn hơn";
		// 	break;
		default:
			$type_filter_name ="";
	}
	return $type_filter_name;
}

?>