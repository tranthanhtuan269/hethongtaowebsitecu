<?php
if(!defined('CMS_ADMIN')) die("Illegal File Access");

global $load_hf;

if ($load_hf) { $noload_hf = 0; } else { $noload_hf = 1; }
if ($noload_hf) {
//	echo "<br>";
//	echo "</div>\n";
//	echo "<div class=\"titlefooter\">"._TITLE_FOOTER."</div>";



    ?>
    <div class="footer">
        <p><?= _TITLE_FOOTER ?> | Design by <a href="http://thietkewebtamphat.com/" target="_blank">Thiết kế Web Tâm Phát</a></p>
    </div>

    </div>

    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
            showLeftPush = document.getElementById( 'showLeftPush' ),
            body = document.body;

        showLeftPush.onclick = function() {
            classie.toggle( this, 'active' );
            classie.toggle( body, 'cbp-spmenu-push-toright' );
            classie.toggle( menuLeft, 'cbp-spmenu-open' );
            disableOther( 'showLeftPush' );
        };

        function disableOther( button ) {
            if( button !== 'showLeftPush' ) {
                classie.toggle( showLeftPush, 'disabled' );
            }
        }
    </script>
    <!--scrolling js-->
<!--    <script src="js/jquery.nicescroll.js"></script>-->
<!--    <script src="js/scripts.js"></script>-->
    <!--//scrolling js-->
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"> </script>
    </div>
    </div>
    <?php
	echo "</body>\n";
	echo "</html>\n";
}
$db->sql_close();


?>