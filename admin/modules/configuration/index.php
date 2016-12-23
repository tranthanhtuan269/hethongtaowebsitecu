<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access.");
?>
    <style>
        @media screen and (max-width: 400px){
            #side-menu {
                max-height: 300px;
                overflow-y: scroll;
            }
            .main-content form:nth-child(1) .table-bordered td{
                width: 100%;
                display: block;
            }
            .main-content form:nth-child(1) .table-bordered td:nth-child(1){
                text-align: left;
            }

        }

    </style>
<?php
if(defined('iS_RADMIN')) {

	$result = $db->sql_query_simple("SELECT content FROM ".$prefix."_gentext WHERE textname='footmsg' AND alanguage='$currentlang'");
	list($footmsg) = $db->sql_fetchrow_simple($result);

	$seld1 = $seld2 = $seld3 = $seld4 = $seld5 = $seld6 = $seld7 = $seld8 = $seld9 = $seld10 = $seld11 = $seld12 = $mod_seo_1 = $mod_seo_0= "";
	if(isset($_POST['submit']) && $_POST['submit'] !="") {
		$content = strip_tags($content, '<b><a><i><u><strong><font><em><br>');
		$content = $escape_mysql_string($_POST['footmsg']);
		if($db->sql_numrows($result) > 0) {
			$db->sql_query_simple("UPDATE ".$prefix."_gentext SET content='$content' WHERE textname='footmsg' AND alanguage='$currentlang'");
		}else{
			$db->sql_query_simple("INSERT INTO ".$prefix."_gentext (textname, content, alanguage) VALUES ('footmsg', '$content', '$currentlang')");
		}
		$charlist = "\\'";
		$config1 = addcslashes($_POST['config1'], $charlist);
		$config2 = addcslashes($_POST['config2'], $charlist);
		$config4 = addcslashes($_POST['config4'], $charlist);
		$config40 = addcslashes($_POST['config40'], $charlist);
		$config5 = addcslashes($_POST['config5'], $charlist);
		$config6 = addcslashes($_POST['config6'], $charlist);
		$config7 = addcslashes($_POST['config7'], $charlist);
		$config8 = addcslashes($_POST['config8'], $charlist);
		$config9 = intval($_POST['config9']);
		$config10 = intval($_POST['config10']);
		$config11 = addcslashes($_POST['config11'], $charlist);
		$config12 = addcslashes($_POST['config12'], $charlist);
		$config13 = intval($_POST['config13']);
		$config14 = addcslashes($_POST['config14'], $charlist);
		$config15 = addcslashes($_POST['config15'], $charlist);
		$config16 = intval($_POST['config16']);
		$config17 = addcslashes($_POST['config17'], $charlist);
		$config18 = intval($_POST['config18']);
		$config22 = intval($_POST['config22']);
		$config23 = intval($_POST['config23']);
		$config24 = intval($_POST['config24']);
		$config25 = addcslashes(nl2br($_POST['config25']), $charlist);
		$config26 = intval($_POST['config26']);
		$config27 = intval($_POST['config27']);
		$config28 = addcslashes($_POST['config28'], $charlist);
		$config29 = addcslashes(trim(stripslashes(strip_tags($_POST['config29']))), $charlist);
		$config30 = addcslashes(trim(stripslashes(strip_tags($_POST['config30']))), $charlist);
		$config32 = intval($_POST['config32']);
		$config33 = intval($_POST['config33']);
		$config34 = addcslashes($_POST['config34'], $charlist);
		$config340 = addcslashes($_POST['config340'], $charlist);
		$config342 = addcslashes($_POST['config342'], $charlist);
		$config356 = addcslashes($_POST['config356'], $charlist);
		$config357 = addcslashes($_POST['config357'], $charlist);

		$mod_seo = intval($_POST['mod_seo']);
		$newExtAllow = addcslashes($_POST['extallow'], $charlist);
		$newMIMEAllow = addcslashes($_POST["mimeallow"], $charlist);
		
		$newArr = array($config1, $config4, $config40, $config50, $config51, $config11, $config12, $config25, $config34,$config340,$config341,$config342, $config356, $config357,$config343,$config344 );
		$newArr = str_replace(array('&', '"'), array('&amp;', '&quot;'), $newArr);
		list($config1, $config4, $config40, $config50, $config51, $config11, $config12, $config25, $config34,$config340, $config341,$config342,$config356, $config357,$config343,$config344) = $newArr;

		@chmod("../data/setting.php", 0777);
		@$file = fopen("../data/setting.php", "w");

		$content = "<?php\n\n";
		$content .= "if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) die('Stop!!!');\n\n";
		$content .= "\$sitename = '$config1';\n";
		$content .= "\$folder_site = '$config2';\n";
		$content .= "\$date_start = '$date_start';\n";
		$content .= "\$keywords_site = '$config4';\n";
		$content .= "\$description_site = '$config40';\n";
		$content .= "\$webmastertools = '$config50';\n";
		$content .= "\$analytics = '$config51';\n";
		$content .= "\$adminmail = '$config5';\n";
		$content .= "\$Default_Temp = '$config6';\n";
		$content .= "\$Home_Module = '$config7';\n";
		$content .= "\$language = '$config8';\n";
		$content .= "\$multilingual = $config9;\n";
		$content .= "\$eror_value = $config10;\n";
		$content .= "\$gzip_method = $config22;\n";
		$content .= "\$counteract = $config23;\n";
		$content .= "\$timecount = $config26;\n";
		$content .= "\$hourdiff = $config16;\n";
		$content .= "\$htg1 = '$config11';\n";
		$content .= "\$htg2 = '$config12';\n";
		$content .= "\$admin_fold = '$config17';\n";
		$content .= "\$mod_rewrite = 0;\n";
		$content .= "\$maxsize_up = $config13;\n";
		$content .= "\$path_upload = '$config14';\n";
		$content .= "\$path_upload_editor = '$config15';\n";
		$content .= "\$disable_site = $config24;\n";
		$content .= "\$disable_message = '$config25';\n";
		$content .= "\n";
		$content .= "\$security_tags = '$security_tags';\n";
		$content .= "\$security_url_get = $security_url_get;\n";
		$content .= "\$security_url_post = $security_url_post;\n";
		$content .= "\$security_cookies = $security_cookies;\n";
		$content .= "\$security_sessions = $security_sessions;\n";
		$content .= "\$security_files = '$security_files';\n";
		$content .= "\n";
		$content .= "\$smtp_mail = $config27;\n";
		$content .= "\$smtp_host = '$config28';\n";
		$content .= "\$smtp_port = ".intval($_POST["smtpport"]).";\n";
		$content .= "\$smtp_username = '$config29';\n";
		$content .= "\$smtp_password = '$config30';\n";
		$content .= "\$yim_support = '$config34';\n";
		$content .= "\$hotline = '$config358';\n";
		$content .= "\$fb = '$config340';\n";
		$content .= "\$gg = '$config343';\n";
		$content .= "\$tw = '$config344';\n";
		$content .= "\$timeof_up = '$config341';\n";
		$content .= "\$youtube = '$config342';\n";
		$content .= "\$itagram = '$config356';\n";
		$content .= "\$slogan1 = '$config357';\n";
		$content .= "\$ajax_active = $config32;\n";
		$content .= "\$rewrite_mod= $config33;\n";
		$content .= "\$mod_seo= $mod_seo;\n";
		$content .= "\n";
		$content .= "\$extAllow = '$extAllow';\n";
		$content .= "\$mimeAllow = '$mimeAllow';\n";
		$content .= "\n";
		$content .= "\$AllowableHTML = array('b'=>1,
	    	'i'=>1,
	    	'a'=>2,
		    'em'=>1,
		    'br'=>1,
	    	'strong'=>1,
		    'blockquote'=>1,
		    'tt'=>1,
	    	'li'=>1,
		    'ol'=>1,
		    'ul'=>1);\n";
		$content .= "\n";
		$content .= "?>";

		@fwrite($file, $content);
		@fclose($file);
		@chmod("../data/setting.php", 0604);
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, "Cau hinh Site");
		header("Location: modules.php?f=$adm_modname&bf");
		exit;
	}

	include("page_header.php");


    ?>
    <div id="page-wrapper">
    <div class="main-page">
        <?php
        echo "<form method=\"POST\" action=\"modules.php?f={$adm_modname}\"><table border=\"0\" align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" class=\"tableborder table table-bordered\">\n";
        echo "<tr><td class=\"header\" colspan=\"2\">"._CONFIG."</td></tr>";
        echo "<tr><td width=\"40%\" class=\"row1\"><b>"._SITENAME.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config1\" value=\"$sitename\" size=\"50\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._URL.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config2\" value=\"$folder_site\" size=\"50\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._STARTDATE.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config3\" value=\"$date_start\" size=\"20\" disabled></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._KEYWORDS.":</b></td><td class=\"row2\"><textarea rows=\"2\" cols=\"52\" name=\"config4\">$keywords_site</textarea></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._DESCRIPTION.":</b></td><td class=\"row2\"><textarea rows=\"2\" cols=\"52\" name=\"config40\">$description_site</textarea></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._WEBMASTER_TOOLS.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config50\" value=\"$webmastertools\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._ANALYTICS.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config51\" value=\"$analytics\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._ADMINMAIL.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config5\" value=\"$adminmail\" size=\"40\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>YIM Support:</b><br>Ngan cach bang dau phay (,) neu su dung nhieu hon 1 nick</td><td class=\"row2\"><input type=\"text\" name=\"config34\" value=\"$yim_support\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Hotline:</b></td><td class=\"row2\"><input type=\"text\" name=\"config358\" value=\"$hotline\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Fanpage Fb:</b></td><td class=\"row2\"><input type=\"text\" name=\"config340\" value=\"$fb\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Links Google+:</b></td><td class=\"row2\"><input type=\"text\" name=\"config343\" value=\"$gg\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Links Twiter:</b></td><td class=\"row2\"><input type=\"text\" name=\"config344\" value=\"$tw\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Links Pintest:</b></td><td class=\"row2\"><input type=\"text\" name=\"config341\" value=\"$timeof_up\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Links Youtube:</b></td><td class=\"row2\"><input type=\"text\" name=\"config342\" value=\"$youtube\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Links Itagram:</b></td><td class=\"row2\"><input type=\"text\" name=\"config356\" value=\"$itagram\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>Địa chỉ:</b></td><td class=\"row2\"><input type=\"text\" name=\"config357\" value=\"$slogan1\" size=\"70\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._DEFAULT_TEMP.":</b></td><td class=\"row2\"><select name=\"config6\">";
        $handle=opendir(RPATH.'templates');
        while ($file = readdir($handle)) {
            if (is_dir(RPATH."templates/$file") && ($file != '.') && ($file != '..')) {
                $themelist .= "$file ";
            }
        }
        closedir($handle);
        $themelist = explode(" ", $themelist);
        sort($themelist);
        for ($i=0; $i < sizeof($themelist); $i++) {
            if($themelist[$i]!="") {
                echo "<option value=\"$themelist[$i]\" ";
                if($themelist[$i]==$Default_Temp) echo "selected";
                echo ">$themelist[$i]\n";
            }
        }
        echo "</select></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._DEFAULT_MOD.":</b></td><td class=\"row2\"><select name=\"config7\">";
        $handle=opendir(RPATH."modules");
        while (false !== ($file = readdir($handle))) {
            if (is_dir(RPATH."modules/$file") && ($file != '.') && ($file != '..')) {
                $ModulFound = $file;
                $moduleslist .= "$ModulFound ";
            }
        }
        closedir($handle);
        $moduleslist = explode(" ", $moduleslist);
        sort($moduleslist);
        for ($i=0; $i < sizeof($moduleslist); $i++) {
            if($moduleslist[$i]!="") {
                echo "<option value=\"$moduleslist[$i]\" ";
                if($moduleslist[$i]==$Home_Module) echo "selected";
                echo ">".ucfirst($moduleslist[$i])."\n";
            }
        }
        echo "</select></td></tr>";
        echo "<tr><td class=\"row1\"><b>"._DEFAULT_LANG.":</b></td><td class=\"row2\"><select name=\"config8\">";
        $handle=opendir(RPATH."language");
        while (false !== ($file = readdir($handle))) {
            if (is_dir(RPATH."language/$file") && ($file != '.') && ($file != '..')) {
                if($language == $file) { $seldalang =" selected"; } else { $seldalang =""; }
                echo "<option value=\"$file\"$seldalang>$file</option>";
            }
        }
        closedir($handle);
        echo "</select></td></tr>";
        echo "<tr><td class=\"row1\"><b>"._MULTILIGUAL.":</b></td><td class=\"row2\">";
        switch($multilingual) {
            case 1: $seld1 =" checked"; break;
            case 0: $seld2 =" checked"; break;
        }
        echo "<input type=\"radio\" name=\"config9\" value=\"1\"$seld1> "._YES." <input type=\"radio\" name=\"config9\" value=\"0\"$seld2> "._NO."";
        echo "</td></tr>";
        echo "<tr><td class=\"row1\"><b>"._ERROR_SHOW.":</b></td><td class=\"row2\">";
        switch($eror_value) {
            case 1: $seld3 =" checked"; break;
            case 0: $seld4 =" checked"; break;
        }
        echo "<input type=\"radio\" name=\"config10\" value=\"1\"$seld3> "._YES." <input type=\"radio\" name=\"config10\" value=\"0\"$seld4> "._NO."";
        echo "</td></tr>";
        echo "<tr><td class=\"row1\"><b>"._GZIPACTIVE.":</b></td><td class=\"row2\">";
        switch($gzip_method) {
            case 1: $seld5 =" checked"; break;
            case 0: $seld6 =" checked"; break;
        }
        echo "<input type=\"radio\" name=\"config22\" value=\"1\"$seld5> "._YES." <input type=\"radio\" name=\"config22\" value=\"0\"$seld6> "._NO."";
        echo "</td></tr>";
        echo "<tr><td class=\"row1\"><b>"._COUNTERACT.":</b></td><td class=\"row2\">";
        switch($counteract) {
            case 1: $seld7 =" checked"; break;
            case 0: $seld8 =" checked"; break;
        }
        echo "<input type=\"radio\" name=\"config23\" value=\"1\"$seld7> "._YES." <input type=\"radio\" name=\"config23\" value=\"0\"$seld8> "._NO."";
        echo "</td></tr>";
        echo "<tr><td class=\"row1\"><b>Ajax active:</b></td><td class=\"row2\">";
        switch($ajax_active) {
            case 1: $seld9 =" checked"; break;
            case 0: $seld10 =" checked"; break;
        }
        echo "<input type=\"radio\" name=\"config32\" value=\"1\"$seld9> "._YES." <input type=\"radio\" name=\"config32\" value=\"0\"$seld10> "._NO."";
        echo "</td></tr>";
        echo "<tr><td class=\"row1\"><b>Rewrite Url active:</b></td><td class=\"row2\">";
        switch($rewrite_mod) {
            case 1: $seld11 =" checked"; break;
            case 0: $seld12 =" checked"; break;
        }
        echo "<input type=\"radio\" name=\"config33\" value=\"1\"$seld11> "._YES." <input type=\"radio\" name=\"config33\" value=\"0\"$seld12> "._NO."";
        echo "</td></tr>";
        //=== Mod SEO
        echo "<tr><td class=\"row1\"><b>"._MOD_SEO."</b></td><td class=\"row2\">";
        switch($mod_seo) {
            case 1: $mod_seo_1 =" checked"; break;
            case 0: $mod_seo_0 =" checked"; break;
        }
        echo "<input type=\"radio\" name=\"mod_seo\" value=\"1\"$mod_seo_1> "._YES." <input type=\"radio\" name=\"mod_seo\" value=\"0\"$mod_seo_0> "._NO."";
        echo "</td></tr>";
        //=== ====
        echo "<tr><td class=\"row1\"><b>"._TIMECOUNT.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config26\" value=\"$timecount\" size=\"10\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._HOURDIFF.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config16\" value=\"$hourdiff\" size=\"15\"> ("._TIMENOW.": ".ext_time(time(),2).")</td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._TYPETIME1.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config11\" value=\"$htg1\" size=\"15\"> (".ext_time(time(),1).")</td></tr>\n";


        /*echo "</table></fieldset></td><td valign=\"top\"><fieldset><legend>"._CONFIG_2."</legend><table border=\"0\" background=\"#f0f0f0\" height=\"100%\" align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">";*/
        echo "<tr><td class=\"row1\"><b>"._TYPETIME2.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config12\" value=\"$htg2\" size=\"15\"> (".ext_time(time(),2).")</td></tr>\n";
        $convertbyte = round($maxsize_up/1048576,1);
        echo "<tr><td class=\"row1\"><b>"._MAXSIZEUP.":</b> (bytes)</td><td class=\"row2\"><input type=\"text\" name=\"config13\" value=\"$maxsize_up\" size=\"15\"> (~$convertbyte MB)</td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._UPLOADPATH.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config14\" value=\"$path_upload\" size=\"20\"> (chmod 777 for unix)</td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._UPLOADPATH_EDITOR.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config15\" value=\"$path_upload_editor\" size=\"20\"> (chmod 777 for unix)</td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._ADMINFOLD.":</b></td><td class=\"row2\"><input type=\"text\" name=\"config17\" value=\"$admin_fold\" size=\"20\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._SMTPMAIL.":</b></td><td class=\"row3\">";
        if($smtp_mail == 1) {
            echo "<input type=\"radio\" name=\"config27\" value=\"1\" checked> "._YES." <input type=\"radio\" name=\"config27\" value=\"0\"> "._NO."";
        } else {
            echo "<input type=\"radio\" name=\"config27\" value=\"1\"> "._YES." <input type=\"radio\" name=\"config27\" value=\"0\" checked> "._NO."";
        }
        echo "</td></tr>";
        echo "<tr><td class=\"row1\"><b>"._SMTPMAIL1.":</b></td><td class=\"row3\"><input type=\"text\" name=\"config28\" value=\"$smtp_host\" size=\"30\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._SMTPPORT.":</b></td><td class=\"row3\"><input type=\"text\" name=\"smtpport\" value=\"$smtp_port\" size=\"15\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._SMTPMAIL2.":</b></td><td class=\"row3\"><input type=\"text\" name=\"config29\" value=\"$smtp_username\" size=\"30\"></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._SMTPMAIL3.":</b></td><td class=\"row3\"><input type=\"password\" name=\"config30\" value=\"$smtp_password\" size=\"30\"></td></tr>\n";

        echo "<tr><td class=\"row1\"><b>"._EXTALLOW.":</b><br>"._COMMASEPARATOR."</td><td class=\"row3\"><input type=\"text\" name=\"extallow\" value=\"$extAllow\" size=\"70\" disabled></td></tr>\n";
        echo "<tr><td class=\"row1\"><b>"._MIMEALLOW.":</b><br>"._COMMASEPARATOR."</td><td class=\"row3\"><input type=\"text\" name=\"mimeallow\" value=\"$mimeAllow\" size=\"70\" disabled></td></tr>\n";
        echo "<tr><td class=\"row1\" valign=\"top\"><b>"._FOOTMSG.":</b></td></td><td class=\"row3\">\n";
        // echo "<textarea cols=\"70\" rows=\"5\" name=\"footmsg\">$footmsg</textarea>\n";
        editor("footmsg", $footmsg,"",200);
        echo "</td></tr>\n";
        echo "<tr><td valign=\"top\" class=\"row1\">"._CLOSESITE.":<br><br>";
        if ($disable_site==1) {
            echo "<input type=\"radio\" name=\"config24\" value=\"1\" checked>"._YES." &nbsp;
        <input type=\"radio\" name=\"config24\" value=\"0\">"._NO."";
        } else {
            echo "<input type=\"radio\" name=\"config24\" value=\"1\">"._YES." &nbsp;
        <input type=\"radio\" name=\"config24\" value=\"0\" checked>"._NO."";
        }
        echo"<br><br>"._CLOSESITE2."</td><td valign=\"top\" class=\"row2\">";
        $disable_message = str_replace("<br />", "\r\n", html_entity_decode($disable_message));
        echo""._EXPLMESSAGE.":<br><br><textarea wrap=\"virtual\" cols=\"70\" rows=\"5\" name=\"config25\">$disable_message</textarea>"
            ."\n";
        echo "</td></tr>";
        echo "<tr class=\"row3\"><td colspan=\"2\" align=\"center\" class=\"row2\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>\n";
        echo "</table></form><br>";
        ?>
    </div>
    </div>
    <?php
	

	include_once("page_footer.php");
}else{
	header("Location: login.php");
}

?>