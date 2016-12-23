<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

if(isset($_POST['submit'])) {
	$config1 = intval($_POST['config1']);
	$config2 = intval($_POST['config2']);
	$config3 = intval($_POST['config3']);

	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0666);
	@$file = fopen(RPATH.DATAFOLD."/config_".$adm_modname.".php", "w");
	$content = "<?php\n\n";
	$content .= "if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) {\n";
	$content .= "die('Stop!!!');\n";
	$content .= "}\n";
	$content .= "\n";
	$content .= "\$pic_per_page = $config1;\n";
	$content .= "\$pic_cat_size = $config2;\n";	
	$content .= "\$pic_size = $config3;\n";	
	@$writefile = fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0644);
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _NCONFIG);
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
}

include_once("page_header.php");

echo "<br><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\">";
echo "<table align=\"center\" width=\"35%\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._NCONFIG."</td></tr>";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG1."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config1\" value=\"$pic_per_page\" size=\"5\"></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG2."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config2\" value=\"$pic_cat_size\" size=\"10\"> (pixels)</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._NCONFIG3."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"config3\" value=\"$pic_size\" size=\"10\"> (pixels)</td>\n";
echo "</tr>\n";

echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>