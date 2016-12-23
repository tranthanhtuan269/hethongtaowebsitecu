<?php
if (!defined('CMS_SYSTEM')) die();

function page_tilecat($catid, $parent, $catname) {
	global $db, $prefix, $module_name,$urlsite;
	$titlecat ="";
	$result = $db->sql_query_simple("SELECT catid, parent, title FROM ".$prefix."_news_cat WHERE catid='$parent'");
	if($db->sql_numrows($result) > 0) {
		list($catid2, $parent2, $title2) = $db->sql_fetchrow_simple($result);
		
		if($parent2 != 0) {
			$titlecat = page_tilecat($catid2, $parent2, $titlecat);
			$titlecat .= "<li><a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid2")."\" >$title2</a></li>";
		}
	}
	else
	{

	}
	$titlecat .= " <li><a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\">$catname</a></li>";

	return $titlecat;
}
function check_module($parent) {
	global $db, $prefix, $module_name;
	$titlecat ="";
	$result = $db->sql_query_simple("SELECT permalink, parent FROM ".$prefix."_news_cat WHERE catid='$parent'");
	if($db->sql_numrows($result) > 0) {
		list($permalink2,$parent2) = $db->sql_fetchrow_simple($result);
		$titlecat=$permalink2;
		if($parent2 != 0) {
			$titlecat = page_tilecat($parent2);
		}
	}
	return $titlecat;
}
function showcatidgoc($catid, $parent, $titlecat) {
    global $db, $prefix, $module_name,$urlsite;
    $titlecat ="";
    if ($parent != 0) {
        $result = $db->sql_query_simple("SELECT catid, parent FROM ".$prefix."_news_cat WHERE catid='$parent'");
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
?>