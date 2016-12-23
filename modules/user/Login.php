<?php
if (!defined('CMS_SYSTEM')) die();
require_once("WebUser.class.php");

if (isset($_POST['submit'])) {
	$user = new WebUser(0, $_POST['email']);
    if ($user)
    {
        $ret = $user->login($_POST['email'], $_POST['password'], $maxLoginAttempt, $_POST['url']);
        if ($ret == USER_LOGIN_SUCCEEDED) {
            echo "<meta http-equiv=\"refresh\" content=\"1;url= ".url_sid("index.php/")."\">";
        }
        else {
            echo "<meta http-equiv=\"refresh\" content=\"1;url= ".url_sid("index.php?f=user&do=login")."\">";
        }
    }
}
else
{
    $title_page = _USER_LOGIN;
    if (!defined('iS_USER') || !isset($userInfo)) {
        include_once("header.php");
    	?>
        <section>
            <div class="row">
                <div class="col-sm-12 col-md-9 col-lg-9 left-col-main">
                    <div class="boxhome_line">
                        <section class="hero">
                        	<div class="hero-bg"></div>
                        	<div class="relative">
                        		<div class="row">
                        			<div class="col-lg-12">
                        				<div class="box box-white padding-20">
                        					<div class="title text-center margin-top-20 no-margin-bottom">
                        						<h4><i class="fa fa-check hidden-xs"></i> Đăng nhập</h4>
                        					</div>

                        						<?php
                        						echo "<form method=\"POST\" action=\"".url_sid("index.php?f=$module_name&do=$do")."\">";
                        						if (isset($err_mess)) {
                        							echo "<div align=\"center\"><font color=\"red\"><b>$err_mess</b></font></div>";
                        						}
                        						?>

                        						<input class="form-control input-lg margin-bottom-10" placeholder="Email" required="" type="text" id="email" name="email" size="40">
                        						<input class="form-control input-lg margin-bottom-10" placeholder="Mật khẩu" required="" type="password" id="password" name="password" size="40">
                        						<script>var currentURL=encodeURI(location.href);
                        						document.write('<input type=\"hidden\" name=\"url\" value=\"' + currentURL + '\">')</script>
                                                <div class="author_name">
                                                    Bạn chưa có tài khoản? <a href="<?= url_sid("index.php?f=user&do=register") ?>" title="Đăng ký">Đăng ký</a> ngay!
                                                </div>
                        						<input class="btn btn-primary pull-right btn-lg" type="submit" name="submit" value="<?= _USER_LOGIN ?>" />
                        						<div class="clearfix"> </div>

                        						<a class="text-dark margin-top-15 padding-top-15 help-block border-top-1 border-grey-200 btn-icon-right" href="<?= url_sid("index.php?f=user&do=recover"); ?>">
                        							<i class="fa fa-user"></i> <b><?= _FORGOT_PASSWORD ?></b>
                        						</a>
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
}
?>