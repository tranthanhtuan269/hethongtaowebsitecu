<?php
if (!defined('CMS_SYSTEM')) die();

function temp_news_cat_index($catid, $titlecat, $idhn, $titlehn, $hometexthn, $newshn_pic, $others) {
	OpenTab($titlecat);

	echo "<table border=\"0\" width=\"100%\" cellpadding=\"0\" style=\"border-collapse: collapse\">";
	echo "<tr>";
	echo "<td>";
	echo "<div style=\"margin-bottom: 3px\" class=\"content\">$newshn_pic<a href=\"".url_sid("index.php?f=news&do=detail&id=$idhn")."\" class=\"titlecat\"><h3>$titlehn</h3></a></div>";
	echo "<div align=\"justify\" class=\"content\">$hometexthn</div>";
	echo "<div class=\"viewmore\" style=\"margin-top: 6px\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$idhn")."\" class=\"strong\">"._READMORE."</a></div>";
	echo "</td>";
	echo "</tr>";
	if($others) {
		echo "<tr><td style=\"padding-left: 8px; padding-top: 10px;\">$others</td></tr>";
	}
	echo "</table>";

	CloseTab();
}
function  temp_newdetail($id, $title, $time, $hometext, $bodytext, $fattach, $othershow, $images,  $imgtext, $others, $others2, $source, $news_tid, $title_seo, $description_seo, $keyword_seo, $tags, $hits, $comment, $comment_content,$pageURL, $parentgoc)
{
	global $module_name, $adm_mods_ar, $admin_fold, $url, $urlsite, $imgFold;

	echo "<div class=\"tin-tuc\">";
	echo "<div class=\"tieu-de\"><h1>$title</h1></div>";
    echo "<div class=\"Author_time_detail\"><span>"._SAVECHANGES.": </span> ".ext_time($time,1)."  <span>"._VIEW.": ".$hits."</span></div>";

    echo "<div class=\"des_detail\">$hometext</div>";
	echo "<div class=\"content\">$bodytext</div>";
	if($fattach !="") {
		echo "<div class=\"clearfix\" style=\" \">";
		echo "<b>"._FILE_ATTACH.":</b> <img border=\"0\" src=\"images/file.gif\" align=\"absmiddle\">&nbsp;<a href=\"".url_sid("$url")."\" style=\" \">$fattach</a> (".ext_time($time,2).") ";
		echo "</div>";
	}
	// if($source !="") {
	// 	echo " <div align=\"right\" style=\"margin-top: 20px\"><i><b>$source</i></b></div>";
	// }

	/*if(defined('iS_SADMIN') || defined('iS_RADMIN') || (defined('iS_ADMIN') && in_array($module_name,$adm_mods_ar))) {
		echo "<div align=\"right\" style=\"margin-top: 3px\">[<a href=\"".$admin_fold."/modules.php?f=news&do=edit_news&id=$id\" target=\"mainFrame\">"._EDIT."</a> | <a href=\"".$admin_fold."/modules.php?f=news&do=delete_news&id=$id\" target=\"mainFrame\" onclick=\"return confirm('"._DELETEASK."');\">"._DELETE."</a>]</div>";
	}*/

	echo "<div class=\"tags\">$tags </div>";

	echo "</div>";
    // if ($parentgoc == 98) {
    //     display_form();
    // }
	echo "<div class=\"div_icon_detail\">";
         echo "<span class=\"icon_1\" style=\"float:left\"><a href=\"javascript:history.go(-1);\">[ "._BACK." ]</a> <a href=\"#\">[ "._TOP." ]</a></span>";
        echo "<span class=\"icon_2\">";
        // echo "<div><a  title=\""._PRINT."\" href=\"".url_sid("index.php?f=news&do=print&id=".$id."")."\" target=\"_blank\"><img border=\"0\" src=\"".$urlsite."/images/print.png\"> In trang</a> <a href=\"javascript:void(0)\" onclick=\"openNewWindow('".url_sid("index.php?f=news&do=email&id=".$id."")."',300,600')\"><img border=\"0\" src=\"".$urlsite."/images/mail.png\" title=\""._SENDFRIEND."\"> Gửi mail</a></div>";
    echo "".links_share()."</span>";
    echo "</div>";

	if($othershow != 1)
	{
        if ($parentgoc == 9811) {
            if($others2) {
                echo "<div class='title_b' style=\"text-align: center;\">";
                echo '<a href="#" class="prev"><img src="'.$urlsite.'/images/otther_prev.png" alt="icon_prev"></a>
            <h2>Các dự án khác</h2>
            <a href="#" class="next"><img src="'.$urlsite.'/images/otther_next.png" alt="icon_next"></a>';
                    echo "<div class=\"row\">";
                    	echo '<div class="owl-carousel">';
                       		echo "$others2";
                       	echo "</div>";
                       	?>
					        <script type="text/javascript">
					            (function($) {
					                "use strict";
					                $(".owl-carousel").each(function(){
					                    var my = $(this);
					                    my.owlCarousel({
					                    items : 4, itemsDesktop : [1000,2],
					                    itemsTablet: [600,1],
					                    itemsMobile : false,
					                    autoPlay: true
					                });
					                my.parent().parent().find('a[href^="#"]').click(function(ev){
					                    ev.preventDefault();
					                    my.trigger('owl.'+$(this).attr('class')); });
					                });
					            })(jQuery);
					        </script>
				        <?php
                    echo "</div>";
                echo "</div>";
            }
        }
		else
        {
    		if($others) {
    			echo "<div class='title_b'>";
                echo "<h6 class='title_b'>"._OTHER_NEWS."</h6>";
    			   echo "$others";
                echo "</div>";
    		}
        }
	}
}

function temp_newcat_start($id, $catid, $source, $title, $hometext, $images, $time, $hits) {
	echo "<div class='list-cat-new'>";
	echo "<div class='img-cat-new'><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" >$images </a></div>";
	echo "<div class='title-cat-new'><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" ><h4 class=\"title2\">$title</h4></a></div>";
//<i class=\"fa fa-calendar\" aria-hidden=\"true\"></i>
	echo "<div class=\"Author_time\"><span></span> ".ext_time($time,1)." - <span>".$hits." "._VIEW."</span></div>";
	
	echo "<div class='list_box_new' align=\"justify\" style=' '>".cutText($hometext,700)."</div>";
	echo "<div class=\"viewmore\"><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" class=\"strong\">"._READMORE." &raquo;</a></div>";
	echo "</div>";
}

function temp_news_index($id, $title, $hometext, $newspic) {
	echo "<div class='list-cat-new'>";
	echo "<div class='img-cat-new'><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" >$newspic </a></div>";
	echo "<div class='title-cat-new'><a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" ><h4 class=\"title2\">$title</h4></a></div>";
	echo "<div class='list_box_new' align=\"justify\" style=' '>".cutText($hometext,200)."<a href=\"".url_sid("index.php?f=news&do=detail&id=$id")."\" class=\"strong\">"._READMORE."</a></div>";

	echo "</div>";
}
?>