<?php

if (!defined('CMS_SYSTEM')) die('Stop!!!');

global $db, $analytics;

function footmsg() 
{
	global $start_time, $db, $prefix, $currentlang,$urlsite;
	 
	list($footmsg) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT content FROM ".$prefix."_gentext WHERE textname='footmsg' AND alanguage='$currentlang'"));
	echo "$footmsg ";
	 
}


function site_address() 
{
	global $db, $prefix, $currentlang;
	list($site_address) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT content FROM ".$prefix."_gentext WHERE textname='address' AND alanguage='$currentlang'"));
	echo "$site_address";
}
if (!$load_hf) 
{
	themefooter();

}

$nohf = 1;
$db->sql_close();

?>
</body>