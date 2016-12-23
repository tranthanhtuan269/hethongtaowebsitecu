<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}

function fixweight_cat() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query_simple("SELECT catid, weight FROM ".$prefix."_news_cat WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow_simple($result)) {
	    $catid = $row['catid'];
		$weight++;
	    $catid = intval($catid);
		$db->sql_query_simple("UPDATE ".$prefix."_news_cat SET weight='$weight' WHERE catid='$catid'");
    }
}

function fixcount_cat() {
	global $prefix, $db;
	 $result = $db->sql_query_simple("SELECT catid, counts FROM ".$prefix."_news_cat");
	 $i =0;
	 while (list($catid, $counts) = $db->sql_fetchrow_simple($result)) {
	 	$numsnew = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_news WHERE catid=$catid"));
	 	if($counts != $numsnew) {
	 		$db->sql_query_simple("UPDATE ".$prefix."_news_cat SET counts=$numsnew WHERE catid=$catid");
	 	}
	 	$i++;
	 }
}

function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_news_cat WHERE parent='$catid' AND catid!='$catseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2) = $db->sql_fetchrow_simple($result)) {
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
?>