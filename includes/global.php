<?php

if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) { die(); }

require_once(RPATH."language/$currentlang/region.php");

$country_arr = array("Việt Nam","Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Botswana","Bouvet Island","Brazil","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Colombia","Comoros","Congo","Cook Islands","Costa Rica","Cote Divoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Faroe Islands","Fiji","Finland","France","French Guiana","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Republic of Korea","Kuwait","Kyrgyzstan","Lao P.D.R","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Macau","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Moldova","Monaco","Mongolia","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Norway","Oman","Pakistan","Palau","Panama","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russian Federation","Rwanda","Saint Lucia","Samoa","San Marino","Saudi Arabia","Senegal","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","Spain","Sri Lanka","St. Helena","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Thailand","Togo","Tokelau","Tonga","Tunisia","Turkey","Turkmenistan","Tuvalu","UAE","Uganda","Ukraine","United Kingdom","Uruguay","USA","Uzbekistan","Vanuatu","Vatican City","Venezuela","Western Sahara","Yemen","Yugoslavia","Zaire","Zambia","Zimbabwe");
$region_arr = array(_HA_NOI, _HO_CHI_MINH, _AN_GIANG, _BA_RIA, _BAC_CAN, _BAC_GIANG, _BAC_LIEU, _BAC_NINH, _BEN_TRE, _BIEN_HOA, _BINH_DINH, _BINH_DUONG, _BINH_PHUOC, _BINH_THUAN, _CA_MAU, _CAN_THO, _CAO_BANG, _DA_NANG, _DAC_LAC, _DIEN_BIEN, _DONG_NAI, _DONG_THAP, _GIA_LAI, _HA_GIANG, _HA_NAM, _HA_TAY, _HA_TINH, _HAI_DUONG, _HAI_PHONG, _HOA_BINH, _HUE, _HUNG_YEN, _KHANH_HOA, _KON_TUM, _LAI_CHAU, _LAM_DONG, _LANG_SON, _LAO_CAI, _LONG_AN, _NAM_DINH, _NGHE_AN, _NINH_BINH, _NINH_THUAN, _PHU_THO, _PHU_YEN, _QUANG_BINH, _QUANG_NAM, _QUANG_NGAI, _QUANG_NINH, _QUANG_TRI, _SOC_TRANG, _SON_LA, _TAY_NINH, _THAI_BINH, _THAI_NGUYEN, _THANH_HOA, _TIEN_GIANG, _TRA_VINH, _TUYEN_QUANG, _KIEN_GIANG, _VINH_LONG, _VINH_PHUC, _VUNG_TAU, _YEN_BAI, _OTHER, _INTERNATIONAL);
$schoolN = array("Đại Học Công Nghiệp Hà Nội","Đại Học Bách Khoa Hà Nội","Đại Học Công Nghiệp Hà Nội","Đại Học Điện Lực","Đại Học Dân Lập Đông Đô - Hà Nội","Đại Học Dân Lập FPT","Đại Học Dân Lập Hải Phòng","Đại Học Giao Thông Vân Tải - Hà Nội","Đại Học Hàng Hải - Hải Phòng","Đại Học Khoa Học Tự Nhiên Hà Nội","Đại Học Kiến Trúc Hà Nội","Đại Học Kinh Tế Quốc Dân","Đại Học Kĩ Thuật Công Nghiệp Thái Nguyên","Đại Học Lâm Nghiệp - Hà Nội","Đại Học Lâm Nghiệp Việt Nam","Đại Học Luật Hà Nội","Đại Học Mỏ Địa Chất","Đại Học Mở Hà Nội","Đại Học Hà Nội","Đại Học Ngoại Thương - Hà Nội","Đại Học Nông Nghiệp I - Hà Nội","Đại Học Phương Đông - Hà Nội","Đại Học Quốc Gia - Hà Nội","Đại Học Sư Phạm Hà Nội","Đại Học Sư Phạm Hải Phòng","Đại Học Sư Phạm Kĩ Thuật Hưng Yên","Đại Học Quốc Tế RMIT","Đại Học Sư Phạm Thái Nguyên","Đại Học Dân Lập Thăng Long - Hà Nội","Đại Học Thương Mại - Hà Nội","Đại Học Thủy Lợi Hà Nội","Đại Học Văn Hóa Hà Nội","Đại Học Vinh","Đại Học Khoa Học Xã Hội Và Nhân Văn Hà Nội","Đại Học Xây Dựng","Đại Học Y Hà Nội","Đại Học Y Thái Bình","Đại Học Y Thái Nguyên","Học Viện Công Nghệ Bưu Chính Viễn Thông","Học Viện Hành Chính Quốc Gia","Học Viện Kĩ Thuật Quân Sự","Học Viện Ngân Hàng Hà Nội","Học Viện Quan Hệ Quốc Tế","Học Viện Tài Chính");
$schoolC = array("");

function errorMess($ds, $id, $content) {
	return "<span style=\"display:".$ds.";\" id=\"".$id."_err\" class=\"error_msg\">".$content."<br></span>";
}

//Lay ra mot doan trong chuoi van ban
function CutString($string, $num){
        if(strlen($string) > $num)
        {
            $result = substr($string,0,$num); //cut string with limited number
            $position = strrpos($result," "); //find position of last space
            if($position)
                $result = substr($result,0,$position); //cut string again at last space if there are space in the result above
            $result .= '..';
        }
        else {
            $result = $string;
        }
        return $result;
}
//convert monney
function bsVndDot($strNum)
{
    $len = strlen($strNum);
    $counter = 3;
    $result = "";
    while ($len - $counter >= 0)
    {
        $con = substr($strNum, $len - $counter , 3);
        $result = '.'.$con.$result;
        $counter+= 3;
    }
    $con = substr($strNum, 0 , 3 - ($counter - $len) );
    $result = $con.$result;
    if(substr($result,0,1)=='.'){
        $result=substr($result,1,$len+1);   
    }
    return $result;
}
function sortBy($url, $s) {
	if (empty($url)) { $url = "?"; } else { $url = "$url&"; }
	$s1 = $s + 1;
	return "<a href=\"{$url}sort=$s\" title=\""._SORTUP."\"><img border=\"0\" src=\"".RPATH."images/sup.gif\"></a> <a href=\"".$url."sort=".$s1."\" title=\""._SORTDOWN."\"><img border=\"0\" src=\"".RPATH."images/sdown.gif\"></a>";
}

function updateadmlog($adname, $area, $title, $action) {
	global $db, $prefix, $currentlang, $client_ip;
	$db->sql_query_simple("INSERT INTO {$prefix}_admin_log (id, adname, dateline, area, ip_add, alanguage, action, title) VALUES (NULL, '$adname', '".TIMENOW."', '$area', '$client_ip', '$currentlang', '$action', '$title')");
}

function createsession($s) {
	$sescode = time();
	$sescode .= generate_code(8);
	$sescode = md5($sescode);
	if(!isset($_SESSION[$s])) {
		//session_register ($s);
		//$_SESSION[$s] = $s;
		$_SESSION[$s] = $sescode;
		return $sescode;
	} else {
		return false;
	}
}

function checksession($s,$code) {
	if(isset($_SESSION[$s]) && $_SESSION[$s] == $code) {
		unset($_SESSION[$s]);
		return true;
	} else {
		return false;
	}
}

function checkmainsess($s) {
	if (isset($_SESSION[$s])) {
		unset($_SESSION[$s]);
		return true;
	} else {
		return false;
	}
}

function delsession($s) {
	if(isset($_SESSION[$s])) {
		unset($_SESSION[$s]);
	}
}

function optioncheck($name, $check="") {
	return "<input type=\"hidden\" name=\"scheck[]\" value=\"$name\"><input type=\"checkbox\" name=\"soption[$name]\" value=\"1\" ".$check." title=\""._MARKDISPLAY."\">";
}


function checkds($check) {
	if($check =="checked") {
		return true;
	} else {
		return false;
	}
}

function signsite() {
	global $db, $prefix, $currentlang;
	list($signsite) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT content FROM ".$prefix."_gentext WHERE textname='sign' AND alanguage='$currentlang'"));
	return $signsite;
}
//lay link cua id
function getlink($adm_modname,$cdo) {
	return "<a href=\"".RPATH.("index.php?f=".$adm_modname."&do=".$cdo)."\" info=\""._GETLINK."\" onclick=\"prompt('"._GETLINK."','".("index.php?f=".$adm_modname."&do=".$cdo)."'); return false;\"><img border=\"0\" src=\"images/link.png\"></a>";
}

function fetch_string($str,$act="") {
	$str = str_replace('"',"''",$str);
	return $str;
}

function idHexde($str) {
	global $numshex_std;
	$nums = hexdec($str);
	$id = $nums%$numshex_std;
	$id = intval($id);
	return $id;
}


function idHexen($id) {
	global $numshex_std;
	$nums = $id+$numshex_std;
	$nums = intval($nums);
	$str = dechex($nums);
	return $str;
}
function getrequest($name,$track=0) {
	$nameds = isset($_POST[''.$name.'']) ? intval($_POST[''.$name.'']) : (isset($_GET[''.$name.'']) ? intval($_GET[''.$name.'']) : $track);
	return $nameds;
}
function fetch_urltitle($url,$title) {
	global $rewrite_mod;
	if ($rewrite_mod == 1) {
		$furl = url_sid($url."&t=".utf8_to_ascii(url_optimization($title)));	
	} else {
		$furl = $url;	
	}
	return $furl;			
}
function getTotal($table, $where="") {
	global $db, $prefix;
	if ($where) {
		$sql_seld =" WHERE {$where}";	
	} else {
		$sql_seld ="";
	}
			
	$numf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_{$table}{$sql_seld}"));
	
	$total = ($numf[0]) ? $numf[0] : 0;
	
	return $total;
}
function trmouse_over($i, $color="") {
	if ($color =="") { $color ="#EAEAEA"; }
	$trmouse_over = "onmouseover=\"setPointer(this, $i, 'over', '{$color}', '#CCFFCC', '#FFCC99');\" onmouseout=\"setPointer(this, $i, 'out', '{$color}', '#CCFFCC', '#FFCC99');\"";
	return $trmouse_over;
}

function check_click($i, $color="") {
	if ($color =="") { $color ="#EAEAEA"; }
	$check_click = "onclick=\"if(this.checked) { nothing(this,'#FFCC99',$i,'#FFCC99'); } else{nothing(this,'{$color}',$i,'#FFCC99');}\"";
	return $check_click;
}
function getrequest_str($name, $track="") {
	$nameds = isset($_POST[''.$name.'']) ? $_POST[''.$name.''] : (isset($_GET[''.$name.'']) ? $_GET[''.$name.''] : $track);
	return trim($nameds);
}

function cleanPosUrl ($str) {
	global $escape_mysql_string;
	
	$nStr = $str;
	$nStr = str_replace("**am**","&",$nStr);
	$nStr = str_replace("**pl**","+",$nStr);
	$nStr = str_replace("**eq**","=",$nStr);
	return $escape_mysql_string(trim(check_html($nStr,"nohtml")));
}
//declare your variables
# Automatically display/resize thumbnail
//Vinhquangvip - 30/09/2010
function tj_thumbnail($src_images,$title,$width, $height) 
{
	return "<img src=\"timthumb.php?src=$src_images&amp;h=$height&amp;w=$width&amp;zc=1\" alt=\"$title\" title=\"$title\" />";
}
function tj_thumbnail_null($src_images,$title,$width, $height) 
{
	return "<img src=\"timthumb.php?src=$src_images&amp;h=$height&amp;w=$width&amp;zc=1\"/>";
}

function OpenBox($title="") {
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\" bgcolor=\"#FFFFFF\">\n";
	echo "<tr>\n";
	echo "<td height=\"19\">\n";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\" height=\"19\">\n";
	echo "<tr>\n";
	echo "<td width=\"19\">\n";
	echo "<img border=\"0\" src=\"images/box/top_left.gif\" width=\"19\" height=\"19\"></td>\n";
	echo "<td background=\"images/box/bg_top.gif\">&nbsp;</td>\n";
	echo "<td width=\"19\">\n";
	echo "<img border=\"0\" src=\"images/box/top_right.gif\" width=\"19\" height=\"19\"></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>\n";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
	echo "<tr>\n";
	echo "<td background=\"images/box/bg_left.gif\" width=\"8\">&nbsp;</td>\n";
	echo "<td>\n";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" style=\"border-collapse: collapse\">\n";
	echo "<tr>\n";
	echo "<td align=\"center\">$title\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>\n";
}

function CloseBox() {
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "<td background=\"images/box/bg_right.gif\" width=\"8\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td height=\"19\">\n";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\" height=\"19\">\n";
	echo "<tr>\n";
	echo "<td width=\"19\">\n";
	echo "<img border=\"0\" src=\"images/box/foot_left.gif\" width=\"19\" height=\"19\"></td>\n";
	echo "<td background=\"images/box/bg_foot.gif\">&nbsp;</td>\n";
	echo "<td width=\"19\">\n";
	echo "<img border=\"0\" src=\"images/box/foot_right.gif\" width=\"19\" height=\"19\"></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

function getRealIpAddr() 
{    
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet    
    {      
         $ip=$_SERVER['HTTP_CLIENT_IP'];    
    }     
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy   
    {    
         $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];  
    }   
    else   
    {      
         $ip=$_SERVER['REMOTE_ADDR'];    
    }   
    return $ip; 
}


?>