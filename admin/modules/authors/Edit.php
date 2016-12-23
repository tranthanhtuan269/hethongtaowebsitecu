<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
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
$adm_pagetitle2 = _EDITADMIN;

$origAcc = isset($_GET['acc']) ? $_GET['acc'] : $_POST['acc'];
$acc = $escape_mysql_string(trim($origAcc));

$result = $db->sql_query_simple("SELECT adname, email, pwd, permission, mods, menus FROM ".$prefix."_admin WHERE adacc='$acc'");
if(empty($acc) || $db->sql_numrows($result) != 1) {
	header("Location: modules.php?f=$adm_modname"); exit;
}

$ds_acc = $ds_adname = $ds_email = $ds_pass = "none";

$stopnick = _ERROR1;

list($adname, $email, $pwdold, $permission, $mods, $menus) = $db->sql_fetchrow_simple($result);
$adacc = $acc;
$adname_old = $adname;
$auth_modules = @explode("|",$mods);
$auth_menus = @explode("|",$menus);
include("page_header.php");

$err_mail = $err_pass = $password = "";
//$permission ="";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$adacc = $escape_mysql_string(trim($_POST['adacc']));
	$adname = $escape_mysql_string(trim($_POST['adname']));
	$email = $escape_mysql_string(trim($_POST['email']));
	if(!defined('iS_SADMIN'))
	{
		$auth_modules = $_POST['auth_modules'];
		$auth_menus = $_POST['auth_menus'];
		$permission = intval($_POST['permission']);
	}
	$password = $_POST['password'];

	$stopnick = AccCheck($adacc, $acc);
	if($stopnick) {
		$err_acc = "<font color=\"red\">$stopnick</font><br>";
		$err = 1;
	}


	if ($adname_old != "Root") {
		if(empty($adname) || (!empty($adname) && $adname == "Root")) {
			$adname ="";
			$err_title = "<font color=\"red\">"._ERROR2."</font><br>";
			$err = 1;
		}
	}

	if(!is_email($email)) {
		$ds_email = "";
		$err = 1;
	}

	if ($db->sql_numrows($db->sql_query_simple("SELECT email FROM ".$prefix."_admin WHERE email='$email' AND adacc!='$acc'")) > 0) {
		$err_mail = "<font color=\"red\">"._ERROR3_1."</font><br>";
		$err = 1;
	}

	if($password !="" && (strlen($password) < 3 || strlen($password) > 10 || strrpos($password,' ') > 0)) {
		$err_pass ="<font color=\"red\">"._ERROR4."</font><br>";
		$err = 1;
	}

	if($adname_old != "Root") {
		if($permission == 1) {
			$auth_modules ="";
			$modlist = "";
		}else{
			$permission = 0;
			$modlist = @implode("|",$auth_modules);
		}
	}

	$menulist = @implode("|",$auth_menus);

	if(!$err) {
		if($password !="") {
			$password = md5($password);
		}else {
			$password = $pwdold;
		}
		if($adname_old =="Root") {
			$db->sql_query_simple("UPDATE ".$prefix."_admin SET adacc='$adacc', pwd='$password', email='$email' WHERE adacc='$acc'");
		}else{
			$db->sql_query_simple("UPDATE ".$prefix."_admin SET adacc='$adacc', adname='$adname', pwd='$password', email='$email', permission='$permission', mods='$modlist', menus='$menulist' WHERE adacc='$acc'");
		}
		updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _EDIT);
		header("Location: modules.php?f=$adm_modname"); exit;
	}

}

if($adname_old == "Root") {$css =" disabled"; }else{ $css =""; }
ajaxload_content();

echo "<form action=\"modules.php?f=$adm_modname&do=$do&acc=$origAcc\" method=\"POST\" onSubmit=\"return checkEditAuthor(this);\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._EDITADMIN."</td></tr>";
echo "<tr>\n";
echo "<td width=\"20%\" align=\"right\" class=\"row1\"><b>"._ATACC."</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_acc, "adacc", $stopnick)."<input type=\"text\" name=\"adacc\" value=\"$adacc\" size=\"30\"></td>\n";
echo "</tr>\n";
if($adname_old != "Root") {
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
	echo "<td class=\"row3\">".errorMess($ds_adname, "adname", _ERROR2)."<input type=\"text\" name=\"adname\" value=\"$adname\" size=\"30\"></td>\n";
	echo "</tr>\n";
}else{
	echo "<tr>\n";
	echo "<td align=\"right\" class=\"row1\"><b>"._ATNAME."</b></td>\n";
	echo "<td class=\"row3\"><input type=\"text\" name=\"adname\" value=\"$adname_old\" size=\"30\" disabled></td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\">".errorMess($ds_email, "email", _ERROR3)."$err_mail<input type=\"text\" name=\"email\" value=\"$email\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\" valign=\"top\"><b>"._MENUPERMISSION."</b></td>\n";
echo "<td class=\"row3\">";
echo "<table border=\"0\"><tr>";
/*$r=0;
$resultmenu = $db->sql_query_simple("SELECT * FROM ".$prefix."_admin_menu ORDER BY weight");
	while(list($mid, $file_menu, $weight, $action) = $db->sql_fetchrow_simple($resultmenu)) 
	{
		
		if (file_exists("menus/adm_".$file_menu.".php") && (defined('iS_RADMIN') || (checkPermAdm($file_menu) && 		!in_array($file_menu,$not_accept_mod)))) 
		{
			include("menus/adm_".$file_menu.".php");
			$seldmenu="";
			if($menu_main != "") 
			{
				$menulist = @explode("|", $menus);
				$checked="";
				if(@in_array($file_menu,$menulist[$r])) 
				//if($file_menu==$menulist[$r])
				{
					$seldmenu="checked";
				}
					echo "<td style=\"padding-right: 30px\"><input type=\"checkbox\" name=\"auth_menus[]\" value=\"$file_menu\"$seldmenu> $menu_main</td>";
				
			}
		}
		if($r == 3) { $r =0; echo "</tr>"; }
		$r++;
	}*/
$m =0;
for($l=0;$l < sizeof($listmenus);$l++) {
	//$title = str_replace("_", " ", $listmenus[$l]);
	//$xstitle = strtolower($listmenus[$l]);
	$seld ="";
	if(@in_array($listmenus[$l],$auth_menus)) {
		$seld =" checked";
	}
	//if(!@in_array($title,$listmods_noaccept)) {
		$m ++;
		echo "<td style=\"padding-right: 30px\"><input type=\"checkbox\" name=\"auth_menus[]\" value=\"$listmenus[$l]\"$seld$css> ".$listnamemenu[$l]."</td>";
	//}
	if($m == 3) { $m =0; echo "</tr>"; }
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
		echo "<td style=\"padding-right: 30px\"><input type=\"checkbox\" name=\"auth_modules[]\" value=\"$listmods[$l]\"$seld$css> ".$listmods_custom[$l]."</td>";
	}
	if($a == 3) { $a =0; echo "</tr>"; }
}
echo "</tr></table>";
echo "</td>\n";
echo "</tr>\n";
if(!defined('iS_SADMIN'))
{
	echo "<tr><td align=\"right\" class=\"row1\"><b>"._GENERALADM.":</b></td>";
	if($permission == 1) {$seld2 =" checked";} else { $seld2 =""; }
	echo "<td class=\"row3\"><input type=\"checkbox\" name=\"permission\" value=\"1\" size=\"40\"$seld2$css></td></tr>";
}
echo "<tr>\n";
echo "<td align=\"right\" class=\"row1\"><b>"._PASSWORD."</b></td>\n";
echo "<td class=\"row3\">$err_pass<input type=\"text\" name=\"password\" value=\"\" size=\"30\"></td>\n";
echo "</tr>\n";
echo "<tr><td class=\"row3\">&nbsp;</td><td class=\"row3\"><input type=\"hidden\" name=\"acc\" value=\"$acc\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._SAVECHANGES."\"> <input type=\"button\" value=\""._CANCEL."\" onclick=\"window.location='modules.php?f=$adm_modname'\"></td></tr>";
echo "</table></form>\n";

include_once("page_footer.php");
?>