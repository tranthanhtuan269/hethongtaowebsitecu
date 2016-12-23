<?php
if (!defined('CMS_SYSTEM')) exit;
global $path_upload, $mod_name, $id, $default_temp,$db;
/*
bo loc chi ap dung cho chuyen muc san pham (da co san pham)
*/
$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : 0;
$national = isset($_GET['national']) ? intval($_GET['national']) : 0;
$company = isset($_GET['company']) ? intval($_GET['company']) : 0;
$color = isset($_GET['color']) ? intval($_GET['color']) : 0;
$age = isset($_GET['age']) ? intval($_GET['age']) : 0;
$price = isset($_GET['price']) ? intval($_GET['price']) : 0;
$show = intval(isset($_GET['show']) ? $_GET['show'] : (isset($_POST['show']) ? $_POST['show']:1));
$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));

$querylink="index.php?f=products&do=categories&catid=$catid&t=$t";
//$querylink=$_SERVER['REQUEST_URI'];

$querycatid_fornational=$querycatid_fornational=$querycatid_forcompany2=$querylink2=$querylink5=$querycatid_forproduct=$querycatid_forcompany=$querycatid_forcolor=$querycatid_fornational2=$querycatid_forcolor3=$querycatid_forcolor4=$querycatid_forcompany3=$querylink1=$querylink3=$querycatid_fornationalid2=$querycatid_forcompanyid3=$querycatid_forage5=$querycompany_forage3=$queryid_forage5=$queryageid_forcompany5=$queryageid_fornational5=$querynational_forprice2=$querycatid_forprice=$querycompany_forprice3=$queryprice_fornational6=$queryprice_fornationalcount6=$queryprice_forcompany6=$queryprice_forcompanycount6="";

if($catid!=0)
{
	$querycatid_forproduct=" catid=$catid AND";
	$querycatid_fornational="id in(SELECT nationalid FROM ".$prefix."_products WHERE catid=$catid) AND";
	$querycatid_forcompany="id in(SELECT companyid FROM ".$prefix."_products WHERE catid=$catid) AND";
	$querycatid_forcolor="id in(SELECT DISTINCT t1.colorid FROM ".$prefix."_products_color AS t1 INNER JOIN ".$prefix."_products AS t2 ON t1.prdid = t2.id WHERE t2.catid=$catid) AND";
	$querycatid_forprice="catid=$catid AND";
}
/*if($national!=0)
{
	$querycatid_fornational2=" nationalid=$national AND";
	$querycatid_fornationalid2=" id=$national AND";
	$querycatid_forcompany2="id in(SELECT companyid FROM ".$prefix."_products WHERE $querycatid_fornational2 catid=$catid) AND";
	$querynational_forprice2="nationalid =$national AND";
	//$querylink2=$querylink."&national=$national";
}
else
{
	//$querylink1=$querylink;
}
*/if($company!=0)     /// hãng sản xuất
{
	//$querylink3=$querylink;
	$querycatid_forcompanyid3=" id=$company AND";
	$querycatid_forcompany3=" companyid=$company AND";
	$querycompany_forage3="id in(SELECT DISTINCT t1.prdid FROM ".$prefix."_products_age AS t1 INNER JOIN ".$prefix."_products AS t2 ON t1.prdid = t2.id WHERE t2.companyid=$company) AND";
	//$querycompany_fornational3="id in(SELECT distinct t22.nationalid FROM ".$prefix."_products_age AS t11 INNER JOIN ".$prefix."_products AS t22 ON t11.prdid = t22.id WHERE t22.companyid=$company) AND";
	//$querycatid_forcompany2="id in(SELECT companyid FROM ".$prefix."_products WHERE $querycatid_fornational2 catid=$catid) AND";
	$querycompany_forprice3="companyid =$company AND";
}
else
{
	$querylink3=$querylink;
}
/*if($color!=0)
{
	//$querylink4=$querylink."&national=$national";
	$querycatid_forcolor4="id in(SELECT  t1.prdid FROM ".$prefix."_products_color AS t1 INNER JOIN ".$prefix."_products AS t2 ON t1.prdid = t2.id WHERE t2.catid=$catid) AND";
}*/

/*if($age!=0)
{
	//$querylink4=$querylink."&national=$national";
	$queryid_forage5=" id=$age OR";
	$querycatid_forage5="id in(SELECT DISTINCT t1.prdid FROM ".$prefix."_products_age AS t1 INNER JOIN ".$prefix."_products AS t2 ON t1.prdid = t2.id WHERE t2.catid=$catid) AND";
	$queryageid_forcompany5="id in(SELECT DISTINCT t1.companyid FROM ".$prefix."_products AS t1 INNER JOIN ".$prefix."_products_age AS t2 ON t1.id = t2.prdid WHERE t2.ageid=$age) AND";
	$queryageid_fornational5="id in(SELECT DISTINCT t12.nationalid FROM ".$prefix."_products AS t12 INNER JOIN ".$prefix."_products_age AS t22 ON t12.id = t22.prdid WHERE t22.ageid=$age) AND";
}
else
{
	//$querylink5=$querylink;
}*/
if($price!=0)
{
	$price_1="price < 100000 AND price != 0";
	$price_2="price >= 100000 AND price <= 150000";
	$price_3="price >= 150000 AND price <= 200000";
	$price_4="price >= 200000 AND price <= 250000";
	$price_5="price >= 250000 AND price <= 300000";
	$price_6="price >= 300000 AND price <= 350000";
	$price_7="price >= 350000 AND price <= 400000";
	$price_8="price >= 400000 AND price <= 450000";
	$price_9="price >= 450000 AND price <= 500000";
	$price_10="price > 500000";
	switch ($price) {
    case 1:
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE $price_1 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE $price_1) AND";
		//for company
		 $queryprice_forcompany6="id in(SELECT companyid FROM ".$prefix."_products WHERE $price_1 AND catid=$catid) AND";
		$queryprice_forcompanycount6="id in(SELECT id FROM ".$prefix."_products WHERE $price_1) AND";
        break;
    case 2:
        //$content .= show_price($number_2,2,$price,"100K - 150K");
		$queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE $price_2 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE $price_2) AND";
		//for company
		 $queryprice_forcompany6="id in(SELECT companyid FROM ".$prefix."_products WHERE $price_1 AND catid=$catid) AND";
		$queryprice_forcompanycount6="id in(SELECT id FROM ".$prefix."_products WHERE $price_1) AND";
        break;
	case 3:
       // $content .= show_price($number_3,3,$price,"150K - 200K");
	    $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE $price_3 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE $price_3) AND";
        break;
	case 4:
       // $content .= show_price($number_4,4,$price,"200K - 250K");
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE $price_4 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE $price_4) AND";
        break;
	case 5:
       // $content .= show_price($number_4,4,$price,"200K - 250K");
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE $price_5 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE $price_5) AND";
        break;
	case 6:
        //$content .= show_price($number_6,6,$price,"300K - 350K");
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE $price_6 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE price >= 300000 AND price <= 350000) AND";
        break;
	case 7:
       // $content .= show_price($number_7,7,$price,"350K - 400K");
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE price >= 350000 AND price <= 400000 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE price >= 350000 AND price <= 400000) AND";
        break;
	case 8:
       // $content .= show_price($number_8,8,$price,"400K - 450K");
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE price >= 400000 AND price <= 450000 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE price >= 400000 AND price <= 450000) AND";
        break;
	case 9:
       // $content .= show_price($number_9,9,$price,"450K - 500K");
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE price >= 450000 AND price <= 500000 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE price >= 450000 AND price <= 500000) AND";
        break;
	case 10:
       // $content .= show_price($number_10,10,$price,">500K");
        $queryprice_fornational6="id in(SELECT nationalid FROM ".$prefix."_products WHERE price > 500000 AND catid=$catid) AND";
		$queryprice_fornationalcount6="id in(SELECT id FROM ".$prefix."_products WHERE price > 500000) AND";
        break;
	default:
		$queryprice_fornational6="";
		$queryprice_fornationalcount6="";
	  } 
}
else
{
	//$querylink5=$querylink;
}
//echo $querylink;
$content ="";
$content .= "<div class=\"div-block\" style=\"border:0px;margin:0px;\">";
$content .= "<div class=\"div-prd\">";
$content .= "<div style=\"padding-top:4px;\"></div>";

$content .= "<script type=\"text/javascript\">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu(\"my_menu\");
		myMenu.init();
	};
	// ]]>
</script>";

$content .= "<div style=\"float: left\" id=\"my_menu\" class=\"sdmenu\">";	
/*
loc theo quoc gia
*/
/*
$content .= "<div>";
$content .= "<span>"._NATIONAL."</span>";
$result = $db->sql_query_simple("SELECT id, title, counts FROM ".$prefix."_national WHERE $queryprice_fornational6 $queryageid_fornational5 $querycatid_fornational $querycatid_fornationalid2  active='1'  ORDER BY weight, id ASC");
while(list($nationalid, $title, $counts) = $db->sql_fetchrow_simple($result)){
	if($counts > 0){
		$resultcount = $db->sql_query_simple("SELECT COUNT(id) as dCount FROM ".$prefix."_products WHERE $querycatid_forproduct $querycatid_forcompany3 $queryprice_fornationalcount6 nationalid=$nationalid AND active='1'");
		list($dCount) = $db->sql_fetchrow_simple($resultcount);
		if($national == $nationalid){
			if($company != 0){
				$querylink1=$querylink."&company=$company";
			}
			else
			{
				$querylink1=$querylink;
			}
			$content .= "<a href=\"$querylink1\" style=\"color:red;\" title=\""._BO_CHON."\"><img src=\"templates/Adoosite/images/false.gif\" style=\"width:9px;\">&nbsp;$title ($dCount)</a> ";
		}else{
			if($dCount!=0){
			//$querylink1=$querylink."&national=$nationalid";
			//neu hang san xuat co nhieu hon 1
			if($company != 0){
				$querylink1=$querylink."&national=$nationalid&company=$company";
			}
			else
			{
				$querylink1=$querylink."&national=$nationalid";
			}
			$content .= "<a href=\"$querylink1\">$title ($dCount)</a> ";
			}
		}		
	}
}
$content .= "</div>"; */
/*
loc theo cong ty
*/
$content .= "<div>";
$content .= "<span>"._COMPANY."</span>";
$result = $db->sql_query_simple("SELECT id, title, counts FROM ".$prefix."_company WHERE $queryprice_forcompany6 $querycatid_forcompany $querycatid_forcompany2 $querycatid_forcompanyid3 $queryageid_forcompany5 active='1' ORDER BY weight, id ASC");

while(list($companyid, $title, $counts) = $db->sql_fetchrow_simple($result)){
	if($counts > 0){	
		$resultcount = $db->sql_query_simple("SELECT COUNT(id) as dCount FROM ".$prefix."_products WHERE $querycatid_fornational2 $querycatid_forproduct $queryprice_forcompanycount6 companyid=$companyid AND active='1'");
		list($dCount) = $db->sql_fetchrow_simple($resultcount);
		if($national != 0){
			$querylink2=$querylink."&national=$national";
		}
		else
			{
				$querylink2=$querylink;
			}
		if($company == $companyid ){
			
			$content .= "<a href=\"$querylink2\" style=\"color:red;\" title=\""._BO_CHON."\"><img src=\"templates/Adoosite/images/false.gif\" style=\"width:9px;\">&nbsp;$title ($dCount)</a> ";
		}else{
				$content .= "<a href=\"$querylink2&company=$companyid\">$title ($dCount)</a> ";
		}
	}
}
$content .= "</div>";
/*
$content .= "<div>";
$content .= "<span>"._AGE."</span>";
$result = $db->sql_query_simple("SELECT id, title FROM ".$prefix."_age WHERE $queryid_forage5 $querycatid_forage5 active='1' ORDER BY weight, id ASC");
while(list($ageid, $title) = $db->sql_fetchrow_simple($result)){
	$resultcount = $db->sql_query_simple("SELECT COUNT(id) as dCount FROM ".$prefix."_products  WHERE $querycompany_forage3 $querycatid_forproduct $querycatid_fornational2 $querycatid_forcompany3 $querycatid_forage5 id IN(SELECT prdid FROM ".$prefix."_products_age WHERE ageid=$ageid) AND active='1'");
	list($dCount) = $db->sql_fetchrow_simple($resultcount);
	//$number_age = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products_age WHERE  $querycatid_forage5 ageid = $ageid "));
	//if($number_age > 0){
		if($national != 0){
			if($company != 0){$querylink5=$querylink."&national=$national&company=$company";}
			else{$querylink5=$querylink."&national=$national";}
		}
		else{
			if($company != 0){$querylink5=$querylink."&company=$company";}
			else{$querylink5=$querylink;}
		}
		if($age == $ageid){
			$content .= "<a href=\"$querylink5\" style=\"color:red;\" title=\""._BO_CHON."\"><img src=\"templates/Adoosite/images/false.gif\" style=\"width:9px;\">&nbsp;$title ($dCount)</a> ";
		}else{
			if($dCount!=0)
				$content .= "<a href=\"$querylink5&age=$ageid\">$title ($dCount)</a> ";
		}
	//}
}

$total = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE active='1' AND age = 1 ORDER BY id ASC"));
	if($total > 0){
		if($age == -1 ){
			$content .= "<a href=\"#\" style=\"color:red;\" title=\""._BO_CHON."\">
						<img src=\"templates/Adoosite/images/false.gif\" style=\"width:9px;\">&nbsp;"._DANG_CAP_NHAT." ($total)</a> ";
		}else{
			$content .= "<a href=\"#\">"._DANG_CAP_NHAT." ($total)</a> ";
		}
	}
$content .= "</div>";	*/

/*
$content .= "<div>";
$content .= "<span>"._GIA."</span>";

$number_0 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price = 0"));
$number_1 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price <= 100000 AND price != 0"));
$number_2 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 100000 AND price <= 150000"));
$number_3 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 150000 AND price <= 200000"));
$number_4 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 200000 AND price <= 250000"));
$number_5 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 250000 AND price <= 300000"));
$number_6 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 300000 AND price <= 350000"));
$number_7 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 350000 AND price <= 400000"));
$number_8 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 400000 AND price <= 450000"));
$number_9 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 450000 AND price <= 500000"));
$number_10 = $db->sql_numrows($db->sql_query_simple("SELECT id FROM ".$prefix."_products WHERE $querycompany_forprice3 $querynational_forprice2 $querycatid_forprice active='1' AND price > 500000")); */


/*
function show_price($number,$i,$price,$s)
{
	$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
	$t = isset($_GET['t']) ? $_GET['t'] : 0;
	$national = isset($_GET['national']) ? intval($_GET['national']) : 0;
	$company = isset($_GET['company']) ? intval($_GET['company']) : 0;
	$color = isset($_GET['color']) ? intval($_GET['color']) : 0;
	$age = isset($_GET['age']) ? intval($_GET['age']) : 0;
	$kieudang = isset($_GET['kieudang']) ? intval($_GET['kieudang']) : 0;
	//$price = isset($_GET['price']) ? intval($_GET['price']) : 0;
	$show = intval(isset($_GET['show']) ? $_GET['show'] : (isset($_POST['show']) ? $_POST['show']:1));
	$sort = intval(isset($_GET['sort']) ? $_GET['sort'] : (isset($_POST['sort']) ? $_POST['sort']:0));
	$querylink6="";
	$querylink="index.php?f=products&do=categories&catid=$catid&t=$t";
	$str ="";
	if($number != 0)
	{
		if($national != 0)
		{
			if($company != 0){$querylink6=$querylink."&national=$national&company=$company";}
			else{$querylink6=$querylink."&national=$national";}
		}
		else
		{
			if($company != 0){$querylink6=$querylink."&company=$company";}
			else{$querylink6=$querylink;}
		}
		if($price == $i )
		{
			$str .= "<a href=\"$querylink6\" style=\"color:red;\" title=\""._BO_CHON."\"><img src=\"templates/Adoosite/images/false.gif\" style=\"width:9px;\">&nbsp;$s ($number)</a> ";
		}else
		{
			$str .= "<a href=\"$querylink6&price=$i\">&nbsp;$s($number)</a> ";
		}
	}
	return 	$str;
}



switch ($price) {
    case 1:
        $content .= show_price($number_1,1,$price,"<100K");
        break;
    case 2:
        $content .= show_price($number_2,2,$price,"100K - 150K");
        break;
	case 3:
        $content .= show_price($number_3,3,$price,"150K - 200K");
        break;
	case 4:
        $content .= show_price($number_4,4,$price,"200K - 250K");
        break;
	case 5:
        $content .= show_price($number_5,5,$price,"250K - 300K");
        break;
	case 6:
        $content .= show_price($number_6,6,$price,"300K - 350K");
        break;
	case 7:
        $content .= show_price($number_7,7,$price,"350K - 400K");
        break;
	case 8:
        $content .= show_price($number_8,8,$price,"400K - 450K");
        break;
	case 9:
        $content .= show_price($number_9,9,$price,"450K - 500K");
        break;
	case 10:
        $content .= show_price($number_10,10,$price,">500K");
        break;
	default:
        $content .= show_price($number_1,1,$price,"<100K");
		$content .= show_price($number_2,2,$price,"100K - 150K");
		$content .= show_price($number_3,3,$price,"150K - 200K");
		$content .= show_price($number_4,4,$price,"200K - 250K");
		$content .= show_price($number_5,5,$price,"250K - 300K");
		$content .= show_price($number_6,6,$price,"300K - 350K");
		$content .= show_price($number_7,7,$price,"350K - 400K");
		$content .= show_price($number_8,8,$price,"400K - 450K");
		$content .= show_price($number_9,9,$price,"450K - 500K");
		$content .= show_price($number_10,10,$price,">500K");
		$content .= show_price($number_0,-1,$price,_DANG_CAP_NHAT);
}
 */

$content .= "</div>";
$content .= "</div>";

$content .= "</div>";
$content .= "</div>";

echo $content;
?>
