<?php
if (!defined('CMS_SYSTEM')) die();
global $url_site;
$catid = intval($_GET['id']);

$result = $db->sql_query_simple("SELECT catid, parent FROM {$prefix}_picture_cat WHERE 1 ORDER BY weight, catid ASC");
if ($db->sql_numrows($result) > 0) {
	$i = 0;
	$tempArr = array();
	while ($rows = $db->sql_fetchrow_simple($result)) {
		list($tempArr[$i]['id'], $tempArr[$i]['parent']) = $rows;
		$i++;
	}
}
$newArr = array();
Common::buildTree($tempArr, $newArr);
$searchArray = Common::recursiveArrayKeyExists($catid, $newArr);
if ($searchArray === false) header("Location: index.php");
$kList = '';
if (is_array($searchArray[$catid])) Common::findAllKeys($searchArray[$catid], $kList);
else $kList = strval($catid);
if (substr($kList, -1) == ':') $kList = substr($kList, 0, strlen($kList) - 1);
$kList = explode(':', $kList);

$result = $db->sql_query_simple("SELECT title, startid FROM ".$prefix."_picture_cat WHERE catid=$catid");
if($db->sql_numrows($result) != 1) header("Location: index.php");

list($catname, $startid) = $db->sql_fetchrow_simple($result);

$page_title = $catname;

include_once("header.php");





$perpage = $pic_per_page;
$perpage = 9;
if(isset($_GET['page'])){
	$page=intval($_GET['page']);
}else{
    $pageURL = 'http';
    if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    $a=trim($pageURL);
    //die($pageURL);
   	$pagen= strstr($a, 'picture');
    @$b=explode("/",$pagen);
    if(@$b[2]!=""){
	$page=substr(@$b[2],0,strlen(@$b[2])-5);
	}else{
		$page=1;
	}
	//die($page);
 //
}
//$page = isset(]) ? intval($_GET['page']) : (isset($_POST['page']) ? intval($_POST['page']):1);
//die($page);
$offset = ($page-1) * $perpage;

$query = "SELECT COUNT(*) FROM {$prefix}_picture WHERE catid=$catid";

$result = $db->sql_query_simple($query);
list($total) = $db->sql_fetchrow_simple($result);
$pageurl = "index.php?f=picture&do=categories&id=$catid";
$query = "SELECT id, title,images FROM ".$prefix."_picture WHERE id!=$startid AND (";
$query .= "catid=$catid OR ";
for ($i = 0; $i < count($kList); $i++) $query .= "catid={$kList[$i]} OR ";
$query = substr($query, 0, strlen($query) - 4);
//$query .= ") ORDER BY time DESC LIMIT $offset, $perpage";
$query .= ") ORDER BY time DESC LIMIT $offset, $perpage";
//die($query);
$resultn = $db->sql_query_simple($query);
?>

<script type="text/javascript" src="<?=$urlsite?>/js/jquery.js "></script>
<script type="text/javascript" src="<?=$urlsite?>/js/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $urlsite ?>/templates/Adoosite/css/jquery.fancybox-1.3.4.css" />
 
 	 
 	<script type="text/javascript">
		$(document).ready(function() {
 
			$("a[rel=example_group]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});
  			 
		});
	</script>	 
<div class="">
	 
	 <div class="title_home"><?php echo $catname ?></div>
	 <div class='row'>
<?php
if($db->sql_numrows($resultn) > 0) {
	$i=0;
	while(list($id, $title, $images) = $db->sql_fetchrow_simple($resultn)) 
	 
	{
		$i++;
		$path_upload_img = "$path_upload/pictures";
		if(file_exists("$path_upload_img/$images") && $images !="") 
		
		{
				
				$images1 = "$path_upload_img/$images";	
				$images2 = resizeImages("$path_upload_img/$images", "$path_upload_img/320x240_$images" ,320,240);	
		}	
?>		  				

	 <div class='col-md-4 col-lg-4 album_img'>
	 
	 	<a rel="example_group" href="<?php echo  $url_site.'/'.$images1;?>" title="<?php echo  $title;?>"><img class='img-responsive' alt="" src="<?php echo  $url_site.'/'.$images2;?>" height="240" width="320"></a>
	</div>


<?php 
			
		
	}
		if($total > $perpage) {
				echo "<div class=\"pagging\">";
					echo paging($total,$pageurl,$perpage,$page);
				echo "</div>";
			}
}
echo '</div>';
echo '</div>';
//CloseTab();
include_once("footer.php");
?>