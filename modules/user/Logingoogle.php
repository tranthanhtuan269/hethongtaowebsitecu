<?php
global $maxLoginAttempt, $prefix, $db;
require_once("WebUser.class.php");
// session_start();
$google_client_id 		= '699896781751-lhlgv8hi5flbvm2icg6800nunemdqbm5.apps.googleusercontent.com';
$google_client_secret 	= 'azVCRYcZDUpWmLW4btyPaLXl';
$google_redirect_url 	= 'http://daylaptrinh.net/index.php?f=user&do=logingoogle'; //path to your script
$google_developer_key 	= 'AIzaSyAoWjz9M4WH2bAEJ6_vDGLtDzXgkYuJcOY';


require_once 'Google/Google_Client.php';
require_once 'Google/contrib/Google_Oauth2Service.php';

$gClient = new Google_Client();
$gClient->setApplicationName('Login to daylaptrinh.net');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);

$google_oauthV2 = new Google_Oauth2Service($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
  $gClient->revokeToken();
  header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL)); //redirect user back to page
}

//If code is empty, redirect user to google authentication page for code.
//Code is required to aquire Access Token from google
//Once we have access token, assign token to session variable
//and we can redirect user back to page and login.
if (isset($_GET['code'])) 
{ 
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	return;
}


if (isset($_SESSION['token'])) 
{ 
	$gClient->setAccessToken($_SESSION['token']);
}


if ($gClient->getAccessToken()) 
{
	  //For logged in user, get details from google using access token
	  $user 				= $google_oauthV2->userinfo->get();
	  $user_id 				= $user['id'];
	  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	  // $profile_url 			= filter_var($user['link'], FILTER_VALIDATE_URL);
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
}
else {
	//For Guest user, get google login url
	$authUrl = $gClient->createAuthUrl();
}

if(isset($authUrl)) //user is not logged in, show login button
{
	header("Location: ".$authUrl);
} 
else // user logged in 
{	
	$p = $user['id'];
	$a = $user['email'];
	//list all user details
	$resultadd = $db->sql_query_simple("SELECT id, email, pass, type FROM ".$prefix."_user WHERE email ='".$user['email']."' ");
	if($db->sql_numrows($resultadd) > 0) 
	{
		list($id, $email, $pass, $type) = $db->sql_fetchrow_simple($resultadd);
		$resultadd1 = $db->sql_query_simple("SELECT email, pass, type FROM ".$prefix."_user WHERE id ='".$id."' and type ='google'");
		if($db->sql_numrows($resultadd1) > 0) 
		{
			
			$user = new WebUser(0, $email);
			$ret = $user->login($email,$p , $maxLoginAttempt, "http://daylaptrinh.net/index.php?f=user&do=login");
			if ($ret == USER_LOGIN_SUCCEEDED) {
				header("Location: http://daylaptrinh.net");
			}
			else{
				header("Location: ".url_sid("index.php?f=user&do=login")."");
			}
		}
		else
		{
			header("Location: ".url_sid("index.php?f=user&do=login&er=1")."");
		}
		

	}
	else
	{
		$resultadd1 =("INSERT INTO {$prefix}_user (id, email, fullname,pass, type,images,registrationTime) values ('','".$user['email']."','".$user['name']."','".Hash::sha256($user['id'])."','google','".$user['picture']."', NOW() )");
		if($db->sql_query_simple($resultadd1) > 0) 
		{	
			header("Location: http://daylaptrinh.net/index.php?f=user&do=logingoogle ");
		}
	}
	// echo '<pre>'; 
	// print_r($user);
	// echo '</pre>';	
}
?>