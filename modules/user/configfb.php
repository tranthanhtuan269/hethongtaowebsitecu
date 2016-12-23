<?php
include_once("inc/facebook.php"); //include facebook SDK
######### Facebook API Configuration ##########
$appId = '1622035831447156'; //Facebook App ID
$appSecret = '8565bc5e5271f7b55cd1090a6448c841'; // Facebook App Secret
$homeurl = 'http://daylaptrinh.net/index.php?f=user&do=loginfb';  //return to home
$fbPermissions = 'email';  //Required facebook permissions

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getUser();
?>