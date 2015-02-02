<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com> Dec_17_2014
 * @tutorial Google Login API
 */
error_reporting(E_ALL);

require_once realpath(dirname(__FILE__) . '/autoload.php');

$client = new Google_Client();
$client->setApplicationName("LyfeKit"); // optional

if($request_from == 'web')
{
    $client_id=($_SERVER['HTTP_HOST'] == 'localhost:13080')?
		    "577040276233-jve4tho9nlqkhtr0gkjt9usmnksssar2.apps.googleusercontent.com"  :"577040276233-uaf3iiujllb7dq49g96a80jsn1690dhg.apps.googleusercontent.com";
    $client_secret=($_SERVER['HTTP_HOST'] == 'localhost:13080')? "DFiFCKwiG5owtXIqMK04CW4N"   :"ajEb8i843yMnsWiXBNCyExpT";
    $redirect_uri = ($_SERVER['HTTP_HOST'] == 'localhost:13080')? 'http://localhost:13080' : 'http://lyfekit.com';
    
    $client->setAccessType('offline');
    /**
     * Important: When your application receives a refresh token, it is important to store that refresh token for future use. If your application loses the refresh token, it will have to re-prompt the user for consent before obtaining another refresh token. If you need to re-prompt the user for consent, include the approval_prompt parameter in the authorization code request, and set the value to force.
     */
    $client->setApprovalPrompt('auto');
    $client->setIncludeGrantedScopes(TRUE);
}
elseif($request_from == 'mobile')
{
    $client_id="577040276233-966074s939hj72put8bbbk9gac7gm4df.apps.googleusercontent.com";
    $client_secret= "PVaezfl9npRfCOiuX4YA8iCb";
    $redirect_uri = 'http://localhost';
    
    $client->setAccessType('offline');
    $client->setIncludeGrantedScopes(TRUE);
}
else {
    $this->print_error(array("status"=>"error","response"=>"Invalid request from input"));
}

$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);


$client->addScope("https://www.googleapis.com/auth/plus.login");
$client->addScope("https://www.googleapis.com/auth/userinfo.email");
$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
//$client->setScopes(array("https://www.googleapis.com/auth/plus.login","https://www.googleapis.com/auth/userinfo.profile","https://www.googleapis.com/auth/userinfo.email") );

/**
 * If access token given use it
 */
if(isset($get['access_token']) && !empty($get['access_token']) )
{
    $in_access_token=urldecode($get['access_token']);
    
    
    /**
     * Check is token expired?
     */
    $client->setAccessToken($in_access_token);
    $output['was_token_expired']=$client->isAccessTokenExpired();
    if($client->isAccessTokenExpired() )
    {
	    if(!empty($refresh_token))
	    {
		/**
		 * This will refresh your access token
		 */
	       $client->refreshToken($refresh_token);
	       $output['access_token']=$client->getAccessToken();
	       $rslt=mysql_query("UPDATE t_tokens SET refresh_token='".$refresh_token."',access_token='".$output['access_token']."',modified_on=NOW() WHERE access_token='".$in_access_token."'",$linkid) or $this->print_error(mysql_error($linkid));
	    }
	    else
	    {
		$client->setApprovalPrompt('force');
		$client->authenticate($code);
		$rslt=mysql_query("SELECT * FROM t_tokens WHERE access_token='".$in_access_token."' and refresh_token!=''",$linkid) or $this->print_error(mysql_error($linkid));

		if(mysql_num_rows($rslt) == 0) { 
		    //$this->print_error("Accesstoken does not exits.".mysql_error()."".$uid); 
		    $this->print_error(array("status"=>"error","response"=>"Regenerate Refresh"));
		}
		else {
		    $row = mysql_fetch_array($rslt);
		    $output['refresh_token']=$row['refresh_token'];
		    /**
		     * Get the Refresh token from db
		     */
		    $client->refreshToken($row['refresh_token']);
		    $output['access_token']=$client->getAccessToken();
		}
		//refresh_token='".$output['refresh_token']."'
	    }
    }
    else
    {
	    $output['refresh_token'] = $refresh_token;
	    $client->setAccessToken($get['access_token']);
	    $output['access_token']=$client->getAccessToken();
    }

    $authObj = json_decode($client->getAccessToken(),true);
    $output['authobj']=$authObj;
    //$accessToken = $authObj['access_token'];
    //$idToken = $authObj['id_token'];
    //$tokenType = $authObj['token_type'];$output['token_type'] = $tokenType;
    $expiresIn = $authObj['expires_in'];
    $createdon = $authObj['created'];
    $output['expires_on'] = date("Y-m-d H:i:s",$createdon+$expiresIn);
    
    //mysql_query("INSERT INTO t_tokens (`refresh_token`,`access_token`) VALUES('".$output['refresh_token']."','".$output['access_token']."')",$linkid);
    
    /**
     * 
     */
//    if(isset($authObj['refresh_token']))
//    {
//	$output['refresh_token'] = $authObj['refresh_token'];
//	$output['access_token']=$client->getAccessToken();
//    }
//    else
//    {   }
    
    
}
else {

    $client->authenticate($code);
    
    $output['access_token']=$client->getAccessToken();
    
    $authObj = json_decode($output['access_token'],true);
    
    $output['refresh_token'] = '';
    if(isset($authObj['refresh_token']))
    {
	$output['refresh_token'] = $authObj['refresh_token'];
	mysql_query("INSERT INTO t_tokens (`refresh_token`,`access_token`,`modified_on`) VALUES('".$output['refresh_token']."','".$output['access_token']."',NOW())",$linkid);
    }

    
}



if ($client->getAccessToken()) {
//    echo 'TEST--#5';
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
    $output['friends']=array();
    $people=new Google_Service_Plus($client);
    $peoplelist = $people->people->listPeople('me','visible', array()); //connected, visible

    //echo '<pre>';print_r($peoplelist);
    $friends=$peoplelist->getItems();
//    echo '<pre>';print_r($friends); echo '</pre>';
//    echo '<div><ul>';
    foreach($friends as $i=>$friend)
    {
	#print "ID: {$friend->id}<br>";
//	print "<li> <a href='{$friend->url}' target='_BLANK'> {$friend->displayName}</a></li>";
//	print "Image Url: {$friend->image['url']}<br>";//print "Url: {$friend->url}<br>";
	
	
	/*
	friends: [3]
	    0:  {
		aboutMe: null
		birthday: null
		braggingRights: null
		circledByCount: null
		currentLocation: null
		displayName: "LyfeOn"
		domain: null
		etag: ""RqKWnRU4WW46-6W3rWhLR9iFZQM/zjJ0kdVz2rq0zTDaDdARISj8dg0""
		gender: null
		id: "118335972259503633372"
		isPlusUser: null
		kind: "plus#person"
		language: null
		nickname: null
		objectType: "page"
		occupation: null
		plusOneCount: null
		relationshipStatus: null
		skills: null
		tagline: null
		url: "https://plus.google.com/+LyfeOn"
		verified: null
	    }-
	    1:  {
		aboutMe: null
		birthday: null
		braggingRights: null
		circledByCount: null
		currentLocation: null
		displayName: "Namratha Vishwananda"
		domain: null
		etag: ""RqKWnRU4WW46-6W3rWhLR9iFZQM/fSDMYBX-Jw64vNdoA8mCb1gNT9Q""
		gender: null
		id: "109243820562492001894"
		isPlusUser: null
		kind: "plus#person"
		language: null
		nickname: null
		objectType: "person"
		occupation: null
		plusOneCount: null
		relationshipStatus: null
		skills: null
		tagline: null
		url: "https://plus.google.com/109243820562492001894"
		verified: null
	    }
	*/
	if( !empty($friend['id']) || !empty($friend['displayName']) )
	{
	    $output['friends'][$i]['id']=$friend['id'];
	    $output['friends'][$i]['displayName']=$friend['displayName'];
	    $output['friends'][$i]['url']=$friend['url'];
	
	    //action=write&module=social-contact&content_style=single_content&uid=4523623456456456&gid=4523623456456456&following_uid=3453452354&following_gid=3453452354
	    //&following_uname=Jagad&following_name=Jagadeesh
	}
	
    }
//    echo '</ul></div>';
    
    if( count($output['friends']) )
    {
	$results=$this->getApiContent($this->site_url()."/api/", 'array', array("action"=>"write","module"=>"social-contact","content_style"=>"list_content","uid"=>$output['uid']
	    ,"gid"=>$output['uid'],"friends"=>$output['friends'],"datetime"=>$this->cur_datetime()) );
	if($results['status_code']==200)
	{
	    //print_r($results); die();
	    $resp_data=json_decode($results['response'],TRUE);
	    //print_r($resp_data); die();
	    if($resp_data['status']=='success')
	    {
		$output['friends']['status']=$resp_data['status'];
		$output['friends']['response']=$resp_data['response'];
	    }
	    else {
		$output['friends']['status']=$resp_data['status'];
		$output['friends']['response']=$resp_data['response'];
	    }
	    //
	}
    }
}

?>
