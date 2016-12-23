<?php
if (!defined('CMS_SYSTEM')) die();

if (!isset($_GET['type']) || ($_GET['type'] != 'quick')) {
	//if (defined('iS_USER') && isset($userInfo)) header("Location: index.php");
	$page_title = _USER_LOGIN;
	include_once("header.php");
	OpenTab(_USER_LOGIN);
}

require_once("WebUser.class.php");
if (isset($_POST['submit'])) {
	$user = new WebUser(0, $_POST['email']);
	if ($user) {
		$ret = $user->login($_POST['email'], $_POST['password'], $maxLoginAttempt, $_POST['url']);
		//header("Location: index.php?f=user&do=login");
		if ($ret == USER_LOGIN_SUCCEEDED) {
			if (!isset($_GET['type']) || ($_GET['type'] != 'quick')) {
				echo "<div align=\"center\">"._USER_LOGIN_SUCCESSFUL."</div>";
				//echo "<meta http-equiv=\"refresh\" content=\"1;url= ".url_sid("index.php")."\">";
				CloseTab();
				include_once("footer.php");
				exit();
				
			} else {
				if (file_exists("blocks/{$_POST['block']}")) {
					$userInfo = checkUser();
					include_once("blocks/{$_POST['block']}");
					echo $content;
				}
				exit();
			}
			//header("Location: index.php");
		} elseif (isset($_GET['type']) && ($_GET['type'] == 'quick')) {
			if ($ret == USER_LOGIN_FAILED) $loginErr = 1;
			else $loginErr = 2;
			if (file_exists("blocks/{$_POST['block']}")) {
				$userInfo = checkUser();
				include_once("blocks/{$_POST['block']}");
				echo $content;
			}
			exit();
		} elseif (($ret == USER_LOGIN_FAILED) || ($ret == USER_LOGIN_FAILED_BLOCKED)) {
			$err_mess = _USER_LOGIN_FAILED;
			if ($ret == USER_LOGIN_FAILED_BLOCKED) $err_mess .= "<br />"._USER_UNBLOCK_INSTRUCTIONS;
		} elseif ($ret == USER_ACCOUNT_BLOCKED) {
			$err_mess = _USER_ACCOUNT_BLOCKED;
		}
	} else {
		if (isset($_GET['type']) && ($_GET['type'] == 'quick')) {
			$loginErr = 1;
			if (file_exists("blocks/{$_POST['block']}")) {
				$userInfo = checkUser();
				include_once("blocks/{$_POST['block']}");
				echo $content;
			}
			exit();
		}
	}
	//header("Location: index.php");
}

//if (!isset($_GET['type']) || ($_GET['type'] != 'quick')) {

	echo "<div align=\"center\">"._USER_LOGIN_SUCCESSFUL."</div>\n";
	echo "<script type=\"text/javascript\"><!--\n";
	echo "setTimeout('Redirect()',2000);\n";
	echo "function Redirect()\n";
	echo "{\n";
	echo "  location.href = 'index.php?f=user&do=login';\n";
	echo "}\n";
	echo "// --></script>\n";
	
	

	//CloseTab();
	include_once("footer.php");
?>