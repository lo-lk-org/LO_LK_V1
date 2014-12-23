<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com> Dec_17_2014
 * @tutorial Google Login API
 */
error_reporting(E_ALL);

require_once realpath(dirname(__FILE__) . '/autoload.php');
echo 'TEST--#1';
$client = new Google_Client();
$client->setApplicationName("LyfeKit"); // optional
echo 'TEST--#2';
if($request_from == 'web')
{
    $client_id=($_SERVER['HTTP_HOST'] == 'localhost:13080')?
		    "577040276233-jve4tho9nlqkhtr0gkjt9usmnksssar2.apps.googleusercontent.com"  :"577040276233-uaf3iiujllb7dq49g96a80jsn1690dhg.apps.googleusercontent.com";
    $client_secret=($_SERVER['HTTP_HOST'] == 'localhost:13080')? "DFiFCKwiG5owtXIqMK04CW4N"   :"ajEb8i843yMnsWiXBNCyExpT";
    $redirect_uri = ($_SERVER['HTTP_HOST'] == 'localhost:13080')? 'http://localhost:13080' : 'http://lyfekit.com/api';
    
    $client->setAccessType('offline');
    /**
     * Important: When your application receives a refresh token, it is important to store that refresh token for future use. If your application loses the refresh token, it will have to re-prompt the user for consent before obtaining another refresh token. If you need to re-prompt the user for consent, include the approval_prompt parameter in the authorization code request, and set the value to force.
     */
    $client->setApprovalPrompt('auto');
    $client->setIncludeGrantedScopes(TRUE);
}
elseif($request_from == 'mobile')
{
    $client_id="842537427973-a381vrch0t5cgrgvtr02lik77a5bc8o7.apps.googleusercontent.com";
    $client_secret="EEOeMFHQpLtaHDU4Rr8k-l3N";
    $redirect_uri = "http://localhost";
    
    $client->setAccessType('offline');
    $client->setIncludeGrantedScopes(TRUE);
}
else {
    $this->print_error(array("status"=>"error","response"=>"Invalid request from input"));
}
//private static String GRANT_TYPE = "authorization_code";
//private static String TOKEN_URL = "https://accounts.google.com/o/oauth2/token";
//private static String OAUTH_URL = "https://accounts.google.com/o/oauth2/auth";
//private static String OAUTH_SCOPE = "https://www.googleapis.com/auth/urlshortener";

//OAUTH_SCOPE="https://www.googleapis.com/auth/plus.login";
//OAUTH_SCOPE="https://www.googleapis.com/auth/userinfo.email";
//OAUTH_SCOPE="https://www.googleapis.com/auth/userinfo.profile";

$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);

echo 'TEST--#3';
$client->addScope("https://www.googleapis.com/auth/plus.login");
$client->addScope("https://www.googleapis.com/auth/userinfo.email");
$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
//$client->setScopes(array("https://www.googleapis.com/auth/plus.login","https://www.googleapis.com/auth/userinfo.profile","https://www.googleapis.com/auth/userinfo.email") );

/**
 * If access token given use it
 */
if(isset($get['access_token']) && !empty($get['access_token']) )
{
    echo 'TEST--#4.1';
    if($client->isAccessTokenExpired())
    {
	//$this->print_error(array("status"=>"error","response"=>"Access token expired, Please regenerate the code."));
    }
    
    $authObj = json_decode($get['access_token']);
    $accessToken = $authObj->access_token;
    $refreshToken = $authObj->id_token;
    $tokenType = $authObj->token_type;
    $expiresIn = $authObj->expires_in;
    $createdon = $authObj->created;
   //"refresh_token":"1/xEoDL4iW3cxlI7yDbSRFYNG01kVKM2C-259HOF2aQbI"
    //$output['refresh_token'] = $refreshToken;
    //$output['token_type'] = $tokenType;
    $output['expires_on'] = date("Y-m-d H:i:s",$createdon+$expiresIn);
    
    /**
     * this will refresh your token
     */
    //$client->refreshToken($refreshToken);
    
    $client->setAccessToken($get['access_token']);
    $output['access_token']=$client->getAccessToken();
}
else {
    echo 'TEST--#4.2';
    //$client->setDefer(TRUE);
    if($client->isAppEngine())
	echo 'Yes running GAE';
    else
	echo 'Not running GAE';
    $client->authenticate($code);
    echo 'TEST--#4.3';
    $output['access_token']=$client->getAccessToken();
    echo 'TEST--#4.4';
}



if ($client->getAccessToken()) {
    echo 'TEST--#5';
    //$_SESSION['access_token'] = $client->getAccessToken();
    
    $oauth2Service = new Google_Service_Oauth2($client); 
    $adminuser=$oauth2Service->userinfo->get();
    //echo '<pre>';  print_r($adminuser); echo '</pre>';
    
    
    $output['user_det']=$adminuser;
    //echo '<pre>';  print_r($output); echo '</pre>';
    
    /**
     * Profile update
     */
    $results=$this->getApiContent($this->site_url()."/api/", 'array', array("action"=>"write","module"=>"profile","content_style"=>"single_content","gid"=>$adminuser['id']
	,"name"=>$adminuser['name'],"email"=>$adminuser['email'],"fname"=>$adminuser['givenName'],"mname"=>$adminuser['familyName'],"lname"=>$adminuser['familyName']
	,"uname"=>$adminuser['givenName'],"phone"=>$adminuser['id'],"timezone"=>$this->cur_datetime(),"img_url"=>$adminuser['picture'],"lat"=>'',"long"=>''));
    
    if($results['status_code']==200)
    {
	$resp_data=json_decode($results['response'],TRUE);
	if($resp_data['status']=='success')
	{
	    $output['status']=$resp_data['status'];
	    $output['uid']=$resp_data['uid'];
	    $output['session_id']=$resp_data['session_id'];
	}
	else {
	    $this->print_error(array("status"=>"error","response"=>$results['response']));
	}
    }
    else
    {
	$this->print_error(array("status"=>"error","response"=>$results['response']));
    }
    
    /**
     * Connected people update
     */
    
}

?>
