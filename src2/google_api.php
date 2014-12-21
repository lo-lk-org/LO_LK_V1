<?php
error_reporting(E_ALL);
/**
 * @author Shivaraj <mrshivaraj123@gmail.com> Dec_17_2014
 * @tutorial Google Login API
 */
require_once realpath(dirname(__FILE__) . '/autoload.php');

$client = new Google_Client();
$client->setApplicationName("LyfeKit"); // optional

if($request_from == 'web')
{
    $client_id=($_SERVER['HTTP_HOST'] == 'localhost:13080')?
		    "577040276233-jve4tho9nlqkhtr0gkjt9usmnksssar2.apps.googleusercontent.com"  :"577040276233-uaf3iiujllb7dq49g96a80jsn1690dhg.apps.googleusercontent.com";
    $client_secret=($_SERVER['HTTP_HOST'] == 'localhost:13080')? "DFiFCKwiG5owtXIqMK04CW4N"   :"ajEb8i843yMnsWiXBNCyExpT";
    $redirect_uri = ($_SERVER['HTTP_HOST'] == 'localhost:13080')? 'http://localhost:13080' : 'http://lyfekit.com';
    
    $client->setAccessType('online');
}
elseif($request_from == 'mobile')
{
    $client_id="842537427973-a381vrch0t5cgrgvtr02lik77a5bc8o7.apps.googleusercontent.com";
    $client_secret="EEOeMFHQpLtaHDU4Rr8k-l3N";
    $redirect_uri = "http://localhost";
    
    $client->setAccessType('online');
}




$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->setApprovalPrompt('auto');

$client->setScopes(array("https://www.googleapis.com/auth/plus.login","https://www.googleapis.com/auth/userinfo.profile","https://www.googleapis.com/auth/userinfo.email") );

/**
 * If access token given use it
 */
if(isset($get['access_token']) && !empty($get['access_token']) )
{
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
    $client->setDefer(TRUE);
    $client->authenticate($code);
    $output['access_token']=$client->getAccessToken();
}



if ($client->getAccessToken()) {
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
