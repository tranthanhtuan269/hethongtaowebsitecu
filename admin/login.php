<?php
define('CMS_ADMIN', true);



require_once("../config.php");
require_once("language/$currentlang/main.php");

if(defined('iS_ADMIN')) {
    header("Location: body.php");
} else {
    $checklogin = 0;
    $substyle ="";
    $messlog ="";
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & E_WARNING);
    //session_register("adm_log");
    $_SESSION['adm_log'] = 0;

    if(isset($_POST['submit'])) {
        $adname = $escape_mysql_string(trim($_POST['adname']));
        $adpwd = $escape_mysql_string(trim($_POST['adpwd']));

        $adname = substr($adname, 0,25);
        $adpwd = substr($adpwd, 0,40);

        if($adname =="" || $adpwd =="" || (preg_match("![^a-zA-Z0-9_-]!",trim($adname)))) {
            $_SESSION['adm_log']++;
            header("Location: login.php?error=2");
        }

        if(!empty($adname)) {
            $adpwd = md5($adpwd);
            list($fbipwd) = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT pwd FROM ".$prefix."_admin WHERE adacc='$adname'"));
            if (($fbipwd == $adpwd)) {
                mt_srand ((double)microtime ()*10000000);
                $maxran = 10000000;
                $checknum = mt_rand (0, $maxran);
                $checknum = md5 ($checknum);
                $agent = substr (trim ($_SERVER['HTTP_USER_AGENT']), 0, 80);
                $addr_ip = substr (trim ($client_ip), 0, 15);
                $db->sql_query_simple("UPDATE {$prefix}_admin SET checknum = '$checknum', last_login = '".time()."', last_ip = '$addr_ip', agent = '$agent' WHERE adacc='$adname'");
                $_SESSION[ADMIN_SES] = base64_encode($adname."#:#".$adpwd."#:#".$checknum."#:#".$agent."#:#".$addr_ip);
                unset($_SESSION['adm_log']);
                session_write_close();
                if (isset($_GET['special'])) header("Location: body.php");
                else header("Location: body.php");
            }else{
                $_SESSION['adm_log']++;
                header("Location: login.php?error=1");
            }
        }


    }

    if($_SESSION['adm_log'] >= 115) {
        $substyle = " disabled";
        $messlog = _BEGONELOGIN." {$_SESSION['adm_log']} "._BEGONELOGIN1;

    }

    echo "<html>\n";
    echo "<head>\n";
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
    <!-- //font-awesome icons -->
    <!-- js-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <!--    <link href="js/jquery.bxslider.min.js" rel="stylesheet" type="text/css" media="all">-->
    <script src="js/modernizr.custom.js"></script>


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



    <!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
    <!--    <meta http-equiv="Content-Type" content="text/html; charset=--><?//= _CHARSET ?><!--" />-->
<?php
//    echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
//    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\">\n";
//    echo "<link rel=\"stylesheet\" href=\"styles/styles.css\" />\n";
    echo "<title>"._TITLE_ADMIN."</title>\n";
    ?>
    <script language="javascript"  type="text/javascript">
        function setFocus() {
            document.login.adname.select();
            document.login.adname.focus();
        }
    </script>
    <?php
    echo "</head>\n";
    echo "<body onload=\"javascript:setFocus();\">\n";
    ?>
    <div id="page-wrapper">
        <div class="main-page">
            <?php
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"tblpage\">\n";
            echo "<tr><!--<td><img src=\"images/j_header_left.png\" border=\"0\" align=\"baseline\"></td>-->";
            //            echo "<td align=\"right\"><img src=\"images/j_header_right.png\" border=\"0\" align=\"baseline\"></td>";
            echo "</tr>";
            echo "<tr><td colspan=\"3\" class=\"tdpage\">";
            echo "<form action=\"login.php\" method=\"POST\" name=\"login\"><table align=\"center\" border=\"0\" width=\"420\" cellpadding=\"0\" style=\"border-collapse: collapse\" bgcolor=\"#ffffff\">\n";
            echo "<tr>\n";
            echo "<td height=\"24\" background=\"images/login/bg_01.gif\"><div style=\"float: left\"><img border=\"0\" src=\"images/login/01.gif\" width=\"20\" height=\"24\"></div><div style=\"float: right\"><img border=\"0\" src=\"images/login/02.gif\" width=\"22\" height=\"24\"></div></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
            echo "<tr>\n";
            echo "<td background=\"images/login/bg_02.gif\" width=\"20\"></td>\n";
            echo "<td colspan=\"3\" width=\"20\"></td>\n";
            echo "<td background=\"images/login/bg_03.gif\" width=\"22\"></td>\n";
            echo "</tr>";
            echo "<tr>\n";
            echo "<td style=\"background: url(images/login/bg_02.gif) repeat-y top left\" width=\"20\">&nbsp;</td>\n";
            echo "<td valign=\"top\" class='sdfsdf' width=\"127\">\n";
            echo "<img border=\"0\" src=\"images/login/Adium.gif\" align=\"baseline\"></td>\n";
            echo "<td valign=\"top\" colspan=\"3\">\n";
            echo "<div><span class=\"title\">"._ADMIN_LOGIN."</span></div>\n";
            echo "<div class='login1' style=\"margin-bottom: 20px; margin-top: 15px; display: block;\"><div style=\"float: right; margin-right: 0\"><input type=\"text\" name=\"adname\" id=\"adname\" maxlength=\"20\" size=\"20\" class=\"login_input\"$substyle></div><b>"._NICKNAME."</b></div>\n";

            echo "<div class='login1' style=\"margin-bottom: 20px; display: block\"><div style=\"float: right; margin-right: 0\"><input type=\"password\" name=\"adpwd\" maxlength=\"20\" size=\"20\" class=\"login_input\"$substyle></div><b>"._PASSWORD."</b></div>\n";
            if($multilingual == 1) {
                echo "<div style=\"margin-bottom: 20px; display: block\"><div style=\"float: right; margin-right: 0\"><select name=\"lang\" class=\"login_input\"$substyle>";
                echo select_language($currentlang);
                echo "</select></div><b>"._LANGUAGE."</b></div>\n";
            }
            echo "<div><div style=\"float: right; margin-right: 0\">\n";
            echo "<input type=\"submit\" name=\"submit\" value=\""._LOGIN."\" maxlength=\"20\" size=\"20\" class=\"login_button cursor\"$substyle></div></div>\n";
            echo "</td>\n";
            echo "<td background=\"images/login/bg_03.gif\" width=\"22\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td background=\"images/login/bg_04.gif\" height=\"23\"><div style=\"float: left\"><img border=\"0\" src=\"images/login/03.gif\" width=\"20\" height=\"23\"></div><div style=\"float: right\"><img border=\"0\" src=\"images/login/04.gif\" width=\"22\" height=\"23\"></div></td>\n";
            echo "</tr>\n";
            echo "</table></form>\n";
            echo "</td></tr>";
            echo "<tr><td>";
            echo "</td></tr></table>\n";
            echo "<div class=\"titlefooter text-center\">"._TITLE_FOOTER."</div>";
            ?>
        </div>
    </div>
    <style>
        #page-wrapper{
            background: #fff;
        }
        @media screen and (max-width: 500px){
            table{
                width: 100%;
            }
            .sdfsdf{
                display: none;
            }
            input{
                width: 100%;
            }
            .login1 div{
                float: none !important;
            }
        }
    </style>
    <?php






    echo "</body>\n";
    echo "</html>\n";
}
?>