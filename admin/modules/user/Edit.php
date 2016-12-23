<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

include_once("page_header.php");
?>
<style>
    @media screen and (max-width: 550px){
        .main-page .table-bordered td{
            width: 100%;
            display: block;
        }
        .main-page td:nth-child(1){
            text-align: left;
        }

    }

</style>
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$email = $fullname = $address = $phone = $err_name = $err_email = $vip ='';
$title = $err = 0;

if (!isset($_POST['subup'])) {
	$db->sql_query_simple("SELECT email, title, fullname, address, phone FROM {$prefix}_user WHERE id=$id");
	list($email, $title, $fullname, $address, $phone) = $db->sql_fetchrow_simple();
}
else {
	$title = intval($_POST['title']);
	$fullname = htmlspecialchars($_POST['name'], ENT_QUOTES);
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
	$address = htmlspecialchars($_POST['address'], ENT_QUOTES);
	$phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);

	if (empty($fullname)) {
		$err = 1;
		$err_name = "<font color=\"red\">"._USER_ERROR_NAME."</font>";
	}

	if (empty($email)) {
		$err = 1;
		$err_email = "<font color=\"red\">"._USER_ERROR_EMAIL."</font>";
	}

	if (!$err) {
		$fullname = nospatags($fullname);
		$email = nospatags($email);
		$address = nospatags($address);
		$phone = nospatags($phone);
		if ($id != 0) {
			$query = "UPDATE {$prefix}_user SET title=$title, fullname='$fullname', email='$email', address='$address', phone='$phone'";
			if (!empty($_POST['password'])) $query .= ", pass='".Hash::sha256($_POST['password'])."'";
			$query .= " WHERE id=$id";
			$db->sql_query_simple($query);
		}
		else {
			$db->sql_query_simple("INSERT INTO {$prefix}_user VALUES (null, '$email', $title, '$fullname', '".Hash::sha256($_POST['password'])."', '$address', '$phone', NULL, NOW(), NULL, NULL, 0, NULL)");
		}
		header("Location: modules.php?f=$adm_modname");
	}
}

echo "<form method=\"POST\" action=\"modules.php?f=$adm_modname&do=$do&id=$id\">\n";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"2\" class=\"header\">"._USER_EDIT_ADD."</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_TITLE.":</td>\n";
echo "<td class=\"row2\"><select name=\"title\" id=\"title\">\n";
$mrSelected = $mrsSelected = '';
if ($title == 0) $mrSelected = ' selected="selected"';
else $mrsSelected = ' selected="selected"';
echo "<option value=\"0\"$mrSelected>"._USER_MR."</option>\n";
echo "<option value=\"1\"$mrsSelected>"._USER_MRS."</option>\n";
echo "</select></td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_FULLNAME.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"name\" id=\"name\" value=\"$fullname\" size=\"50\" /><br />$err_name</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_EMAIL.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"email\" id=\"email\" value=\"$email\" size=\"50\" /><br />$err_email</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">&nbsp;"._USER_PASSWORD.":</td>\n";
echo "<td class=\"row2\"><input type=\"password\" name=\"password\" id=\"password\" size=\"50\" /><br />";
if ($id != 0) echo _USER_LEAVE_BLANK_TO_KEEP_AS_IS;
echo "</td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">"._USER_ADDRESS.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"address\" id=\"address\" value=\"$address\" size=\"50\" /></td></tr>\n";
echo "<tr><td class=\"row1\" width=\"20%\">"._USER_PHONE.":</td>\n";
echo "<td class=\"row2\"><input type=\"text\" name=\"phone\" id=\"phone\" value=\"$phone\" size=\"50\" /></td></tr>\n";
echo "<input type=\"hidden\" value=\"1\" name=\"subup\" />";
$btnName = ($id == 0) ? _ADD : _SAVECHANGES;
echo "<tr><td class=\"row2\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"$btnName\" /></td></tr>\n";
echo "</table></form>";

include_once("page_footer.php");
?>
