<?php

if(!defined('CMS_ADMIN')) {
	die("Illegal File Access");
}
?>
    <style>
        @media screen and (max-width: 400px){
            .main-page .table-bordered td{
                width: 100%;
                display: block;
            }
            .main-page .table-bordered td:nth-child(1){
                text-align: left;
            }

        }

    </style>
<?php
$adm_pagetitle2 = _ADDAUTHOR;

$adacc = $adname = $err_mail = $email = $permission = $err_pass = $password = $acc = "";
$ds_acc = $ds_adname = $ds_email = $ds_pass = "none";

$stopnick = _ERROR1;

include("page_header.php");

if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$adacc = trim(stripslashes(resString($_POST['adacc'])));
	$adname = trim(stripslashes(resString($_POST['adname'])));
	$email = trim(stripslashes(resString($_POST['email'])));
	if(!defined('iS_SADMIN'))
	{
		$auth_modules = $_POST['auth_modules'];
		$auth_menus = $_POST['auth_menus'];
		$permission = intval($_POST['permission']);
	}
	$password = $_POST['password'];

	$stopick ="";
	$stopnick = AccCheck($adacc,$acc);
	if($stopnick) {
		$ds_acc = "";
		$err = 1;
	}


	if(empty($adname) || (!empty($adname) && $adname == "Root")) {
		$ds_adname = "";
		$err = 1;
	}

	if(!is_email($email)) {
		$ds_email = "";
		$err = 1;
	}

	if ($db->sql_numrows($db->sql_query_simple("SELECT email FROM ".$prefix."_admin WHERE email='$email'")) > 0) {
		$err_mail = "<font color=\"red\">"._ERROR3_1."</font><br>";
		$err = 1;
	}

	if($password =="") {
		$ds_pass ="";
		$err = 1;
	}

	if($password !="" && (strlen($password) < 3 || strlen($password) > 10 || strrpos($password,' ') > 0)) {
		$err_pass ="<font color=\"red\">"._ERROR4."</font><br>";
		$err = 1;
	}

	if($permission == 1) {
		$auth_modules ="";
		$modlist = "";
	}else{
		$permission = 0;
		$modlist = @implode("|",$auth_modules);
	}
	
	$menulist = @implode("|",$auth_menus);

	if(!$err) {
		$password = md5($password);

		$db->sql_query_simple("INSERT INTO ".$prefix."_admin (adacc, adname, email, pwd, permission, mods, menus) VALUES ('$adacc', '$adname', '$email', '$password', '$permission', '$modlist', '$menulist')");
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _ADD);
		header("Location: modules.php?f=".$adm_modname);
	}

}
ajaxload_content();

echo "<form action=\"modules.php?f=$adm_modname&do=$do\" method=\"POST\" onSubmit=\"return checkAddAuthor(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._ADDAUTHOR."</td></tr>";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>"._ATACC."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_acc, "adacc", $stopnick)."<input type=\"text\" name=\"adacc\" value=\"$adacc\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_adname, "adname", _ERROR2)."<input type=\"text\" name=\"adname\" value=\"$adname\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_email, "email", _ERROR3)."$err_mail<input type=\"text\" name=\"email\" value=\"$email\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._MENUPERMISSION."</b></td>\n";
echo "<td class=\"row3\">";
echo "<table border=\"0\"><tr>";
$r=0;
$resultmenu = $db->sql_query_simple("SELECT * FROM ".$prefix."_admin_menu ORDER BY weight");
	while(list($mid, $file_menu, $weight, $action) = $db->sql_fetchrow_simple($resultmenu)) 
	{
		$r++;
		if (file_exists("menus/adm_".$file_menu.".php") && (defined('iS_RADMIN') || (checkPermAdm($file_menu) && 		!in_array($file_menu,$not_accept_mod)))) 
		{
			include("menus/adm_".$file_menu.".php");
			$seldmenu="";
			if($menu_main != "") 
			{
				echo "<td style=\"padding-right: 30px\"><input type=\"checkbox\" name=\"auth_menus[]\" value=\"$file_menu\"> $menu_main</td>";
			}
			
		}
		if($r == 3) { $r =0; echo "</tr><tr>"; }
	}
echo "</tr></table>";
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._ATPERMISSION."</b></td>\n";
echo "<td class=\"row3\">";
echo "<table border=\"0\"><tr>";
$a =0;
for($l=0;$l < sizeof($listmods);$l++) {
	$title = str_replace("_", " ", $listmods[$l]);
	$xstitle = strtolower($listmods[$l]);
	$seld ="";
	if(@in_array($listmods[$l],$auth_modules)) {
		$seld =" checked";
	}

	if(!@in_array($title,$listmods_noaccept)) {
		$a ++;
		echo "<td style=\"padding-right: 30px\"><input type=\"checkbox\" name=\"auth_modules[]\" value=\"$listmods[$l]\"$seld> ".$listmods_custom[$l]."</td>";
	}
	if($a == 3) { $a =0; echo "</tr><tr>"; }
}
echo "</tr></table>";
echo "</td>\n";
echo "</tr>\n";
if(!defined('iS_SADMIN'))
{
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._GENERALADM.":</b></td>";
	if($permission == 1) {$seld2 =" checked";} else { $seld2 =""; }
	echo "<td class=\"row3\"><input type=\"checkbox\" name=\"permission\" value=\"1\" size=\"40\"$seld2></td></tr>";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_pass, "password", _ERROR4)."$err_pass<input type=\"text\" name=\"password\" value=\"$password\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"hidden\" name=\"acc\" value=\"$acc\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._ADD."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form>\n";

include_once("page_footer.php");
?>