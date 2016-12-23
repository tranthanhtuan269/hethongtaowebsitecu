<?php
if (!defined('CMS_SYSTEM')) exit;

global $Default_Temp,$imgFold;

$bl_arr = array();
$bl_arr[] = $bl_l;
$bl_arr[] = $bl_r;
$basename = pathinfo(__FILE__, PATHINFO_BASENAME);
$correctArr = array();
for ($i = 0; $i < count($bl_arr); $i++) {
	for ($h = 0; $h < count($bl_arr[$i]); $h++) {
		$temp = explode("@", $bl_arr[$i][$h]);
		if (($temp[5] == $currentlang) && ($temp[6] == $basename)) {
			$correctArr = $temp;
			break;
		}
	}
}

$result = $db->sql_query_simple("SELECT showtitle FROM ".$prefix."_blocks WHERE title='$correctArr[1]' AND active=1");
list($showtitle) = $db->sql_fetchrow_simple($result);


$content = "";
global $imgFold, $currentlang, $prefix, $urlsite, $db,$urlsite;
// thucong:))
$resultadd = $db->sql_query_simple("SELECT address FROM ".$prefix."_contact_add WHERE address!='' AND alanguage='$currentlang'");
if($db->sql_numrows($resultadd) > 0) 
{
	if($showtitle==1){
		echo  "<div class=\"div-tblock \"><i class=\"fa fa-bars fa-1\"></i> {$correctArr[1]} </div>";		 
	}
	echo "<div class='divblock' style=\"margin-bottom: 5px; width: 100%; float: left;\">";
		echo  "<div class=\"div-block\">";
	// list($address_ct) = $db->sql_fetchrow_simple($resultadd);
	// echo "<div class=\"titnormal\"  >$address_ct</div>";
		?>
<div style="display: block;" class="tao-lich-left" id="lvsicsoft-widgetlichthang">
<script src="http://lichngaytot.com/Scripts/widgetlichthang.js"></script>
<script type="text/javascript">
document.write('<div id="widgetlichthang-lvsicsoft" stylde="width:auto;" data-urlimage="../../Images/hinhlich/Party.jpg" data-startin="1" data-amduongshow="2" data-colorbackground="#fff" data-colortoday="#ff0" data-colorborder="#dddddd" data-coloram="#c0c0c0" data-colorduong="#000"><div class="css-style-widget"></div> <div id="calendar-widget-lvs" class="lvs-calendar-container" style="width: 100%; float: left;"></div>');
getWidgetLichThang('css-style-widget', 'widgetlichthang-lvsicsoft');
</script>
		<?php
		echo "</div>";
	echo "</div>";	
	echo "</div>";	
	echo "</div>";	
}

	

?>