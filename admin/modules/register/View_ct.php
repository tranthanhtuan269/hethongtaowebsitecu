<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");
global $urlsite;
$adm_pagetitle2 = "Chi tiết học viên";

$id = intval($_GET['id']);
// $result = $db->sql_query_simple("SELECT id,ctname,email,phone,content,ctdate,status FROM {$prefix}_contact WHERE id=$id");
$result = $db->sql_query_simple("SELECT  id,name,birthday,address,email,phone,cmnd,khuvuc,totnghiep,diemtn,hocba,diemhb,hedangky,hoidongthi,sbd,datedk,status   FROM ".$prefix."_register WHERE id=$id and alanguage='$currentlang'  ");
if($db->sql_numrows($result) != 1) header("Location: modules.php?f=$adm_modname");
$err = "";
list($id, $name, $birthday, $address, $email,$phone, $cmnd,$khuvuc,$totnghiep,$diemtn,$hocba,$diemhb,$hedangky,$hoidongthi,$sbd,$datedk,$status) = $db->sql_fetchrow_simple($result);
$atitle = "Re: $name";
switch($status) {
	case 0: $status_o = "Chưa xử lý";  break;
	case 1: $status_o = "Đang xử lý"; break;
	case 2: $status_o = "Đã xử lý"; break;
}

include_once("page_header.php");

$err_atitle = $err_mess = $err_amess =$amess = "";
if(isset($_POST['subup']) && $_POST['subup'] == 1) {
	$atitle = nospatags($_POST['atitle']);
	$amess = trim(stripslashes(resString($_POST['amess'])));
	$show = isset($_POST['show']) ? intval($_POST['show']) : 0;
	if (empty($atitle)) {
		$err_atitle = "<font color=\"red\">"._ERROR3."</font><br>";
		$err = 1;
	}

	if (empty($amess)) {
		$err_amess = "<font color=\"red\">"._ERROR4."</font><br>";
		$err = 1;
	}

	if(!$err) {
		// $signsite = signsite();
		// $message = "<html><body style=\"font-family: Arial; font-size: 12px\">"._HELLO.", "._CTANSWER.":<br>";
		// $message .= "".nl2br($amess)."<br>$signsite.<br></body></html>";
		// $subject = _CONTACT_RESPONSE_FROM." $sitename";
		// sendmail($subject, $email, $adminmail, $message);
		// $db->sql_query_simple("UPDATE {$prefix}_contact SET response='".$escape_mysql_string($amess)."', onHome=$show WHERE id=$id");
		// updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CONTACT_RESPONSE);
		mb_internal_encoding("UTF-8");
		 
		$message ='Người gửi :  '.$adminmail."<br/>";
		$message .='Họ tên :'.$urlsite."<br/>";
		 
		$message .='Nội dung:'.$amess."<br/>";
		$hoten = 'admin';
		
		//echo $message;
		$to = $email;  //khai báo địa chỉ mail người nhận
		$options="Content-type:text/html;charset=utf-8\r\nFrom:$email\r\nReply-to:$email";
		 //Gửi mail
		//sendmail($subject, $email, $adminmail, $message);
		$mail_sent = @mb_send_mail($to,$hoten,$message,$options );

		echo $mail_sent ? "Mail sent" : "Mail failed";

		// header("Location: modules.php?f=".$adm_modname."");
	}

}
switch ($totnghiep) {
	case 0: $khoi = "A";break;
	case 1: $khoi = "B";break;
	default: $khoi = "A";break;
}
switch ($khoi) {
	case 'A': $diem = "Toán/Lý/Hóa: $diemtn";break;
	case 'B': $diem = "Toán/Hóa/Sinh: $diemtn";break;
	default: $diem = "";break;
}

switch ($hocba) {
	case 0: $khoi1 = "A";break;
	case 1: $khoi1 = "B";break;
	default: $khoi1 = "A";break;
}
switch ($khoi1) {
	case 'A': $diem1 = "Toán/Lý/Hóa: $diemhb";break;
	case 'B': $diem1 = "Toán/Hóa/Sinh: $diemhb";break;
	default: $diem1 = "";break;
}

echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" class=\"tableborder table table-bordered\">\n";
echo "<tr><td colspan=\"7\" class=\"header\">Chi tiết học viên</td></tr>";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Id tin</b></td>\n";
echo "<td class=\"row3\"><b>#$id</b></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Ngày đăng ký</b></td>\n";
echo "<td class=\"row3\">$datedk</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>"._STATUS."</b></td>\n";
echo "<td class=\"row3\"><font color=\"red\"><b>$status_o</b></font></td>\n";
echo "</tr>\n";
echo "<tr><td colspan=\"2\" bgcolor=\"#FFFFFF\" height=\"4\" style=\"border-bottom: solid 1px #CCC\"></td></tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Tên học viên</b></td>\n";
$app_ar = array("Nam","Nữ");
echo "<td class=\"row3\"><b> $name</b></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Số báo danh</b></td>\n";
echo "<td class=\"row3\"><b>$sbd</b></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Ngày sinh</b></td>\n";
 
echo "<td class=\"row3\"><b> $birthday</b></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Số cmnd</b></td>\n";
echo "<td class=\"row3\"><b>$cmnd</b></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Diện ưu tiên / khu vực</b></td>\n";
echo "<td class=\"row3\"><b>$khuvuc</b></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Điểm tốt nghiệp THPT</b></td>\n";
echo "<td class=\"row3\"><b>Khối : $khoi - $diem</b></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Điểm học bạ</b></td>\n";
echo "<td class=\"row3\"><b>Khối : $khoi1 - $diem1</b></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Hội đồng thi</b></td>\n";
echo "<td class=\"row3\"><b>$hoidongthi</b></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Hệ đăng ký</b></td>\n";
echo "<td class=\"row3\">".$hedangky."</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>"._ADDRESS."</b></td>\n";
echo "<td class=\"row3\">$address</td>\n";
echo "</tr>\n";


echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Số điện thoại</b></td>\n";
echo "<td class=\"row3\">0$phone</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>Email</b></td>\n";
echo "<td class=\"row3\"><a href=\"mailto:$email\">$email</a></td>\n";
echo "</tr>\n";
 

echo "<tr>\n";
echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\"><td align=\"right\" class=\"row1\"><select name=\"stat\">";
$stat_ar = array('Chưa xử lý','Đang xử lý','Đã xử lý');
for($i =0; $i < 3; $i ++) {
	$seld ="";
	if($i == $status) { $seld =" selected"; }
	echo "<option value=\"$i\"$seld>$stat_ar[$i]</option>";
}
echo "</select></td>\n";
echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
echo "<input type=\"hidden\" name=\"do\" value=\"status_ct\">";
echo "<td class=\"row3\"><input type=\"submit\" value=\""._SAVECHANGES."\" class=\"button2\"></td></form>";
echo "</tr>\n";
// echo $do;
// echo "<tr><td colspan=\"2\" class=\"rowst\">"._ANSWER."</td></tr>\n";
// echo "<tr>\n";
// echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" method=\"POST\" onsubmit=\"this.submit.disabled=true;\"><td width=\"150\" align=\"right\" class=\"row1\"><b>"._TITLE."</b></td>\n";
// echo "<td class=\"row3\">$err_atitle<input type=\"text\" name=\"atitle\" value=\"$atitle\" size=\"50\"></td>\n";
// echo "</tr>\n";
// echo "<tr>\n";
// echo "<td width=\"150\" align=\"right\" class=\"row1\"><b>"._CONTENT."</b></td>\n";
// echo "<td class=\"row3\">$err_amess\n";
// editor("amess",$amess,'',200);
// echo "</td>\n";
// echo "</tr>\n";
// echo "<tr><td width=\"150\" align=\"right\" class=\"row1\"><b>"._CONTACT_SHOW."</b></td>\n";
// $showSelected = '';
// if (@$show) $showSelected = ' checked="checked"';
// echo "<td class=\"row3\"><input type=\"checkbox\" name=\"show\" value=\"1\"$showSelected></td>\n";
// echo "</tr>\n";
// echo "<tr><td class=\"row1\">&nbsp;</td><td class=\"row1\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\""._SEND."\"></form></td></tr>\n";
echo "</table><br>";

updateadmlog($admin_ar[0], $adm_modname, "Chi tiết học viên", _VIEW);
include_once("page_footer.php");

?>