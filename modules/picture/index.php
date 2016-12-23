<?php
if (!defined('CMS_SYSTEM')) die();
$title_page = _MODTITLE;
include_once("header.php");
global $path_upload, $db, $prefix;
?>
<section>
    <?php echo advertising(55); ?>
</section>
<section>
    <div class="row">
        <div class="col-sm-12 col-md-9 col-lg-9 left-col-main">
            <div class="boxhome_line">
                <div class="box_title">
                    <h2><?= _MODTITLE ?></h2>
                </div>
    			<div class="box_picture">
    			    <script src="<?php echo $urlsite?>/js/lightbox.js"></script>
    				<div class="row">
    					<?php
    					$result_p = $db->sql_query_simple("SELECT id,title,images FROM ".$prefix."_picture WHERE active= '1' ORDER BY catid ASC ");
    					$j=1;
    					while(list($id, $title_p,$images) = $db->sql_fetchrow_simple($result_p)){
    						// $images_p = "files/pictures/$images_p";
    						$path_upload_img = "$path_upload/pictures";
    						if(file_exists("$path_upload_img/$images") && $images !="")
    						{
    							$new_goc=$urlsite."/".$path_upload_img."/".$images;
    							$news_img1 = $urlsite."/".resizeImages("$path_upload_img/$images", "$path_upload_img/380x250_$images" ,380,250);
    						}
    						else
    						{
    						  	$new_goc ="images/no_image.gif";
    						  	$news_img1 = $urlsite."/".resizeImages("images/no_image.gif", "images/380x250_no_image.gif" ,380,250);
    						}
    						?>
    							<div class="post_duan col-sm-4 col-xs-12 gallery-grid" style="margin-bottom: 30px;">
    									<a href="<?= $new_goc ?>" class="example-image-link" data-lightbox="example-set" data-title="<?= $title_p ?>">
    										<img src="<?= $news_img1 ?>"  alt="<?=  $title_p ?>" class="example-image img-responsive zoom-img" />
    									</a>
    							</div>
    						<?php
    						$j++;
    					}
    					?>
    				</div>
    			</div>
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 right-col-block">
            <?php blocks("left",$module_name); ?>
        </div>
    </div>
</section>
<?php
include_once("footer.php");



function home_new_list2(){
    global $db,$prefix,$currentlang,$path_upload,$get_path,$urlsite ;
    $result_lastnew = $db->sql_query_simple("SELECT catid,guid, title  FROM ".$prefix."_news_cat WHERE parent=0 AND active='1' AND alanguage='$currentlang' ORDER BY catid DESC LIMIT 1");
    $numrows = $db->sql_numrows($result_lastnew);
    if($numrows > 0) 
    {
        while(list($idlast,$guid, $titlelast) = $db->sql_fetchrow_simple($result_lastnew)) 
        {
            $url_news_detail =url_sid($guid);
            ?>
            <div class="listcat">
                <div class="flexs">
                    <h2 class="titseo"><a class="hhf" href="<?= $url_news_detail ?>" title="<?= $titlelast ?>"><?= $titlelast ?></a></h2>
                    <div class="xemall0">
                        <a class="xemall" href="<?= $url_news_detail ?>" title="<?= $url_news_detail ?>">Xem t?t c? <img src="<?php echo $urlsite ?>/templates/Adoosite/assets/images/xemall.png" class="img-responsive mauto" alt="Xem t?t c?"></a>
                    </div>
                </div>
                <div class="row m10 news">
                    <?php
                    list($total)  = $db->sql_fetchrow_simple($db->sql_query_simple("SELECT count(*) FROM {$prefix}_news WHERE catid='$idlast' AND active=1"));
                    if($total == 8)
                    {
                        $a = 8;
                    }
                    else{$a = 8 - $total;}
                    $result_newsindex = $db->sql_query_simple("SELECT id, title, hometext, images, time,hits FROM {$prefix}_news WHERE catid='$idlast' AND active=1 ORDER BY time DESC LIMIT 8");
                    if($db->sql_numrows($result_newsindex) > 0)
                    {
                        $i = 1;
                        while(list($idnewind, $titlenewind, $hometextind, $imagesind, $timenewind,$hits) = $db->sql_fetchrow_simple($result_newsindex))
                        {
                            $url_news_detail =url_sid("index.php?f=news&do=detail&id=$idnewind");
                            $get_path_newindex = get_path($timenewind);
                            $path_upload_imgnewind = "$path_upload/news/$get_path_newindex";

                            if($i == 1){
                                if($imagesind !="" && file_exists("$path_upload_imgnewind/$imagesind"))
                                {
                                    $imagesind = $urlsite."/".resizeImages("$path_upload_imgnewind/$imagesind", "$path_upload_imgnewind/350x175_$imagesind" ,350,175);
                                    $imagesind= "<img class='img-responsive ' title=\"$titlenewind\" alt=\"$titlenewind\"  src=\"$imagesind\" />";

                                }
                                else
                                {
                                    $imagesind = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_imgnewind/350x175_no_image.gif" ,350,175);
                                    $imagesind= "<img class='img-responsive' title=\"$titlenewind\" alt=\"$titlenewind\" src=\"$imagesind\"/>";
                                }
                                ?>
                                <div class="col-sm-6 p10">
                                    <div class="col-sm-12 news-1 p10">
                                        <div class="col-sm-12 box-new-1 p10">
                                            <a class="new-img hvr-float-shadow" href="<?= $url_news_detail?>" title="<?= $titlenewind ?>">
                                                <?= $imagesind?>
                                            </a>
                                            <div class="">
                                                <a href="#" title="" class="tit-new"><h3 class="hb"><?= $titlenewind ?></h3></a>
                                            </div>
                                            <div class="imfor f13">
                                                <p class="list-inlineb f13"><?= date('d/m/Y'); ?></p>
                                                <p class="list-inlineb f13"><?= $hits." dã xem" ?></p>
                                            </div>
                                            <div class="box-imfor">
                                                <div class="des">
                                                    <?=  $hometextind ?>
                                                </div>
                                                <div class="clr"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 p10 img_left">
                            <?php
                            }else if($i == 2){
                                if($imagesind !="" && file_exists("$path_upload_imgnewind/$imagesind"))
                                {
                                    $imagesind = $urlsite."/".resizeImages("$path_upload_imgnewind/$imagesind", "$path_upload_imgnewind/90x60_$imagesind" ,90,60);
                                    $imagesind= "<img class='img-responsive ' title=\"$titlenewind\" alt=\"$titlenewind\"  src=\"$imagesind\" />";

                                }
                                else
                                {
                                    $imagesind = $urlsite."/".resizeImages("images/no_image.gif", "$path_upload_imgnewind/90x60_no_image.gif" ,90,60);
                                    $imagesind= "<img class='img-responsive' title=\"$titlenewind\" alt=\"$titlenewind\" src=\"$imagesind\"/>";
                                }
                                ?>
                                        <div class="col-sm-12 box-new-1 p10">
                                            <a class="new-img hvr-float-shadow" href="<?= $url_news_detail?>" title="<?= $titlenewind ?>">
                                                <?= $imagesind?>
                                            </a>
                                            <div class="">
                                                <a href="#" title="" class="tit-new"><h3 class="hb"><?= $titlenewind ?></h3></a>
                                            </div>
                                            <div class="imfor f13">
                                                <p class="list-inlineb f13"><?= date('d/m/Y'); ?></p>
                                                <p class="list-inlineb f13"><?= $hits." dã xem" ?></p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 boxts p0">
                                            <ul class="boxts-ul">
                                            <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <li>
                                                <a href="<?= $url_news_detail ?>" title="">
                                                    <span class="fa fa-caret-right"></span><?= $titlenewind ?>
                                                </a>
                                            </li>
                                        <?php
                                    }
                                        if($total <8)
                                        {
                                            if($i == $a){
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        }
                                        else
                                        {
                                            if($i == 8)
                                            {
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                        }
                                    $i++;
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        <?php
    }
}


?>