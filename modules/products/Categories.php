<?php
if (!defined('CMS_SYSTEM')) die();
global $Default_Temp, $urlsite,$prefix;
//$catid = intval($_GET['id']);
$where = $idbuc = $select = $select1 = $select2 = $select3 = $select4 = "" ;
$catid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$t = isset($_GET['t']) ? $_GET['t'] : "";
$n = isset($_GET['n']) ? $_GET['n'] : "";
$c = isset($_GET['c']) ? $_GET['c'] : "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$t=trim($t);
if($catid!=0)
	$where.="catid=$catid AND ";
if($t!="")
	$where.="permalink='$t' AND ";
if($c!="")
	$where.="permalink='$c' AND ";



$result = $db->sql_query_simple("SELECT catid, title,gioithieu, guid, sub_id, parentid, seo_keyword, seo_description FROM ".$prefix."_products_cat WHERE $where alanguage='$currentlang'");

list($catid, $catname, $gioithieu, $guid, $sub_id, $parentid, $seo_keyword, $seo_description) = $db->sql_fetchrow_simple($result);



$cat_relate_ids = get_sub_cat($catid);
$parentid1 = $parentid;
if ($parentid != 0) {
	$title_cat = page_tilecat($catid, $parentid, $catname);
	$page_title .= "$seo_keyword";
} else {
	$title_cat = "$seo_keyword";
	$page_title .= " - $seo_description";
}
if($parentid != 0) {
	$title_cat = page_tilecat($catid, $parentid, $catname);
	$title_home = "<a href=\"".url_sid("index.php/")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE." &raquo; </a>";
	// $title_home .= "<li><a href=\"".url_sid("index.php?f=".$module_name."")."\" > "._MODTITLE." </a></li>";
	$title_home .= "".$title_cat."";
} else {
	$catname2 = "<li><a href=\"".url_sid("index.php?f=".$module_name."&do=categories&id=$catid")."\" > $catname</a></li>";
	$title_home = "<li><a href=\"".url_sid("index.php/")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE." &raquo; </a></li>";
	// $title_home .= "<li> <a href=\"".url_sid("index.php?f=".$module_name."")."\" > "._MODTITLE." </a></li>";
	$title_home .= "".$catname2."";
}

include_once("header.php");
$parentgoc = showcatidgoc($catid,$parentid,"");
list($images_cat) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT images FROM adoosite_products_cat WHERE catid = $parentgoc LIMIT 1"));
$path_upload_img = "$path_upload/products";
if(file_exists("$path_upload_img/$images_cat") && $images_cat !="") 
{
	$news_img = "$path_upload_img/$images_cat";
	
} 
else 
{
	$news_img = ""; 
}
	// code tim kie
 
	// $op = intval(isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op']:0));
$pageURL = 'http';
if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}

	$op = substr($pageURL,strlen($pageURL)-3,3);
	if($op == 'asc'){
		$op = 1;
		$select1 = "selected";
	}elseif ($op == 'des') {
		$op = 2;
		$select2 = "selected";
	}elseif ($op == 'tim') {
		$op = 3;
		$select3 = "selected";
	}elseif ($op == 'time') {
		$op = 4;
		$select4 = "selected";
	}
	else{
		$op = 0;
		$select = "selected";
	}

$query = "";
	 
switch($op){
	case 0 : $query .= "Order By id desc"; break;
	case 1 : $query .= "Order By price1 asc"; break;
	case 2 : $query .= "Order By price1 desc";break;
	case 3 : $query .= "Order By time asc";break;
	case 4 : $query .= "Order By id asc"; break;
	default: $query .= "Order By id desc";
}
?>
<section class="box_content">
	<section class="bg_title">
		<div class="container">
	        <ol class="breadcrumb">
	            <?= $title_home ?>
	        </ol>
	    </div>
	</section>

	<div class="container">
		<div class="bg_sp_home">
            <div class="title_sp">
                <h1 class="title_gt"><a title="<?= $title_cat ?>"><?= $catname ?></a></h1>
            </div>
        </div>
        <div class="clear"></div>
		<?php

		//fantrang
		$cat_sub = listparentprd("products",$catid,"");
		$cat_sub = trim($cat_sub,",");

		$perpage = $prd_perpage;
		$perpage=6;
		$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
		$offset = ($page-1) * $perpage;
		
		$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_products WHERE catid in ($cat_sub) and active=1 AND alanguage='$currentlang'"));//AND catid=87 
		$total = ($countf[0]) ? $countf[0] : 1;
		$pageurl = "index.php?f=products&do=categories&id=$catid";
		
		//end fantrang	
		$result_prd_home = $db->sql_query_simple("SELECT id,prdcode, title, link1, price1, priceold, text, link2, link3,  hits, counts, link1 FROM {$prefix}_products WHERE catid in ($cat_sub) AND active='1' AND alanguage='$currentlang' $query  LIMIT $offset, $perpage");
		if($db->sql_numrows($result_prd_home) > 0)
		{
			echo '<div class="row">';
			$i = 1;
			while(list($id,$prdcode, $title, $link1, $price1, $priceold, $text , $link2, $link3, $hits, $counts , $link1) = $db->sql_fetchrow_simple($result_prd_home))
			{
					$text = strip_tags($text, '<a><b><u><i><strong><em>');
				$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id order by id asc LIMIT 1");
				list($images) = $db->sql_fetchrow_simple($result_prd_img);
				$path_upload_img = "$path_upload/products";
				if(file_exists("$path_upload_img/$images") && $images !="")
				{
	                $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/370x0_$images" ,370,0);
					$news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
				}
				else
				{
					$news_img ="";
	                $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/370x0_no_image.gif" ,370,0);
					$news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
				}
		  		if($price1 != 0 )
                {
                    $gia = "".dsprice($price1).' đ';
                    
                    if($priceold !=0 && $priceold > $price1){
                        $gia1 = "".dsprice($priceold).' đ';
                    }
                    else
                    {
                        $gia1 = "";
                    }
                }
                else
                {
                    $gia = "Liên hệ";
                    $gia1 = "";
                }
				$url_prd =url_sid("index.php?f=products&do=detail&id=$id");
				?>
					<div class="item-doc col-sm-3 col-xs-12" style="margin-bottom: 30px;">
                        <div class="item">
                            <div class="item-box-img">
                                <a href="<?= $url_prd ?>" title="<?= $title ?>"><?= $news_img ?></a>
                            </div>
                            <div class="item-box-info">
                                <a href="<?= $url_prd ?>" title="<?= $title ?>"><?= $title ?></a>
                            </div>
                            <div class="item-box-count">
                                <div class="count-view">
                                    <span><font style="color:#f00"><?= $gia ?></font></span>
                                </div>
                            </div>
                            <div class="item-box-des">
                                <a class="btn btn-info" href="<?= $url_prd ?>" title="<?= $title ?>"> Chi tiết</a>
                                <a class="btn btn-info" href="<?= url_sid("index.php?f=products&do=cart_add&id=$id") ?>" title="Mua ngay"> Mua ngay</a>
                            </div>
                        </div>
                    </div>
		 		<?php
		        $i++;
			}
			echo "</div>";
			echo "<div class=\"cl\"></div>"; 
			if($total > $perpage) {
				echo "<div class=\"pagging\">";
					echo paging($total,$pageurl,$perpage,$page);
				echo "</div>";
			}
		}
		else
		{
			echo '<p>Thông tin đang cập nhập...</p>';
		}
		?>
	</div>
</section>
<?php
include_once("footer.php");

?>