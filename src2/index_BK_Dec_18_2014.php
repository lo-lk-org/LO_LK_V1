<?php

error_reporting(E_ALL);
include_once "examples/templates/base.php";

//set_include_path(get_include_path() . PATH_SEPARATOR . '/path/to/google-api-php-client/src');

require_once realpath(dirname(__FILE__) . '/autoload.php');

#include_once 'includes/myclasses.php';
$client_id=($_SERVER['HTTP_HOST'] == 'localhost:13080')?
		"577040276233-jve4tho9nlqkhtr0gkjt9usmnksssar2.apps.googleusercontent.com"  :"577040276233-uaf3iiujllb7dq49g96a80jsn1690dhg.apps.googleusercontent.com";
$client_secret=($_SERVER['HTTP_HOST'] == 'localhost:13080')? "DFiFCKwiG5owtXIqMK04CW4N"   :"ajEb8i843yMnsWiXBNCyExpT";
$redirect_uri = ($_SERVER['HTTP_HOST'] == 'localhost:13080')? 'http://localhost:13080' : 'http://lyfekit.com';

//$config = new Google_Config();
//$config=array("disable_gzip"=>TRUE,"enable_gzip_for_uploads"=>TRUE);
//$config->disable_gzip=FALSE;
//$config->GZIP_DISABLED=FALSE;
//$config->GZIP_ENABLED=TRUE;
//echo '<pre>';print_r($config);
//die();enable_gzip_for_uploads
$client = new Google_Client();
$client->setApplicationName("LyfeKit"); // optional
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
//$client->setDeveloperKey(MY_SIMPLE_API_KEY);

//$client->setClassConfig('Google_Http_Request', 'disable_gzip', false);

$client->addScope("https://www.googleapis.com/auth/plus.login");
$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
#$client->addScope("https://www.googleapis.com/auth/userinfo.email");
#$client->addScope("https://www.googleapis.com/auth/urlshortener");
#$client->addScope("https://www.googleapis.com/auth/drive");
#$client->addScope("https://www.googleapis.com/auth/youtube");
#$this->client->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/analytics.readonly'));

$client->setApprovalPrompt('auto');
//$client->setClassConfig('Google_Cache_File',array('directory' => '/tmp/cache'));

#$yt_service = new Google_Service_YouTube($client);
#$dr_service = new Google_Service_Drive($client);





if (isset($_REQUEST['logout']) && isset($_SESSION['access_token'] ) ) { //
	$client->revokeToken($_SESSION['access_token']);
	unset($_SESSION['access_token']);
}

/*if (isset($_GET['code']) && !isset($_SESSION['access_token']) ) { //
//    echo '<pre>';  print_r($_GET);  print_r($_SESSION);  echo '</pre>';
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  
  #$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  //$redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/';
  //header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}*/

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  //$client->refreshToken($session_refresh_token);
} else {
  $authUrl = $client->createAuthUrl();
}

if(isset($_GET['error'])) {
    echo '<h3>Error: '.ucfirst($_GET['error']).'</h3>';
}

/*if ($client->getAccessToken()) {
    
  $_SESSION['access_token'] = $client->getAccessToken();
  
    
  $oauth2Service = new Google_Service_Oauth2($client); 
  $adminuser=$oauth2Service->userinfo->get();
  //echo '<pre>';  print_r($adminuser); echo '</pre>';
  
  echo 'Expired?'.$client->isAccessTokenExpired();
  
  $people=new Google_Service_Plus($client);
  
  $peoplelist = $people->people->listPeople('me','visible', array()); //connected, visible
  
  
  //$dr_results = $dr_service->files->listFiles(array('maxResults' => 10));

  //$yt_channels = $yt_service->channels->listChannels('contentDetails', array("mine" => true));
  //$likePlaylist = $yt_channels[0]->contentDetails->relatedPlaylists->likes;
  //$yt_results = $yt_service->playlistItems->listPlaylistItems("snippet",array("playlistId" => $likePlaylist) );

//  echo '<br>NEW=';
//  $request = new apiHttpRequest("https://www.googleapis.com/oauth2/v1/userinfo?alt=json");
//  $userinfo = $client->getIo()->authenticatedRequest($request);

  
}*/

echo pageHeader("LyfeKit Login Screen");
if ( $client_id == '<YOUR_CLIENT_ID>' || $client_secret == '<YOUR_CLIENT_SECRET>'  || $redirect_uri == '<YOUR_REDIRECT_URI>') {
  echo missingClientSecretsWarning();
}
?>
<div class="request">
<?php 
if (isset($authUrl)) {
	echo "<a class='login' href='" . $authUrl . "'>Sign In</a>";
	
} else {
  echo <<<END
    <form id="url" method="GET" action="{$_SERVER['PHP_SELF']}"></form>
    <a class='logout' href='?logout'>Logout</a>
END;
    /*
    echo '<div style="overflow: auto;">Dear '.$adminuser->name.', <img src="'.$adminuser->picture.'" alt="Profile" width="100" height="100" align="left"/></div>';
    
//    echo '<br>Email:'.$plus->email;
//    echo '<br>Name:'.$plus->name;
//    echo '<br>FamilyName:'.$plus->familyName;
//    echo '<br>Gender:'.$plus->gender;
//    echo '<br>GivenName:'.$plus->givenName;
//    echo '<br>ID:'.$plus->id;
//    echo '<br>Link:'.$plus->link;
    echo '<p>Welcome to LyfeKit</p>
	';
    
	//echo "<h3>Results Of Drive List:</h3>";
	//foreach ($dr_results as $item) {
	//	echo $item->title, "<br /> \n";
	//}

	//echo "<h3>Results Of YouTube Likes:</h3>";
	//foreach ($yt_results as $item) {
	//	echo $item['snippet']['title'], "<br /> \n";
	//}
	//$token = json_decode($client->getAccessToken());
	//echo '<pre>';print_r($token);
	//$attributes = $client->verifyIdToken($token->id_token, $client_id)->getAttributes();
	
	//echo 'GPLUS ID:'.$gplus_id = $attributes["payload"]["sub"];
	  
    //echo '<pre>';print_r($peoplelist);
    $friends=$peoplelist->getItems();
//    echo '<pre>';print_r($friends); echo '</pre>';
    //echo '<div><ul>';
    foreach($friends as $friend)
    { 
	#print "ID: {$friend->id}<br>";
	//print "<li> <a href='{$friend->url}' target='_BLANK'> {$friend->displayName}</a></li>";
	//print "Image Url: {$friend->image['url']}<br>";//print "Url: {$friend->url}<br>";
    }
    //echo '</ul></div>';
     
     */
}
?>
  </div>