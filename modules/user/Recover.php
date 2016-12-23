<?php
if (!defined('CMS_SYSTEM')) die();

$title_page = _USER_RECOVER_PASSWORD;

include_once("header.php");

require_once("WebUser.class.php");

$db->sql_query_simple("UPDATE {$prefix}_user SET recoverCode=NULL WHERE UNIX_TIMESTAMP(recoverCodeTime) + ".strval($activationPeriod * 3600).' <= NOW() AND recoverCode IS NOT NULL');

?>
<section class="hero">
	<div class="hero-bg"></div>
	<div class="container relative">
		<div class="row">
			<div class="col-lg-12">
				<div class="box box-white padding-20">
					<div class="title text-center margin-top-20 no-margin-bottom">
						<h4><i class="fa fa-check hidden-xs"></i><?= _USER_RECOVER_PASSWORD ?></h4>
					</div>
<?php
if (isset($_GET['email']) && isset($_GET['code'])) {
	$user = new WebUser(0, $_GET['email']);
	$ret = $user->recover($_GET['code']);
	if ($ret) {
		echo '<div align="center">'._USER_YOUR_NEW_PASSWORD_IS.": <b>$ret</b></div>";
		echo '<div align="center"><font color="red">'._CHANGE_PASS."</font></div><br>";
	} else {
		echo '<div align="center"><font color="red"><b>'._USER_ERROR_RECOVER."</b></font></div><br>";
	}
} else {
	if (isset($_POST["submit"])) {
		$user = new WebUser(0, $_POST['email']);
		$ret = $user->newRecover($_POST['url']);
		if ($ret) echo '<br><div align="center">'.$ret."</div><br>";//_USER_CHECK_MAIL_TO_RECOVER
		else echo '<br><div align="center">'._USER_NEW_RECOVER_FAILED."</div><br>";
	} else {
		echo "<div style=\"padding-left:10px;text-align:center;\">"._USER_RECOVER_INSTRUCTIONS."</div><p></p>";
		echo "<div align=\"center\">";
		echo "<form action=\"".url_sid("index.php?f=$module_name&do=$do")."\" method=\"POST\">";
		echo "<input class=\"form-control input-lg\" placeholder=\"Email\" type=\"text\" name=\"email\" class=\"text\"><br />";
		echo "<script>var currentURL=encodeURI(location.href);";
		echo "document.write('<input style=\"text-align: left;\" type=\"hidden\" name=\"url\" value=\"' + currentURL + '\">')</script>";
		echo "<input type=\"submit\" name=\"submit\" value=\""._SEND."\" class=\"btn btn-primary pull-right btn-lg\">";
		echo "</form>";
		echo "</div>";
	}
}
?>
</div>
</div>
</div>
</div>
</section>
<?php
include_once("footer.php");
?>