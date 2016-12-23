<?php

if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) { die('Stop!!!'); }

function url_sid($s,$act="",$value="")
{
	global $db,$f, $do, $rewrite_mod, $urlsite, $prefix;
	//permalink for news detail
	$query_news = "SELECT n.permalink, c.permalink FROM ".$prefix."_news AS n,".$prefix."_news_cat AS c WHERE n.guid='$s' AND n.catid=c.catid";
	$result_news = $db->sql_query_simple($query_news);
	list($permalink,$cpermalink) = $db->sql_fetchrow_simple($result_news);
	//permalink for news category
	$query_newscat = "SELECT permalink, catid FROM ".$prefix."_news_cat WHERE guid='$s'";
	$result_newscat = $db->sql_query_simple($query_newscat);
	list($permalink_cat, $catid) = $db->sql_fetchrow_simple($result_newscat);



	//permalink for products detail
	$query_prd = "SELECT p.permalink, c.permalink FROM ".$prefix."_products AS p,".$prefix."_products_cat AS c WHERE p.guid='$s' AND p.catid=c.catid";
	$query_prd = $db->sql_query_simple($query_prd);
	list($prdlink,$cprdlink) = $db->sql_fetchrow_simple($query_prd);
	//permalink for products category
	$query_prdcat = "SELECT permalink FROM ".$prefix."_products_cat WHERE guid='$s'";
	$query_prdcat = $db->sql_query_simple($query_prdcat);
	list($prdlink_cat) = $db->sql_fetchrow_simple($query_prdcat);


    $query_video = "SELECT p.permalink FROM ".$prefix."_video AS p WHERE p.guid='$s'";
    $query_video = $db->sql_query_simple($query_video);
    list($prdlinkvideo) = $db->sql_fetchrow_simple($query_video);


	$urlin = array(
	"'download.php\?id=([0-9]*)'",
	"'click.php\?id=([0-9]*)'",
	"'(?<!/)index.php'",

    "'(?<!/)$urlsite\?f=video&do=detail&id=([0-9]*)'",

	"'(?<!/)$urlsite\?f=news&do=print&id=([0-9]*)'",
	"'(?<!/)$urlsite\?f=news&do=detail&id=([0-9]*)'",
	"'(?<!/)$urlsite\?f=news&do=categories&id=([0-9]*)&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=news&do=categories&id=([0-9]*)'",
	"'(?<!/)$urlsite\?f=news&do=tags&tag=(.*)&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=news&do=tags&tag=(.*)'",
	"'(?<!/)$urlsite\?f=news'",

	"'(?<!/)$urlsite\?f=products&do=detail&id=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=categories&id=([0-9]*)&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=categories&id=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=tags&tag=(.*)&catid=(.*)&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=tags&tag=(.*)&catid=(.*)'",
	"'(?<!/)$urlsite\?f=products&do=tags&tag=(.*)&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=tags&tag=(.*)'",
	"'(?<!/)$urlsite\?f=products&do=search&p=([0-9]*)&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=search&p=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=datphong'",

	"'(?<!/)$urlsite\?f=user&do=login&type=log&alias=(.*)'",
	"'(?<!/)$urlsite\?f=user&do=login'",
	"'(?<!/)$urlsite\?f=user&do=register'",
	"'(?<!/)$urlsite\?f=user&do=recover'",
	"'(?<!/)$urlsite\?f=user&do=logout'",
	"'(?<!/)$urlsite\?f=user&do=edit_profile'",

	"'(?<!/)$urlsite\?f=products&do=cart_add&id=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=cart_delete&id=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products&do=giohang'",
	"'(?<!/)$urlsite\?f=products&do=tags'",
	"'(?<!/)$urlsite\?f=products&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=products'",
	"'(?<!/)$urlsite\?f=restaurant'",
	"'(?<!/)$urlsite\?f=specialty'",
	"'(?<!/)$urlsite\?f=contact'",
	"'(?<!/)$urlsite\?f=daily'",
	"'(?<!/)$urlsite\?f=sitemap'",
    "'(?<!/)$urlsite\?f=picture'",
	"'(?<!/)$urlsite\?f=picture&do=categories&id=([0-9]*)&page=([0-9]*)'",
	"'(?<!/)$urlsite\?f=picture&do=categories&id=([0-9]*)'",


	);

	$urlout = array(
	"$urlsite/download/\\1/",
	"$urlsite/click/\\1/",
	"$urlsite",

    "$urlsite/video/".$prdlinkvideo.".html",

	"$urlsite/print/\\1.html",
	"$urlsite/".$cpermalink."/".$permalink.".html",
	"$urlsite/".$permalink_cat."/page/\\2/",
	"$urlsite/".$permalink_cat."/",
	"$urlsite/tag/\\1/\\2/",
	"$urlsite/tag/\\1",
	"$urlsite/tin-tuc.html",

	//"$urlsite/".$cprdlink."/".$prdlink.".htm",
	"$urlsite/".$prdlink."-\\1.html",
	"$urlsite/".$prdlink_cat."/page/\\2",
	"$urlsite/".$prdlink_cat.".html",
	"$urlsite/san-pham/tags/\\1/catid/\\2/\\3/",
	"$urlsite/san-pham/tags/\\1/catid/\\2",
	"$urlsite/tag/\\1/\\2/",
	"$urlsite/tag/\\1",
	"$urlsite/tim-kiem/\\1/\\2",
	"$urlsite/tim-kiem/\\1",
	"$urlsite/phong-nghi/dat-phong-nhanh/",

	"$urlsite/khach-hang/dang-nhap.html&type=(.*)&alias=(.*)",
	"$urlsite/khach-hang/dang-nhap.html",
	"$urlsite/khach-hang/dang-ky/",
	"$urlsite/khach-hang/quen-mat-khau/",
	"$urlsite/khach-hang/thoat/",
	"$urlsite/khach-hang/thong-tin-ca-nhan/",

	"$urlsite/san-pham/add/\\1",
	"$urlsite/san-pham/del/\\1",
	"$urlsite/san-pham/gio-hang/",
	"$urlsite/san-pham/tags/",
	"$urlsite/san-pham/\\1.htm",
	"$urlsite/san-pham",
	"$urlsite/san-pham-robo.html",
	"$urlsite/dac_san.html",
	"$urlsite/lien-he.html",
	"$urlsite/dai-ly.html",
	"$urlsite/sitemap.html",
    "$urlsite/thu-vien-anh.html",
	"$urlsite/picture/\\1/\\2.html",
	"$urlsite/picture/\\1.html",



	);
	$s=$s.$value;
	if($rewrite_mod == 1) {
		if($act == 1 || ($act != 1 && !defined('CMS_ADMIN'))) {
			$s = preg_replace($urlin, $urlout, $s);
		}
	}

	return $s;

}

?>