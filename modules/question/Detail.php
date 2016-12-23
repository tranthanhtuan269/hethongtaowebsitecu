<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");
echo "<div class=\"main-question\">";
	echo "<div class=\"main-question-left\">";
		include_once("blocks/In-danh_muc_hoi_dap.php");
	echo "</div>";	
	echo "<div class=\"main-question-content\">";
$id = intval($_GET['id']);
$qid=$id;
//OpenTab("<a href=\"".url_sid("index.php")."\">"._HOI_DAP."</a>");
$result = $db->sql_query_simple("SELECT n.catid, c.title FROM ".$prefix."_question AS n,".$prefix."_question_cat AS c WHERE n.id='$id' AND n.catid=c.catid AND n.alanguage='$currentlang'");
if($db->sql_numrows($result) != 1) {
	$rwtitlecat = utf8_to_ascii(url_optimization($titlecat));
	header("Location: ".url_sid("index.php?f=$module_name")."");	
}
list($catid,$catname) = $db->sql_fetchrow_simple($result);
echo "<div class=\"title_home\"><h2><a href=\"".url_sid("index.php?f=question&do=categories&id=$catid&t=$catname")."\">$catname</a></h2></div>";
echo "<div class=\"div-home\">";

echo "<div class=\"add-question\">";	
	echo "<span class=\"add\"><a href=\"index.php?f=question&do=create\">"._ADD_QUESTION."</a></span>";
	echo "<img src=\"images/addition.gif\" >";
echo "</div>";

$perpage = 10;

$db->sql_query_simple("UPDATE ".$prefix."_question SET hits=hits+1 WHERE id='$id'"); 

$query = "SELECT COUNT(*) FROM {$prefix}_question WHERE alanguage='$currentlang'";
$result = $db->sql_query_simple($query);
list($total) = $db->sql_fetchrow_simple($result);
echo "<div class=\"question-answer\" style=\"padding-top:0px;\">";
$query = "SELECT id, title, content, time, name, email,hits FROM ".$prefix."_question WHERE id = $id ";
$resultn = $db->sql_query_simple($query);
if($db->sql_numrows($resultn) > 0) {
	while(list($id, $title, $content, $time, $name, $email,$hits) = $db->sql_fetchrow_simple($resultn)) {
		$rwtitle = utf8_to_ascii(url_optimization($title));
		$url_detail =url_sid("index.php?f=question&do=detail&id=$id&t=$rwtitle");
		
		echo "<div class=\"qtitle\"><a href=\"".url_sid("$url_detail")."\">$title</a></div>";
		echo "<div class=\"qname\">"._NGUOI_GUI."<b>$name</b> "._THOI_GIAN." ".ext_time($time,2)."</div>";
		$resul_cat = $db->sql_query_simple("SELECT c.catid, c.title FROM ".$prefix."_question AS q INNER JOIN ".$prefix."_question_cat AS c ON c.catid = q.catid  WHERE  q.id = $id");
		list($catid, $titlecat) = $db->sql_fetchrow_simple($resul_cat);
		$rwtitlecat = utf8_to_ascii(url_optimization($titlecat));
		echo "<div class=\"qname\">"._THUOC_CHUYEN_MUC_1."<a href=\"".url_sid("index.php?f=question&do=categories&id=$catid&t=$rwtitlecat")."\"> $titlecat</a></div>";	
		$result_total = $db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_answer WHERE qid=$id");
		list($total) = $db->sql_fetchrow_simple($result_total);		
		echo "<div class=\"qname\">"._TRA_LOI."($total) "._LUOT_XEM."($hits)</div>";
		echo "<div class=\"qname\"><b>"._NOI_DUNG."</b></div>";
		echo "<div class=\"qname\" align=\"justify\">$content</div>";
		
		//echo "<div class=\"qrow\"></div>";


///================== DS tra loi		
echo "<div class=\"box-border\" style=\"margin-top:10px;\">";

$resul_answer = $db->sql_query_simple("SELECT id, content, time, name, email FROM ".$prefix."_answer WHERE active=1 AND qid=$qid  ORDER BY time DESC ");
if($db->sql_numrows($resul_answer) > 0) {
	$query = "SELECT COUNT(*) FROM {$prefix}_answer WHERE active=1 AND qid=$qid ";
	$result = $db->sql_query_simple($query);
	list($total) = $db->sql_fetchrow_simple($result);
	echo "<div class=\"breakcoup\" style=\"font-size:15px;padding-left:23px; margin-right:0px;float:left;width:97%;margin-top:5px; \">"._DS_TRA_LOI." ($total)</div>";
	
	while(list($idanswer,$contentanswer, $timeanswer, $nameanswer, $emailanswer) = $db->sql_fetchrow_simple($resul_answer)) {		

		echo "<div class=\"qname\" style=\"margin-top:5px;\">"._NGUOI_GUI."<b>$nameanswer</b> "._THOI_GIAN."".ext_time($timeanswer,2)."</div>";
				
		echo "<div class=\"qname\"><b>"._NOI_DUNG."</b></div>";
		echo "<div class=\"qname\" align=\"justify\">$contentanswer</div>";
		echo "<div class=\"qrow\"></div>";
	}
}
echo "</div>";		
			
		
//====================
$active = 1;
$contentanswer = $emailanswer = $nameanswer = $err_name =  $err_email =  $err_content = '';		
if( isset($_POST['subup']) && $_POST['subup'] == 1) {
	$err = 0;
	$nameanswer = ($_POST['nameanswer']);
	$active = 1;
	$contentanswer = $escape_mysql_string(trim($_POST['contentanswer']));
	$emailanswer = ($_POST['emailanswer']);

	
	if (empty($nameanswer)) {
		$err_name = "<font color=\"red\">"._ERROR_NAME."</font>";
		$err = 1;
	}	
	if (empty($nameanswer)) {
		$err_content = "<font color=\"red\">"._ERROR_CONTENT."</font>";
		$err = 1;
	}
	
	if (!$err) {		
		$insertIntoTable = "{$prefix}_answer";
		$query = "INSERT INTO $insertIntoTable (id, qid, name, alanguage, content, email, active,time) VALUES (NULL, $id, '$nameanswer', '$currentlang', '$contentanswer', '$emailanswer', $active,".time().")";
		$result = $db->sql_query_simple($query);
		
		//updateadmlog($admin_ar[0], $adm_modname, _MODTITLE, _CAMNANG_CREATE_CAMNANG);
		
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('"._THANKS_ANSWER."');";
		echo "window.location.href=\"index.php?f=".$module_name."&do=detail&id=$qid\"";
		echo "</script>";
		
		
	}
}	

echo "<script language=\"javascript\" type=\"text/javascript\">";
echo "function Check_Valid(f) {";

echo "var Content = document.getElementById('contentanswer');";
echo "var Name = document.getElementById('nameanswer');";
echo "var err = 0;";
echo "if (isEmpty(Name.value)) {";
echo "alert('"._ERROR_NAME."');";
echo "Name.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";

echo "if (isEmpty(Content.value)) {";
echo "alert('"._ERROR_CONTENT."');";
echo "Content.focus();";
echo "return false;";
echo "err = 1;";
echo "}	";
echo "if(!err) {";
echo "if(f.submit) f.submit.disabled = true; }";
echo "return true; ";
echo "}";
echo "</script>";	

	if(defined('iS_USER') || isset($userInfo)){
		$nameanswer = $userInfo['fullname'];
		$emailanswer = $userInfo['email'];
		$dis = "";
		$note = "";
	} else{
		$nameanswer = "";
		$emailanswer = "";
		$dis = "disabled";
		$note = "<div style=\"padding-top:8px;color:red;\">"._HAY."<a href=\"".url_sid("index.php?f=user&do=login")."\" style=\"color:red;\"><b>"._DANG_NHAP."</b></a>"._TRA_LOI_CAU_HOI."</div>";
	} 		
		
		echo "<form action=\"index.php?f=$module_name&do=$do&id=$id\" method=\"POST\" onsubmit=\"return Check_Valid(this);\">";
		echo "<div class=\"question1\">";
		echo "<div class=\"breakcoup\" style=\"font-size:15px;padding-left:10px;width:auto;float:left;border:0px;\">"._TRA_LOI_CAU_HOI."</div>$note";	
				
		echo "<table class=\"tableborder\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"width:100%;\" >\n";			
		echo "<td class=\"row1\">"._NAME_CONTACT."&nbsp;$err_name<input type=\"text\" id=\"nameanswer\" name=\"nameanswer\" value=\"$nameanswer\" size=\"40\" $dis></td>\n";
		echo "<td class=\"row1\">"._EMAIL_CONTACT."&nbsp;$err_email<input type=\"text\" name=\"emailanswer\"  id=\"emailanswer\" value=\"$emailanswer\" size=\"40\" $dis></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td colspan=\"2\" class=\"row1\">"._CONTENT_ANSWER."&nbsp;$err_content<br><textarea cols=\"81\" rows=\"7\" name=\"contentanswer\" id=\"contentanswer\" $dis>$contentanswer</textarea></td>\n";
		echo "</tr>\n";
		echo "<tr><td></td><td style=\"padding:3px;\"><input type=\"hidden\" name=\"subup\" value=\"1\"><input class=\"sb_but1\" type=\"submit\" name=\"submit\" value=\""._SEND."\" class=\"button2\"></td></tr>";
		echo "</table>";
		
		echo "</div>";
		echo"</form>";
		
	}

}


echo "</div>";

echo "</div>";
//CloseTab();
echo "</div>";
echo "</div>";
include_once("footer.php");

?>