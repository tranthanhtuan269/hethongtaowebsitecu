<?php
if (!defined('CMS_SYSTEM')) die();
include_once("header.php");
include("blocks/Menu_Left.php");
//begin check show menu
$faobucmenu = isset($_GET['f']) ? $_GET['f'] : "";
if($faobucmenu=="video"){$idbuc=36;}
echo show_left_menu($idbuc);
//begin check show menu
$perpage = 9;
$page = intval(isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page']:1));
$offset = ($page-1) * $perpage;
$countf = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT COUNT(*) FROM {$prefix}_video WHERE active=1"));
$total = ($countf[0]) ? $countf[0] : 1;
OpenTab("<a href=\"".url_sid("index.php")."\" \" title=\""._HOMEPAGE."\">"._HOMEPAGE."</a> &gt; "._MODTITLE."");
	echo "<div class=\"content\">";
	$result = $db->sql_query_simple("SELECT id,title,images,links FROM ".$prefix."_video WHERE active=1 ORDER BY id ASC LIMIT $offset, $perpage");
	$i=0;
	while(list($id, $title,$images, $links_video) = $db->sql_fetchrow_simple($result)){
		$i++;
		echo "<div class=\"video-pic\">";	
			echo "<div class=\"img\">";
			$images = resizeImages("files/video/$images", "files/video/142x91_$images" ,142,91);				
			echo "<a id=\"various$i\"  href=\"#inline$i\" title=\"$title\" ><img src=\"$images\"></a>";
				//echo "<a href=\"".url_sid("index.php?f=video&do=detail&id=$id",$title)."\"><img src=\"$images\"></a>";
				//echo "<a href=\"".url_sid("index.php?f=video&do=detail&id=$id",$title)."\"><div class=\"play\"></div></a>";					
			echo "</div>";	
			echo "<div class=\"video-title\">";
				echo "<a href=\"".url_sid("index.php?f=video&do=detail&id=$id",$title)."\">".$title."</a>";	
			echo "</div>";
		echo "</div>";
		echo "<div style=\"display: none;\">
		<div id=\"inline$i\" >
			$links_video
		</div>
	</div>";
	}	
	echo "<div class=\"cl\"></div>";
if($total > $perpage) {
	echo "<div style=\"float:right;padding-right:10px;\">";
	$pageurl = "index.php?f=".$module_name."";
	echo paging($total,$pageurl,$perpage,$page);
	echo "</div>";
}					
echo "</div>";

/*echo "<li><a id=\"various1\" href=\"#inline1\" title=\"Lorem ipsum dolor sit amet\">Inline</a></li><div style=\"display: none;\">
		<div id=\"inline1\" style=\"width:400px;height:100px;overflow:auto;\">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam quis mi eu elit tempor facilisis id et neque. Nulla sit amet sem sapien. Vestibulum imperdiet porta ante ac ornare. Nulla et lorem eu nibh adipiscing ultricies nec at lacus. Cras laoreet ultricies sem, at blandit mi eleifend aliquam. Nunc enim ipsum, vehicula non pretium varius, cursus ac tortor. Vivamus fringilla congue laoreet. Quisque ultrices sodales orci, quis rhoncus justo auctor in. Phasellus dui eros, bibendum eu feugiat ornare, faucibus eu mi. Nunc aliquet tempus sem, id aliquam diam varius ac. Maecenas nisl nunc, molestie vitae eleifend vel, iaculis sed magna. Aenean tempus lacus vitae orci posuere porttitor eget non felis. Donec lectus elit, aliquam nec eleifend sit amet, vestibulum sed nunc.
		</div>
	</div>
";*/
CloseTab();
include_once("footer.php");
?>