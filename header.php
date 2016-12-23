<?php
if (!defined('CMS_SYSTEM')) die('Stop!!!');
ob_start();
global $keywords_site, $description_site, $title_site, $header_page_keyword, $home, $Default_Temp, $page_title, $page_title2, $module_title, $sitename, $siteurl, $urlsite, $pagetitle, $module_name, $load_hf, $n_imgz;
$urlsite = "http://localhost/chetancuong";
include_once("resizeImg.Class.php");
@session_start();
session_name("size");
$title_site = $sitename;
if($home == 1) $module_title = ""._HOMEPAGE."";
include_once(RPATH."templates/$Default_Temp/index.php");
echo '<!DOCTYPE HTML>';
echo "<html>\n";
echo "<head>\n";
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo "<meta http-equiv=\"content-type\" content=\"text/html; charset="._CHARSET."\">\n";
echo "<meta http-equiv=\"expires\" content=\"0\">\n";
$genhour = gmdate("H");
if (intval($genhour) != "" && intval($genhour) != 0) $genhour = $genhour - 1;
@header("Last-Modified: ".gmdate("D, d M Y ".intval($genhour).":i:s")." GMT");
@header("Content-Type: text/html; charset="._CHARSET."");
echo "<meta name=\"resource-type\" content=\"document\">\n";
echo "<meta name=\"distribution\" content=\"global\">\n";
echo "<meta name=\"author\" content=\"$sitename\">\n";
echo "<meta name=\"google-site-verification\" content=\" \" />";
echo "<meta name=\"copyright\" content=\"Copyright (c) ".gmdate("Y")." by $sitename\">\n";
if (!isset($keywords_page) || $keywords_page == "") {$key_words = $keywords_site;}
else  {$key_words = $keywords_page;}
echo "<meta name=\"keywords\" content=\"".strip_tags($key_words)."\">\n";
if (!isset($description_page) || $description_page == "") {$description_1 = $description_site;}
else {$description_1 = $description_page;}
echo "<meta name=\"description\" content=\"".strip_tags($description_1)."\">\n";
echo "<meta name=\"robots\" content=\"index, follow\">\n";
echo "<meta name=\"revisit-after\" content=\"1 days\">\n";
echo "<meta name=\"rating\" content=\"general\">\n";
if ($home == 1) {
  // $n_imgz = "logo.png";
}
?>
<meta name="google-site-verification" content="<?php echo $webmastertools ?>" />
<link rel="shortcut icon" href="<?php echo $urlsite?>/favicon.ico" type="image/x-icon" /><?php
if (!isset($title_page) || $title_page == "") {$page_title = $title_site;}
else  {$page_title = $title_page;}
echo "<title>".strip_tags($page_title)."</title>\n";
$genhour = gmdate("H");
if (intval($genhour) != "" && intval($genhour) != 0) $genhour = $genhour - 1;
@header("Last-Modified: ".gmdate("D, d M Y ".intval($genhour).":i:s")." GMT");
@header("Content-Type: text/html; charset="._CHARSET."");
?>
<meta property="og:url"           content="<?= getCurrentPageURL() ?>" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="<?=  strip_tags($page_title);  ?>" />
<meta property="og:description"   content="<?=  strip_tags($description_1);  ?>" />
<meta property="og:image"         content="<?= $urlsite."/". $n_imgz  ?>" />
<link rel="StyleSheet" href="<?php echo $urlsite ?>/templates/Adoosite/bootstrap/css/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $urlsite ?>/templates/Adoosite/font-awesome/css/font-awesome.min.css">
<link rel="StyleSheet" href="<?php echo $urlsite ?>/templates/Adoosite/css/style.css" type="text/css"/>
<link href="<?php echo $urlsite ?>/templates/Adoosite/css/helpers.css" rel="stylesheet"  type="text/css" media="all" />
<link href="<?php echo $urlsite ?>/templates/Adoosite/css/lightbox.css" rel="stylesheet"  type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo $urlsite ?>/templates/Adoosite/css/owl.carousel.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="<?php echo $urlsite ?>/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo $urlsite ?>/templates/Adoosite/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
 $(document).ready(function () {
    $('#abc').click(function(){
        $(this).css('display', 'none');
        $(this).html('');
    });
  });
</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6&appId=1622035831447156";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</head>
<?php
if (!$load_hf) themeheader();
$nohf = 1;
ob_end_flush();

function getCurrentPageURL() {
    $pageURL = 'http';
    if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
