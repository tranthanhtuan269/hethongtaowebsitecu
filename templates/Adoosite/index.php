 <?php
global $urlsite;
$imgFold = "$urlsite/templates/{$Default_Temp}/images";
function showprdhome_spe($colunm, $title_colunm)
{
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite, $imgFold;

	$result_prd_home = $db->sql_query_simple("SELECT id, prdcode, title, text, priceold, price1 FROM {$prefix}_products WHERE  active='1' AND $colunm = 1  AND alanguage='$currentlang' order by rand() LIMIT 5");
	if($db->sql_numrows($result_prd_home) > 0)
    {
        ?>
        	<div class="title_sp">
        		<h3 class="title_gt"><a title="<?= $title_colunm ?>"><?= $title_colunm ?></a></h3>
        	</div>
        	<div class="bg_box_sp">
	        	<div class="row">
	            <?php
	    		while(list($id, $prdcode, $title, $text, $priceold, $price1) = $db->sql_fetchrow_simple($result_prd_home))
	    		{
	     			$text = strip_tags($text, '<a><b><u><i><strong><em>');
	    			$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
	    			list($images) = $db->sql_fetchrow_simple($result_prd_img);
	    			$path_upload_img = "$path_upload/products";
	    			if(file_exists("$path_upload_img/$images") && $images !="")
                    {
                    	$news_img = $urlsite."/"."$path_upload_img/$images";
                        // $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/370x0_$images" ,370,0);
                        $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
                    }
                    else
                    {
                        $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/370x0_no_image.gif" ,370,0);
                        $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                    }
	    			$url_prd =url_sid("index.php?f=products&do=detail&id=$id");
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
	    			?> 
	    			<div class="item-doc col-sm-3 col-xs-12">
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
	    		}
	            ?>
	            </div>
            </div>
        <?php
	}
}
function show_menu22()
{


	global $imgFold, $currentlang, $prefix, $urlsite, $db, $k,$home;
  $k=2;
	?>
		<script type="text/javascript">
            function setActive() {
                var aObj = document.getElementById('menu2').getElementsByTagName('a');
                for(i=0;i<aObj.length;i++) {
                    if(aObj[i].href == document.location.href)
                        aObj[i].className='menu_select';
                }
            }
            window.onload = setActive;
     </script>


    <?php
    $query1 = "SELECT mid, url, title FROM ".$prefix."_mainmenus WHERE parentid=0 AND type='menungang' AND alanguage='$currentlang' AND active=1 ORDER BY weight";
     $result_cat1 = $db->sql_query_simple($query1);
		if ($db->sql_numrows($result_cat1) > 0){
		?>
		<div id='menu-custom'>
		 <nav id='menu-nav ' class="navbar navbar-inverse menu">
		 	<div class='fix'>
		 	<div class="navbar-header">
		 				<div class='danhmuc'>Danh mục</div>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>
             <div id="navbar" class="navbar-collapse collapse"  >
            <ul class="nav navbar-nav">
               <!--  <li class="active"><a href="./index.html">Home</a></li> -->
                <?php
                $i=1;
                   while(list($mid, $url, $titlecat) = $db->sql_fetchrow_simple($result_cat1)){

			       		$query_con =$db->sql_query_simple("SELECT mid, url, title FROM ".$prefix."_mainmenus WHERE parentid=$mid AND type='menungang' AND alanguage='$currentlang' AND active=1 ORDER BY weight");
			       		if($db->sql_numrows($query_con)){

			       			?>
			       			<li class="dropdown">
				                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$titlecat ?><span class="caret"></span></a>
				                <ul class="dropdown-menu" role="menu">
			       			<?php
			       			while (list($midcon, $urlcon, $titlecatcon) = $db->sql_fetchrow_simple($query_con)) {
			       				$url_link = url_sid($urlcon);
			       				?> <li><a href="<?= $url_link ?>"><?= $titlecatcon?></a></li><?php
			       			}
			       			echo  '</ul></li>';
			       		}else{
			       			 $url_cha = url_sid($url);
			       			 if($i==1){ ?><li class=""><a href="<?=$url_cha ?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span><?=$titlecat ?></a></li><?php }
			       			 else{ ?><li class=""><a href="<?=$url_cha ?>"><?=$titlecat ?></a></li><?php }

			       		}
			       		$i++;
			  		}
                   ?>
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Plugins<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Plugins 1</a></li>
                        <li><a href="#">Plugins 2</a></li>
                        <li><a href="#">Plugins 3</a></li>
                    </ul>
                </li> -->

            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.nav-fix -->
        </nav>
    </div>
		<?php
  	}
}
function show_menu()
{
	global $imgFold, $currentlang, $prefix, $urlsite,$path_upload, $db, $hotline,$home;
  	$k=2;

	?>
	<nav>
		<div class="nav-control">
            <ul class="ulmenu">
            <li class="active"><a href="<?= url_sid("index.php/") ?>"><img src="<?= $urlsite ?>/images/iconhome.png" alt="iconhome"></a></li>
            <?php
	            $query1 = "SELECT mid, url,images, title FROM ".$prefix."_mainmenus WHERE parentid=0 AND type='menungang' AND alanguage='$currentlang' AND active=1 ORDER BY weight";
			    $result_cat1 = $db->sql_query_simple($query1);
				if ($db->sql_numrows($result_cat1) > 0){
				while(list($mid, $url,$images, $titlecat) = $db->sql_fetchrow_simple($result_cat1)){
					$url_cha = url_sid($url);
					$query2 = "SELECT mid, url,images, title FROM ".$prefix."_mainmenus WHERE parentid=$mid AND type='menungang' AND alanguage='$currentlang' AND active=1 ORDER BY weight";
					$result_cat2 = $db->sql_query_simple($query2);
					if ($db->sql_numrows($result_cat2) > 0){
						?>
							<li class="dropdown">
			               	<a href="#" class="dropdown-toggle"><?= $titlecat ?> <i class="fa fa-angle-down"></i></a>
			               	<ul class="dropdown-menu default">
				                <?php
				                	$i=1;
									while(list($mid_2, $url_2,$images_2, $titlecat_2) = $db->sql_fetchrow_simple($result_cat2))
									{
							       		$url_cha_2 = url_sid($url_2);
							       		$img_2 = $urlsite.'/'.$path_upload.'/menu/'.$images_2;
							       		$query_con =$db->sql_query_simple("SELECT mid, url, title FROM ".$prefix."_mainmenus WHERE parentid=$mid_2 AND type='menungang' AND alanguage='$currentlang' AND active=1 ORDER BY weight");
							       		if($db->sql_numrows($query_con))
							       		{

							       			?>
							       			<li class="dropdown-submenu">
								               	<a href="<?= $url_cha_2 ?>" class="dropdown-toggle" data-toggle="rina-dropdown" aria-expanded="false"  ><?=$titlecat_2 ?><span class="fa fa-caret-right m-fa"></span> </a>
								                <ul class="dropdown-menu default sub-menu">
									       			<?php
									       			while (list($midcon, $urlcon, $titlecatcon) = $db->sql_fetchrow_simple($query_con)) {
									       				$url_link = url_sid($urlcon);
									       				?> <li><a href="<?= $url_link ?>"><?= $titlecatcon?></a></li>
									       				<?php
									       			}
									       			?>
									       		</ul>
									       	</li>
									       	<?php
							       		}
							       		else
							       		{
											?>
							       			 	<li>
							       			 		<a href="<?=$url_cha_2 ?>"><?=$titlecat_2 ?></a>
							       			 	</li>
							       			<?php
							       		}
						       		$i++;
						  			}
					            ?>
			            	</ul>
		            	</li>
		            	<?php
						}
						else
						{
							?><li><a href="<?=$url_cha ?>"><?=$titlecat ?></a></li><?php
						}
					}
  				}
  				?>
            </ul>
        </div>
    </nav>
<?php
}
function show_menu_footer_didong()
{
	 global $db, $currentlang, $prefix,$urlsite ;
	$count = $db->sql_query_simple("SELECT count(*) FROM {$prefix}_footermenus WHERE  parentid='0' AND active='1' and alanguage='$currentlang'");
	list($total) = $db->sql_fetchrow_simple($count);
	$result_fmenu = $db->sql_query_simple("SELECT mid,title, url   FROM ".$prefix."_mainmenus WHERE parentid=0 AND type='menungang' AND alanguage='$currentlang' AND active=1 ORDER BY weight");
	if($db->sql_numrows($result_fmenu) > 0)
	{	echo '<div class="dmmenu"><ul><li class="chonmenu"><a href='.$urlsite.'>Trang chủ</a></li><li class="chonmenu1">Menu <span class="glyphicon glyphicon-list" aria-hidden="true"></span></li></ul></div>';
		$i=1;
		echo "<div class=\"footer-menu\">";
		echo "<ul>";
		while (list($mid, $title_menu, $url_menu) = $db->sql_fetchrow_simple($result_fmenu))
		{
			$result_check = $db->sql_query_simple("SELECT mid,  title ,url FROM ".$prefix."_mainmenus WHERE parentid=$mid AND type='menungang' AND alanguage='$currentlang' AND active=1 ORDER BY weight");
		if ($db->sql_numrows($result_check) > 0){
			echo "<li class='menu-li-sub' ><a>$title_menu <span class=\"caret\"></span></a>";
			echo "<ul class='menu-ul-sub'>";
			while (list($mids,$title_menus,$url_menus) = $db->sql_fetchrow_simple($result_check)) {
				echo "<li><a href=\"".url_sid($url_menu)."\"  >- $title_menus</a></li>";
			}
			echo "</ul>";
			echo "</li>";
		}
		else{
			echo "<li><a href=\"".url_sid($url_menu)."\">$title_menu</a></li>";
		}
		if($i<$total){
			// echo "<li class=\"border-fmenu\"></li>";
		}
		$i++;
		}
		echo "</ul>";
		echo "</div>";

	}
}

function show_footermenu()
{
	global $db, $currentlang, $prefix;
	// $result_fmenu = $db->sql_query_simple("SELECT mid, title, url FROM {$prefix}_footermenus WHERE  parentid='0' AND active='1' and alanguage='$currentlang' Order by weight asc LIMIT 1");
	// if($db->sql_numrows($result_fmenu) > 0)
	// {
	// 	$ii=1;
	// 	while (list($mid, $title_menu, $url_menu) = $db->sql_fetchrow_simple($result_fmenu))
	// 	{
			$result_fmenu2 = $db->sql_query_simple("SELECT mid, title, url FROM {$prefix}_footermenus WHERE  parentid='0' AND active='1' and alanguage='$currentlang' Order by weight asc");
			if($db->sql_numrows($result_fmenu2) > 0)
			{
				$i=1;
				// echo "<div class=\"col-sm-12 col-md-3 col-lg-3 col-footer\">";
				// 	echo "<div class=\"content_fotter\">";
				// 		echo '<h4>'.$title_menu.'</h4>';
				// 		echo '<div class="footer-menu1">';
				    		echo "<ul>";
				        		while (list($mid, $title_menu2, $url_menu2) = $db->sql_fetchrow_simple($result_fmenu2))
				        		{
				        			echo "<li><a href=\"".url_sid($url_menu2)."\">$title_menu2</a></li>";
				        			$i++;
				        		}
				    		echo "</ul>";
				   //  	echo "</div>";
		    	// 	echo "</div>";
		    	// echo "</div>";
			}
			$i++;
	// 	}
	// }
}




function show_banner_logo()
{
	global $imgFold, $currentlang, $prefix,$path_upload,$db,$urlsite,$sitename;


	$bnid = 28;
	$result_flash = $db->sql_query_simple("SELECT a.images,b.bwidth,b.bheight,a.links FROM ".$prefix."_advertise AS a INNER JOIN ".$prefix."_advertise_banners AS b ON a.bnid=b.bnid  WHERE a.bnid='$bnid' AND a.active=1 AND a.alanguage='$currentlang' ORDER BY a.id DESC LIMIT 1");
	if($db->sql_numrows($result_flash) > 0 ) {
		list($images,$width,$height,$links) = $db->sql_fetchrow_simple($result_flash);
		//== kiem tra xem la anhr hay la flash
		// echo "<div class=\"logo\">";
		$check = Common::getExt($images);
	 	if($check=="swf"){
			echo "<a href=\"$links\" title=\"$sitename\" class=\"logo\">";
				show_flash("FlashID_banner","$urlsite/".$path_upload."/adv/".$images."","".$width."px","".$height."px");
			echo "</a>";
		}
		else{
			echo "<a href=\"$links\" title=\"$sitename\" class=\"logo\">";
				echo "<img src=\"$urlsite/".$path_upload."/adv/".$images."\" width=\" \" height=\" \" alt=\"$sitename\" title=\"$sitename\" />";
			echo "</a>";
		}
		// echo "</div>";
	}

}

function banner_logo($bnid)
{
	global $imgFold, $currentlang, $prefix,$path_upload,$db,$urlsite,$sitename;



	$result_flash = $db->sql_query_simple("SELECT a.images,b.bwidth,b.bheight,a.links FROM ".$prefix."_advertise AS a INNER JOIN ".$prefix."_advertise_banners AS b ON a.bnid=b.bnid  WHERE a.bnid='$bnid' AND a.active=1 AND a.alanguage='$currentlang' ORDER BY a.id DESC LIMIT 1");
	list($images,$width,$height,$links) = $db->sql_fetchrow_simple($result_flash);
	//== kiem tra xem la anhr hay la flash
	echo "<div style=\"  \">";
	$check = Common::getExt($images);
 	if($check=="swf"){
		echo "<a href=\"$links\" title=\"$sitename\">";
			show_flash("FlashID_banner","$urlsite/".$path_upload."/adv/".$images."","".$width."px","".$height."px");
		echo "</a>";
	}
	else{
		echo "<a href=\"$links\" title=\"$sitename\">";
			echo "<img class='img-responsive' src=\"$urlsite/".$path_upload."/adv/".$images."\" width=\" \" height=\" \" alt=\"$sitename\" title=\"$sitename\" />";
		echo "</a>";
	}
	echo "</div>";


}


function subcat($catid, $text="", $catcheck="", $catseld="") {
	global $db, $prefix;
	$treeTemp ="";
	$result = $db->sql_query_simple("SELECT catid, title FROM ".$prefix."_products_cat WHERE parentid='$catid' AND catid!='$catseld'");
	if($db->sql_numrows($result) > 0 ) {
		$text = "$text--";
		while(list($cat_id, $title2) = $db->sql_fetchrow_simple($result)) {
			$treeTemp .= "<option value=\"$cat_id\">$text-- $title2</option>";
			$treeTemp .= subcat($cat_id,$text, $catcheck, $catseld);
		}
	}
	return $treeTemp;
}

function show_search()
{
	global $imgFold, $currentlang, $prefix,$path_upload,$hotline;
    echo "<script language=\"javascript\" type=\"text/javascript\">";
	echo "function selectText_b()
	{document.getElementById(\"tag\").value	= \"\";}

	function addText_b()
	{if(document.getElementById(\"tag\").value	== \"\")
		{document.getElementById(\"tag\").value= \""._INPUT_SEARCH."\";	}
		else {}
	}";
	echo "</script>";

	echo "<div id=\"search\">";
		
	echo "<form class=\"navbar-form\" name=\"formSearch\" action=\"".url_sid("index.php?f=products&do=tags")."\" method=\"post\">";
        // echo "<div class=\"search_box\">";
		 echo "<div class=\"input-group stylish-input-group\"><input type=\"text\" name=\"tag\" class=\"key-search\" id=\"tag\" value=\"Nhập từ khóa tìm kiếm\" onclick = \"selectText_b()\" onblur=\"addText_b()\" /><span class=\"input-group-addon\">

			 <button type=\"submit\"/><span class=\"fa fa-search\" aria-hidden=\"true\"></span></button></span></div>

	 </form>";
	echo "</div>";
}




 function show_contact()
 {
 	global $path_upload, $mod_name,  $default_temp,$prefix,$db,$currentlang;

	$result_lastnew = $db->sql_query_simple("SELECT catid, title  FROM ".$prefix."_news_cat WHERE parent= 86 and active='1' AND alanguage='$currentlang' ORDER BY catid asc LIMIT 1");
	$numrows = $db->sql_numrows($result_lastnew);
	if($numrows > 0) {
		//
		echo "<div class='row'>";
		while(list($idlast, $titlelast) = $db->sql_fetchrow_simple($result_lastnew)) {
		echo '<div class=\'col-xs-12 col-md-6 col-lg-6\'>';
		echo "<div class='title_cat_bottom'><h3>$titlelast</h3></div>";
		echo "<ul>";
		$result_lastnew1 = $db->sql_query_simple("SELECT id, title,time,images FROM ".$prefix."_news WHERE catid= $idlast and active='1' AND alanguage='$currentlang' ORDER BY id asc LIMIT 5");
		if($db->sql_numrows($result_lastnew1) > 0) {
		while(list($id , $title ,$time,$images) = $db->sql_fetchrow_simple($result_lastnew1)) {
			$url=url_sid("index.php?f=news&do=detail&id=$id");
			echo "<li><i class=\"fa fa-angle-double-right fa-1\"></i> <a href=".$url.">$title</a></li>";
		}
		echo "</ul>";
		}
		echo "</div>";
		}
		echo '<div class=\'col-xs-12 col-md-6 col-lg-6\'>';
			echo "<div class='title_cat_bottom'><h3>Social network</h3></div>";
				echo "<ul>";
					echo "<li> <a title='liên kết Facebook' href=''><i class=\"fa fa-facebook-official fa-2\"></i> Facebook</a></li>";
					echo "<li> <a title='liên kết Youtube' href=''><i class=\"fa fa-youtube fa-2\"></i> Youtube</a></li>";
					echo "<li> <a title='liên kết Skyper' href=''><i class=\"fa fa-skype fa-2\"></i> Skyper</a></li>";
				echo "</ul>";
			echo "</div>";
		//
		echo "</div>";
	}
 }





function show_lang()
{
	global $urlsite;
	?>
	<div class='show_lang'>
		<ul>
			<li class='vietnam'>
				<a href="index.php?lang=vietnamese" title="Vienamese">
					<img src="<?= $urlsite ?>/images/vietnam.gif" alt="Vienamese">
				</a>
			</li>
			<li class='tienganh'>
				<a href="index.php?lang=english" title="English">
					<img src="<?= $urlsite ?>/images/english.png" alt="English">
				</a>
			</li>
		</ul>
	</div>
	<?php
}
function show_qcright(){
	echo "<div class='right'>";
		echo advertising(41);
	echo "</div>";
}
function show_slidehome1($bnid){
	?>
	<div id="carousel-example-generic" class="carousel slide slide-home-n" data-ride="carousel">

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
  <?php
  global $imgFold, $currentlang, $prefix, $path_upload, $db, $urlsite;
  //$bnid = 37;
  	$result_flash = $db->sql_query_simple("SELECT a.images,b.bwidth,b.bheight FROM ".$prefix."_advertise AS a INNER JOIN ".$prefix."_advertise_banners AS b ON a.bnid=b.bnid  WHERE a.bnid='$bnid' AND a.active=1 AND a.alanguage='$currentlang' ORDER BY a.id ASC LIMIT 5");
  	$i=1;
  	while(list($images,$width,$height) = $db->sql_fetchrow_simple($result_flash)){

	if( $i== 1){
		echo '<div class="item active">';
	}else{
		echo '<div class="item">';
	}
	   	?>

	     <?php echo "<a href=''><img src=\"".$urlsite."/".$path_upload."/adv/".$images."\" width=\"100%\" height=\"".$height."px\" /></a>"; ?>
	    </div>
	    <?php $i++; } ?>

  </div>

</div>
	<?php
}
function show_slidehome1n($bnid){
     global $imgFold, $currentlang, $prefix, $path_upload, $db, $urlsite;
?>
    <script type="text/javascript" src="<?php echo $urlsite ?>/js/js/jssor.slider.min.js"></script>
    <script>
        jssor_1_slider_init = function() {

            var jssor_1_SlideshowTransitions = [
              {$Duration:1200,x:0.2,y:-0.1,$Delay:20,$Cols:8,$Rows:4,$Clip:15,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:260,$Easing:{$Left:$Jease$.$InWave,$Top:$Jease$.$InWave,$Clip:$Jease$.$OutQuad},$Outside:true,$Round:{$Left:1.3,$Top:2.5}},
              {$Duration:1500,x:0.3,y:-0.3,$Delay:20,$Cols:8,$Rows:4,$Clip:15,$During:{$Left:[0.1,0.9],$Top:[0.1,0.9]},$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:260,$Easing:{$Left:$Jease$.$InJump,$Top:$Jease$.$InJump,$Clip:$Jease$.$OutQuad},$Outside:true,$Round:{$Left:0.8,$Top:2.5}},
              {$Duration:1500,x:0.2,y:-0.1,$Delay:20,$Cols:8,$Rows:4,$Clip:15,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:260,$Easing:{$Left:$Jease$.$InWave,$Top:$Jease$.$InWave,$Clip:$Jease$.$OutQuad},$Outside:true,$Round:{$Left:0.8,$Top:2.5}},
              {$Duration:1500,x:0.3,y:-0.3,$Delay:80,$Cols:8,$Rows:4,$Clip:15,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$Easing:{$Left:$Jease$.$InJump,$Top:$Jease$.$InJump,$Clip:$Jease$.$OutQuad},$Outside:true,$Round:{$Left:0.8,$Top:2.5}},
              {$Duration:1800,x:1,y:0.2,$Delay:30,$Cols:10,$Rows:5,$Clip:15,$During:{$Left:[0.3,0.7],$Top:[0.3,0.7]},$SlideOut:true,$Reverse:true,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:2050,$Easing:{$Left:$Jease$.$InOutSine,$Top:$Jease$.$OutWave,$Clip:$Jease$.$InOutQuad},$Outside:true,$Round:{$Top:1.3}},
              {$Duration:1000,$Delay:30,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:2049,$Easing:$Jease$.$OutQuad},
              {$Duration:1000,$Delay:80,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Easing:$Jease$.$OutQuad},
              {$Duration:1000,y:-1,$Cols:12,$Formation:$JssorSlideshowFormations$.$FormationStraight,$ChessMode:{$Column:12}},
              {$Duration:1000,x:-0.2,$Delay:40,$Cols:12,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Assembly:260,$Easing:{$Left:$Jease$.$InOutExpo,$Opacity:$Jease$.$InOutQuad},$Opacity:2,$Outside:true,$Round:{$Top:0.5}},
              {$Duration:2000,y:-1,$Delay:60,$Cols:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Easing:$Jease$.$OutJump,$Round:{$Top:1.5}}
            ];

            var jssor_1_options = {
              $AutoPlay: true,
              $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizing
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 1366);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", $Jssor$.$WindowResizeFilter(window, ScaleSlider));
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
        };
    </script>
    <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1366px; height: 406px; overflow: hidden; visibility: hidden;">
        <!-- Loading Screen -->
        <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
            <div style="position:absolute;display:block;background:url('loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
        </div>
    <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1366px; height: 406px; overflow: hidden;">


 <?php
        $result_flash = $db->sql_query_simple("SELECT a.images,b.bwidth,b.bheight, a.title, a.links, imgtext FROM ".$prefix."_advertise AS a INNER JOIN ".$prefix."_advertise_banners AS b ON a.bnid=b.bnid  WHERE a.bnid='$bnid' AND a.active=1 AND a.alanguage='$currentlang' ORDER BY a.id ASC ");
        $i=1;
        while(list($images, $width, $height, $title, $links, $imgtext) = $db->sql_fetchrow_simple($result_flash))
        {

        ?>
            <div data-p="" style="display: none;">
                <?php echo "<a href='".$links."'><img   data-u=\"image\" src=\"".$urlsite."/".$path_upload."/adv/".$images."\" width=\"100%\" height=\"".$height."px\" /></a>"; ?>
            </div>
        <?php
            $i++;
        }



  ?>



        </div>
        <!-- Bullet Navigator 
        <div data-u="navigator" class="jssorb01" style="bottom:16px;left:50%;">
            <div data-u="prototype" style="width:15px;height:15px;"></div>
        </div>
        Arrow Navigator -->
        <span data-u="arrowleft" class="jssora05l" style="top:0px;left:0px;width:38px;height:37px;" data-autocenter="2"></span>
        <span data-u="arrowright" class="jssora05r" style="top:0px;right:0px;width:38px;height:37px;" data-autocenter="2"></span>
    </div>
    <script>
        jssor_1_slider_init();
    </script>


    <?php
}
function show_product(){
	 global $imgFold, $currentlang, $prefix, $path_upload, $db, $urlsite;

	 $result_prd_home = $db->sql_query_simple("SELECT id,prdcode, title, donvitinh, price1, priceold, text, pnews, ptops, psale FROM {$prefix}_products WHERE  active='1' AND ptops=1  AND alanguage='$currentlang' LIMIT 3");

		 if($db->sql_numrows($result_prd_home) > 0){
			echo "<div class=\"container\">";

			while(list($id,$prdcode, $title, $donvitinh, $price1, $priceold, $text , $pnews, $ptops, $psale ) = $db->sql_fetchrow_simple($result_prd_home))
			{

				$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
				list($images) = $db->sql_fetchrow_simple($result_prd_img);
				$path_upload_img = "$path_upload/products";

			   if(file_exists("$path_upload_img/$images") && $images !="")

				{
					$new_goc=$urlsite."/".$path_upload_img."/".$images;
					$news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/250x180_$images" ,250,180);
					$news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"  />";
					$new_goc = "<img src=\"$new_goc\" alt=\"$title\" title=\"$title\"   />";
				} else

				  {
				  $news_img ="";
				  $news_img = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_img/250x180_no_image.gif" ,250,180);
				  $new_goc = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
				  $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
				  }

				$url_prd =url_sid("index.php?f=products&do=detail&id=$id");
				?>
				<!-- line1 -->
				<div id="prd_home" class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<div class="prd_home">
					<div class="img_prd_home"><a href=" <?= $url_prd?>"><?= $new_goc ?></a>
						<div class="title_prd_home"><h3><a href=" <?= $url_prd?>" title="<?= $title?> "><?= CutString($title,35)?></a></h3></div>
					</div>

				</div>
				</div>
				<!-- het line 1 -->
				<?php
			}
			echo "</div>";
		}
}
function cart(){
	global $db,$prefix,$currentlang,$urlsite;
	$url_cat = url_sid('index.php?f=products&do=giohang');
	?>
			<div class='show-cart' >
			<div class='cart'>
				<div class=''>
				<img src="../ibv/templates/Adoosite/images/cart.jpg" alt="">
				<a name='#cart' href="<?=$url_cat?>">

				Giỏ hàng: <small> (
					<?php
					if(isset($_SESSION['giohang']) && $_SESSION['giohang'] != ""){
						$count = count($_SESSION['giohang']);
						$soluong =@$_SESSION['giohang'];
						echo $count;
					}else{
						echo $count = '0 ';
					}
					?>) </small>
				</a>
				</div>
			</div>
			<div class='show_cart'>
				<?php
				if(isset($_SESSION['giohang']) && $_SESSION['giohang'] != ""){
						$count = count($_SESSION['giohang']);
						$soluong =@$_SESSION['giohang'];
						//echo $count;
				if($count == 0){
				 	echo "<p>Không có sản phẩm nào.</p>";
				 }else{
				 	// gio hang nhe
				 	foreach ($soluong as $key => $value) {
					 	@$mangID[] = $key;
					}
						sort($mangID);
						$manngIDN = implode(',', $mangID);
						global $path_upload;
						$result_prd = $db->sql_query_simple("SELECT id, prdcode, title,price1,style FROM {$prefix}_products WHERE id in($manngIDN) ");
						while (list($id,$prdcode,$title,$price1,$style)=$db->sql_fetchrow_simple($result_prd)) {
						 	$url_detail =url_sid("index.php?f=products&do=detail&id=$id");
						 	$result_prd_img = $db->sql_query_simple("SELECT title FROM ".$prefix."_prd_images WHERE  prdid=$id ");
							 list($images) = $db->sql_fetchrow_simple($result_prd_img);
							 $path_upload_img = "$path_upload/products";
							 if(file_exists("$path_upload_img/$images") && $images !=""){
									$new_goc=$urlsite."/".$path_upload_img."/".$images;
								} else {

								}
								?>
								<div class='prd_cart'>
							 		<div class='img_prd_cart'><img class='img-responsive' width='40px' src="<?= $new_goc?>"></div>
							 		<div class='title_prd_cart'><a href="<?= $url_detail?>"><?= $title?> </a></div>
							 		<span>X<?= $_SESSION['giohang'][$id]?> $ <?= dsprice($_SESSION['giohang'][$id]*$price1) ?></span>
							 		<a href='<?php echo url_sid("index.php?f=products&do=cart_delete&id=$id") ?>'><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"> Xóa</span></a>
							 	</div>
								<?php
							}

				 	// het gio hang nhe

				 	?>
				 	<div class='view_cart'>
				 		<a class="button" href="<?php echo  url_sid("index.php?f=products&do=giohang") ?>"><span>Xem Giỏ hàng</span></a>
				 		<!-- <a class="button" href="index.php?f=products&do=giohang#giohang"><span>Thanh toán</span></a> -->
				 	</div>
				 	<?php
				 }
				}else{
					echo "<p>Không có sản phẩm nào.</p>";
				}
				?>
			</div>
			</div>
			<?php
}

function show_danhmuc(){
	global $db,$prefix,$currentlang,$urlsite;
	$query1 = "SELECT mid, url, title FROM ".$prefix."_leftmenus WHERE parentid=0 AND alanguage='$currentlang' ORDER BY weight asc";
    $result_cat1 = $db->sql_query_simple($query1);
    if ($db->sql_numrows($result_cat1) > 0)
      {
      	while(list($mid, $url, $titlecat) = $db->sql_fetchrow_simple($result_cat1))
      	{
      		$query2 = "SELECT mid, url, title FROM ".$prefix."_leftmenus WHERE parentid=$mid AND alanguage='$currentlang' ORDER BY weight asc";
      		?>
      		<div class="danhmuchome">
				<h3><?= $titlecat ?></h3>
			<?php
			 	$result_cat2 = $db->sql_query_simple($query2);
			      if ($db->sql_numrows($result_cat2) > 0)
				  {
				  	echo '<ul>';
				  	while (list($catsub,$url,$titlesub) = $db->sql_fetchrow_simple($result_cat2)) {
				  		$url1=url_sid($url);
				  		echo "<li ><a class='licha' href=".$url1.">$titlesub</a>";
				  		$query3 = $db->sql_query_simple("SELECT mid, url, title FROM ".$prefix."_leftmenus WHERE parentid=$catsub AND alanguage='$currentlang' ORDER BY weight");
				  		 if ($db->sql_numrows($query3) > 0){
				  		 	echo "<ul class='subli'>";
				  		 		while (list($idsub,$url12,$titlesub1) = $db->sql_fetchrow_simple($query3)) {
				  		 			$url2=url_sid($url12);
				  		 			echo "<li>  <a class='licon' href=".$url2."> ".cutText($titlesub1,34)."</a></li>";
				  		 		}
				  		 	echo "</ul>";
				  		 }
				  		echo "</li>";
				  	}
				  	echo '</ul>';
				  }
			?>
			</div>
      		<?php
      	}
    }
}

function themeheader()
{
	global $home, $module_name,$yim_support, $imgFold, $Default_Temp, $do, $db, $prefix, $currentlang, $module_name,$userInfo,$hotline,$urlsite, $fb, $tw, $slogan, $gg, $adminmail, $slogan1;
	if(!isset($_GET['f'])){$f = "";} else {$f = $_GET['f'];}
	if(!isset($_GET['do'])){$do = "";} else {$do = $_GET['do'];}
	$qstring= $_SERVER['QUERY_STRING'];
echo "<body class=\"header-fixed\">";
	echo '<div class="bodypage">';
	?>
	<header>
		<div class="box_banner">
			<div class="container">
				<div class="logo_box">
	                <?php show_banner_logo(); ?>
	            </div>
	            <div class="menutop">
	            	<?php show_footermenu(); ?>
	            </div>
	            <menu>
	                <span class="bar hide"></span>
	                <?php show_menu(); ?>
	                <script type="text/javascript" src="<?php echo $urlsite ?>/js/core.js"></script>
			    </menu>
            </div>
        </div>
		
	</header>	
	<main>
		
		<?php if ($home == 1) {
			?>
				<section>
				    <div class="boxhome_ slidehome ">
				        <?php show_slidehome1n(60); ?>
				    </div>
				</section>
			<?php
		} 
	
}
function themefooter()
{
	global $imgFold ,$module_name, $Default_Temp,$home, $db, $path_upload, $prefix, $fb, $urlsite, $yim_support, $hotline, $yim_support, $adminmail, $onls_g, $statcls, $stathits1, $gg, $tw, $timeof_up, $currentlang, $slogan1;
				if ($onls_g!="") 
				{ 
					$onls_g1 = explode("|",$onls_g); 
					$num_h2 = sizeof($onls_g1); 
				} 
				else 
				{ 
					$num_h2 = 0; 
				}
				$num_h2 = str_pad( $num_h2, 4, "0", STR_PAD_LEFT );

				if (isset($_POST['submitdk'])) 
				{

				    $email_dk = mysql_real_escape_string($_POST['email_dk']);
				    $fullname_dk = mysql_real_escape_string($_POST['fullname_dk']);
				    $query = ("INSERT INTO ".$prefix."_newsletter (id, fullname, email, status, html, time) VALUES ('', '$fullname', '$email_dk', '2', '0', '".time()."')");
				    if($db->sql_query_simple($query))
				    {
				        ?>
					        <script type="text/javascript">
					            window.alert('<?= _DKMAIL ?>');
					            window.history.back();
					        </script>
				        <?php
				    }
				}
		?>
		</main>
		<footer>
			<div class="container">
	 			<div class="row">
					<div class="col-sm-12 col-md-8 col-lg-8 col-footer">
	            		<div class="content_fotter ">
	 						<?php footmsg(); ?>
	            		</div>
	            	</div>

	                <div class="col-sm-12 col-md-4 col-lg-4 col-footer">
	                	
	                </div>
				</div>
			</div>
			<div class="nav_fot">
				<div class="container">
					<span>Copyright 2016 by OneCMS. Disign by <a href="http://webvaseo.com.vn/" title = "Thiết kế Web Tâm Phát"> TamPhat</a>.</span>
				</div>
			</div>
		</footer>
	</div>
</div>
<?php
}
function show_hoidap($catid){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
    $result_newskh = $db->sql_query_simple("SELECT catid, title, guid FROM {$prefix}_news_cat WHERE active=1 AND alanguage = '$currentlang' AND catid = $catid  ORDER BY weight asc LIMIT 1");
    if($db->sql_numrows($result_newskh) > 0)
    {
        while(list($catid, $title_cat, $guid) = $db->sql_fetchrow_simple($result_newskh))
        {
           
            $result_newskh = $db->sql_query_simple("SELECT id, title, hometext, time, images, hits FROM {$prefix}_news WHERE active=1 AND catid = $catid  ORDER BY time DESC LIMIT 8");
            if($db->sql_numrows($result_newskh) > 0)
            {
                ?><h4 class="title_news"><a href="<?= url_sid($guid) ?>" title="<?= $title_cat ?>"><?= $title_cat ?></a></h4><?php
                ?><div class="box_hoidap"><?php
                $i=1;
                while(list($id, $title, $hometext, $timea, $images, $hits) = $db->sql_fetchrow_simple($result_newskh))
                {
                    $url_news_kh =url_sid("index.php?f=news&do=detail&id=$id");
                    $get_path = get_path($timea);
                    $path_upload_img = "$path_upload/news/$get_path";
                    if(file_exists("$path_upload_img/$images") && $images !="")
                    {
                        $news_img = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/270x0_$images" ,270,0);
                        // $news_img = $urlsite."/".$path_upload_img."/".$images;
                        $news_img = "<img src=\"$news_img\" alt=\"$title\" title=\"$title\"/>";
                    } else {
                        // $news_img = $urlsite."/"."images/no_image.gif";
                        $news_img = $urlsite."/".resizeImages("images/no_image.gif", "images/270x0_no_image.gif" ,270,0);
                        $news_img = "<img title=\"$title\" alt=\"$title\" src=\"$news_img\"/>";
                    }
                        ?>
                                <div class="item_hoidap post_doanhnghiep">
                                    <h3><a href="<?= $url_news_kh ?>" title="<?= $title ?>"><i class="fa fa-angle-right" aria-hidden="true"></i><?= $title ?></a></h3>
                                </div>
                        <?php
                    $i++;
                }
                ?></div><?php
            }
        }
    }
}
function show_logo(){
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
	//_PARTNERS
	?>
	<div class="box_title_tintuc">
        <h3 class="title_doitac">Khách hàng - Đối tác tiêu biểu</h3>
    </div>
    <div class="row">
		<div class="owl-carousel3">
			<?php
				$bnid = 55;
				$result_flash = $db->sql_query_simple("SELECT a.images,b.bwidth,b.bheight,a.links, a.title FROM ".$prefix."_advertise AS a INNER JOIN ".$prefix."_advertise_banners AS b ON a.bnid=b.bnid  WHERE a.bnid='$bnid' AND a.active=1 AND a.alanguage='$currentlang' ORDER BY a.id DESC LIMIT 15");
				while(list($images,$width,$height,$links, $title) = $db->sql_fetchrow_simple($result_flash)){
					echo "<div class='img_chay'>";
						echo "<a href=\"$links\" title=\"".$title."\">";
							echo "<img class='img-responsive' src=\"$urlsite/".$path_upload."/adv/".$images."\" alt=\"".$title."\" />";
						echo "</a>";
					echo "</div>";
				}
			?>
		</div>
	</div>
	<script type="text/javascript">
        (function($) {
            "use strict";
            $(".owl-carousel3").each(function(){
                var my = $(this);
                my.owlCarousel({
                items : 5, itemsDesktop : [1000,4],
                itemsTablet: [600,2],
                itemsMobile : false,
                autoPlay: true
            });
            my.parent().find('a[href^="#"]').click(function(ev){
                ev.preventDefault();
                my.trigger('owl.'+$(this).attr('class')); });
            });
        })(jQuery);
    </script>
	<?php
}
function showfot(){
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite, $fb, $gg, $tw,$timeof_up,$slogan ;
	?>
	<div class=" col-sm-6 col-md-4 col-lg-4 gthome1">
		<?php
			echo advertising(53);
			echo "<div class=\"bg_foter\">";
			   	echo footmsg();
			echo "</div>";
		?>
	</div>
	<div class="col-sm-6 col-md-4 col-lg-4 gthome1">
		<?php
			echo "<div class=\"bg_foter\">";
				show_gentext();
			echo "</div>";
		?>
		<div class="ketnoi"><h4 class="fdm">KẾT NỐI VỚI CHÚNG TÔI</h4></div>
		<div class="imgsocial">
			<a href="<?= $fb ?>">
				<img src="<?= $imgFold ?>/icon-facebook.png" title="facebook">
			</a>
			<a href="<?= $gg ?>">
				<img src="<?= $imgFold ?>/icon-gplus.png" title="google plus">
			</a>

			<a href="<?= $timeof_up ?>">
				<img src="<?= $imgFold ?>/icon-pintest.png" title="pinterest">
			</a>

			<a href="<?= $tw ?>">
				<img src="<?= $imgFold ?>/icon-tw.png" title="twitter">
			</a>

			<a href="<?= $slogan ?>">
				<img src="<?= $imgFold ?>/icon-youtube.png" title="youtube">
			</a>

			<br />
		</div>
	</div>

	<div class="col-sm-6 col-md-4 col-lg-4 gthome1">
		<?php
		$query1 = "SELECT mid, url, title FROM ".$prefix."_footermenus WHERE parentid=0 AND active='1' and alanguage='$currentlang' ORDER BY weight asc";
   		$result_cat1 = $db->sql_query_simple($query1);
   		if ($db->sql_numrows($result_cat1) > 0)
        {
        	$i=1;
        	echo "<div class=\"dvdl\">Danh mục</div>";
			echo "<div class=\"footer-menu-3\">";
				echo "<ul>";
	          	while (list($mid, $url, $title_menu) = $db->sql_fetchrow_simple($result_cat1)){
						echo "<li><a href=\"".url_sid($url)."\"><i class=\"fa fa-angle-double-right\" aria-hidden=\"true\"></i>$title_menu</a></li>";
					$i++;
				}
				echo "</ul>";
			echo "</div>";
		}
		?>
	</div>
	<?php
}

function home_new(){
	global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
	echo "<div class=\"box_cat_home \">";// new nhe -------------------------------------------
	 	echo "<div class=\"box_cat_title box_cat_titlenew \"><h3> <span class=\"glyphicon glyphicon-credit-card\"></span>Tin tức mới</h3></div>";// titlehomehot -----------
	 	echo "<div class=\"box__home homenew \">";// box home
			echo '<div class= "row">';
			$result_newsindex = $db->sql_query_simple("SELECT id, title, hometext, images, time FROM {$prefix}_news WHERE active=1  AND catid=81 ORDER BY time DESC LIMIT 3");
			if($db->sql_numrows($result_newsindex) > 0)
			{
				while(list($idnewind, $titlenewind, $hometextind, $imagesind, $timenewind) = $db->sql_fetchrow_simple($result_newsindex))
				{
					$url_news_detail =url_sid("index.php?f=news&do=detail&id=$idnewind");
					$get_path_newindex = get_path($timenewind);
					$path_upload_imgnewind = "$path_upload/news/$get_path_newindex";
					if($imagesind !="" && file_exists("$path_upload_imgnewind/$imagesind"))
					{
						$imagesind = $urlsite."/".resizeImages("$path_upload_imgnewind/$imagesind", "$path_upload_imgnewind/316x184_$imagesind" ,316,184);
						$imagesind= "<img class='img-responsive img-thumbnail' title=\"$titlenewind\" alt=\"$titlenewind\"  src=\"$imagesind\" />";

					}
					else
					{
						$imagesind = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_imgnewind/316x184_no_image.gif" ,316,184);
						$imagesind= "<img class='img-responsive' title=\"$titlenewind\" alt=\"$titlenewind\" src=\"$imagesind\"/>";
					}
					?>
				<div class='col-sm-4 col-md-4 col-lg-4'>
					<div class='box-home-new '>
						<div class='img-home-new'><a href="<?= $url_news_detail?>"><?= $imagesind?></a></div>
						<div class='title-home-new'><a title='<?= $titlenewind?>' href="<?= $url_news_detail?>"><h3><?= $titlenewind?></h3></a></div>
						<div class='desc-home-new'><p><?= cutText(strip_tags($hometextind),250)?></p></div>
					</div>
				</div>
				<?php
				}
			}

			echo "</div>";
		echo "</div>";
	echo "</div>";// het new nhe -------------------------------------------------------------
}


function backup(){
	?>
	<div id='bttop'></div>
	<script type='text/javascript'>$(function(){$(window).scroll(function(){if($(this).scrollTop()!=0){$('#bttop').fadeIn();}else{$('#bttop').fadeOut();}});$('#bttop').click(function(){$('body,html').animate({scrollTop:0},800);});});</script>
	<?php
}

 function show_qc_1($bnid)
{
	global $imgFold, $currentlang, $prefix, $path_upload, $db, $urlsite;
	$result_flash = $db->sql_query_simple("SELECT a.images,b.bwidth,b.bheight, a.links FROM ".$prefix."_advertise AS a INNER JOIN ".$prefix."_advertise_banners AS b ON a.bnid=b.bnid  WHERE a.bnid='$bnid' AND a.active=1 AND a.alanguage='$currentlang' ORDER BY a.id DESC LIMIT 1");
	list($images,$width,$height, $links) = $db->sql_fetchrow_simple($result_flash);
	//== kiem tra xem la anhr hay la flash
	$check = Common::getExt($images);
 	if($check=="swf"){show_flash("FlashID_banner","$urlsite/".$path_upload."/adv/".$images."","".$width."px","".$height."px");}
	else{echo "<a href='$links'><img src=\"$urlsite/".$path_upload."/adv/".$images."\" width=\"".$width."px\" height=\"".$height."px\" /></a>";}

}
function show_gentext(){
	global $imgFold, $currentlang, $prefix,$path_upload,$db;
	$result_gentext= $db->sql_query_simple("SELECT content FROM ".$prefix."_gentext WHERE textname='scroll' AND alanguage='$currentlang' ");
	if ($db->sql_numrows($result_gentext) > 0) {
		list($content) = $db->sql_fetchrow_simple($result_gentext);
		// echo  "<marquee behavior=\"alternate\" loop=\"infinite\" bgcolor=\"white\" style=\"      height: 24px;     margin-top: 0;  width: 100%;\" >";
			echo " ".$content." ";
		// echo  "</marquee>";
	}
}


function OpenTab($title, $ret = false) {
	global $imgFold;
$c = "<div class='box_detail_new'>
		<div class='title_brk'>$title</div>
		<div class=\"div-line\"></div>";
	if ($ret) return $c;
	else echo $c;
}

function CloseTab($ret = false) {
$c = "</div>";
	if ($ret) return $c;
	else echo $c;
}

function temp_blocks_left($title, $content, $link, $id, $stitle) {
	echo "$content";
}

function temp_blocks_right($title, $content, $link, $id, $stitle) {
	echo "$content";
}

