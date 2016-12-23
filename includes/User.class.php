<?php
define('USER_LOGIN_SUCCEEDED', 1);
define('USER_LOGIN_FAILED', 0);
define('USER_ACCOUNT_BLOCKED', -1);
define('USER_LOGIN_FAILED_BLOCKED', -2);

class User {
	var $id;
	var $sex;
	var $name;
	var $email;
	var $password;
	var $sess;
	var $tableName;
	var $loginAttempt;
	var $actives;

	function User() {
		$numArgs = func_num_args();
		$args = func_get_args();
		$this->setLoginAttempt(0);
		$this->setId(-1);
		if ($numArgs == 5) {
			$this->setSex($args[0]);
			$this->setName($args[1]);
			$this->setEmail($args[2]);
			$this->setPassword($args[3]);
			$this->setSess($args[4]);
		} else if ($numArgs == 1) {
			$this->setSex(0);
			$this->setName('');
			$this->setEmail('');
			$this->setPassword('');
			$this->setSess($args[0]);
		}
	}

	function setSess($sess) {
		$this->sess = $sess;
	}

	function setPassword($password) {
		$this->password = $password;
	}

	function setSex($sex) {
		$this->sex = intval($sex);
	}

	function setName($name) {
		$this->name = $name;
	}

	function setEmail($email) {
		$this->email = $email;
	}

	function setId($id) {
		$this->id = intval($id);
	}

	function setLoginAttempt($attempt) {
		$this->loginAttempt = $attempt;
	}

	function setActives($actives) {
		$this->actives = $actives;
	}

	function activate($code) {
		global $db, $escape_mysql_string;

		$db->sql_query_simple("UPDATE {$this->tableName} SET activationCode=NULL WHERE activationCode='".$escape_mysql_string($code)."' AND email='".$escape_mysql_string($this->email)."'");
		if ($db->sql_affectedrows() > 0) {
			$db->sql_query_simple("UPDATE {$this->tableName} SET actives=1 WHERE email='".$escape_mysql_string($this->email)."'");

			$email = $this->email;
			$password = $this->password;
			$_SESSION[$this->sess] = "$email;$password";
			return true;
		}
		else return false;
	}

	function unblock($code) {
		global $db, $escape_mysql_string;

		$unblockCode = $escape_mysql_string($code);
		$db->sql_query_simple("UPDATE {$this->tableName} SET loginAttempt=0, unblockCode=NULL WHERE unblockCode='$unblockCode' AND email='".$escape_mysql_string($this->email)."'");
		if ($db->sql_affectedrows() > 0) return true;
		else return false;
	}

	function del() {
		global $db;

		if ($this->id != -1) {
			$db->sql_query_simple("DELETE FROM {$this->tableName} WHERE id={$this->id}");
			if ($db->sql_affectedrows() > 0) return true;
			else false;
		} else return false;
	}

	function recover($code) {
		global $escape_mysql_string, $db;

		$newPass = uniqid('');
		//$db->sql_query_simple("UPDATE {$this->tableName} SET recoverCode=NULL, pass='".Hash::sha256($newPass)."' WHERE email='".$escape_mysql_string($this->email)."' AND recoverCode='".$escape_mysql_string($code)."'");
		$db->sql_query_simple("UPDATE {$this->tableName} SET recoverCode=NULL, pass='".Hash::sha256($newPass)."' WHERE email='".$escape_mysql_string($this->email)."'");
		if ($db->sql_affectedrows() > 0) return $newPass;
		else return false;
	}

	function newRecover($url) {
		global $db, $escape_mysql_string, $module_name, $adminmail, $smtp_username, $smtp_password, $urlsite;

		$recoverCode = md5(uniqid(rand(), true));
		$db->sql_query_simple("UPDATE {$this->tableName} SET recoverCode='$recoverCode', recoverCodeTime=NOW() WHERE email='".$escape_mysql_string($this->email)."'");
		if ($db->sql_affectedrows() > 0) {

			$newPass = uniqid('');
			$db->sql_query_simple("UPDATE {$this->tableName} SET recoverCode=NULL, pass='".Hash::sha256($newPass)."' WHERE email='".$escape_mysql_string($this->email)."'");
			if ($db->sql_affectedrows() > 0) {
				$recoverMsg = _USER_GREETING."&nbsp;<b>".$this->name."</b>,<br />"._USER_RECOVER_1."<br />"._USER_YOUR_NEW_PASSWORD_IS.": <b>$newPass</b><br /><font color=\"red\">"._CHANGE_PASS."</font><br />"._USER_FOOTER;
			}else {
				$recoverMsg = _USER_GREETING."<b>".$this->name."</b>,<br />"._USER_ERROR_RECOVER_1."<br />"._USER_FOOTER;
			}

			$recoverURL = "".$urlsite."/index.php?f={$module_name}&do=recover&email={$this->email}&code=$recoverCode";
			$recoverMsg = _USER_GREETING.'<b>'.$this->name.'</b>,<br />'._USER_RECOVER_EXPLAIN.'<br />'._USER_CLICK_TO_RECOVER.":<br /> <a style=\"color:#f00\" href=\"$recoverURL\" target=\"_blank\">$recoverURL</a><br />";
			$recoverMsg .= _USER_THIS_LINK_IS_VALID_FOR." 24 "._USER_HOUR."<br />"._USER_FOOTER;
            // $mail = new sendMail();
			// $mail ->sendMailFree($this->email, $smtp_username, $smtp_password,'Tên trang web', _USER_RECOVER_SUBJECT, $recoverMsg);
			return $recoverMsg;
		} else return false;
	}

	function login($email, $password, $maxLoginAttempt, $url) {
		global $db, $module_name, $adminmail, $smtp_username, $smtp_password;

		$password = Hash::sha256($password);

		if ($this->loginAttempt >= $maxLoginAttempt) return USER_ACCOUNT_BLOCKED;
		else {
			if (($email == $this->email) && ($password == $this->password)) {
				$db->sql_query_simple("UPDATE {$this->tableName} SET loginAttempt=0 WHERE id={$this->id}");
				$this->setLoginAttempt(0);
				$_SESSION[$this->sess] = "$email;$password";
				return USER_LOGIN_SUCCEEDED;
			} else {
				$db->sql_query_simple("UPDATE {$this->tableName} SET loginAttempt=loginAttempt+1 WHERE id={$this->id}");
				$this->setLoginAttempt($this->loginAttempt + 1);
				if ($this->loginAttempt == $maxLoginAttempt) {
					$unblockCode = md5(uniqid(rand(), true));
					$db->sql_query_simple("UPDATE {$this->tableName} SET unblockCode='$unblockCode' WHERE id={$this->id}");
					$recoverURL = Common::constructURL($url, "?f={$module_name}&do=recover");
					$unblockURL = Common::constructURL($url, "?f={$module_name}&do=unblock&email=$email&code=$unblockCode");
					die($recoverURL."<br />".$unblockURL);
					$blockedMsg = _USER_GREETING.",<br />"._USER_BLOCKED_EXPLAIN.": <a href=\"$recoverURL\" target=\"_blank\">$recoverURL</a>.<br />";
					$blockedMsg .= _USER_CLICK_TO_UNBLOCK.": <a href=\"$unblockURL\" target=\"_blank\">$unblockURL</a>.<br />";
                    // $mail = new sendMail();
                    // $mail ->sendMailFree($email, $smtp_username, $smtp_password,'Tên trang web', _USER_ACCOUNT_BLOCKED_SUBJECT, $blockedMsg);
					return USER_LOGIN_FAILED_BLOCKED;
				}
				return USER_LOGIN_FAILED;
			}
		}
	}
}
?>