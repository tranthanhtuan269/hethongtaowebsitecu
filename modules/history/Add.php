<?php
if (!defined('CMS_SYSTEM')) die();

include_once("header.php");

$page_title = _USER_ORDER;
$title_page = _USER_ORDER;

$userid = $userInfo['id'];


 $email= $email_1 = "";

		 global $check_email;
		 $email = $_POST['email_1'];
		
		
		$result = $db->sql_query_simple("SELECT email FROM {$prefix}_user WHERE email='$email'");
		if ($db->sql_numrows($result) > 0) 
		{
			//$db->sql_query_simple("INSERT INTO {$prefix}_user (email) VALUES ('$email')");	
			
			// neeus cos email
			 $check_email= $email;
			  echo checkmail();
			 
			
		}else{
			$db->sql_query_simple("INSERT INTO {$prefix}_user (email) VALUES ('$email')");		
			echo "<script language = javascript>alert('"._NEWSLETTER_ACTIVATION_SUCCESSFUL."');</script>";
			 $check_email= $email;
			  echo checkmail();
		}
		
		

function checkmail()
{
	
global $imgFold, $currentlang, $prefix,$path_upload,$db,$urlsite,$sitename,$check_email; 	

echo "<div class=\"title_home\"><h2>DANH SÁCH ĐƠN HÀNG</h2></div></br>";
echo "<div class=\"div-home\">";
	$perpage = 20;
	$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
	$offset = ($page-1) * $perpage;

	
	$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM ".$prefix."_products_order WHERE mail='$check_email'"));
	
	$total = ($countf[0]) ? $countf[0] : 1;
	$pageurl = "index.php?f=history&do=orders";	
	
	////////////// thông tin người đặt hàng

echo "<div id=\"print\">";
	
	$result = $db->sql_query_simple("SELECT title, fullname, mail, phone, address, info, orderList, DATE_FORMAT(orderTime,'%d/%c/%Y %T'), status FROM {$prefix}_products_order WHERE mail='$check_email' ");
if($db->sql_numrows($result) > 0) {
	list($title, $fullname, $mail, $phone, $address, $info, $orderList, $orderTime, $status) = $db->sql_fetchrow_simple($result);


	echo "<div style=\"  float: left;
    font-weight: bold;
    padding: 10px;
    text-transform: uppercase;\">Thông tin khách hàng</div>";

	echo "<br />\n";
	//echo "<form action=\"modules.php?f=$adm_modname&do=$do&id=$id\" name=\"frm\" method=\"POST\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" class=\"tbl-main\">\n";

	echo "<tr><td class=\"row1\" width=\"15%\">Tên: </td>\n";

	echo "<td class=\"row2\"> $fullname</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">Email: </td>\n";
	echo "<td class=\"row2\">$mail</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">Điện thoại: </td>\n";
	echo "<td class=\"row2\">$phone</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">Địa chỉ: </td>\n";
	echo "<td class=\"row2\">$address</td></tr>\n";
	echo "<tr><td class=\"row1\" width=\"15%\">Thông tin: </td>\n";
	echo "<td class=\"row2\">$info</td></tr>\n";
	echo "</table>";
}
	
	///////////////////// end thông tin người đặt hàng
	
	echo "<div style=\"  float: left;
    font-weight: bold;
    padding: 10px;
    text-transform: uppercase;\">Thông tin sản phẩm </div>";
	$result_orders = $db->sql_query_simple("SELECT userid, sale, orderList, DATE_FORMAT(orderTime,'%d/%c/%Y %T'), status FROM {$prefix}_products_order WHERE mail='$check_email' ORDER BY orderTime DESC LIMIT $offset, $perpage");
if($db->sql_numrows($result_orders) > 0) {	
	$ii=1;	
	while(list($userid, $sale, $orderList, $orderTime, $status) = $db->sql_fetchrow_simple($result_orders)){
	$ii++;	
	if($ii%2==0){
		echo "<table border=\"1\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;background:#D9FDEE;\">\n";
	}else {
		echo "<table border=\"1\" width=\"100%\" cellpadding=\"5\" cellspacing=\"5\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;background:#FFFFFF;\">\n";
	}	
	
	echo "<tr><td style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">"._PRD_ORDER_ORDER_TIME."</td><td colspan=\"4\" align=\"left\" align=\"right\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\"><b>".$orderTime."</b></td></tr>\n";

	echo "<tr>\n";
	echo "<th width=\"120px\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">"._PRD_ORDER_ID."</th>\n";
	echo "<th style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">"._PRODUCT_NAME."</th>\n";
	echo "<th width=\"60px\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">"._PRODUCT_COUNT."</th>\n";
	echo "<th  width=\"100px\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">"._PRODUCT_PRICE." (VND)</th>\n";
	echo "<th width=\"100px\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">"._PRODUCT_TOTAL." (VND)</th>\n";
	echo "\n</tr>\n";
	$query = "SELECT id, title, price1 FROM {$prefix}_products WHERE";
	$orderList = explode(',', $orderList);
	$newOrderList = array();
	for ($i = 0; $i < count($orderList); $i++) {
		$orderList[$i] = explode('x', $orderList[$i]);
		$query .= " id={$orderList[$i][1]} OR";
		$newOrderList[$orderList[$i][1]] = intval($orderList[$i][0]);
	}
	$query = substr($query, 0, strlen($query) - 3);
	$db->sql_query_simple($query);
	$totalPrice = 0;
	$i = 0;
	while (list($prdId, $prdTitle, $prdPrice) = $db->sql_fetchrow_simple()) {
		

		echo "<tr>\n";
		$j=$i+1;
		echo "<td align=\"center\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">$j</td>\n";
		echo "<td style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">&nbsp;$prdTitle</td>\n";
		echo "<td align=\"center\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">{$newOrderList[$prdId]}</td>\n";
		echo "<td align=\"right\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">".dsprice($prdPrice)."</td>\n";
		$currentTotal = $newOrderList[$prdId] * intval($prdPrice);
		$totalPrice += $currentTotal;
		echo "<td align=\"right\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">".dsprice($currentTotal)."</td>\n";
		echo "\n</tr>\n";
		$i++;
	}	
	
	if($sale==0){
		echo "<tr><td colspan=\"4\" align=\"right\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\"><b>"._PRODUCT_TOTAL."</b></td><td align=\"right\"><b>".dsprice($totalPrice)."</b></td></tr>\n";
	} else {
		$total_1= $totalPrice - (($totalPrice*3)/100);
		$total_1 = floor($total_1);
		echo "<tr><td colspan=\"4\" align=\"right\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\"><b>"._PRODUCT_TOTAL."</b><br>"._PRODUCT_SALE_OOF."</td><td align=\"right\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\"><b><s>".dsprice($totalPrice)."</s></b><br><b style=\"color:red\">".bsVndDot($total_1)."</b></td></tr>\n";
	}	
	
	echo "<tr><td >"._PRD_ORDER_STATUS."</td><td colspan=\"4\" align=\"left\" align=\"right\" style=\"padding:3px;border-collapse: collapse;border: 1px solid #999;\">";		
	/*if ($status == '0') {		
		echo "<font color=\"red\">"._PRD_ORDER_UNPROCESSED."</font>";			
	}	
	else if ($status == '1') {
		echo "<b>"._PRD_ORDER_PROCESSED."</b>";
	} */	
	echo "</td></tr>\n";
	
	echo "</table>\n";
	echo "<br />\n";
			
	}

echo paging($total,$pageurl,$perpage,$page);
	
}else{
	echo "<br><br><div style=\"text-align: center; float: left; width: 100%;\"><span style=\"color:#FF6600; text-align: center; \">"._NO_ORDER."</span></div>";
}
	
echo "</div>";

}
echo "</div>";

?>
<div class="print" style="float:right"><input type="button" value="In phiếu mua" onclick="printPage('print');"></input></div>

<?php 

include_once("footer.php");		
?>