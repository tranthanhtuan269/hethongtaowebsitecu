<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

if(isset($_POST['submit'])) {
	$intro_thumbsize = intval($_POST['thumbsize']);
	if (($_POST['pic_align'] == "left") || ($_POST['pic_align'] == "right")) $pic_align = $_POST['pic_align'];
	else $pic_align = "left";
	if (($_POST['cat_pic_align'] == "left") || ($_POST['cat_pic_align'] == "right")) $cat_pic_align = $_POST['cat_pic_align'];
	else $cat_pic_align = "left";
	if (($_POST['intro_list_align'] == "left") || ($_POST['intro_list_align'] == "right")) $intro_list_align = $_POST['intro_list_align'];
	else $intro_list_align = "left";

	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0666);
	$file = @fopen(RPATH.DATAFOLD."/config_".$adm_modname.".php", "w");
$content = <<<EOT
<?php
if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) die('Stop!!!');

\$intro_thumbsize = $intro_thumbsize;
\$pic_align = "$pic_align";
\$cat_pic_align = "$cat_pic_align";
\$intro_list_align = "$intro_list_align";
?>
EOT;
	@fwrite($file, $content);
	@fclose($file);
	@chmod(RPATH.DATAFOLD."/config_".$adm_modname.".php", 0644);
	updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _INTRO_CONFIG);
	header("Location: modules.php?f=$adm_modname&do=$do&bf");
}

include_once("page_header.php");

echo "<br><form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\"><table align=\"center\" border=\"0\" width=\"\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._CONFIG."</td></tr>";
echo "<tr>\n";
echo "<td width=\"50%\" align=\"right\" class=\"row1\"><b>"._INTRO_CONFIG_THUMB_SIZE."</b></td>\n";
echo "<td class=\"row3\"><input type=\"text\" name=\"thumbsize\" value=\"$intro_thumbsize\" size=\"10\"> (pixels)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INTRO_CONFIG_PIC_ALIGN."</b></td>\n";
echo "<td class=\"row3\"><select name=\"pic_align\">";
$pozar = array("left","right");
$poz_nam = array(_LEFT,_RIGHT);
for($i = 0; $i < 2; $i ++) {
	$seld ="";
	if($pozar[$i] == $pic_align) { $seld =" selected=\"selected\""; }
	echo "<option value=\"$pozar[$i]\"$seld>$poz_nam[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INTRO_CONFIG_CAT_PIC_ALIGN."</b></td>\n";
echo "<td class=\"row3\"><select name=\"cat_pic_align\">";
$pozar = array("left","right");
$poz_nam = array(_LEFT,_RIGHT);
for($i = 0; $i < 2; $i ++) {
	$seld ="";
	if($pozar[$i] == $cat_pic_align) { $seld =" selected=\"selected\""; }
	echo "<option value=\"$pozar[$i]\"$seld>$poz_nam[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._INTRO_CONFIG_INTRO_LIST_ALIGN."</b></td>\n";
echo "<td class=\"row3\"><select name=\"intro_list_align\">";
$pozar = array("left","right");
$poz_nam = array(_LEFT,_RIGHT);
for($i = 0; $i < 2; $i ++) {
	$seld ="";
	if($pozar[$i] == $intro_list_align) { $seld =" selected=\"selected\""; }
	echo "<option value=\"$pozar[$i]\"$seld>$poz_nam[$i]</option>";
}
echo "</select></td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></tr>";
echo "</table></form>";

include_once("page_footer.php");
?>