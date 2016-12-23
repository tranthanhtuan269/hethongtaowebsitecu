<?php
define('WEBUSER_REGISTRATION_SUCCEEDED', 1);
define('WEBUSER_REGISTRATION_FAILED', 0);
define('WEBUSER_EMAIL_REGISTERED', -1);

class WebUser extends User {
	var $address;
	var $phone;
	var $images;

	function WebUser() {
		global $prefix;

		$this->tableName = "{$prefix}_user";

		$numArgs = func_num_args();
		$args = func_get_args();
		if ($numArgs == 7) {
			parent::User($args[0], $args[1], $args[2], $args[5], USER_SESS);
			$this->setAddress($args[3]);
			$this->setPhone($args[4]);
			$this->setImages($args[6]);
		} elseif ($numArgs == 1) {
			$this->setSess(USER_SESS);
			return $this->getUser($args[0]);
		} elseif ($numArgs == 2) {
			$this->setSess(USER_SESS);
			return $this->getUser($args[0], $args[1]);
		} else {
			parent::User(USER_SESS);
			$this->setAddress('');
			$this->setPhone('');
			$this->setImages('');
		}
	}

	function setAddress($address) {
		$this->address = $address;
	}

	function setPhone($phone) {
		$this->phone = $phone;
	}
	function setImages($images) {
		$this->images = $images;
	}

	function register($url) {
		global $db, $escape_mysql_string, $module_name, $prefix, $activationPeriod, $smtp_username, $smtp_password, $adminmail;

		if (!empty($this->name) && !empty($this->email) && !empty($this->address) && !empty($this->phone)) {
			$name = $escape_mysql_string($this->name);
			$email = $escape_mysql_string($this->email);
			$address = $escape_mysql_string($this->address);
			$phone = $escape_mysql_string($this->phone);
			$pass = $escape_mysql_string($this->password);
			$images = $escape_mysql_string($this->images);
			$activationCode = md5(uniqid(rand(), true));
			$db->sql_query_simple("SELECT id FROM {$prefix}_user WHERE email='$email'");
			if ($db->sql_numrows() > 0) {
				return WEBUSER_EMAIL_REGISTERED;
			} else {
				$db->sql_query_simple("INSERT INTO {$this->tableName} (title, fullname, email, pass, address, phone, images, activationCode, registrationTime, loginAttempt) VALUES ({$this->sex}, '$name', '$email', '$pass', '$address', '$phone','$images', '$activationCode', NOW(), 0)");
				if ($db->sql_affectedrows() > 0) {
					$parsedURL = Common::constructURL($url, "?f=$module_name&do=activate&code=$activationCode&email={$this->email}");
					$msg = _USER_GREETING."&nbsp;<b>$name</b>";
					$msg .= ",<br/>"._USER_PLEASE_CLICK.": <a href=\"$parsedURL\" target=\"_blank\">$parsedURL</a><br />";
					$msg .= _USER_THIS_LINK_IS_VALID_FOR." $activationPeriod "._USER_HOUR."<br />"._USER_FOOTER;
					// $mail = new sendMail();
					// $mail ->sendMailFree($this->email, $smtp_username, $smtp_password,_USER_ACCOUNT_ACTIVATION, $adminmail, $msg);
					return WEBUSER_REGISTRATION_SUCCEEDED;
				} else return WEBUSER_REGISTRATION_FAILED."";
			}
		}
	}

	function update() {
		global $db, $escape_mysql_string;

		if (!empty($this->name) && !empty($this->email) && !empty($this->address) && !empty($this->phone) && ($this->id != -1)) {
			$name = $escape_mysql_string($this->name);
			$address = $escape_mysql_string($this->address);
			$phone = $escape_mysql_string($this->phone);
			$email = $escape_mysql_string($this->email);
			$pass = $escape_mysql_string($this->password);
			$images = $escape_mysql_string($this->images);
			$query = "UPDATE {$this->tableName} SET title={$this->sex}, fullname='$name', email='$email', address='$address', phone='$phone', images='$images'";
			if (!empty($pass)) $query .= ", pass='$pass'";
			$query .= " WHERE id={$this->id}";
			$ret = $db->sql_query_simple($query);
			return $ret;
		} else return false;
	}

	function getUser($id, $email = '') {
		global $db, $escape_mysql_string;

		if (empty($email)) $db->sql_query_simple("SELECT id, title, fullname, email, pass, address, phone, images, loginAttempt FROM {$this->tableName} WHERE id=".intval($id));
		else $db->sql_query_simple("SELECT id, title, fullname, email, pass, address, phone, images, loginAttempt FROM {$this->tableName} WHERE email='".$escape_mysql_string($email)."'");

		if ($db->sql_numrows() > 0) {
			list($id, $title, $name, $email, $pass, $address, $phone, $images, $loginAttempt) = $db->sql_fetchrow_simple();
			$this->WebUser($title, $name, $email, $address, $phone, $pass, $images);
			$this->setId($id);
			$this->setLoginAttempt($loginAttempt);
			return true;
		} else {
			$this->WebUser();
			return false;
		}
	}
}
?>