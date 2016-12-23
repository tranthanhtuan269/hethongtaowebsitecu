<?php
if (!defined('CMS_SYSTEM')) die();
$title_page = ""._CONTACT."";
include_once("header.php");

if(isset($_POST['gui']) && $_POST['gui'] == 1){
    $hoten = trim($_POST['fullname']);

    $sdt = intval($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $noidung = trim($_POST['note']);
    //$tieude1 = trim($_POST['title']);
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $timed=date('d/m/Y H:i:s');

    $result = ("INSERT INTO {$prefix}_contact (id, title, ctname, address, email, phone, content, ctdate, alanguage ) VALUES ( '', 'Liên hệ','$hoten', '$address', '$email', '$sdt', '$noidung', '$timed', '$currentlang') ");
    if($db->sql_query_simple($result)){
        ?>
        <script type="text/javascript">
            window.alert('Thông tin của bạn đã được gửi đi. Xin cám ơn!');
        </script>
        <?php
    }

}
?>
<script type="text/javascript">
    function check_frm(){
        var fullname = document.getElementById('fullname').value;
        var address = document.getElementById('address').value;
        var email = document.getElementById('email').value;
        var phone = document.getElementById('phone').value;


        var kiemTraDT = isNaN(phone);
        if(fullname == ""){
            window.alert(' Vui lòng nhập họ tên.');
            document.getElementById('fullname').focus();
            return false;
        }
        if(address == ""){
            window.alert(' Vui lòng nhập địa chỉ.');
            document.getElementById('address').focus();
            return false;
        }
        var aCong=email.indexOf("@");
        var dauCham = email.lastIndexOf(".");
        if (email == "") {
            alert("Email không được để trống");
            return false;
        }
        else if ((aCong<1) || (dauCham<aCong+2) || (dauCham+2>email.length)) {
              alert("Email bạn điền không chính xác");
              return false;
          }

        if(phone == ""){
            window.alert('Nhập số điện thoại.');
            document.getElementById('phone').focus();
            return false;
        }
         if (kiemTraDT == true) {
              alert("Điện thoại phải để ở định dạng số");
              return false;
          }
        if(soc1 == "" ){
            window.alert('Xác nhận không chính xác!!!');
            document.getElementById('soc').focus();
            return false;
        }
        return true;
    }
</script>

<section class="box_content">    
    <section class="bg_title">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="<?= url_sid("index.php/") ?>" title="Trang chủ"><?= _HOMEPAGE ?> &raquo; </a></li>
                <li><a><?= _CONTACT ?></a></li>
            </ol>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="content_contact">
                        <?php
                            $resultadd = $db->sql_query_simple("SELECT banggia FROM ".$prefix."_contact_add WHERE banggia!='' AND alanguage='$currentlang'");
                            if($db->sql_numrows($resultadd) > 0)
                            {
                                list($address_ct) = $db->sql_fetchrow_simple($resultadd);
                                echo $address_ct;
                            }
                        ?>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="content_contact" style="margin-bottom: 15px;">
                    <?php  
                        if ($currentlang == 'vietnamese') {
                            echo "Mời bạn điền mẫu thông tin bên dưới và gửi cho chúng tôi. Các chuyên viên tư vấn của chúng tôi sẽ giải đáp mọi thắc mắc của bạn 1 cách sớm nhất.";
                        }
                        else
                        {
                            echo "Invites you to fill out the information below and send it to us. The counselors Our answer all your questions as soon as possible 1.";
                        }

                    ?>
                    </div>
                    <div class="row">
                        <form method="post" active="" id="frm_contact" name="thongtindh" onsubmit="return check_frm()">
                            <div class="form-group">
                                <div class="col-lg-12">
                                  <input type="text" name="fullname" id="fullname" class="form-control" placeholder="<?= _FULLNAME ?>" style="width:100%;" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                  <input type="text" class="form-control" id="address" name="address" placeholder="<?= _ADDRESS ?>" style="width:100%;" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" style="width:100%;" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="<?= _PHONE ?>" style="width:100%;" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                  <textarea class="form-control" name="note" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="hidden" name="gui" class="btn btn-primary" value="1">
                                    <input type="submit" name="submit_step1" class="btn btn-primary" value="<?= _SENDTO ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<?php
include_once("footer.php");
?>