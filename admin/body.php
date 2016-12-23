<?php
define('CMS_ADMIN', true);
require_once("../config.php");
require_once("language/$currentlang/main.php");

if (defined('iS_ADMIN')) {
	include_once("page_header.php");

    ?>
    <div id="page-wrapper">
    <div class="main-page">
        <?php
        echo "<table border=\"0\" width=\"99%\" align=\"center\" cellspacing=\"0\" cellpadding=\"5\" class=\"tableborder\">\n";
        echo "<tr><td class=\"header\" colspan=\"2\">"._BASEINFO."</td></tr>";
        echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\">"._SITENAME.":</b></td><td class=\"row2\">$sitename</td></tr>\n";
        echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._STARTDATE.":</b></td><td class=\"row2\">$date_start</td></tr>\n";
        if(defined('iS_RADMIN')) {
            $numsadmin = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_admin"));
            $numsadmin1 = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_admin WHERE permission='1'"));
            $numsadmin2 = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_admin WHERE permission='0'"));
            echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._ADMINSITE.":</b></td><td class=\"row2\">$numsadmin</td></tr>\n";
            echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._ADMINSITE1.":</b></td><td class=\"row2\">$numsadmin1</td></tr>\n";
            echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._ADMINSITE2.":</b></td><td class=\"row2\">$numsadmin2</td></tr>\n";
        }
        echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._DEFAULTLANG.":</b></td><td class=\"row2\">$language</td></tr>\n";
        $totalnews = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_news"));
        echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._TOTALNEWS.":</b></td><td class=\"row2\">$totalnews</td></tr>\n";
        $totaladv = $db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_advertise"));
        echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._TOTALADV.":</b></td><td class=\"row2\">$totaladv</td></tr>\n";
        list($totalhits) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT hits FROM ".$prefix."_stats"));
        echo "<tr><td width=\"50%\" class=\"row1\" align=\"right\"><b>"._TOTALHITS.":</b></td><td class=\"row2\">$totalhits</td></tr>\n";
        echo "</table>";

        ?>
    </div>
    </div>
    <?php
	

	include_once("page_footer.php");
}else{
	header("Location: login.php");
}
?>