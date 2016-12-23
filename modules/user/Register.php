<?php
if (!defined('CMS_SYSTEM')) die();

if (defined('iS_USER') && isset($userInfo)) header("Location: index.php");


// include_once("configfb.php");
$sAgreeChecked = $sMrSelected = $sMrsSelected = 0;
$sEmail = $sCEmail = $sAddress = $sPhone = $sName = '';

if (isset($_POST['submit'])) {
    require_once("WebUser.class.php");
	$user = new WebUser('0', $_POST['name'], $_POST['email'],'đang cập nhật', $_POST['phone'], Hash::sha256($_POST['password']),'');
		$ret = $user->register($_POST['url']);
		if ($ret == WEBUSER_EMAIL_REGISTERED)
		{
			$err_mess = _USER_EMAIL_REGISTERED;
		}
		elseif ($ret == WEBUSER_REGISTRATION_SUCCEEDED)
		{
			// list ($xid) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT max(id) AS xid FROM ".$prefix."_user"));
			// $time_x = date("Y-m-d");
			// $db->sql_query_simple("INSERT INTO ".$prefix."_user_check (id, user_id, title, content, time) values ('','".$xid."','vừa gia nhập xemtailieu','Chúc bạn một ngày vui vẻ <i class=\"fa fa-smile-o\"></i>.', '".$time_x."')");

			echo "<div align=\"center\">"._USER_REGISTRATION_SUCCESSFUL."</div>";
			echo "<meta http-equiv=\"refresh\" content=\"5;url= ".url_sid("index.php?f=user&do=login")."\">";
			include_once("footer.php");
			exit();
		}
		elseif ($ret == WEBUSER_REGISTRATION_FAILED)
		{
			$err_mess = _USER_ERROR_REGISTERING;
		}
}
else
{
    $title_page = _USER_REGISTER;
    include_once("header.php");
?>
<section>
    <div class="row">
        <div class="col-sm-12 col-md-9 col-lg-9 left-col-main">
            <div class="boxhome_line">
            	<section class="hero">
            		<script language="javascript" type="text/javascript">
            			function Check_Valid() {
            				var password = document.getElementById('password').value;
            				var cpassword = document.getElementById('cpassword').value;

            		        if(cpassword != password ){
            					window.alert('Mật khẩu xác nhận không chính xác!!!');
            					document.getElementById('cpassword').focus();
            					return false;
            				}
            				return true;
            			}
            		</script>
            		<div class="hero-bg"></div>
            		<div class="container relative">
            			<div class="row">
            				<div class="col-lg-12">
            					<div class="box box-white padding-20" style="">
                                    <div class="title text-center margin-top-20 no-margin-bottom">
                                        <h4><i class="fa fa-check hidden-xs"></i> Đăng ký thành viên </h4>
                                    </div>
            						<form class="class="no-padding-xs padding-left-20 padding-right-20 margin-bottom-10"" method="POST" action="#" onsubmit="return Check_Valid();" enctype="multipart/form-data">
            							<?php
            							if (isset($err_mess)) {
            									echo "<div align=\"center\"><font style=\"color: red;\"><b>$err_mess</b></font></div>";
            								}
            							?>
            							<div class="form-group">
            								<div class="col-sm-12">
            									<input class="form-control input-lg margin-bottom-10" placeholder="Email" id="email" name="email" value="" size="38" type="text" required="true">
            								</div>
            							</div>
            							<div class="form-group">
            								<div class="col-sm-12">
            									<input class="form-control input-lg margin-bottom-10" placeholder="Mật khẩu" id="password" name="password" size="38" type="password" required="true">
            								</div>
            							</div>
            							<div class="form-group">
            								<div class="col-sm-12">
            									<input class="form-control input-lg margin-bottom-10" placeholder="Xác nhận lại mật khẩu" id="cpassword" name="cpassword" size="38" type="password">
            								</div>
            							</div>
            							<div class="form-group">
            								<div class="col-sm-12">
            									<input class="form-control input-lg margin-bottom-10" placeholder="Họ và tên" id="name" name="name" value="" size="38" type="text" required="true">
            								</div>
            							</div>

            							<div class="form-group">
            								<div class="col-sm-12">
            									<input class="form-control input-lg margin-bottom-10" placeholder="Số điên thoại liên hệ" id="phone" name="phone" value="" size="38" type="text" required="true">
            								</div>
            							</div>
            							<div class="form-group" style="text-align: center;margin-bottom:6px;padding: 0px 15px;">
            								<script>
            									var currentURL=encodeURI(location.href);
            									document.write('<input type="hidden" name="url" value="' + currentURL + '">')
            								</script>
                                            <div class="author_name">
                                                Bạn đã có tài khoản? <a href="<?= url_sid("index.php?f=user&do=login") ?>" title="Đăng nhập">Đăng nhập</a> ngay!
                                            </div>
                    						<input class="btn btn-primary pull-right btn-lg" name="submit" value="Đăng ký" type="submit">
            							</div>
            						</form>
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
include_once("footer.php");
}
?>