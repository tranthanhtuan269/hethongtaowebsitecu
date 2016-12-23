<?php
if (!defined('CMS_SYSTEM')) die();
function showcatidgoc($catid, $parent, $titlecat) {
    global $db, $prefix, $module_name,$urlsite;
    $titlecat ="";
    if ($parent != 0) {
        $result = $db->sql_query_simple("SELECT catid, parentid FROM ".$prefix."_products_cat WHERE catid='$parent'");
        if($db->sql_numrows($result) > 0) {
            list($catid2, $parent2) = $db->sql_fetchrow_simple($result);

            if($parent2 != 0) {
                $titlecat = showcatidgoc($catid2, $parent2, "");
            }
            else
            {
                $titlecat = $catid2;
            }
        }
    }
    else
    {
        $titlecat = $catid;
    }
    return $titlecat;
}
function page_tilecat($catid, $parentid, $title) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query_simple("SELECT catid, parentid, title FROM ".$prefix."_products_cat WHERE catid='$parentid'");
	if($db->sql_numrows($result) > 0) {
		list($catid2, $parentid2, $title2) = $db->sql_fetchrow_simple($result);
		$titlecat .= "<li><a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid2")."\">$title2</a></li>";
		if($parentid2 != 0) {
			$titlecat = page_tilecat($catid2, $parentid2, $titlecat);
		}
	}

	$titlecat .= "<li><a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\">$title</a></li>";

	return $titlecat;
}

function showRating($id) {
	global $db, $prefix, $module_name;
	$result_total = $db->sql_query_simple("SELECT rating FROM {$prefix}_rating WHERE prdid='$id'");
	$num_total = $db->sql_numrows($result_total);
	if($num_total > 0){
		$sum = 0;
		while(list($rat) = $db->sql_fetchrow_simple($result_total)) {
			$sum = $sum + $rat;
		}
		$rating = ceil($sum/$num_total);
	}else $rating = 0;	
	
	$check0 = $check1 = $check2 = $check3 = $check4 = $check5 = "";
	switch($rating){
		case 0 : $check0 = "checked=checked"; break;
		case 1 : $check1 = "checked=checked"; break;
		case 2 : $check2 = "checked=checked"; break;
		case 3 : $check3 = "checked=checked"; break;
		case 4 : $check4 = "checked=checked"; break;
		case 5 : $check5 = "checked=checked"; break;
	}
	$show = "";
	$show .= "<div>";	
		 $show .= "<input class=\"hover-star\" type=\"radio\" name=\"rating$id\" value=\"1\" disabled=\"disabled\" $check1 />";
		 $show .= "<input class=\"hover-star\" type=\"radio\" name=\"rating$id\" value=\"2\" disabled=\"disabled\" $check2 />";
		 $show .= "<input class=\"hover-star\" type=\"radio\" name=\"rating$id\" value=\"3\" disabled=\"disabled\" $check3 />";
		 $show .= "<input class=\"hover-star\" type=\"radio\" name=\"rating$id\" value=\"4\" disabled=\"disabled\" $check4 />";
		 $show .= "<input class=\"hover-star\" type=\"radio\" name=\"rating$id\" value=\"5\" disabled=\"disabled\" $check5 />";	
	$show .= "</div>";

	return $show;
}

if(!function_exists("get_relate_property")){
	function get_relate_property($cat_id, &$properties){
		global $db,$prefix;
		//Get parent ID;
		$result_cat = $db->sql_query_simple("SELECT catid,parentid FROM ".$prefix."_products_cat WHERE catid=$cat_id");
		if($db->sql_numrows($result_cat) > 0 ) {
			while(list($id, $parentid) = $db->sql_fetchrow_simple($result_cat)) {
				$result = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$parentid AND p.is_filter=1 ORDER BY p.weight");
				if($db->sql_numrows($result) > 0){
					$properties = array_merge($properties,$db->sql_fetchrow_array($result));
					get_relate_property($parentid,$properties);
				}
			}
		}
		return $properties;
	}
}

function get_property($catid){
	global $db,$prefix;
	//Get property of category
	$result_property = $db->sql_query_simple("SELECT p.* FROM {$prefix}_property AS p INNER JOIN {$prefix}_cat_property_mapping AS m ON p.id = m.property_id WHERE m.cat_id =$catid AND p.is_filter=1 ORDER BY p.weight");
	$properties = array();
	if($db->sql_numrows($result_property) > 0){
		$properties = $db->sql_fetchrow_array($result_property);
	}
	//Get relate property
	get_relate_property($catid, $properties);
	return $properties;
}

function get_relate_cat($catid){
	global $db,$prefix;
	$array_cat = array();
	$result = $db->sql_query_simple("SELECT catid, parentid FROM ".$prefix."_products_cat WHERE catid='$catid' ");
	if($db->sql_numrows($result) > 0 ) {
			$cat = $db->sql_fetchrow_simple($result);
			array_push($array_cat,$catid);
			$array_cat = array_merge($array_cat, get_relate_cat($cat['parentid']));
	}
	return $array_cat;
}

function get_sub_cat($catid){
	global $db,$prefix;
	$array_cat = array($catid);
	$result = $db->sql_query_simple("SELECT catid FROM ".$prefix."_products_cat WHERE parentid='$catid' ");
	if($db->sql_numrows($result) > 0 ) {
		while(list($cat_id) = $db->sql_fetchrow_simple($result)) {
			array_push($array_cat,$catid);
			$array_cat = array_merge($array_cat, get_sub_cat($cat_id));
		}
	}
	return $array_cat;
}

function get_filters($catid, $property_id){
	global $db,$prefix;
	$catids = get_relate_cat($catid);
	$cats_math = implode(",", $catids);
	$array_filter = array();
	$result = $db->sql_query_simple("SELECT * FROM ".$prefix."_filter WHERE cat_id in($cats_math) AND property_id = $property_id ORDER BY id");
	if($db->sql_numrows($result) > 0 ) {
		$array_filter = $db->sql_fetchrow_array($result);
	}
	return $array_filter;
}

function get_product_value($product_ids, $property_id, $query = "", $cat_relate_ids = array()){
	global $db,$prefix;
	$values = array();
	if(count($product_ids) > 0){
		$product_match = "product_id in (".implode(",", $product_ids).") AND";
	}else{
		$product_match = "product_id = 0 AND";
	}
	if(count($cat_relate_ids) > 0){
		$cat_match = "category_id in (".implode(",", $cat_relate_ids).") AND";
	}else{
		$cat_match = "category_id = 0 AND";
	}
	
	if(!empty($query)){
		$query = ' AND '.$query;
	}
	
	$result = $db->sql_query_simple("SELECT DISTINCT product_id, property_id, category_id, value, text_view FROM ".$prefix."_property_value WHERE $product_match $cat_match property_id = $property_id $query");
	if($db->sql_numrows($result) > 0 ) {
		$values = $db->sql_fetchrow_array($result);
	}
	return $values;
}

function get_product_filter($cat_ids, $filter, $sortby = "", $offset = 0, $perpage = 30){
	global $db,$prefix;
	$products = array();
	$values = array();
	
	/*
	 * Get array value
	 */
	$property_id = $filter['property_id'];
	if(count($cat_ids) > 0){
		$cat_match = "category_id in (".implode(",", $cat_ids).") AND";
		$cat_match_product = "catid in (".implode(",", $cat_ids).") AND";
	}else{
		$cat_match = "category_id = 0 AND";
		$cat_match_product = "catid = 0 AND";
	}
	
	if(count($filter) > 0){
		$query = ' AND '.get_query_filter($filter);
	}
	
	$result = $db->sql_query_simple("SELECT * FROM ".$prefix."_property_value WHERE $cat_match property_id = $property_id $query");
	if($db->sql_numrows($result) > 0 ) {
		$values = $db->sql_fetchrow_array($result);
	}
	
	/*
	 * Get products tu array value
	 */
	$product_ids = array();
	$product_price = array();
	foreach ($values as $value){
		array_push($product_ids, $value['product_id']);
		if($value['property_id'] == PRICE_ID){
			$product_price[$value['product_id']]  = $value['value'];
		}
	}
	if(count($product_ids) > 0){
		$product_match = "id in (".implode(",", $product_ids).")";
	}else{
		$product_match = "";
	}
	$result_product = $db->sql_query_simple("SELECT * FROM ".$prefix."_products WHERE  $cat_match_product $product_match AND active = 1 $sortby  LIMIT $offset, $perpage");
	
	if($db->sql_numrows($result_product) > 0 ) {
		$products = $db->sql_fetchrow_array($result_product);
	}
	
	return $products;
}

function get_product_price($product_id, $cat_ids){
	global $db,$prefix;
	
	if(count($cat_ids) > 0){
		$cat_match = "category_id in (".implode(",", $cat_ids).") AND";
	}else{
		$cat_match = "category_id = 0 AND";
	}
	$result = $db->sql_query_simple("SELECT * FROM ".$prefix."_property_value WHERE $cat_match product_id = $product_id AND property_id = ".PRICE_ID);
	$value = array();
	if($db->sql_numrows($result) > 0 ) {
		$value = $db->sql_fetchrow_simple($result);
	}else{
		$value['value'] = 0;
	}
	return $value['value'];
}

function product_in_array($product, $arr_product){
	foreach ($arr_product as $p){
		if($product['id'] == $p['id']){
			return true;
		}
	}
	return false;
}

function get_query_filter($filter, $field_value = "value"){
	$query = "";
	switch($filter['type']){
		case 0:
			$query = " $field_value < ".$filter['value']." ";
			break;
		case 1:
			$arr = unserialize($filter['value']);
			$query = " $field_value <= ".$arr['max']." AND $field_value >= ".$arr['min']." ";
			break;
		case 2:
			$query = " $field_value > ".$filter['value']." ";
			break;
		case 3:
			$query = " $field_value = ".$filter['value']." ";
			break;
	}
	return $query;
}

function get_name_filter($filter, $unit = ""){
	global $db,$prefix;
	switch($filter['type']){
		case 0:
			{$name = " < ".number_format($filter['value'],0,'','.')." $unit";
			//$name = dsprice($name);
			}
			break;
		case 1:
			$arr = unserialize($filter['value']);
		    $name = number_format($arr['min'],0,'','.')." $unit - ".number_format($arr['max'],0,'','.')." $unit";
			
			
			break;
		case 2:
			{$name = " > ".number_format($filter['value'],0,'','.')." $unit";
			//$name = dsprice($name);
			}
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

?>
