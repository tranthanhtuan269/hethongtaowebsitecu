<?php

$cid = intval($_GET['cid']);
$id = intval($_GET['id']);

$ip = getRealIpAddr(); // lay dia chi IP cua may

$resul_check = $db->sql_query_simple("SELECT id FROM ".$prefix."_comment_like WHERE ip='$ip' AND comment_id = '$cid'");
if($db->sql_numrows($resul_check) == 0) {
	$db->sql_query_simple("INSERT INTO ".$prefix."_comment_like (id, ip, comment_id, time) VALUES (NULL, '$ip', '$cid', '".time()."')");
} else{
	echo "<script language=\"javascript\">\n";	
		echo "alert('Bạn đã thích bình luận này rồi.');\n";
	echo "</script>	\n";
}

///===========
$perpage = 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
$offset = ($page-1) * $perpage;
$total = $db->sql_numrows($db->sql_query_simple("SELECT id FROM {$prefix}_comment WHERE cid = $id AND type='news' AND alanguage='$currentlang'"));
$resultn = $db->sql_query_simple("SELECT id, name, content, time FROM ".$prefix."_comment WHERE cid = $id AND type='news' AND alanguage='$currentlang' ORDER BY time DESC LIMIT $offset, $perpage ");
echo "<div id=\"comment\" >";  /// comment 2
if($db->sql_numrows($resultn) > 0) {
	echo "<div class=\"list-comment\">"._LIST_COMMENT."</div>";
	while(list($id_coment, $name, $content, $time) = $db->sql_fetchrow_simple($resultn)) {	
		echo "<div class=\"qrows\">";
		echo "<div class=\"qname\"><b>$name</b> <i>&nbsp;"._GUI_LUC." &nbsp;".ext_time($time,2)."</i>&nbsp;&nbsp;";	
		/// LIKE
		$query_like = $db->sql_query_simple("SELECT id FROM {$prefix}_comment_like WHERE comment_id = $id_coment");
		$number_like = $db->sql_numrows($query_like);
		 if($number_like == 0) {
		 	echo "<a href=\"$urlsite/index.php?f=$module_name&do=likes&id=$id&cid=$id_coment&page=$page\" title=\""._LIKE."\" 			
			onclick=\"ajaxinfoget('$urlsite/index.php?f=$module_name&do=likes&id=$id&cid=$id_coment&page=$page','ajaxload_container', 'comment',''); return false;\"			
			 ><img  src=\"$urlsite/templates/Adoosite/images/likeact.jpg\"></a>";
		 }else {
			 $ip = getRealIpAddr(); // lay dia chi IP cua may 
			 $check_like = $db->sql_query_simple("SELECT id FROM {$prefix}_comment_like WHERE comment_id=$id_coment AND ip='$ip'");
			 $cheknum = $db->sql_numrows($check_like);
			 if($cheknum){
				 echo "<img src=\"$urlsite/templates/Adoosite/images/likedis.jpg\"><span class=\"number_com\">(".$number_like.")</span>";
			 }else {
				 echo "<a href=\"$urlsite/index.php?f=$module_name&do=likes&id=$id&cid=$id_coment&page=$page\" title=\""._LIKE."\" onclick=\"ajaxinfoget('$urlsite/index.php?f=$module_name&do=likes&id=$id&cid=$id_coment&page=$page','ajaxload_container', 'comment','');return false;\" ><img src=\"$urlsite/templates/Adoosite/images/likedis.jpg\"></a><span class=\"number_com\">(".$number_like.")</span>";
			 }			 
		 }		 		 
		echo "</div>";		
		echo "<div class=\"qcontent\" align=\"justify\" >$content</div>";	
		echo "</div>";	
		echo "<div class=\"qline\"></div>";
	}
	if($total > $perpage) {
		echo "<div class=\"qpagging\">";
			echo "<div style=\"float:right;\">";		
				$pageurl = "index.php?f=$module_name&do=$do&id=$id";
				//echo paging($total,$pageurl,$perpage,$page);
				echo pagging($total,$pageurl,$perpage,$page,$id); /// pagging Ajax
			echo "</div>";
		echo "</div>";
	}
}



?>