<?php
if(!defined('CMS_ADMIN')) {
	die();
}

$v = intval($_POST['v']);
$mid = $_POST['mid'];
$poz = $_POST['poz'];

if ($v == 1) {
	for($i =0; $i < sizeof($mid); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_leftmenus SET active='0' WHERE mid='$mid[$i]'");
	}
}

if ($v == 2) {
	for($i =0; $i < sizeof($mid); $i ++) {
		$db->sql_query_simple("UPDATE ".$prefix."_leftmenus SET active='1' WHERE mid='$mid[$i]'");
	}
}

if ($v == 3) {
	foreach ($poz as $midx => $weight) {
		$midx = intval($midx);
		$weight = intval($weight);
		$db->sql_query_simple("UPDATE ".$prefix."_leftmenus SET weight='$weight' WHERE mid='$midx'");
	}
	fixweight_mn();
}
if ($v == 4) {
    for($i =0; $i < sizeof($mid); $i ++) {
        // $db->sql_query_simple("DELETE FROM ".$prefix."_mainmenus WHERE mid='$mid[$i]'");
        deletecat($mid[$i]);
    }
    truncate_table("leftmenus");
    fixweight_mn();
}
Header("Location: modules.php?f=".$adm_modname."");

?>