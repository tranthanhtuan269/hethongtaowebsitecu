<?php
include_once("configfb.php");
include_once("modules/user/includes/functions.php");
require_once("WebUser.class.php");
global $maxLoginAttempt, $prefix, $db;
//destroy facebook session if user clicks reset
if(!$fbuser){
  $fbuser = null;
  $loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$homeurl,'scope'=>$fbPermissions));
  $output = '<a href="'.$loginUrl.'"><img src="images/fb_login.png"></a>';  
}else{
  $user_profile = $facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
  $userss = new Users();
  $user_data = $userss->checkUserss('facebook',$user_profile['id'],$user_profile['first_name'],$user_profile['last_name'],$user_profile['email'],$user_profile['gender'],$user_profile['locale'],$user_profile['picture']['data']['url']);
  if(!empty($user_data)){

    $email = $user_data['email'];
    $p = $user_data['oauth_uid'];

    $user = new WebUser(0, $email);
    $ret = $user->login($email,$p , $maxLoginAttempt, "http://daylaptrinh.net/index.php?f=user&do=login");
    if ($ret == USER_LOGIN_SUCCEEDED) {
      header("Location: http://daylaptrinh.net");
    }
    else{
      header("Location: ".url_sid("index.php?f=user&do=login")."");
    }

  }else{
    header("Location: ".url_sid("index.php?f=user&do=login&er=1")."");
  }

}
