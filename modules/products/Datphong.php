<?php
if (!defined('CMS_SYSTEM')) die();

if (isset($_POST['submit'])) 
{
    $fullname_dp = mysql_real_escape_string($_POST['fullname_dp']);
    $loaiphong_dp = mysql_real_escape_string($_POST['loaiphong_dp']);
    $email_dp = mysql_real_escape_string($_POST['email_dp']);
    $phone_dp = mysql_real_escape_string($_POST['phone_dp']);
    $address_dp = mysql_real_escape_string($_POST['address_dp']);
    $notes_dp = mysql_real_escape_string($_POST['notes_dp']);
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $timed=date('d/m/Y H:i:s');
    $chuoi = $loaiphong_dp."-1,";
    $query = ("INSERT INTO ".$prefix."_products_order (id, fullname, phone, mail, address, info, orderTime, chuoi) VALUES (NULL, '$fullname_dp', '$phone_dp', '$email_dp', '$address_dp', '$notes_dp', '$timed','$chuoi')");
    if($db->sql_query_simple($query)){
        ?>
        <script type="text/javascript">
            window.alert('Gửi yêu cầu thành công. Chúng tôi sẽ sớm liên hệ với bạn');
            window.history.back();
        </script>
        <?php
    }
}
else
{

}
?>