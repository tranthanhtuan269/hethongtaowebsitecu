<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");
		$email = $_POST['email_1'];
		$result = $db->sql_query_simple("SELECT id FROM {$prefix}_newsletter WHERE email='$email'");
		if ($db->sql_numrows($result) > 0) {
			//echo '<div align="center">'._NEWSLETTER_ACTIVATION_ESXIT."</div>";
			echo "<script language = javascript>alert('"._NEWSLETTER_ACTIVATION_ESXIT."');</script>";
		}else{
			$db->sql_query_simple("INSERT INTO {$prefix}_newsletter (email, status, html, checkkey, time) VALUES ('$email', 2,0, '', '".TIMENOW."')");		
			//echo '<div align="center">'._NEWSLETTER_ACTIVATION_SUCCESSFUL."</div>";
			echo "<script language = javascript>alert('"._NEWSLETTER_ACTIVATION_SUCCESSFUL."');</script>";
		}
		
		//echo "<meta http-equiv=\"refresh\" content=\"2;url=index.php\">";
		echo "<script language = javascript>history.back();</script>";
		
include_once("footer.php");		
?>