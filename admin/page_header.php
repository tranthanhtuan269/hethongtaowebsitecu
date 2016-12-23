<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

require("language/$currentlang/menu.php");
@require_once(RPATH."editor/fckeditor.php");

global $adm_pagetitle, $adm_modname, $adm_pagetitle2, $load_hf;
if($load_hf) { $noload_hf = 0; } else { $noload_hf = 1; }

$scolor1 = "#ebf3f8";
$scolor2 = "#F0F0F0";
$scolor3 = "#4399d0";

//danh sach module theo file
$modlist = "";
$testmodhandle=opendir(RPATH.'modules');
while ($file = readdir($testmodhandle))
{
    if (is_dir(RPATH."modules/$file") && ($file != '.') && ($file != '..'))
    {
        $modlist[] = "$file";
    }
}
closedir($testmodhandle);
sort($modlist);
//danh sach modules bi khoa
$listmods_noaccept = array("search","home");
//danh sach modules chua khoa
$listmenus_naccept = array("");
//show list mod
$listmods = "";
$ml2result = $db->sql_query_simple("SELECT title, custom_title FROM ".$prefix."_modules WHERE alanguage='$currentlang'");
while($rowmod = $db->sql_fetchrow_simple($ml2result))
{
    $titlemod =  $rowmod['title'];
    $titlemod_custom = $rowmod['custom_title'];
    if(@in_array($titlemod, $modlist))
    {
        $listmods[] = "".$titlemod."";
        $listmods_custom[] = "".$titlemod_custom."";
        $listmods_name[$titlemod] = $titlemod_custom;
    }
    else
    {
        $db->sql_query_simple("delete from ".$prefix."_modules where title='$titlemod'");
        $db->sql_query_simple("OPTIMIZE TABLE ".$prefix."_modules");
    }
}
//get all menus admin
$listmenus = "";
$menuresult = $db->sql_query_simple("SELECT file_menu FROM ".$prefix."_admin_menu order by weight");
while($rowmenu = $db->sql_fetchrow_simple($menuresult))
{
    if (file_exists("menus/adm_".$rowmenu['file_menu'].".php"))
    {
        $file_menu =  $rowmenu['file_menu'];
        $listmenus[] = "".$file_menu."";
        include("menus/adm_".$file_menu.".php");
        if($menu_main != "")
        {
            $listnamemenu[]= $menu_main;
        }
    }
}
//cap nhat modules
for ($i=0; $i < sizeof($modlist); $i++)
{
    if($modlist[$i] != "" AND !@in_array($modlist[$i],$listmods))
    {
        $db->sql_query_simple("INSERT INTO " . $prefix . "_modules (mid, title, custom_title, active, view, inmenu, alanguage) VALUES (NULL, '$modlist[$i]', '$modlist[$i]', '0', '0', '1', '$currentlang')");
    }
}

function OpenDiv() {
    global $scolor1, $scolor3;
    echo "<table align=\"center\" border=\"2\" width=\"90%\" cellspacing=\"0\" cellpadding=\"10\" style=\"border-collapse: collapse\" bordercolor=\"$scolor3\" class=\"tableborder table table-bordered\">\n";
    echo "<tr><td bgcolor=\"$scolor1\">\n";
}

function CloseDiv() {
    echo "</td></tr></table>";
}

if ($noload_hf) {
//	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
    echo "<!DOCTYPE HTML>";
    echo "<html dir=\"ltr\" lang=\"en\">\n";
    echo "<head>\n";
    echo "<title>";
    if($adm_pagetitle) {
        echo "$adm_pagetitle - ";
    }
    if($adm_pagetitle2) {
        echo "$adm_pagetitle2 - ";
    }
    echo "Admin Control Panel";
    echo "</title>\n";



    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=<?= _CHARSET ?>" />
    <meta name="keywords" content="#" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>


    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- font CSS -->
    <!-- font-awesome icons -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css" />
    <!-- //font-awesome icons -->
    <!-- js-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.tableborder, .tbl-main').addClass('table table-bordered');
            // $('#footermenus_main,#leftmenus_main,#daily_main,.main-content>form,#forum_main,#surveys_main,#register_main,#langEditor_main,#number_main,#picture_main,#blocks_main,#authors_main,#products_main, #ajaxload_container,#mainmenus_main,#adminmenu_main,.main-content>table,#contact_main,#advertise_main,#introduce_main,#modules_main,#adminlog_main,#user_main,#link_main,#question_main,#newsletter_main,#video_main').wrapAll('<div id="page-wrapper"><div class="main-page"><!--<div class="table table-responsive"></div>--></div></div>');
            if('#page-wrapper #page-wrapper'){
                $('#page-wrapper #page-wrapper').removeAttr("id");
            }
            $("#lof_go_top, .toptop").click(function(){
                $("html,body").animate({scrollTop:0}, '200');
            });
            $(window).scroll(function(){
                var scroll = $(window).scrollTop();
                if (scroll>200){
                    $("#lof_go_top").fadeIn();
                }else{
                    $("#lof_go_top").fadeOut();
                }
            });

            $('#showLeftPush').click(function(){
                $('i',this).toggleClass('fa-arrow-left');
            })

        })
    </script>
    <script>
        (function($){
            $(window).on("load",function(){
                $("#content-1").mCustomScrollbar({
                    scrollButtons:{enable:true}
//                    theme:"light-thick"
                });
            });
        })(jQuery);
    </script>

    <!--webfonts-->
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <!--//webfonts-->
    <!--animate-->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">

    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <!--//end-animate-->
    <!-- chart -->
    <script src="js/Chart.js"></script>
    <!-- //chart -->
    <!--Calender-->
    <!--    <link rel="stylesheet" href="css/clndr.css" type="text/css" />-->
    <!--    <script src="js/underscore-min.js" type="text/javascript"></script>-->
    <!--    <script src= "js/moment-2.2.1.js" type="text/javascript"></script>-->
    <!--    <script src="js/clndr.js" type="text/javascript"></script>-->
    <!--    <script src="js/site.js" type="text/javascript"></script>-->
    <!--End Calender-->
    <!-- Metis Menu -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
    <style>

    body {
        font-family: "Roboto",Helvetica Neue,Helvetica,Arial,sans-serif;

        color: #46484a;
    }

    body  a{ color: #46484a; }

    img {
        max-width: 100%;
    }

    .fa-bars.fa-arrow-left:before {
        content: "\f061" !important;
    }
    #side-menu{
        max-height: auto !important;
        overflow-y: inherit !important;
    }
    select[name^="cat"], input[type^="text"], textarea[name^="description_seo"], textarea, textarea[name^="seo_description"], input[type="file"] {
        border: 1px solid #ccc;
        padding: 5px 8px;
        color: #616161;
        background: #fff;
        box-shadow: none !important;
        width: 100%;
        font-weight: 300;

        border-radius: 0;
        -webkit-appearance: none;
        resize: none;
    }

    .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {

        line-height: 18px;
        vertical-align: middle;
        font-size: 13px !important;
        border: 1px solid #eee;
        padding: 5px;
        color: #616161;
        background: #fff;
        box-shadow: none !important;
        font-weight: 300;
        border-radius: 0;
        -webkit-appearance: none;
        resize: none;
        font-family: "Roboto",Helvetica Neue,Helvetica,Arial,sans-serif;

    }
    *{
        font-family: arial;
        /*font-size: 13px;*/
    }
    .sidebar ul li a {
        font-size: 14px;
        padding: 4px 10px;
    }
    .sidebar .nav-second-level li a {
        font-size: 13px;
    }
    .sidebar ul li {
        margin-bottom: 10px;
    }
    .header{
        font-weight: 600 !important;
        font-size: 16px !important;
        padding: 0.8em 1em !important;
        /* border-bottom: 1px solid transparent !important; */
        color: #333;
        background-color: #f5f5f5 !important;
        border-color: #ddd !important;
        /* font-family: 'Roboto Condensed', sans-serif; */

    }

    .main-page {
        background-color: #fff;
        box-shadow: 0 -1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
        -webkit-box-shadow: 0 -1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
        -moz-box-shadow: 0 -1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);}

    .table-bordered b, .table-bordered td{
        font-size: 13px;
    }
    .cbp-spmenu-vertical {
        background-color: #16addc;
    }
    .nav > li > a:hover, .nav > li > a:focus {
        color: #FFEB3B;
    }
    .sidebar .nav-second-level li a.active, .sidebar ul li a.active {
        color: #FFEB3B;
    }
    .logo {
        background: #23b3e0;
    }

    .cke_skin_v2 .cke_browser_gecko18 .cke_rcombo, .cke_skin_v2 .cke_browser_gecko18 .cke_rcombo .cke_label, .cke_skin_v2 .cke_browser_gecko18 .cke_rcombo .cke_text, .cke_skin_v2 .cke_browser_gecko18 .cke_rcombo .cke_openbutton, .cke_skin_v2 .cke_browser_webkit .cke_rcombo .cke_label, .cke_skin_v2 .cke_browser_webkit .cke_rcombo .cke_text, .cke_skin_v2 .cke_browser_webkit .cke_rcombo .cke_openbutton {
        display: block;
        float: left;
    }
    .cke_skin_v2 .cke_ltr .cke_rcombo .cke_text {
        -moz-border-radius-topleft: 3px;
        -webkit-border-top-left-radius: 3px;
        border-top-left-radius: 3px;
        -moz-border-radius-bottomleft: 3px;
        -webkit-border-bottom-left-radius: 3px;
        border-bottom-left-radius: 3px;
    }
    .cke_skin_v2 .cke_rcombo .cke_text {
        height: 20px !important;
    }
    input[name^="submit"] {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;

        color: #fff;
        background-color: #F2B33F;

    }
    input[name^="submit"]:hover{
        color: #fff;
        background-color: #35bde5;
        border-color: #29a6cb;
    }
    #lof_go_top:hover {
        background-color: #C22500;
        border: 3px solid #C22500;
    }
    #lof_go_top {
        background: url(images/back-top.png) no-repeat scroll 50% 50% #bbb;
        bottom: 150px;
        display: none;
        height: 55px;
        position: fixed;
        right: 12px;
        width: 55px;
        border: 3px solid #bbb;
        z-index: 99999;
        border-radius: 30px;
    }
    select[name^="cat"], input[type^="text"], textarea[name^="description_seo"], textarea, textarea[name^="seo_description"], input[type="file"]{
        width: 100%;
    }
    .pagenum{
        display: inline-block;
        min-width: 30px;
        text-align: center;
        padding: 3px;
        border: 1px solid #ccc;
        margin: 0 2px;
    }
    .page{
        margin-bottom: 20px;
        margin-top: 20px;
    }
    form[name^="formSearch"] td{
        font-size: 13px;
        padding: 5px;
    }
    #countrytabs li{
        float: left;
        list-style: none;
        border: 1px solid #ddd;
        margin: 0 8px 15px 0px;
    }
    #countrytabs li a{
        padding: 5px 10px;
        display: block;
    }
    #countrytabs:after{
        clear: both;
        content: '';
        display: block;
    }
    .imagese{
        max-width: 100px;
    }
    #countrytabs li .selected{
        color: #004499;
        background: #eee;
    }

    .content,.content-3{
        height: 500px;
    }
    @media screen and (max-width: 767px){
        #side-menu {
            max-height: auto !important;
            overflow-y: inherit !important;
        }
        .content{
            height: 400px;
        }
        .cbp-spmenu-push div#page-wrapper {
            padding: 8.2em 0.3em 1.5em;
        }
    }
    @media screen and (min-width: 768px) and (max-width: 860px){
        .logo {
            float: none;
            display: inline-block;
        }
    }
    @media (max-width: 640px){
        select[name^="cat"], input[type^="text"], textarea[name^="description_seo"], textarea, textarea[name^="seo_description"], input[type="file"] {
            width: 100% !important;
        }
        .header-right {
            width: auto !important;
            float: right !important;
        }
        .header-left {
            width: auto !important;
            float: left !important;
        }
    }
    @media (max-width: 520px){
        .user-name{
            display: none;
        }
        .logo a h1 {
            font-size: 13px;
            line-height: 1em;
        }
        button#showLeftPush {
            padding: 13px 0;
        }
        .cbp-spmenu-vertical {
            top: 61px !important;
        }

    }
    .sidebar ::-webkit-scrollbar {
        width: 12px;
    }


    </style>
<?php



//	echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
//	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">\n";
//	echo "<link rel=\"stylesheet\" href=\"styles/styles.css\" />\n";
	echo "<script language=\"javascript\" src=\"../js/common.js\"></script>\n";
//	echo "<script language=\"javascript\" src=\"js/tooltip.js\"></script>\n";
	echo "<script language=\"javascript\" src=\"js/adm_common.js\"></script>\n";
	echo "<script language=\"javascript\" src=\"js/tabcontent.js\"></script>\n";
//	echo "<script type=\"text/javascript\" src=\"../js/mudim.packed.js?ver=1.2\"></script>\n";
//    echo'<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>';



    // echo "<script type=\"text/javascript\" src=\"js/jquery.price_format.2.0.min.js\"></script>\n";
    //popup menu



//	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/ddlevelsmenu-base.css\" />";
//	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/ddlevelsmenu-topbar.css\" />";
//	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/ddlevelsmenu-sidebar.css\" />";
//	echo "<script type=\"text/javascript\" src=\"js/ddlevelsmenu.js\"></script>";
//	echo "<script type=\"text/javascript\" src=\"js/jquery-1.8.0.min.js\"></script>";




    //end popup menu
    if (file_exists("js/".$adm_modname.".js")) {
        echo "<script type=\"text/javascript\" src=\"js/".$adm_modname.".js\"></script>\n";
    }
    echo "</head>\n";

    echo "<body class='cbp-spmenu-push' bgcolor=\"#FFFFFF\" topmargin=\"0\" leftmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" scroll=\"yes\"  onload='/*set_cp_title();*/'   >\n";
    ?>
    <div id="lof_go_top">
        <span class="ico_up"></span>
    </div>
    <?php
    echo "<div class='main-content'>";
    include_once("menu.php");
}

?>
