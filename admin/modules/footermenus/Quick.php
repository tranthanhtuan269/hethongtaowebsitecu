<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$v = intval($_POST['v']);
$mid = $_POST['mid'];
$poz = $_POST['poz'];

if ($v == 1) {
	for($i =0; $i < sizeof($mid); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_footermenus SET active='0' WHERE mid='$mid[$i]'");
	}
}

if ($v == 2) {
	for($i =0; $i < sizeof($mid); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_footermenus SET active='1' WHERE mid='$mid[$i]'");
	}
}

if ($v == 3) {
	foreach ($poz as $midx => $weight) {
		$midx = intval($midx);
		$weight = intval($weight);
		$db->sql_query_simple("UPDATE ".$prefix."_footermenus SET weight='$weight' WHERE mid='$midx'");
	}
	fixweight_mn();
}
if ($v == 4) {
    for($i =0; $i < sizeof($mid); $i ++) {
        // $db->sql_query
        die("DELETE FROM ".$prefix."_footermenus WHERE mid='$mid[$i]'");
        // deletecat($mid[$i]);
    }
    truncate_table("footermenus");
    fixweight_mn();
}
Header("Location: modules.php?f=".$adm_modname."");

?>