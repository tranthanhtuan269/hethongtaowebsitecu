<?php
class sendMail{

function sendMail(){}

function sendMailFree($recipient, $email, $password, $name, $subject, $body)
{
    $mailer = new PHPMailer();
    $mailer->IsSMTP(); // bật chức năng SMTP
    $mailer->Mailer='smtp';
    $mailer->SMTPDebug = 0; // kiểm tra lỗi : 1 là  hiển thị lỗi và thông báo cho ta biết, 2 = chỉ thông báo lỗi
    $mailer->SMTPAuth = true; // bật chức năng đăng nhập vào SMTP này
    $mailer->SMTPSecure = 'ssl'; // sử dụng giao thức SSL vì gmail bắt buộc dùng cái này
    $mailer->Host = 'smtp.gmail.com'; // host mail
    $mailer->Port = 465; /// port mail
    $mailer->SMTPAuth = true;

	$mailer->Username = $email;
    $mailer->Password = $password;

    $mailer->FromName = $name; // This is the from name in the email, you can put anything you like here

    $mailer->Subject = $subject;

	$mailer->CharSet = "utf-8";   //Thiết lập định dạng font chữ


	$body  = eregi_replace("[\]",'',$body);
	$mailer->MsgHTML($body);

	//$mailer->Body = $body;
    $mailer->SMTPDebug=1;

     $mailer->AddAddress($recipient); // This is where you put the email adress of the person you want to mail

	// Check gui thanh cong
    if (!$mailer->Send()) {
        echo 'Loi Gui Mail'. $mailer->ErrorInfo;
        //return array('status' => false, 'err' => $mailer->ErrorInfo);
		return 0;
    } else {
        echo $mailer->ErrorInfo;
        //return array('status' => true, 'err' => 'OK');
        $mailer->ClearAllRecipients();
        $mailer->Body = null;
        $mailer->Subject = null;
		return 1;
    }

}
}

?>
