<?php
if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
function subcat($mid, $text="", $mcheck="", $mseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query_simple("SELECT mid, title FROM ".$prefix."_footermenus WHERE parentid='$mid' AND mid!='$mseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($mid, $title) = $db->sql_fetchrow_simple($result)) {
			if($mid) {
				if($mid == $mcheck) {
					$seld = " selected";
				}else{
					$seld ="";
				}
			}
			$treeTemp .= "<option value=\"$mid\"$seld>$text-- $title</option>";
			$treeTemp .= subcat($mid,$text, $mcheck, $mseld);
		}
	}
	return $treeTemp;
}
function fixweight_mn() {
	global $db, $prefix, $currentlang;
    $result = $db->sql_query_simple("SELECT mid, weight FROM ".$prefix."_footermenus WHERE alanguage='$currentlang' order by weight ASC");
    $weight = 0;
    while($row = $db->sql_fetchrow_simple($result)) {
	    $mid = $row['mid'];
		$weight++;
	    $mid = intval($mid);
		$db->sql_query_simple("UPDATE ".$prefix."_footermenus SET weight='$weight' WHERE mid='$mid'");
    }
}
function deletecat($mid) {
    global $db, $prefix;
    $treeTemp ="";
    $result = $db->sql_query_simple("SELECT mid, title FROM ".$prefix."_footermenus WHERE parentid='$mid'");
    if($db->sql_numrows($result) > 0 ) {
        $db->sql_query_simple("DELETE FROM ".$prefix."_footermenus WHERE mid='$mid'");
        while(list($mid, $title) = $db->sql_fetchrow_simple($result)) {
            $db->sql_query_simple("DELETE FROM ".$prefix."_footermenus WHERE mid='$mid'");
            deletecat($mid);
        }
    }
    else
    {
        $db->sql_query_simple("DELETE FROM ".$prefix."_footermenus WHERE mid='$mid'");
    }
}
?>