<?php
if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) die();
if (!defined('CMS_CONFIG')) die();

session_name("NVS");
session_start();


if (defined('CMS_ADMIN')) define('RPATH', '../');
else define('RPATH', './');

/* by Richard Heyes of phpguru.org */
function stripslashes_r($str)
{
	if (is_array($str)) {
		foreach ($str as $k => $v) {
			$str[$k] = stripslashes_r($v);
		}

		return $str;

	} else {
		return stripslashes($str);
	}
}

if (get_magic_quotes_gpc()) {
	foreach (array('_GET', '_POST', '_COOKIE') as $super) {
		foreach ($GLOBALS[$super] as $k => $v) {
			$GLOBALS[$super][$k] = stripslashes_r($v);
			if (ini_get("magic_quotes_sybase") == "1") $GLOBALS[$super][$k] = str_replace("''", "'", $v);
		}
	}
	if (!empty($_FILES)) {
		foreach ($_FILES as $f => $v) {
			$_FILES[$f]['name'] = stripslashes($v['name']);
			if (ini_get("magic_quotes_sybase") == "1") $_FILES[$f]['name'] = str_replace("''", "'", $v['name']);
		}
	}
}

set_magic_quotes_runtime(0);
@require_once(RPATH.DATAFOLD."/setting.php");
@include_once(RPATH."includes/security.php");
@require_once (RPATH.'includes/db.php');
@require_once (RPATH.'includes/rewrite.php');
if (defined('CMS_ADMIN')) {
	@include_once(RPATH."includes/login.php");
}
//require_once(RPATH."editor/fckeditor.php");
@require_once(RPATH."editor/ckeditor/ckeditor.php");
@require_once(RPATH."editor/ckfinder/ckfinder.php");
if($rewrite_mod == 1) {
	$siteurl = "http://".$_SERVER['HTTP_HOST']."";
	if($folder_site) {
		$siteurl .= "/$folder_site";
	}
} else {
	$siteurl = ".";
}
/*---------------*/
if($multilingual == 1) {
	if(!defined('CMS_ADMIN')) {
		//session_register("wld_lang");
		$_SESSION['wld_lang'] = "wld_lang";
		if(isset($_GET['lang']) || isset($_POST['lang'])) {
			$lang = trim(stripslashes(( isset($_POST['lang']) ) ? $_POST['lang'] : $_GET['lang']));
			if (file_exists(RPATH."language/".$lang."/main.php") && is_dir(RPATH."language/$lang") && ($lang != '.') && ($lang != '..')) {
				$_SESSION['wld_lang'] = $lang;
				include_once(RPATH."language/".$lang."/main.php");
				$currentlang = $lang;
			} elseif (file_exists(RPATH."language/".$language."/main.php")) {
				$_SESSION['wld_lang'] = $language;
				include_once(RPATH."language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("Error: Language files not found!");
			}
		} elseif (isset($_SESSION['wld_lang'])) {
			if (file_exists(RPATH."language/".$_SESSION['wld_lang']."/main.php") && is_dir(RPATH."language/{$_SESSION['wld_lang']}") && ($_SESSION['wld_lang'] != '.') && ($_SESSION['wld_lang'] != '..')) {
				include_once(RPATH."language/".$_SESSION['wld_lang']."/main.php");
				$currentlang = $_SESSION['wld_lang'];
			} elseif (file_exists(RPATH."language/".$language."/main.php")) {
				$_SESSION['wld_lang'] = $language;
				include_once(RPATH."language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		} else {
			if (file_exists(RPATH."language/".$language."/main.php")) {
				$_SESSION['wld_lang'] = $language;
				include_once(RPATH."language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		}
	} else {
		//session_register("wld_lang_adm");
		$_SESSION['wld_lang_adm'] = 'wld_lang_adm';
		if(isset($_GET['lang']) || isset($_POST['lang'])) {
			$lang = trim(stripslashes(( isset($_POST['lang']) ) ? $_POST['lang'] : $_GET['lang']));
			if (file_exists("language/".$lang."/main.php") && is_dir("language/$lang") && ($lang != '.') && ($lang != '..')) {
				$_SESSION['wld_lang_adm'] = $lang;
				include("language/".$lang."/main.php");
				$currentlang = $lang;
			} elseif (file_exists("language/".$language."/main.php")) {
				$_SESSION['wld_lang_adm'] = $language;
				include("language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		} elseif (isset($_SESSION['wld_lang_adm'])) {
			if (file_exists("language/".$_SESSION['wld_lang_adm']."/main.php") && is_dir("language/{$_SESSION['wld_lang_adm']}") && ($_SESSION['wld_lang_adm'] != '.') && ($_SESSION['wld_lang_adm'] != '..')) {
				include("language/".$_SESSION['wld_lang_adm']."/main.php");
				$currentlang = $_SESSION['wld_lang_adm'];
			} elseif (file_exists("language/".$language."/main.php")) {
				$_SESSION['wld_lang'] = $language;
				include("language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		} else {
			if (file_exists("language/".$language."/main.php")) {
				$_SESSION['wld_lang_adm'] = $language;
				include("language/".$language."/main.php");
				$currentlang = $language;
			} else {
				die("<center>Error: Language files not found!</center>");
			}
		}
	}
} else {
	if (file_exists("language/".$language."/main.php")) {
		include("language/".$language."/main.php");
		$currentlang = $language;
	} else {
		die("<center>Error: Language files not found!</center>");
	}
}

/*-------------------*/
$db = new sql_db($dbhost, $dbuname, $dbpass, $dbname, false);
$db->sql_query_simple("SET NAMES 'latin1'");
if(!$db->db_connect_id) {
	die("<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n<meta http-equiv=\"refresh\" content=\"5\"><title>$sitename</title></head>\n<body>\n<br><br><center><img border=\"0\" src=\"images/logo.jpg\"><br><br><b>"._SQLSERVERPROBLEM."</center></b></body></html>");
}


class Common {
	static function debug($msg) {
		if (defined('DEBUG') && DEBUG) die($msg);
		else die("Error");
	}

	static function getExt($file) {
		$temp = explode('.', $file);
		

		//var_dump($temp[sizeof($temp) - 1]);
		return strtolower($temp[sizeof($temp) - 1]);
	}

	static function recursiveCopy($src, $dest) {
		if (is_dir($src)) {
			@mkdir($dest);
			$handle = opendir($src);
			while (false !== ($file = readdir($handle))) {
				if (($file == '.') || ($file == '..')) continue;
				$file2 = "$src/$file";
				if (is_dir($file2)) Common::recursiveCopy($file2, "$dest/$file");
				else copy($file2, "$dest/$file");
			}
			closedir($handle);
		} else {
			copy($src, $dest);
		}
	}

	static function recursiveArrayKeyExists($key, $arr) {
		if (array_key_exists($key, $arr) === false) {
			foreach ($arr as $nextArr) {
				if (is_array($nextArr)) {
					$r2 = Common::recursiveArrayKeyExists($key, $nextArr);
					if ($r2 !== false) return $r2;
				}
			}
		} else return $arr;
		return false;
	}

	static function findAllKeys($arr, &$kList) {
		foreach ($arr as $key => $val) {
			$kList .= $key.':';
			if (is_array($val)) Common::findAllKeys($val, $kList);
		}
	}

	static function buildTree($a, &$na) {
		static $listRoot = array();
		static $listRootI = 0;
		$preserveI = false;
		if (count($na) < 1) {
			for ($i = 0; $i < count($a); $i++) {
				if ($a[$i]['parent'] == '0') {
					$idInt = intval($a[$i]['id']);
					$na[$idInt] = 0;
					$listRoot[] = $idInt;
				}
			}
			if (count($na) > 0) Common::buildTree($a, $na);
		} else {
			if (is_int($listRoot[$listRootI])) {
				for ($i = 0; $i < count($a); $i++) {
					if (isset($a[$i]) && isset($listRoot[$listRootI]) && ($listRoot[$listRootI] == intval($a[$i]['parent']))) {
						if (!isset($na[$listRoot[$listRootI]]) || !is_array($na[$listRoot[$listRootI]])) $na[$listRoot[$listRootI]] = array();
						$idInt = intval($a[$i]['id']);
						$na[$listRoot[$listRootI]][$idInt] = 0;
						$listRoot[] = $listRoot[$listRootI].':'.$a[$i]['id'];
						array_splice($a, $i, 1);
						$preserveI = true;
						Common::buildTree($a, $na);
					}
				}
			} elseif (is_string($listRoot[$listRootI])) {
				$parts = explode(':', $listRoot[$listRootI]);
				$countParts = count($parts);
				for ($i = 0; $i < count($a); $i++) {
					if (isset($a[$i]) && ($parts[$countParts - 1] == $a[$i]['parent'])) {
						$evalStr = '$arr = &$na';
						for ($g = 0; $g < $countParts; $g++) $evalStr .= "[{$parts[$g]}]";
						$evalStr .= ';';
						eval($evalStr);
						if (!isset($arr) || !is_array($arr)) $arr = array();
						$arr[intval($a[$i]['id'])] = 0;
						$listRoot[] = $listRoot[$listRootI].':'.$a[$i]['id'];
						array_splice($a, $i, 1);
						$preserveI = true;
						Common::buildTree($a, $na);
					}
				}
			}
		}
		if (!$preserveI) {
			$listRootI++;
			if ($listRootI < count($listRoot)) Common::buildTree($a, $na);
		}
	}

	static function constructURL($base, $suffix, $forceIndex = false) {
		$parsedURL = parse_url($base);
		$parsedURL2 = $parsedURL['scheme'].'://';
		if (!empty($parsedURL['user']) || !empty($parsedURL['pass'])) {
			if (!empty($parsedURL['user'])) $parsedURL2 .= $parsedURL['user'];
			if (!empty($parsedURL['pass'])) $parsedURL2 .= ":{$parsedURL['pass']}";
			$parsedURL2 .= "@";
		}
		$parsedURL2 .= $parsedURL['host'].$parsedURL['path'].$suffix;
		if ($forceIndex) $parsedURL2 = str_replace($_SERVER['REQUEST_URI'], '/'.url_sid("index.php"));
		return $parsedURL2;
	}

	static function makeDOB($year, $month, $day) {
		return strval(intval($year)).'-'.strval(intval($month)).'-'.strval(intval($day));
	}
}
$client_ip = $_SERVER['HTTP_CLIENT_IP'];
if (!strstr($client_ip,".")) $client_ip = $_SERVER['REMOTE_ADDR'];
if (!strstr($client_ip,".")) $client_ip = getenv( "REMOTE_ADDR" );
$client_ip = trim($client_ip);

$mainfile = 1;
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$start_time = $mtime;

$do_gzip_compress = FALSE;
function compress_output_gzip($output) {
	return gzencode($output);
}

function compress_output_deflate($output) {
	return gzdeflate($output, 9);
}

if($gzip_method == 1) {
	//gzip.php v1.2 - read http://rm.pp.ru/?1.phpgzip
	$PREFER_DEFLATE = false; // prefer deflate over gzip when both are supported
	$FORCE_COMPRESSION = false; // force compression even when client does not report support

	if(isset($_SERVER['HTTP_ACCEPT_ENCODING']))
	$AE = $_SERVER['HTTP_ACCEPT_ENCODING'];
	else
	$AE = $_SERVER['HTTP_TE'];

	$support_gzip = (strpos($AE, 'gzip') !== FALSE) || $FORCE_COMPRESSION;
	$support_deflate = (strpos($AE, 'deflate') !== FALSE) || $FORCE_COMPRESSION;

	if($support_gzip && $support_deflate) {
		$support_deflate = $PREFER_DEFLATE;
	}

	if ($support_deflate) {
		header("Content-Encoding: deflate");
		ob_start("compress_output_deflate");
	} else{
		if($support_gzip){
			header("Content-Encoding: gzip");
			ob_start("compress_output_gzip");
		} else {
			ob_start();
		}
	}
} else {
	ob_start();
}
//END gzip.php v1.2

if (!ini_get('register_globals')) {
	//@import_request_variables("GPC", "");
}


$client_ip = $_SERVER['HTTP_CLIENT_IP'];
if (!strstr($client_ip,".")) $client_ip = $_SERVER['REMOTE_ADDR'];
if (!strstr($client_ip,".")) $client_ip = getenv( "REMOTE_ADDR" );
$client_ip = trim($client_ip);

if($eror_value==1) {
	@ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	@ini_set('display_errors', 0);
	error_reporting(0);
}

/*------------------*/
unset($admin, $user, $adm_name, $adm_super, $admin_ar, $user_ar, $mbrow);
if(isset($_SESSION[ADMIN_SES]) && !empty($_SESSION[ADMIN_SES])) {
	$admin = base64_encode(addslashes(base64_decode($_SESSION[ADMIN_SES])));
	if(!is_array($admin)) {
		$admin_ar = explode("#:#", addslashes(base64_decode($admin)));
	}
	if (substr(addslashes($admin_ar[0]), 0, 25)!="" && $admin_ar[1]!="") {
		$admsql = "SELECT pwd, adname, permission, mods, menus FROM ".$prefix."_admin WHERE adacc='".trim(substr(addslashes($admin_ar[0]), 0, 25))."' AND checknum = '$admin_ar[2]' AND agent = '$admin_ar[3]' AND last_ip = '$admin_ar[4]'";
		$admresult = $db->sql_query_simple($admsql);
		$pass = $db->sql_fetchrow_simple($admresult);
		$db->sql_freeresult($admresult);
		if (($pass[0] == $admin_ar[1]) && !empty($pass[0]) && ($admin_ar[3] == substr (trim ($_SERVER['HTTP_USER_AGENT']), 0, 80))) {
			define('iS_ADMIN', true);
			$adm_name = addslashes($pass[1]);
			$adm_super = intval($pass[2]);
			$adm_mods = $pass[3];
			$adm_mods_ar = @explode("|",$adm_mods);
			$adm_menus_ar = @explode("|",$adm_menus);

			if($adm_super==1) {
				define('iS_SADMIN', true);
			}
			if($adm_name == "Root" && $adm_super == 2) {
				define('iS_RADMIN', true);
			}
		} else {
			unset ($_SESSION[ADMIN_SES]);
		}
	}
}

function checkUser() {
	global $db, $prefix, $escape_mysql_string;

	if (isset($_SESSION[USER_SESS]) && !empty($_SESSION[USER_SESS])) {
		$userArr = explode(';', $_SESSION[USER_SESS]);
		$db->sql_query_simple("SELECT id, title, fullname, address, phone, email, money FROM {$prefix}_user WHERE email='".$escape_mysql_string($userArr[0])."' AND pass='".$escape_mysql_string($userArr[1])."'");
		if ($db->sql_numrows() > 0) {
			if (!defined('iS_USER')) define('iS_USER', true);
			$userInfo = array();
			list($userInfo['id'], $userInfo['title'], $userInfo['fullname'], $userInfo['address'], $userInfo['phone'], $userInfo['email'], $userInfo['money']) = $db->sql_fetchrow_simple();
			return $userInfo;
		} else {
			unset($_SESSION[USER_SESS]);
			return false;
		}
	} else {
		return false;
	}
}
$userInfo = checkUser();
if (!$userInfo) unset($userInfo);

if (isset($_SESSION[JOB_SESS]) && !empty($_SESSION[JOB_SESS])) {
	$jobUserArr = explode(';', $_SESSION[JOB_SESS]);
	$db->sql_query_simple("SELECT id, name, userType, sex, nationality, region, receiveNewsletter, dob, experience, lastJob, currentPos, qual, salary, address, country, homePhone, cellPhone, maritalStatus, photo FROM {$prefix}_job_user WHERE email='".$escape_mysql_string($jobUserArr[0])."' AND pass='".$escape_mysql_string($jobUserArr[1])."'");
	if ($db->sql_numrows() > 0) {
		define('iS_JOB_USER', true);
		$jobUserInfo = array();
		list($jobUserInfo['id'], $jobUserInfo['name'], $jobUserInfo['userType'], $jobUserInfo['sex'], $jobUserInfo['nationality'], $jobUserInfo['region'], $jobUserInfo['receiveNewsletter'], $jobUserInfo['dob'], $jobUserInfo['experience'], $jobUserInfo['lastJob'], $jobUserInfo['currentPos'], $jobUserInfo['qual'], $jobUserInfo['salary'], $jobUserInfo['address'], $jobUserInfo['country'], $jobUserInfo['homePhone'], $jobUserInfo['cellPhone'], $jobUserInfo['maritalStatus'], $jobUserInfo['photo']) = $db->sql_fetchrow_simple();
	} else {
		unset($_SESSION[JOB_SESS]);
	}
}

function admModCheck($mod) {
	global $adm_mods_ar;
	if((defined('iS_ADMIN') && @in_array($mod,$adm_mods_ar)) || defined('iS_SADMIN') || defined('iS_RADMIN')) {
		return true;
	}
	return false;
}

#############thongketruycap
if (!defined('CMS_ADMIN') AND $counteract == 1) {
	// $result = mysql_query("SELECT * FROM ".$prefix."_stats");
	// if (!$result) {
	//     echo 'Could not run query: ' . mysql_error();
	//     exit;
	// }
	// $row = mysql_fetch_row($result);
	// var_dump($row);die;

	//var_dump($db->sql_fetchrow_simple($db->sql_query_simple("SELECT * FROM ".$prefix."_stats")));die;

	list($online, $statclients, $stathits) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT * FROM ".$prefix."_stats"));
	//die($stathits);
	//list($online, $statclients, $stathits) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT * FROM ".$prefix."_stats"));

	$past = time()-60;
	$onls_g = "";//khach online
	$onls_m = "";//memb online
	$uname = $client_ip;
	if($online!="") {
		$online1 = explode("|",$online);
		$g=0;
		$g_online[0] = "";
		$m=0;
		$m_online[0] = "";
		for($l=0; $l < sizeof($online1); $l++) {
			$online2 = explode(":",$online1[$l]);
			if(intval($online2[2]) > $past) {
				if($online2[1]==1) {
					if($onls_g!="") { $onls_g .= "|"; }
					if($online2[0]!=$uname) {
						$onls_g .= $online1[$l];
					} else {
						$onls_g .= $online2[0].":1:".time();
					}
					$g_online[$g] = $online2[0];
					$g++;
				} else {
					if($onls_m!="") { $onls_m .= "|"; }
					if($online2[0]!=$uname) {
						$onls_m .= $online1[$l];
					} else {
						$onls_m .= $online2[0].":0:".time();
					}
					$m_online[$m] = $online2[0];
					$m++;
				}
			}
		}
		if(!in_array($uname,$g_online) AND !in_array($uname,$m_online)) {
			if($onls_g!="") { $onls_g .= "|"; }
			$onls_g .= $uname.":1:".time();
		}
	} else {
		$onls_g = $uname.":1:".time();
	}
	if($onls_g=="") { $onls_t = $onls_m; }
	elseif($onls_m=="") { $onls_t = $onls_g; }
	elseif($onls_g!="" AND $onls_m!="") { $onls_t = $onls_g."|".$onls_m; }

	$stats_time = time() - intval($timecount);
	$statcls = "";//so ip truy cap trong khoang thoi gian timecount
	$stathits1 = intval($stathits);//tong so truy cap
	if($statclients!="") {
		$statclients_ar = explode("|",$statclients);
		$m=0;
		$statip[0] = "";
		for($l=0;$l < sizeof($statclients_ar);$l++) {
			$statclients_ar2 = explode(":",$statclients_ar[$l]);
			if(intval($statclients_ar2[1]) > $stats_time) {
				if($statcls != "") { $statcls .= "|"; }
				$statcls .= $statclients_ar[$l];
				$statip[$m] = $statclients_ar2[0];
				$m++;
			}
		}
		if(!in_array($client_ip,$statip)) {
			if($statcls != "") { $statcls .= "|"; }
			$statcls .= $client_ip.":".time();
			$stathits1++;
		}
	} else {
		$statcls = $client_ip.":".time();
		$stathits1++;
	}

	if($onls_t!="$online" || $statcls!="$statclients" || $stathits1!=$stathits) {
		$db->sql_query_simple("UPDATE {$prefix}_stats SET online='".$onls_t."', clients='".$statcls."', hits='".$stathits1."'");
	}

}

function del_online($del) {
	global $db,$prefix;
	list($online) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT online FROM ".$prefix."_stats"));
	$onl1 = explode("|",$online);
	$onl="";
	for($z=0; $z < sizeof($onl1); $z++) {
		$onl2 = explode(":",$onl1[$z]);
		if($onl2[0]!=$del) {
			if($onl!="") { $onl .= "|"; }
			$onl .= "".$onl1[$z]."";
		}
	}
	$db->sql_query_simple("UPDATE ".$prefix."_stats SET online='".$onl."'");
}
###############end

$mfhandle=@opendir(RPATH."includes");
while ($mffile = @readdir($mfhandle)) {
	if((substr(strtolower($mffile), -4) == '.php') && !in_array($mffile, array("functions.php", "security.php", "login.php", "rewrite.php"))) {
		include_once(RPATH."includes/$mffile");
	}
}
@closedir($mfhandle);

/*if($rewrite_mod == 1 && (strpos($_SERVER['REQUEST_URI'], "index.php") !== false) && !defined('CMS_ADMIN')) {
message_sys(_MESSAGESYS,_FILENOTFOUND,1,0);
die();
}*/

$site_redirect ="";
if (!defined('NO_REDIRECT') AND !defined('IN_AJAX') AND !defined('CMS_ADMIN')) {
	$_SESSION['sys_redirect'] = $_SERVER['REQUEST_URI'];
	$site_redirect = "http://".$_SERVER["SERVER_NAME"]."".$_SESSION["sys_redirect"]."";
}

function get_lang($module, $mod_name="") {
	global $currentlang, $language;
	if($module == "admin") {
		if (file_exists("language/$currentlang/main.php")) {
			@include_once("language/$currentlang/main.php");
		}

		if (file_exists("language/".$currentlang."/".$mod_name.".php")) {
			@include_once("language/".$currentlang."/".$mod_name.".php");
		}
	}else{
		if (file_exists("language/".$currentlang."/".$module.".php")) {
			@include_once("language/".$currentlang."/".$module.".php");
		}
	}
}

function getlangmod($module) {
	global $currentlang, $language;
	if (file_exists("language/".$currentlang."/".$module.".php")) {
		@include_once("language/".$currentlang."/".$module.".php");
	}
}

function mod_active($module) {
	global $prefix, $db;
	$module = trim(stripslashes(resString($module)));
	$result = $db->sql_query_simple("SELECT active, view FROM ".$prefix."_modules WHERE title='$module'");
	list($act, $view) = $db->sql_fetchrow_simple($result);
	$act = intval($act);
	$view = intval($view);
	if (!$result || $act == 0 || ($view == 1 && !defined('iS_SADMIN'))) {
		return 0;
	} else {
		return 1;
	}
	$db->sql_freeresult($result);
}

function WeightMax($table, $parentid="", $poz="", $parentIDName = 'parentid') {
	global $db, $prefix, $currentlang;
	if($parentid) {
		list($xweight) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT MAX(weight) AS xweight FROM ".$prefix."_".$table." WHERE alanguage='$currentlang' AND $parentIDName=$parentid"));
	}elseif ($poz) {
		list($xweight) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT MAX(weight) AS xweight FROM ".$prefix."_".$table." WHERE alanguage='$currentlang' AND position='$poz'"));
	} else {
		list($xweight) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT MAX(weight) AS xweight FROM ".$prefix."_".$table." WHERE alanguage='$currentlang'"));
	}
	if ($xweight == -1) { $weight = 1; } else { $weight = $xweight+1; }
	return $weight;
}

function resString ($what = "") {
	$what = str_replace("'", "''", $what);
	while (strpos($what, "\\\\'") !== false) {
		$what = str_replace("\\\\'", "'", $what);
	}
	return $what;
}

/*********************************************************/
/* text filter                                                 */
/*********************************************************/

function check_words($Message) {
	global $CensorMode, $EditedMessage, $datafold;
	include("".RPATH."$datafold/config.php");
	$EditedMessage = $Message;
	if ($CensorMode != 0) {
		if (is_array($CensorList)) {
			$Replace = $CensorReplace;
			if ($CensorMode == 1) {

				for ($i = 0; $i < count($CensorList); $i++) {
					$EditedMessage = str_replace("$CensorList[$i]([^a-zA-Z0-9])","$Replace\\1",$EditedMessage);
				}
			} elseif ($CensorMode == 2) {
				for ($i = 0; $i < count($CensorList); $i++) {
					$EditedMessage = str_replace("(^|[^[:alnum:]])$CensorList[$i]","\\1$Replace",$EditedMessage);
				}
			} elseif ($CensorMode == 3) {
				for ($i = 0; $i < count($CensorList); $i++) {
					$EditedMessage = str_replace("$CensorList[$i]","$Replace",$EditedMessage);
				}
			}
		}
	}
	return ($EditedMessage);
}

function delQuotes($string){
	/* no recursive function to add quote to an HTML tag if needed */
	/* and delete duplicate spaces between attribs. */
	$tmp="";        # string buffer
	$result=""; # result string
	$i=0;
	$attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
	$quote=0;        # Is a string quote delimited opened ? 0=no, 1=yes
	$len = strlen($string);
	while ($i<$len) {
		switch($string[$i]) { # What car is it in the buffer ?
			case "\"": #"        # a quote.
			if ($quote==0) {
				$quote=1;
			} else {
				$quote=0;
				if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
				$tmp="";
				$attrib=-1;
			}
			break;
			case "=":                # an equal - attrib delimiter
			if ($quote==0) {  # Is it found in a string ?
				$attrib=1;
				if ($tmp!="") $result.=" $tmp";
				$tmp="";
			} else $tmp .= '=';
			break;
			case " ":                # a blank ?
			if ($attrib>0) {  # add it to the string, if one opened.
				$tmp .= $string[$i];
			}
			break;
			default:                # Other
			if ($attrib<0)          # If we weren't in an attrib, set attrib to 0
			$attrib=0;
			$tmp .= $string[$i];
			break;
		}
		$i++;
	}
	if (($quote!=0) && ($tmp != "")) {
		if ($attrib==1) $result .= "=";
		/* If it is the value of an atrib, add the '=' */
		$result .= "\"$tmp\"";        /* Add quote if needed (the reason of the function ;-) */
	}
	return $result;
}

function check_html($str, $strip="") {
	/* The core of this code has been lifted from phpslash */
	/* which is licenced under the GPL. */
	include(RPATH.DATAFOLD."/setting.php");
	if ($strip == "nohtml")
	$AllowableHTML=array('');
	$str = stripslashes($str);
	$str = str_replace("<[[:space:]]*([^>]*)[[:space:]]*>",'<\\1>', $str);
	// Delete all spaces from html tags .
	$str = str_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",'<a href="\\1">', $str);
	// Delete all attribs from Anchor, except an href, double quoted.
	$str = str_replace("<[[:space:]]* img[[:space:]]*([^>]*)[[:space:]]*>", '', $str);
	// Delete all img tags
	$str = str_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?javascript[[:punct:]]*\"?[^>]*>", '', $str);
	// Delete javascript code from a href tags -- Zhen-Xjell @ http://nukecops.com
	$tmp = "";
	while (@ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
		$i = strpos($str,$reg[0]);
		$l = strlen($reg[0]);
		if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
		else $tag = strtolower($reg[1]);
		if ($a = $AllowableHTML[$tag])
		if ($reg[1][0] == "/") $tag = "</$tag>";
		elseif (($a == 1) || ($reg[2] == "")) $tag = "<$tag>";
		else {
			# Place here the double quote fix function.
			$attrb_list=delQuotes($reg[2]);
			// A VER
			$attrb_list = str_replace("&","&amp;",$attrb_list);
			$tag = "<$tag" . $attrb_list . ">";
		} # Attribs in tag allowed
		else $tag = "";
		$tmp .= substr($str,0,$i) . $tag;
		$str = substr($str,$i+$l);
	}
	$str = $tmp . $str;
	return $str;
	exit;
	/* Squash PHP tags unconditionally */
	$str = str_replace("<\?","",$str);
	return $str;
}

function filter_text($Message, $strip="") {
	global $EditedMessage;
	check_words($Message);
	$EditedMessage=check_html($EditedMessage, $strip);
	return ($EditedMessage);
}

function ext_time($vtime,$ht) {
	global $hourdiff, $htg1, $htg2;
	if ($ht == 2) { $xht = $htg2; } else { $xht = $htg1; }

	$timeadjust = ($hourdiff * 60);
	$viewtime = date("$xht", $vtime + $timeadjust);
	return($viewtime);
}

function NameDay($time) {
	global $hourdiff;

	$timeadjust = ($hourdiff * 60);
	$weekday = array(_SUND, _MON, _TUE, _WED, _THU, _FRI, _SAT);
	$datename = $weekday[date("w",$time+$timeadjust)];

	return $datename;
}
//kiem tra mail
function is_email($str){
	$pos = strpos($str,"@");
	if($pos==false){
		return 0;
	}
	$user = substr($str,0,$pos);
	$domain = substr($str,$pos+1);
	$pos = strrpos($domain,".");
	if($pos==false){
		return 0;
	}
	$subdomain = substr($domain,0,$pos);
	$topdomain = substr($domain,$pos+1);
	return ((is_topdomain($topdomain))&&(is_subdomain($subdomain))&&(is_subdomain($user)));
}
//kiem tra mail
function is_valid_email($email)
{
	if(preg_match("/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0)
	return true;
else
	return false;
}
function is_topdomain($str){
	if(preg_match("!^(ad|ae|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gov|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nato|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$!i", $str)){
		return 1;
	} else {
		return 0;
	}
}
function is_subdomain($str){
	if (preg_match('!^([a-zA-Z0-9_-]+(\.?[a-zA-Z0-9_-]+)*)$!', $str)){
		return 1;
	} else {
		return 0;
	}
}

function is_phone($str){
	if (preg_match("!^([0-9]{6,16})$!", $str)){
		return 1;
	} else {
		return 0;
	}
}
function is_mobile($str){
	if (preg_match("!^([0-9]{9,16})$!", $str)){
		return 1;
	} else {
		return 0;
	}
}
#######################################
# CHECK NUMBER
#######################################
function is_number($str){
	if(preg_match("!^([0-9]{1,15})$!",$str)){
		return 1;
	} else {
		return 0;
	}
}

function is_url($str){
	$domain_Pattern = '!^(?:[a-zA-Z0-9_-]\.)+(?:ad|ae|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gov|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nato|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)(?:\:[0-9]+)?$!i';
	$subURL_Pattern = '!^(?:/\w+(?:\-\w+)*(?:\.\w+(?:\-\w+)*)*){1,}$!';
	$subURL = strstr($str,"/");
	$domain = substr($str,0,(strlen($str)-strlen($subURL)));
	if(!preg_match($domain_Pattern,$domain)){
		return 0;
	}
	if(($subURL=='')||(strlen($subURL)==1)){
		return 1;
	}
	if(!preg_match($subURL_Pattern,$subURL)){
		return 0;
	}
	return 1;
}

function exc_time($d, $m, $y, $h, $n) {
	$time_post ="";
	$time_post = mktime($h, $n, 0, $m, $d, $y);
	return $time_post;
}

function checkPermAdm($admod) {
	global $adm_mods;
	$modlist = @explode("|",$adm_mods);
	if((@in_array($admod,$modlist) && defined('iS_ADMIN')) || defined('iS_RADMIN') || defined('iS_SADMIN')) {
		return true;
	}else{
		return false;
	}
}

function select_language($alang) {
	$langlist = '';
	$handle=opendir(RPATH."language");
	while ($file = readdir($handle)) {
		if (($file != ".") && ($file !="..")) {
			if (is_dir(RPATH."/language/$file")){
				if($alang == $file) { $seldalang =" selected"; } else { $seldalang =""; }
				$langlist .= "<option value=\"$file\"$seldalang>$file</option>";
			}
		}
	}
	closedir($handle);
	return $langlist;
}

function editor($content,$value="",$width,$height) {
	if(empty($width)) { $width = "100%"; }
	if(empty($height)) { $height = 100; }
	$CKEditor = new CKeditor() ;
	CKFinder::SetupCKEditor( $CKEditor, ''.RPATH.'editor/ckfinder/' ) ;
	$CKEditor->editor($content,$value);
}

function editorbasic($content,$value="",$width,$height) {
	if(empty($width)) { $width = "100%"; }
	if(empty($height)) { $height = 300; }
	$CKEditor = new CKeditor() ;
	$config = array();
	$config['toolbar'] = array(
		array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		array( 'Image', 'Link', 'Unlink', 'Anchor' )
	);
	CKFinder::SetupCKEditor( $CKEditor, ''.RPATH.'editor/ckfinder/' ) ;
	$CKEditor->editor($content,$value,$config);
}

function editbasic($content,$value="",$width,$height) {
	if(empty($width)) { $width = "80%"; }
	if(empty($height)) { $height = 100; }
	$CKEditor = new CKeditor() ;
	$config = array();
	$config['toolbar'] = array(
		array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		array( 'Image', 'Link', 'Unlink', 'Anchor' )
	);
	CKFinder::SetupCKEditor( $CKEditor, ''.RPATH.'editor/ckfinder/' ) ;
	$CKEditor->editor($content,$value,$config);
}


/////////////// phân trang //////////////////////
// $total		: tổng số
// $pageurl		: đường dẫn module
// $perpage
// $page
// $book
// $mark
function paging($total,$pageurl,$perpage,$page,$book="",$mark = 0) {
	if($mark == 1) { $target ="?"; } else { $target ="&"; }
	$page = intval($page);
	if($page == 0) {$page = 1;}
	@$numpages = ceil($total / $perpage);
	$res = '';
	if ($numpages > 1) {
		$res .= "<div class=\"page\">\n";
		// $res .= "<div class=\"pagenumactive\"><span>"._PAGE." <strong>$page</strong>/$numpages</span></div>";
		if ($page > 1) {
			$prevpage = $page - 1 ;
			$leftarrow = "images/left.gif" ;
			$res .= "<div class=\"pagenum\"><a  href=\"".url_sid($pageurl)."$book\" title=\""._FIRSTPAGE."\">&lsaquo;&lsaquo;</div><div class=\"pagenum\"><a  href=\"".url_sid($pageurl,"","".$target."page=$prevpage")."$book\" title=\""._PREPAGE."\">&lsaquo;</a></div>";

		}
		for ($i=1; $i < $numpages+1; $i++) {
			if ($i == $page) {
				$res .= "<div class=\"pagenumactive\"><span><strong>$i</strong></div>";
			}
			else {
				$pagelink = 2;
				if (($i > $page) AND ($i < $page+$pagelink) OR ($i < $page) AND ($i > $page-$pagelink)) {
					$res .= " <div class=\"pagenum\"><a  href=\"".url_sid($pageurl,"","".$target."page=$i")."$book\">$i</a></div> ";
				}
				if (($i == $numpages) AND ($page < $numpages-$pagelink)){
					$res .= "<div class=\"pagenum\">... <a  href=\"".url_sid($pageurl,"","".$target."page=$i")."$book\">$i</a></div>";
				}
				if (($i == 1) AND ($page > 1+$pagelink)){
					$res .= "<div class=\"pagenum\"><a  href=\"".url_sid($pageurl,"","".$target."page=$i")."$book\">$i</a> ...</div>";
				}
			}
		}
		if ($page < $numpages) {
			$nextpage = $page + 1 ;
			$rightarrow = "images/right.gif" ;
			$res .= "<div class=\"pagenum\"><a  href=\"".url_sid("$pageurl","","".$target."page=$nextpage")."$book\" title=\""._NEXTPAGE."\">&rsaquo;</a></div><div class=\"pagenum\"><a  href=\"".url_sid("$pageurl","","".$target."page=$numpages")."$book\" title=\""._FINISHPAGE."\">&rsaquo;&rsaquo;</a></div>";
		}
		$res .= "</div>";
	}
	return $res;
}

function pagging($total,$pageurl,$perpage,$page,$id) {
	$page = intval($page);
	if($page == 0) {$page = 1;}
	@$numpages = ceil($total / $perpage);
	$res = '';
	if ($numpages > 1) {
		$res .= "<table class=\"page\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
		$res .= "<tr>\n";

		for ($i=1; $i < $numpages+1; $i++) {
			if ($i == $page) {
				$res .= "<td><div class=\"pagenumactive\"><b>$i</b></div></td>";
			}
			else {
				$pagelink = 5;
				if (($i > $page) AND ($i < $page+$pagelink) OR ($i < $page) AND ($i > $page-$pagelink)) {
					$res .= " <td><div class=\"pagenum\"><a href=\"".url_sid("index.php?f=products&do=page&id=$id&page=$i")."\"					onclick=\"ajaxinfoget('index.php?f=products&do=page&id=$id&page=$i','ajaxload_container', 'comment','');return false;\"
					>$i</a></div></td> ";
				}
				if (($i == $numpages) AND ($page < $numpages-$pagelink)){
					$res .= "<td><div class=\"pagenum\">... <a href=\"".url_sid("index.php?f=products&do=page&id=$id&page=$i")."\" onclick=\"ajaxinfoget('index.php?f=products&do=page&id=$id&page=$i','ajaxload_container', 'comment','');return false;\"  >$i</a></div></td>";
				}
				if (($i == 1) AND ($page > 1+$pagelink)){
					$res .= "<td><div class=\"pagenum\"><a href=\"".url_sid("index.php?f=products&do=page&id=$id&page=$i")."\" onclick=\"ajaxinfoget('index.php?f=products&do=page&id=$id&page=$i','ajaxload_container', 'comment','');return false;\" >$i</a> ...</div></td>";
				}
			}
		}

		$res .= "</tr></table>";
	}
	return $res;
}

function removecrlf($str) {
	return strtr($str, "\015\012", ' ');
}

function cutText($str, $text_long) {
	$str = strip_tags($str);
	if(strlen($str) > $text_long) {
		$str = "".substr($str, 0, $text_long)."";
		$tdes= explode(" ", $str);
		$str = substr($str, 0, strlen($str)-strlen($tdes[sizeof($tdes)-1])-1);
		$str = "".$str."...";
	}
	return $str;
}

function get_path($time) {
	$year = date("Y",$time);
	$month = date("m",$time);
	$path = $year."_".$month;
	return $path;
}

function message_sys($title, $message, $load ="", $back="") {
	global $sitename, $Default_Temp, $siteurl;
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">\n";
	echo "<title>$sitename- $title</title>\n";
	echo "<link rel=\"StyleSheet\" href=\"templates/".$Default_Temp."/css/styles.css\" type=\"text/css\">\n";
	echo "</head>\n";
	echo "<body bgcolor=\"#CCCCCC\">\n";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse; margin-top: 150px\">\n";
	echo "<tr>\n";
	echo "<td align=\"center\">\n";
	echo "<table border=\"1\" bgcolor=\"#FFFFFF\" cellpadding=\"5\" style=\"border-collapse: collapse\" width=\"65%\" bordercolor=\"#035683\">\n";
	echo "<tr>\n";
	echo "<td bgcolor=\"#1E84BC\" background=\"images/blbg.gif\" class=\"titlearl\"><b><font color=\"#FFFFFF\">$title....</font></b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td style=\"padding: 10px\" class=\"titlearl\" align=\"center\">$message";
	if($load) {
		echo "<div align=\"center\" style=\"margin-top: 10px\"><img border=\"0\" src=\"images/loading.gif\"></div>\n";
	}
	if($back) {
		echo "<div align=\"center\" style=\"margin-top: 10px\"><input type=\"button\" value=\""._BACK."\" onclick=\"history.back(1);\"></div>\n";
	}
	echo "</td></tr></table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "\n";
	echo "</body>\n";
	echo "\n";
	echo "</html>\n";
}

function nospatags($str) {
	global $escape_mysql_string;
	$str = $escape_mysql_string(trim((check_html($str, "nohtml"))));
	return $str;
}

function generate_code($chars){
	$r ="";
	for($i=0;$i<=($chars-1);$i++){
		$r0 = rand(0,1); $r1 = rand(0,2);
		if($r0==0){$r .= chr(rand(ord('A'),ord('Z')));}
		elseif($r0==1){ $r .= rand(0,9); }
		if($r1==0){ $r = strtolower($r); }
	}
	return $r;
}

function foldcreate($modname) {
	global $path_upload;
	$now_month = date("m");
	$now_year = date("Y");
	if((!file_exists("".RPATH."".$path_upload."/".$modname."/".$now_year."_".$now_month.""))||(!is_dir("".RPATH."".$path_upload."/".$modname."/".$now_year."_".$now_month.""))){
		@mkdir("".RPATH."".$path_upload."/".$modname."/".$now_year."_".$now_month."");
	}
}

#######################################
# URL OPTIMIZATION
#######################################
function url_optimization($name) {
	$name = preg_replace('/&.+?;/', '', utf8_to_ascii($name));
	$name = str_replace('_', '-', $name );
	$name = preg_replace('/[^a-z0-9\s-.]/i', '', $name);
	$name = preg_replace('/\s+/', '-', $name);
	$name = preg_replace('|-+|', '-', $name);
	$name = trim($name, '-');
	return $name;
}
#######################################
# CONVERT UTF8 TO ASCII
#######################################
function utf8_to_ascii($str) {
	$chars = array(
		'a'	=>	array('A','ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),
		'e' =>	array('E','ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),
		'i'	=>	array('I','í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),
		'o'	=>	array('O','ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),
		'u'	=>	array('U','ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),
		'y'	=>	array('Y','ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
		'd'	=>	array('D','đ','Đ'),
		'q'	=>	array('Q'),
		'w'	=>	array('W'),
		'r'	=>	array('R'),
		't'	=>	array('T'),
		'p'	=>	array('P'),
		's'	=>	array('S'),
		'f'	=>	array('F'),
		'g'	=>	array('G'),
		'h'	=>	array('H'),
		'j'	=>	array('J'),
		'k'	=>	array('K'),
		'l'	=>	array('L'),
		'z'	=>	array('Z'),
		'x'	=>	array('X'),
		'c'	=>	array('C'),
		'v'	=>	array('V'),
		'b'	=>	array('B'),
		'n'	=>	array('N'),
		'm'	=>	array('M'),
	);
	foreach ($chars as $key => $arr){
		foreach ($arr as $val){
			$str = str_replace($val, $key, $str);
		}
	}
	return trim($str);
}

//Hàm chuyển đổi tiêu đề tiếng việt có dấu sang không dấu

function cv2urltitle($text) {

$text = str_replace(
array(' ','%',"/","\\",'"','?','<','>',"#","^","`","'","=","!",":" ,",,","..","*","&","__","▄"),
array('-','' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'-','' ,'-','' ,'' ,'' , "_" ,"" ,""),
$text);

$chars = array("a","A","e","E","o","O","u","U","i","I","d", "D","y","Y");

$uni[0] = array("á","à","ạ","ả","ã","â","ấ","ầ","ậ","ẩ","ẫ","ă","ắ","ằ","ặ","ẳ","�� �");
$uni[1] = array("Á","À","Ạ","Ả","Ã","Â","Ấ","Ầ","Ậ","Ẩ","Ẫ","Ă","Ắ","Ằ","Ặ","Ẳ","�� �");
$uni[2] = array("é","è","ẹ","ẻ","ẽ","ê","ế","ề","ệ","ể","ễ");
$uni[3] = array("É","È","Ẹ","Ẻ","Ẽ","Ê","Ế","Ề","Ệ","Ể","Ễ");
$uni[4] = array("ó","ò","ọ","ỏ","õ","ô","ố","ồ","ộ","ổ","ỗ","ơ","ớ","ờ","ợ","ở","�� �");
$uni[5] = array("Ó","Ò","Ọ","Ỏ","Õ","Ô","Ố","Ồ","Ộ","Ổ","Ỗ","Ơ","Ớ","Ờ","Ợ","Ở","�� �");
$uni[6] = array("ú","ù","ụ","ủ","ũ","ư","ứ","ừ","ự","ử","ữ");
$uni[7] = array("Ú","Ù","Ụ","Ủ","Ũ","Ư","Ứ","Ừ","Ự","Ử","Ữ");
$uni[8] = array("í","ì","ị","ỉ","ĩ");
$uni[9] = array("Í","Ì","Ị","Ỉ","Ĩ");
$uni[10] = array("đ");
$uni[11] = array("Đ");
$uni[12] = array("ý","ỳ","ỵ","ỷ","ỹ");
$uni[13] = array("Ý","Ỳ","Ỵ","Ỷ","Ỹ");

for($i=0; $i<=13; $i++) {
$text = str_replace($uni[$i],$chars[$i],$text);
}

return $text;
}

function sendmail($subject, $mailto, $sender_mail, $message, $extraHeader = "", $plainBody = "") {
	global $smtp_mail, $smtp_host, $smtp_username, $smtp_password, $smtp_port;

	if ($smtp_mail == 1) $m = new Mail($sender_mail, $mailto, $subject, $message, "SMTP", $smtp_host, $smtp_username, $smtp_password, $smtp_port);
	else $m = new Mail($sender_mail, $mailto, $subject, $message);
	if (!empty($plainBody)) $m->setPlainBody($plainBody);

	$ret = $m->send();
	return $ret;
}

function useragentrs() {
	global $client_ip;
	$agent = substr (trim ($_SERVER['HTTP_USER_AGENT']), 0, 80);
	$addr_ip = substr (trim ($client_ip), 0, 15);

	$client = "{$agent}{$addr_ip}";
	$client = md5($client);

	return $client;
}

function currency_select($curr) {
	$curr_arr = array(_VND,_USD);
	for($i =0; $i < sizeof($curr_arr); $i ++) {
		$seld ="";
		if($curr == $i) { $seld =" selected"; }
		echo "<option value=\"$i\"$seld>$curr_arr[$i]</option>\n";
	}
}

function dsprice($price) {
	$price = number_format($price, 0,'.','.');
	return $price;
}

function info_exit($text, $goback="") {
	message_sys(_MESSAGESYS, $text, $load ="", $goback);
	exit();
}

function truncate_table($t) {
	global $db, $prefix;
	if ($db->sql_numrows($db->sql_query_simple("SELECT * FROM ".$prefix."_".$t)) == 0) {
		$db->sql_query_simple("TRUNCATE TABLE ".$prefix."_".$t);
	}
}

function ajaxload_content() {
	echo "<div id=\"ajaxload_container\" style=\"display: none; text-align:center; width:auto; height:auto; margin: 0 auto;\">\n";
	echo "<div id=\"ajaxload_content\">\n";
	echo "<img src=\"images/load_bar.gif\" border=\"0\" alt=\"\">\n";
	echo "</div>\n";
	echo "</div>\n";
}

function subcat_home($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='$catid' AND catid!='$catseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2) = $db->sql_fetchrow_simple($result)) {
			$treeTemp .= "<option value=\"$cat_id\">$text-- $title2</option>";
			$treeTemp .= subcat_home($cat_id,$text, $catcheck, $catseld);
		}
	}
	return $treeTemp;
}

////////////////////////////////////////////////
// function show flash
//code by: vinhquangvip - date: 12-2-2010
//show_flash(tên falsh, đường dẫn file flash, độ rộng flash, độ dài flash)
////////////////////////////////////////////////
function show_flash($flashid,$fileflash,$fwidth,$fheight)
{
	echo"<object id=\"$flashid\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"$fwidth\" height=\"$fheight\">\n";
	echo"<param name=\"movie\" value=\"$fileflash\" />\n";
	echo"<param name=\"quality\" value=\"high\" />\n";
	echo"<param name=\"wmode\" value=\"opaque\" />\n";
	echo"<param name=\"swfversion\" value=\"9.0.45.0\" />\n";
	echo"<!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete \"it if you don't want users to see the prompt. -->\n";
	echo"<param name=\"expressinstall\" value=\"Scripts/expressInstall.swf\" />\n";
	echo"<!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->\n";
	echo"<!--[if !IE]>-->\n";
	echo"<object type=\"application/x-shockwave-flash\" data=\"$fileflash\" width=\"$fwidth\" height=\"$fheight\">\n";

	echo"<!--<![endif]-->\n";
	echo"<param name=\"quality\" value=\"high\" />\n";
	echo"<param name=\"wmode\" value=\"opaque\" />\n";
	echo"<param name=\"swfversion\" value=\"9.0.45.0\" />\n";
	echo"<param name=\"expressinstall\" value=\"Scripts/expressInstall.swf\" />\n";
	echo"<!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->\n";
	echo"<div>\n";
	echo"<h4>Content on this page requires a newer version of Adobe Flash Player.</h4>\n";

	echo"<p><a href=\"http://www.adobe.com/go/getflashplayer\"><img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" width=\"112\" height=\"33\" /></a></p>\n";
	echo"</div>\n";
	echo"<!--[if !IE]>-->\n";
	echo"</object>\n";
	echo"<!--<![endif]-->\n";
	echo"</object>\n";
	echo"<script type=\"text/javascript\">\n";
	echo"<!--\n";
	echo"swfobject.registerObject(\"$flashid\");\n";
	echo"//-->\n";
	echo"</script>\n";
}

function getRight($string){
	$keys = explode(" ", $string);
	$i = sizeof($keys)-1;
	return $keys[$i];
}

// ReSize Images

function resizeImages($imgIn, $ingOut, $width = 0, $height = 0) {
	if(!file_exists($ingOut)) {
		$MyImg = new Image;
		$MyImg->SrcFile = $imgIn; //Ảnh gốc
		$MyImg->DestFile = $ingOut; //Ảnh sao chép sau khi resize
		if($width != 0 && $height != 0){
			$MyImg->NewWidth = $width;
			$MyImg->NewHeight = $height;
			$MyImg->SaveFileWH();
		} else if($width != 0 && $height == 0){
			$MyImg->WidthPercent = $width;
			$MyImg->SaveFileW();
		} else if($width == 0 && $height != 0){
			$MyImg->HeightPercent = $height;
			$MyImg->SaveFileH();
		}else {
			$ingOut = $imgIn;
		}

		return $ingOut;
	}else {
		return $ingOut;
	}
}

function showTitleCompany($prdid) {
	global $db, $prefix, $module_name;
	$titlecompany = "";
	$result = $db->sql_query_simple("SELECT c.id, c.title FROM ".$prefix."_company AS c INNER JOIN ".$prefix."_products AS p ON c.id = p.companyid  WHERE p.id='$prdid'");
	list($comid, $titlecom) = $db->sql_fetchrow_simple($result);
	$titlecom = "<a href=\"".fetch_urltitle("index.php?f=products&do=company&company=$comid",$titlecom)."\" >$titlecom</a>";
	return $titlecom;
}

function show_imagerating($id) {
	global $db, $prefix, $module_name;
	$show = "";
	$wrating="";
	$result_total = $db->sql_query_simple("SELECT rating FROM {$prefix}_rating WHERE prdid='$id'");
	$num_total = $db->sql_numrows($result_total);
	if($num_total > 0){
		$sum = 0;
		while(list($rat) = $db->sql_fetchrow_simple($result_total)) {
			$sum = $sum + $rat;
		}
		$rating = ceil($sum/$num_total);
	}else $rating = 0;

	$check0 = $check1 = $check2 = $check3 = $check4 = $check5 = "";
	switch($rating){
		case 0 : $wrating = "width: 0%"; break;
		case 1 : $wrating = "width: 20%"; break;
		case 2 : $wrating = "width: 40%"; break;
		case 3 : $wrating = "width: 60%"; break;
		case 4 : $wrating = "width: 80%"; break;
		case 5 : $wrating = "width: 100%"; break;
	}
	$show .= "<div class=\"rating-box\">
                <div style=\"$wrating\" class=\"rating\"></div>
            </div>";
	return $show;
}


function insert_contact($title, $fullname, $email, $phone, $note, $timed)
{
	global $db, $prefix, $module_name, $currentlang;
	$result = ("INSERT INTO {$prefix}_contact (id, title, ctname, email, phone, content, ctdate, alanguage ) VALUES ( '','$title', '$fullname','$email','$phone','$note','$timed','$currentlang') ");
	$db->sql_query_simple($result);
}
function display_form()
{
	?>
		<div class="form_home">
			<h3>Gửi yêu cầu tư vấn</h3>
			<script type="text/javascript">
				function check_frm_home(){
					var fullname = document.getElementById('fullname').value;
					var email = document.getElementById('email').value;
					var phone = document.getElementById('phone').value;
					var title = document.getElementById('title').value;

					var kiemTraDT = isNaN(phone);
					if(fullname == ""){
						window.alert('Vui lòng nhập họ và tên!');
						document.getElementById('fullname').focus();
						return false;
					}
					var aCong=email.indexOf("@");
			        var dauCham = email.lastIndexOf(".");
			        if (email == "") {
			            alert("Vui lập nhập địa chỉ Email!");
			            return false;
			        }
			        else if ((aCong<1) || (dauCham<aCong+2) || (dauCham+2>email.length)) {
			              alert("Email bạn điền không chính xác");
			              return false;
			          }

					if(phone == ""){
						window.alert('Vui lòng nhập số điện thoại!');
						document.getElementById('phone').focus();
						return false;
					}
					 if (kiemTraDT == true) {
			              alert("Điện thoại phải để ở định dạng số!");
			              return false;
			          }
			        if(title == "" ){
						window.alert('Vui lòng nhập tiêu đề thư!');
						document.getElementById('title').focus();
						return false;
					}
					return true;
				}
			</script>
			<form method="post" action="#" id="form-dang-ky" name="thongtindh" onsubmit="return check_frm_home()">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
        				<div class="form-group">
        			        <div class="line_text">
        			          	<input type="text" name="fullname" id="fullname" placeholder="Họ và tên:*"  class="form-control" style="width:100%;" value="">
        			    	</div>
        			    </div>

        			    <div class="form-group">
        		            <div class="line_text">
        		              	<input type="text" class="form-control" id="email" placeholder="Email:*"  name="email" style="width:100%;" value="">
        		            </div>
        		        </div>

        		        <div class="form-group">
        					<div class="line_text">
        						<input type="text" class="form-control" id="phone" placeholder="Số điện thoại:*"  name="phone" style="width:100%;" value="">
        					</div>
        				</div>

        				<div class="form-group">
        					<div class="line_text">
        						<input type="text" class="form-control" id="title" placeholder="Tiêu đề:*"  name="title" style="width:100%;" value="">
        					</div>
        				</div>

                        <div class="form-group">
                            Các ô đánh dấu * là bắt buộc phải nhập.
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
        				<div class="form-group">
        		            <div class="line_text">
        		              	<textarea class="form-control" name="note" rows="5"  ></textarea>
        		            </div>
        		        </div>

        		        <div class="form-group">
        		            <input type="hidden" name="gui" class="btn btn-primary" value="1">
        		            <input type="submit" name="submit_step1" class="btn btn-primary btn-submitform" value="Gửi">
        		        </div>
                    </div>
                </div>
		    </form>
		    <?php
		    	if(isset($_POST['gui']) && $_POST['gui'] == 1){
		    		$title = trim($_POST['title']);
		    		$hoten = trim($_POST['fullname']);
					$sdt = intval($_POST['phone']);
					$email = trim($_POST['email']);
					$noidung = trim($_POST['note']);
					date_default_timezone_set('Asia/Ho_Chi_Minh');
					$timed=date('d/m/Y H:i:s');
					insert_contact($title, $hoten, $email, $sdt, $noidung, $timed);
		    	}
		    ?>
		</div>
		<div class="note_tuvan">
			<?php show_gentext() ?>
		</div>
	<?php
}

function form_card($table,$catid,$price, $userInfo){
	include_once('includes/MobiCard.php');
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
	$so = "";
	if ((!defined('iS_USER') || !isset($userInfo))) {
		$nut = "<a  onclick=\"return confirm('Bạn phải đăng nhập để sử dụng tính năng này!');\" class=\"btn-submit\" >Nạp Ngay</a>";
	}
	else{
		$nut = "<input type=\"submit\" id=\"ttNganluong\" class=\"btn-submit\" name=\"NLNapThe".$catid."\" value=\"Nạp Ngay\"  />";
	}
	if(isset($_POST['NLNapThe'.$catid.'']))
	{
		$soseri = $_POST['txtSoSeri'];
		$sopin = $_POST['txtSoPin'];
		$type_card = $_POST['select_method'];

		if ($_POST['txtSoSeri'] == "" ) {
			echo '<script>alert("Vui lòng nhập Số Seri");</script>';
			echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
			exit();
		}
		if ($_POST['txtSoPin'] == "" ) {
			echo '<script>alert("Vui lòng nhập Mã Thẻ");</script>';
			echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
			exit();
		}

		switch ($type_card) {
			case '92':
				$type_card="VMS";
				break;
				case '93':
				$type_card="VNP";
				break;
				case '107':
				$type_card="VIETTEL";
				break;
				case '121':
				$type_card="VCOIN";
				break;
				case '120':
				$type_card="GATE";
				break;
			default:
				echo '<script>alert("Vui lòng chọn loại Thẻ");</script>';
				echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
				exit();
				break;
		}
		switch ($catid) {
        	case '30':
        		$loai = "bachthu";
        		break;
        	case '31':
        		$loai = "songthu";
        		break;
        	case '32':
        		$loai = "xien2";
        		break;
        	case '33':
        		$loai = "khung2";
        		break;
        	case '34':
        		$loai = "khung3";
        		break;

        	default:
        		echo '<script>alert("Lỗi!.");</script>';
        		echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
				exit();
        		break;
        }

		$arytype= array(92=>'VMS',93=>'VNP',107=>'VIETTEL',121=>'VCOIN',120=>'GATE');
		$call = new MobiCard();
		$rs = new Result();
		$coin1 = rand(10,999);
		$coin2 = rand(0,999);
		$coin3 = rand(0,999);
		$coin4 = rand(0,999);
		$ref_code = $coin4 + $coin3 * 1000 + $coin2 * 1000000 + $coin1 * 100000000;
		$rs = $call->CardPay($sopin,$soseri,$type_card,$ref_code,"","","");

	  	$sotien = $rs->card_amount;

		if($rs->error_code == 00)
		{
			$sql = $db->sql_query_simple("SELECT ".$loai." From {$prefix}_number_date where active = 0 order by id desc LIMIT 1 ");
			if($db->sql_numrows($sql) > 0){
	        	list($number_code) = $db->sql_fetchrow_simple($sql);

	        	// if ($price <= 500000) {
	        	// 	$so = "Số đự đoán: <span>".$number_code."</span>";
	        	// }
	        	// else
	        	// {
	        		$tongtien = $userInfo['money'] + $sotien;
	        		$sql_update = $db->sql_query_simple("UPDATE {$prefix}_user SET money = ".$tongtien." Where id = ".$userInfo['id']."");
	        		$sql_select= $db->sql_query_simple("SELECT money FROM {$prefix}_user Where id = ".$userInfo['id']."");
	        		if($db->sql_numrows($sql_select) > 0){
	        			list($money) = $db->sql_fetchrow_simple($sql_select);
		        			if ($money >= $price) {
		        			$total = $money - $price;
		        			$sql_update = $db->sql_query_simple("UPDATE {$prefix}_user SET money = ".$total." Where id = ".$userInfo['id']."");
		        			$so = "Số đự đoán: <span>".$number_code."</span>";
		        		}
		        		else
		        		{
		        			?>
		        			<script>
		        				alert("Bạn đã nạp thành công '<?= $sotien ?>' vào trong tài khoản.\nTài khoản của bạn hiện không đủ để kết quả tại mục này.\nVui lòng nạp thêm để nhận được kết quả!");
		        				window.location.href="http://soicaumienbac247.com/";
		        				</script>
		        			<?php
		        		}
	        		}
	        	}
	        // }
		}
		else {
			echo  '<script>alert("Lỗi :'.$rs->error_message.'");</script>';
		}
	}
	?>
		<form action="#" method="post" accept-charset="utf-8">
			<div class="col-lg-6">
				<div class="form-group">
				    <div class="line_text">
				        <label class="col-lg-4 control-label">Mã thẻ</label>
				        <input type="text" name="txtSoPin" id="txtSoPin" class="form-control" style="width:100%;" value="" required>
				    </div>
				</div>

				<div class="form-group">
			        <div class="line_text">
			           	<label class="col-lg-4 control-label">Số Seri</label>
			            <input type="text" class="form-control" id="txtSoSeri" name="txtSoSeri" style="width:100%;" value="" required>
			        </div>
			    </div>
			</div>
			<div class="col-lg-6">
			    <div class="form-group">
					<div class="line_text">
						<label class="col-lg-4 control-label">Loại thẻ</label>
						<select name="select_method" class="form-control">
			    			<option value="0">Chọn thẻ nạp</option>
			    			<option value="92">-- Mobifone</option>
			    			<option value="93">-- Vinaphone</option>
			    			<option value="107">-- VIETTEL</option>
			    			<option value="121">-- VCOIN</option>
			    			<option value="120">-- GATE</option>
			    		</select>
					</div>
				</div>
				<div class="form-group">
					<div class="line_text">
						<label class="col-lg-4 control-label"></label>
						<?= $nut ?>
					</div>
				</div>

			</div>
			<div class="line-dubao"><?= $so ?></div>
		</form>
	<?php
}


function links_share()
{
	global $sitename;
	?>
		<div class="connenct">
			<script src="https://apis.google.com/js/platform.js" async defer>
				{lang: 'vi'}
			</script>
			<div class="g-plus" data-action="share" data-annotation="bubble" data-href="<?= getCurrentPageURL(); ?>"></div>
			<div class="fb-like" data-href="<?= getCurrentPageURL(); ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= getCurrentPageURL(); ?>" data-via="<?= $sitename ?>">Tweet</a>
		</div>
	<?php
}
function coment_fb()
{
	?>
		<div class="comment_fb">
			<div class="fb-comments" data-href="<?= getCurrentPageURL(); ?>" data-numposts="5" data-width="100%" ></div>
		</div>
	<?php
}


function listparent($table,$catid,$cat_sub){
global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
    $result_prdcat = $db->sql_query_simple("SELECT catid, parent FROM {$prefix}_".$table."_cat WHERE  active='1' AND parent = ($catid) AND alanguage='$currentlang'");
    if($db->sql_numrows($result_prdcat) > 0){
        $i=1;
        $cat_sub .= $catid.",";
        while(list($catid, $parentid ) = $db->sql_fetchrow_simple($result_prdcat))
        {
            $cat_sub .= $catid.",";
            $cat_sub .= listparent($table, $catid, "");
            $i++;
        }
    }
    else
    {
        $cat_sub = $catid.",";
    }
    return $cat_sub;
}
function listparentprd($table,$catid,$cat_sub){
global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
    $result_prdcat = $db->sql_query_simple("SELECT catid, parentid FROM {$prefix}_".$table."_cat WHERE  active='1' AND parentid = ($catid) AND alanguage='$currentlang'");
    if($db->sql_numrows($result_prdcat) > 0){
        $i=1;
        $cat_sub .= $catid.",";
        while(list($catid, $parentid ) = $db->sql_fetchrow_simple($result_prdcat))
        {
            $cat_sub .= $catid.",";
            $cat_sub .= listparentprd($table, $catid, "");
            $i++;
        }
    }
    else
    {
        $cat_sub = $catid.",";
    }
    return $cat_sub;
}
?>
