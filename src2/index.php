<?php
/**
 * @author Shivaraj <mrshivaraj123@gmail.com> Dec_18_2014
 * @tutorial Web google login
 */
error_reporting(E_ALL);
include_once 'includes/myclasses.php';
$ob=new Myactions();

$site_url= ($_SERVER['HTTP_HOST'] == 'localhost:13080')? 'http://localhost:13080' : 'http://lyfekit.com';
$client_id=($_SERVER['HTTP_HOST'] == 'localhost:13080')?
		"577040276233-jve4tho9nlqkhtr0gkjt9usmnksssar2.apps.googleusercontent.com"  :"577040276233-uaf3iiujllb7dq49g96a80jsn1690dhg.apps.googleusercontent.com";
$client_secret=($_SERVER['HTTP_HOST'] == 'localhost:13080')? "DFiFCKwiG5owtXIqMK04CW4N"   :"ajEb8i843yMnsWiXBNCyExpT";
$redirect_uri = ($_SERVER['HTTP_HOST'] == 'localhost:13080')? 'http://localhost:13080' : 'http://lyfekit.com';

require_once realpath(dirname(__FILE__) . '/autoload.php');

$client = new Google_Client();
$client->setApplicationName("LyfeKit"); // optional
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->setApprovalPrompt('auto');
$client->setIncludeGrantedScopes(TRUE);
$client->setAccessType('offline');

$client->setScopes(array("https://www.googleapis.com/auth/plus.login","https://www.googleapis.com/auth/userinfo.profile","https://www.googleapis.com/auth/userinfo.email") );

if (isset($_SESSION['access_token']) && !empty($_SESSION['access_token']) ) {
    $client->setAccessToken($_SESSION['access_token']);
    //print_r(array("action"=>"write","module"=>"google-login","content_style"=>"single_content","code"=>"","access_token"=>$_SESSION['access_token'],"request_from"=>"web"));
    $results=$ob->getApiContent($site_url."/api/", 'array', array("action"=>"write","module"=>"google-login","content_style"=>"single_content","code"=>"","access_token"=>$_SESSION['access_token'],"request_from"=>"web"));//,"refresh_token"=>"1/rJd6RBEfmHV_Y_O5EUyuvqL73LFnSzNpiwOIBWsb8RYMEudVrK5jSpoR30zcRFq6"
    
    if($results['status_code']==200)
    {
	$resp_data=json_decode($results['response'],TRUE);
	if(isset($resp_data['user_det']))
	{
	    $user_det=$resp_data['user_det'];
	    $_SESSION['name'] =$user_det['name'];
	    $_SESSION['gid'] =$user_det['id'];
	    $_SESSION['email'] =$user_det['email'];
	}
	else
	{
	    redirectto('#1');
	}
    } else {
	echo $results['response'];
    }
   // echo '<pre>';print_r($user_det); die();
    
} else {
  $authUrl = $client->createAuthUrl();
}

if (isset($_GET['code']) ) //&& !isset($_SESSION['access_token'])
{
     //die("REDEEM CODE FROM API");
     /**
      * Execution break here, getting access token through google-login API
      */
    $results=$ob->getApiContent($site_url."/api/", 'array', array("action"=>"write","module"=>"google-login","content_style"=>"single_content","code"=>$_GET['code'],"access_token"=>"","request_from"=>"web"));
    //echo '<pre>';print_r($results);
    
    if($results['status_code']==200)
    {
	$resp_data=json_decode($results['response'],TRUE);
	if($resp_data['status']==FALSE)
	{
	    
	    if(isset($_SESSION['access_token']))
	    {
		//echo 'Error: '.$resp_data['response'];
		redirectto('#2');
	    }
	    else {
		redirectto('#3');
	    }
	
	}
	elseif(isset($resp_data['access_token']))
	{
	    $_SESSION['access_token'] =$resp_data['access_token'];
	    redirectto('#4');
	}
	
	
    }
    else {
	echo '<pre>';print_r($results);die();
    }
    //print_r($results); die();
}
elseif (isset($_GET['code']) && isset($_SESSION['access_token']) )
{
    redirectto('welcome');
}

if (isset($_REQUEST['logout']) && isset($_SESSION['access_token'] ) ) { //
    $client->revokeToken($_SESSION['access_token']);
    unset($_SESSION['access_token']);
    redirectto('#5');
}
function redirectto($url="#")
{
    ob_end_clean();
    $redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/'.$url;
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}
/*
?>
<div class="request">
<?php 
if (isset($authUrl)) {
	echo "<a class='login' href='" . $authUrl . "'>Sign In</a>";
	
} else {
  echo <<<END
    <a class='logout' href='?logout'>Logout</a>
END;

  echo '<div style="overflow: auto;">Dear '.$user_det['name'].', <img src="'.$user_det['picture'].'" alt="Profile" width="100" height="100" align="left"/></div>';
  echo '<p>Welcome to LyfeKit</p>
	';
}
?>
</div>
*/?>
