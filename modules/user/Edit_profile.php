<?php
if (!defined('CMS_SYSTEM')) die();

if (!defined('iS_USER') || !isset($userInfo)) header("Location: index.php?f=user&do=login");

$page_title = _USER_EDIT_PROFILE;

include_once('header.php');
require_once('WebUser.class.php');

$user = new WebUser($userInfo['id']);
// $path_upload_img = "$path_upload/user";
if (isset($_POST["submit_up"])) {
	//$captcha = new CAPTCHA(6);
	//if ($captcha->isValid($_POST['captcha'])) {
		// $delpic = isset($_POST['delpic']) ? $_POST['delpic']:0;
		// if($delpic == 1) {
		// 	@unlink("../$path_upload_img/$images");
		// 	$images = "";
		// }
		// $permalink1=url_optimization(trim($_POST['name']));
		// if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
		// 	$path_upload_img = "$path_upload/user";
		// 	$upload = new Upload("userfile", $path_upload_img, $maxsize_up,$permalink1);
		// 	$images_up = $upload->send();
		// }
		// else
		// {
		// 	$images_up = "";
		// }

		//$user->setSex($_POST['title']);
		$user->setName($_POST['name']);
		$user->setAddress('Đang cập nhật');
		$user->setPhone($_POST['phone']);

		// if ($images_up != "") {
		// 	$user->setImages($images_up);
		// }
		if (!empty($_POST['password']) && !empty($_POST['cpassword'])) {
			$user->setPassword(Hash::sha256($_POST['password']));
		}
		$ret = $user->update();
		if ($ret) $err_mess = _USER_PROFILE_UPDATED;
		else $err_mess = _USER_ERROR_UPDATING_PROFILE;
	//} else {
	//	$err_mess = _INCORRECT_CAPTCHA;
	//}
}

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";
echo "var err = 0";
echo "var Password = fetch_object('password');";
echo "var CPassword = fetch_object('cpassword');";
echo "var Name = fetch_object('name');";
echo "var Address = fetch_object('address');";
echo "var Phone = fetch_object('phone');";
echo "if (Address.value == '') {";
echo "alert('"._USER_ERROR_INCOMPLETE."');";
echo "Address.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (Phone.value == '') {";
echo "alert('"._USER_ERROR_INCOMPLETE."');";
echo "Phone.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (Name.value == '') {";
echo "alert('"._USER_ERROR_INCOMPLETE."');";
echo "Name.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if (Password.value != CPassword.value) {";
echo "alert('"._USER_ERROR_PASSWORD."');";
echo "Password.focus();";
echo "return false;";
echo "err = 1;";
echo "}";
echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";
?>
<section>
    <div class="row">
        <div class="col-sm-12 col-md-9 col-lg-9 left-col-main">
            <div class="boxhome_line">
                <section class="hero">
                	<div class="hero-bg"></div>
                	<div class="container relative">
                		<div class="row">
                			<div class="col-lg-12">
                				<div class="box box-white padding-20">
                					<div class="title text-center margin-top-20 no-margin-bottom">
                						<h4><i class="fa fa-check hidden-xs"></i><?= _USER_EDIT_PROFILE ?></h4>
                					</div>
<?php
echo "<form method=\"POST\" action=\"".url_sid("index.php?f=$module_name&do=$do")."\" enctype=\"multipart/form-data\" onsubmit=\"return Check_Valid(this);\">";
if (isset($err_mess)) {
	echo "<div align=\"center\"><font color=\"red\"><b>$err_mess</b></font></div>";
}
echo "<table border=\"0\" align=\"center\" width=\"100%\" style=\"line-height:30px\" class=\"profile_user\">";
echo "<tr><td><font size=\"2\">"._USER_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px" ><input type="password" id="password" name="password" size="40" class="form-control input-lg"></td>'."</tr>";

echo "<tr><td height=\"24\"><font size=\"2\">"._USER_CONFIRM_PASSWORD.": </font></td>";
echo '<td style="padding-left: 10px"><input placeholder="'._USER_LEAVE_BLANK_TO_KEEP_AS_IS.'" type="password" id="cpassword" name="cpassword" size="40" class="form-control input-lg"></td>'."</tr>";

echo "<tr><td height=\"24\" colspan=\"2\"><hr /></td></tr>";

echo "<tr><td><font size=\"2\">"._USER_EMAIL.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" disabled id="email" value="'.$user->email.'" name="email" size="40" class="form-control input-lg"></td>'."</tr>";

echo "<tr><td><font size=\"2\">"._USER_FULLNAME.": </font></td>";
echo '<td style="padding-left: 10px"><input type="text" id="name" value="'.$user->name.'" name="name" size="40" class="form-control input-lg"></td>'."</tr>";

// echo "<tr><td><font size=\"2\">"._USER_ADDRESS.": </font></td>";
// echo '<td style="padding-left: 10px"><input type="text" id="address" value="'.$user->address.'" name="address" size="40" class="form-control input-lg"></td>'."</tr>";

echo "<tr><td><font size=\"2\">"._USER_PHONE.": </font></td>";
echo '<td style="padding-left: 10px" align=\"left\"><input type="text" id="phone" value="'.$user->phone.'" name="phone" size="40" class="form-control input-lg"></td>'."</tr>";
/*
$imagesk=$user->images;
if(!empty($imagesk) && file_exists("$path_upload/user/$imagesk")) {
	echo "<tr>\n";
	echo "<td width=\"20%\" align=\"right\" class=\"row1\">Xóa ảnh</td>\n";
	echo "<td class=\"row2\"><input type=\"checkbox\" name=\"delpic\" value=\"1\">";
	?><img style="margin-left: 10px;" src="<?= $urlsite."/".$path_upload."/user/".$imagesk ?>" alt="" width="85px"><?php
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"row1\">Thay đổi Avatar</td>\n";
	echo "<td class=\"row2\" style=\"padding-left: 10px\"><input type=\"file\" name=\"userfile\" size=\"30\"></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"row1\">"._IMAGE."</td>\n";
	echo "<td class=\"row2\" style=\"padding-left: 10px\"><input type=\"file\" name=\"userfile\" size=\"30\"></td>\n";
	echo "</tr>\n";
}
*/
//echo "<tr><td colspan=\"2\" align=\"center\">";
//echo "<div align=\"center\"><img src=\"index.php?f=captcha\"></div>";
//echo "</td></tr>";
//echo "<tr><td colspan=\"2\">"._ENTER_CAPTCHA.": <input type=\"text\" name=\"captcha\" id=\"captcha\"></td></tr>";
echo "<tr><td colspan=\"2\"><hr /></td></tr>";
// echo "<tr><td colspan=\"2\">";
// echo "<a href=\"".url_sid("index.php?f=history")."\" class=\"edit-link\">"._LICH_SU_TRUY_CAP."</a>";
// echo "<a href=\"".url_sid("index.php?f=history&do=orders")."\" class=\"edit-link\">"._DS_DON_HANG."</a>";
// echo "<a href=\"".url_sid("index.php?f=history&do=wishlist")."\" class=\"edit-link\">"._DS_SP_UA_THICH."</a>";
// echo "</td></tr>";
// echo "<tr><td colspan=\"2\"><hr /></td></tr>";
echo '<input type="hidden" name="submit_up" value="1">';
echo "<tr><td height=\"24\" colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"btn btn-primary pull-right btn-lg\" name=\"submit\" value=\""._SAVECHANGES."\"></td></tr>";
echo "</table></form>";

?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 right-col-block">
            <?php blocks("left",$module_name); ?>
        </div>
    </div>
</section>
<?php
//CloseTab();
include_once('footer.php');
?>