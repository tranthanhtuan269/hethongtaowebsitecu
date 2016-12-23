<?php
if (!defined('CMS_SYSTEM')) die();

ob_end_clean();
$img = new CAPTCHA(4);
$img->getImage();
?>