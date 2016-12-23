<?php
if (!defined('CMS_SYSTEM')) die();


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

global $keywords_page, $description_page, $title_page,$currentlang,$userInfo ;

include_once("header.php");
//////// comment

$active = 1;
$content = $name = $err_name = $err_content = '';
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	
	$err = 0;
	$name = ($_POST['title_comment']);
	$content = $escape_mysql_string(trim($_POST['content']));
	
	if ($name == _NAME_COMMENT) {
		$err_name = "<font color=\"red\">"._ERR_NAME."</font>";
		$err = 1;
	}	
	if ($content == "") {
		$err_content = "<font color=\"red\">"._ERR_CONTENT."</font>";
		$err = 1;
	}

	if (!$err) {		
		$insertIntoTable = "{$prefix}_question";
		$query = "INSERT INTO {$prefix}_comment (id, prdid, name, alanguage, content, active,time) VALUES (NULL, $id, '$name', '$currentlang', '$content', 
		$active,'".time()."')";
		$result = $db->sql_query_simple($query);
	}	
}

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";
echo "var Name = document.getElementById('title_comment');";
echo "var err = 0;";
echo "if (Name.value == \""._NAME_COMMENT."\") {";
echo "alert('"._ERR_NAME."');";
echo "Name.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";

echo "var Content = document.getElementById('content');";
echo "if (Content.value == \""._NOTE_COMMENT."\") {";
echo "alert('"._ERR_CONTENT."');";
echo "Content.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";

echo "var Code = document.getElementById('security_codes');";
echo "if (Code.value == \"Mã xác nhận\" || Code.value != ".$_SESSION['security_code']." ) {";
echo "alert('Bạn đã nhập sai mã xác nhận');";
echo "Code.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";

echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";

//echo "<a href=\"javascript:void(0);\" id=\"link-comment\"  style=\"width:100%;color:#E97D13;padding:10px 0px 0px 10px;float:left;text-transform:uppercase;\"><b>"._VIEW_COMMENT."</b></a>";	//onclick=\"return ShowHide('comment');\"

$perpage = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;

$total = $db->sql_numrows($db->sql_query_simple("SELECT id FROM {$prefix}_comment WHERE active=1 AND prdid = $id AND alanguage='$currentlang'"));

$resultn = $db->sql_query_simple("SELECT id, name, content, time FROM ".$prefix."_comment WHERE active=1 AND prdid = $id AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage ");

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function selectText()
{document.getElementById(\"title_comment\").value	= \"\";}

function addText()
{if(document.getElementById(\"title_comment\").value	== \"\")
	{document.getElementById(\"title_comment\").value= \""._NAME_COMMENT."\";	}
	else {}
}";
echo "function selectText1()
{document.getElementById(\"content\").value	= \"\";}

function addText1()
{if(document.getElementById(\"content\").value	== \"\")
	{document.getElementById(\"content\").value= \""._NOTE_COMMENT."\";	}
	else {}
}";
echo "function selectText2()
{document.getElementById(\"security_codes\").value	= \"\";}

function addText2()
{if(document.getElementById(\"security_codes\").value	== \"\")
	{document.getElementById(\"security_codes\").value= \"Mã xác nhận\";	}
	else {}
}";

echo "</script>";

echo "<form action=\"index.php?f=$module_name&do=detail&id=$id#link-comment\" method=\"POST\" onsubmit=\"return Check_Valid(this);\">";
echo "<div class=\"comment\" id=\"comment\">"; //style=\"display:none;\"
echo "<div class=\"title-comment\" style=\"width:100%;margin-top:20px;\"> Hiển thị tất cả $total &nbsp;"._COMMENT."</div>";

if($db->sql_numrows($resultn) > 0) {
	while(list($id_coment, $name, $content, $time) = $db->sql_fetchrow_simple($resultn)) {	
		echo "<div class=\"qrow\"></div>";
		echo "<div class=\"qname\"><b style=\"color:#003399;\">$name</b> &nbsp;"._GUI_LUC." &nbsp;".ext_time($time,2)."&nbsp;&nbsp;&nbsp;";	
	
		$query_like = $db->sql_query_simple("SELECT id FROM {$prefix}_comment_like WHERE comment_id = $id_coment");
		$number_like = $db->sql_numrows($query_like);
		 if($number_like == 0) {
		 	echo "<a href=\"index.php?f=products&do=likes&id=$id&cid=$id_coment&page=$page\" title=\""._LIKE."\" 
			
			onclick=\"ajaxinfoget('index.php?f=products&do=likes&id=$id&cid=$id_coment&page=$page','ajaxload_container', 'comment',''); return false;\"
			
			 ><img  src=\"templates/Adoosite/images/like.jpg\"></a><span>&nbsp;"._LIKE."</span>";
		 }	else {
			 echo "<a href=\"index.php?f=products&do=likes&id=$id&cid=$id_coment&page=$page\" title=\""._LIKE."\" onclick=\"ajaxinfoget('index.php?f=products&do=likes&id=$id&cid=$id_coment&page=$page','ajaxload_container', 'comment','');return false;\" ><img src=\"templates/Adoosite/images/like.jpg\"></a><span>&nbsp;"._CO."<b>".$number_like."</b>&nbsp;"._THICH_BL_NAY."</span>";
		 }
		echo "</div>";	
		
		echo "<div class=\"qname\" align=\"justify\" style=\"color:#000;\">$content</div>";	
		
		if(defined('iS_SADMIN') || defined('iS_RADMIN') || (defined('iS_ADMIN') && in_array($module_name,$adm_mods_ar))) {
		echo "<div style=\"margin-top: 3px;float:right;\">[<a href=\"index.php?f=products&do=del_comment&prdid=$id&id=$id_coment\" onclick=\"return confirm('"._DELETEASK_PHAN_HOI."');\">X</a>]</div>";
	}
	
	}
	if($total > $perpage) {
		echo "<div class=\"qrow\"></div>";
		echo "<div style=\"float:right;\">";		
		$pageurl = "index.php?f=$module_name&do=$do&id=$id";
		echo pagging($total,$pageurl,$perpage,$page,$id);
		echo "</div>";
	}
}

echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
echo "<tr>\n";
if(!defined('iS_USER') || !isset($userInfo)){
echo "<td width=\"80px\" colspan=\"2\" style=\"padding:3px;\"  class=\"row1\">&nbsp;<input type=\"text\" name=\"title_comment\" value=\""._NAME_COMMENT."\" id=\"title_comment\" size=\"60\" style=\"height:17px;color:#929292;\" onclick = \"selectText()\" onblur=\"addText()\" disabled=\"disabled\">&nbsp;$err_name";
echo "<font color=\"red\">Hãy <a href=\"".url_sid("index.php?f=user&do=login")."\" style=\"color:red;\"><b>đăng nhập</b></a> để gửi bình luận cho sản phẩm này</font></td>\n";
echo "</tr>\n";
}else {
echo "<td width=\"80px\" colspan=\"2\" style=\"padding:3px;\"  class=\"row1\"><input type=\"text\" name=\"title_comment\" value=\"".$userInfo['fullname']."\" id=\"title_comment\" size=\"60\" style=\"height:17px;color:#929292;\">&nbsp;$err_name</td>\n";
echo "</tr>\n";	
}

echo "<tr>\n";;
echo "<td  class=\"row1\" colspan=\"2\"  style=\"padding:3px;\">&nbsp;$err_content";
//editorbasic("content","","",50);
if(!defined('iS_USER') || !isset($userInfo)){
echo "<textarea cols=\"80\" rows=\"6\" name=\"content\" id=\"content\" style=\"color:#B2B2B2;\" onclick = \"selectText1()\" onblur=\"addText1()\" disabled=\"disabled\">"._NOTE_COMMENT."</textarea>";
}else {
echo "<textarea cols=\"80\" rows=\"6\" name=\"content\" id=\"content\" style=\"color:#B2B2B2;\" onclick = \"selectText1()\" onblur=\"addText1()\">"._NOTE_COMMENT."</textarea>";
}
echo "</td>\n";//$content
echo "</tr>\n";
echo "<tr><td colspan=\"2\" style=\"padding:3px;\" align=\"left\"><input type=\"hidden\" name=\"subup\" value=\"1\">";
echo "Nhập mã xác nhận";
echo "&nbsp;&nbsp;<img src=\"CaptchaSecurityImages.php?width=50&height=30&characters=3\" style=\"margin-bottom:-10px;\" />&nbsp;&nbsp;";
echo "<input id=\"security_codes\" name=\"security_codes\" type=\"text\" size=\"13\"  value=\"Mã xác nhận\" style=\"color:#666;\" onclick = \"selectText2()\" onblur=\"addText2()\" />&nbsp;&nbsp;";
echo "<input type=\"submit\" name=\"submit\" value=\""._SEND_COMMENT."\" class=\"button2\"></td></tr>";
echo "</table>";

echo "</div>";
echo"</form>";

echo "</div>";
//include_once("footer.php");
?>


