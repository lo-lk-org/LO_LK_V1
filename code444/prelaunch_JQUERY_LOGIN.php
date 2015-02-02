<?php
session_start();
?>
<script>
var site_url ="http://"+(document.domain =='localhost'?'localhost:13080':document.domain)+"/";
</script>
<!DOCTYPE html>
<head>
	<meta charset="ISO-8859-1">
	<title>LyfeKit - Manage your Life.</title>
	<meta name="description" content=" LyfeKit - Manage Life. Manage Money, Health, Tasks, Shopping and Notes. Collaborate with friends, family or co-workers.">
	<!-- INCLUDES -->
	<script type="text/javascript">WebFontConfig={google:{families:["Roboto:400,300,500,700,900:latin","Roboto+Condensed:400,300,700:latin","Roboto+Slab:400,300,700:latin"]}};(function(){var e=document.createElement("script");e.src=("https:"==document.location.protocol?"https":"http")+"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";e.type="text/javascript";e.async="true";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})()</script>
    <link rel="stylesheet" href="http://works.tarakaanil.com/master/design/assets/css/global.css" type="text/css" media="all">
</head>
<body style="height:100%;font-family:Roboto Condensed;line-height:150%;top:0;left:0;margin-top:0;margin-left:0;">
	<div>
	    <?php
		//client-id
		$client_id=($_SERVER['HTTP_HOST'] == 'localhost:13080')?
			"577040276233-jve4tho9nlqkhtr0gkjt9usmnksssar2.apps.googleusercontent.com"
		    :"577040276233-uaf3iiujllb7dq49g96a80jsn1690dhg.apps.googleusercontent.com";
		// api key
		$apiKey = 'AIzaSyDHjqldF75Nm54jrlQ6oUHpRPEI-5HrwuM';//'AIzaSyBt514eUceQLLd8b_KI2XKD_tsaVtwm4E8';
	    ?>
		<div class="bg_white" style="">
			<div class="mw45em center" style="padding:3%;">
				<h1 style="font-size:200%;font-weight:600;" title="LyfeKit">LyfeKit</h1>
				<h2 style="font-size:110%;font-weight:100;">Manage your Life. <br><strong>You just Love and Live. We do the rest.</strong></h2>
				<p style="font-family:Roboto; font-weight:400;font-size:75%;">Coming soon on <a href="https://chrome.google.com/webstore/detail/lyfekit/ddpmfmlfaonpbigeobfkjeklaloplepn" class="font_black" style="text-decoration:underline;">Chrome</a> and <a href="https://play.google.com/store/apps/details?id=com.lyfekit.oneapp&hl=en" class="font_black" style="text-decoration:underline;">Android</a></p>
				<!-- Show after Login -->
				<span class="aflog" style="font-size:150%;font-weight:100;">Great ! Just hold tight. <br>We will let you know when your LyfeKit is ready.<br><br></span>
				<span class="aflog signout_block" style="font-family:Roboto; font-weight:400;font-size:75%;"></span>
				<!-- Show before Login -->
				<span class="blog" style="font-size:150%;font-weight:100;">Get Early Access. Sign up with your Google Account<br><br></span>
				<div class="blog"> <!-- Google+ Signin Button -->
					<!-- Add where you want your sign-in button to render -->
					<div id="signinButton">
					  <span class="g-signin"
					    data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email"
					    data-clientid="<?=$client_id;?>"
					    data-redirecturi="postmessage"
					    data-accesstype="offline"
					    data-cookiepolicy="single_host_origin"
					    data-callback="signInCallback">
					  </span>
					    <!--data-apikey="<?=$apiKey;?>"-->
					</div>
					<div id="signin_result"></div>
				</div>
			</div>
			<?php /*<div class="mw45em center" style="padding:3%;">
			    <h1 style="font-size:200%;font-weight:600;" title="LyfeKit">LyfeKit</h1>
			    <h2 style="font-size:110%;font-weight:100;">Manage your Life. <br><strong>You just Love and Live. We do the rest.</strong></h2>
			    <?php 
			    //phpinfo(); die();
			    include('src2/index.php'); ?>
			</div>*/?>
		</div>
		<div class="bg_black font_white" style="margin:0;padding:0;">
			<div class="mw45em center" style="padding:5%;">
				<h1 style="font-size:200%;font-weight:600;margin:0;">Manage Life Quickly and Easily.<br></h1>
				<h2 style="font-size:140%;font-weight:400;"><br>Manage your Money <!-- , Health, Tasks, Shopping and Notes.  --><br><br>Create Groups and Collaborate with your family, friends or colleagues.</h2>
				
			</div>	
		</div>
		<div class="bg_green font_white" style="margin:0;padding:0;">
			<div class="mw45em center" style="padding:5%;">
				<h1 style="font-size:200%;font-weight:600;margin:0;">Manage Money<br></h1>
				<p style="font-size:110%;font-weight:100;"><br>Manage your money the free and fast way. See what is happening with every penny.<br><br>Get hold of your finances be it your home finance or your small business finance.<br><br>With quick money management from LyfeKit, you can now focus on what matters the most for you.</p>
			</div>	
		</div>
		<div class="bg_blue font_white" style="margin:0;padding:0;">
			<div class="mw45em center" style="padding:5%;">
				<h1 style="font-size:200%;font-weight:600;margin:0;">Create Groups and Collaborate<br></h1>
				<p style="font-size:110%;font-weight:100;"><br>Create a group for your home or family or even your business. <br><br>Collaborate with friends and colleagues and manage life together.</p>
			</div>	
		</div>
		<!-- Footer -->
		<div style="position:relative;left:2%;font-size:75%;font-family:Roboto;font-weight:400;padding-top:1%;border-top:1px solid #cecece;width:100%;">
			<span><a href="http://lyfekit.com/about">About LyfeKit</a></span>&nbsp;&nbsp;&nbsp;
			<span><a href="http://lyfekit.com/terms">Terms</a></span>&nbsp;&nbsp;&nbsp;
			<span><a href="http://lyfekit.com/help">Help</a></span>&nbsp;&nbsp;&nbsp;
			<span><a href="https://twitter.com/LyfeKit">Twitter</a></span>&nbsp; &nbsp;&nbsp;
			<span><a href="https://google.com/+LyfeKit">Google</a> </span>&nbsp;&nbsp;&nbsp;
			<span><a href="https://facebook.com/LyfeKit">Facebook</a> </span>&nbsp;&nbsp;&nbsp;
		</div>
		
	</div>
	
<!-- BEGIN Google+ Pre-requisites -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script type="text/javascript">
    (function () {
      var po = document.createElement('script');
      po.type = 'text/javascript';
      po.async = true;
      po.src = 'https://plus.google.com/js/client:plusone.js?onload=start';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(po, s);
    })();
  </script>
  <!-- END Google+ Pre-requisites -->
  <!-- BEGIN Google Analytics -->
  <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-54352542-3', 'auto');
	  ga('send', 'pageview');
  </script>
  <!-- END Google Analytics -->
  
  <!-- BEGIN FACEBOOK SDK -->
	  <script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '484129578287464',
		      xfbml      : true,
		      version    : 'v2.1'
		    });
		  };
		
		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "//connect.facebook.net/en_US/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
	</script>
  <!-- END FACEBOOK SDK -->
  <script>
    function handleAuthClick(event)
    {
	var clientId=$(".g-signin").attr("data-clientid");
//	var apiKey=$(".g-signin").attr("data-apikey");
	var scopes=$(".g-signin").attr("data-scope");

	//====
//	setApiKey(apiKey);
	//====

	// Step 3: get authorization to use private data
	gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, signInCallback);
	return false;
    }
    
    var access_token = '';
    function signInCallback(authResult)
    {
	
	 var authorizeButton = document.getElementById('signinButton');
	  if (authResult && !authResult.error) {
	      //============
	      access_token= authResult.access_token;
////	      console.log( access_token );
////	      alert(access_token);
	      //console.log( authResult );//return false;
//	      initializeApi();
	      //============
	    authorizeButton.style.visibility = 'hidden';
//	    $(".aflog").show();
//	    $(".blog").hide();
	    makeApiCall();
		    var redirecturl=site_url+'welcome';
//		    var redirecturl= $(location).attr('href');
		    window.location.href=redirecturl;
	      
	  } else {
	    authorizeButton.style.visibility = '';
	    authorizeButton.onclick = handleAuthClick;
	  }

    }
//    
//    // Load the API and make an API call.  Display the results on the screen.
    function makeApiCall()
    {
//        
//	   
//            // Step 4: Load the Google+ API
            gapi.client.load('plus', 'v1', function() {
//                
//                // Step 5: Assemble the API request
                  var request = gapi.client.plus.people.get({
                    'userId': 'me'
                  });
                  
		   $("#signin_result").html("Loading...");
//                  
//                // Step 6: Execute the API request
                  request.execute(function(resp) {
//		      console.log(resp);
//                        var heading = document.createElement('h4');var image = document.createElement('img');image.src = resp.image.url;heading.appendChild(image);heading.appendChild(document.createTextNode(resp.displayName));document.getElementById('content').appendChild(heading);
                        var rdata = resp.result;
			
			//API WORK
                        var gid=rdata.id;
                        //uid=rdata.id;
                        var name=rdata.displayName;
                        var emails =rdata.emails; $.each(emails,function(key,val){ email=val.value;});
                        
                        var fname=rdata.name.givenName;
                        var mname='';
                        var lname=rdata.name.familyName;
			var uname='';
			var phone='';
			var timezone=getTimeStamp();
			var lat='4567345';
			var long='45.6645';
//                        var img_url=rdata.image.url;//enco(currency)
                        
                        console.log(postData);
			var getData="action=write&module=profile&content_style=single_content";
			var postData = {gid:enco(gid),name:enco(name),email:enco(email),fname:enco(fname),lname:enco(lname),uname:enco(uname),phone:enco(phone),timezone:enco(timezone),img_url:enco(img_url)};//,uid:uid/
			$.post(site_url+"api/?"+getData,{},function(resp){
			    if(resp.status=='success')
			    {
				console.log(resp);
				if(resp.session_id)
				{
				    $(".signout_block").html("<span class='return signOut("+resp.session_id+");'>SignOut</span>");
				}
			    }
			    else
			    {
				alert(resp.message);
			    }
			},'json');
			var name=rdata.displayName;
			
			var welcomemsg = "Welcome, "+name;
                        $("#signin_result").html(welcomemsg);
			
			//=================
			var redirecturl='/welcome';
//	    	      var redirecturl= $(location).attr('href');
			window.location.href=redirecturl;

		  });
	    });
    }

    /**
     * Set required API keys and check authentication status.
     */
    function setApiKey(apiKey) {
      gapi.client.setApiKey(apiKey);
    }

    function signOut(session_id)
    {
	var getData="action=write&module=session&content_style=single_content";
	$.get(site_url+"api/?"+getData,{session_id:session_id},function(resp) {
	    if(resp.status=='success')
	    {
		alert(resp);
	    }
	    else
	    {
		alert(resp.message);
	    }
	});
    }
    
    //==================================================
    
    
    function getTimeStamp() {
	var fullDate = new Date($.now());
	//convert month to 2 digits
	var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : (fullDate.getMonth()+1);
	var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" + fullDate.getDate();
	return currentDate+" "+fullDate.getHours()+":"+fullDate.getMinutes()+":"+fullDate.getSeconds();
    }

    function showTimeStamp(dateString) {
	var dateStringArr = dateString.split(' ');
	var dateString1 = dateStringArr.join('T');
	var fullDate = new Date(dateString1);
	//convert month to 2 digits
	//var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : (fullDate.getMonth()+1);
	var monthNameArr = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	var monthName = monthNameArr[fullDate.getMonth()]; 
	var year = fullDate.getFullYear();
	var hours = fullDate.getHours();
	var minutes = fullDate.getMinutes();

	// check is year same year
	var sameyear = new Date($.now()).getFullYear(); 
	var year = (sameyear == year ) ? '' : year +', ';

	var currentDate = year+" "+ monthName +" "+fullDate.getDate()+",";
	return currentDate+" "+hours+":"+minutes;//+":"+fullDate.getSeconds();
    }

    function coreTimeFormat(dateString) {
	var dateStringArr = dateString.split(' ');
    //    var dateString1 = dateStringArr.join('T');
    //    var fullDate = new Date(dateString1);
    //    convert month to 2 digits
    //    var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : (fullDate.getMonth()+1);
    //    var monthNameArr = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    //    var monthName = fullDate.getMonth(); 
    //    var year = fullDate.getFullYear();
    //    var hours = fullDate.getHours();
    //    var minutes = fullDate.getMinutes();

	// check is year same year
    //    var sameyear = new Date($.now()).getFullYear(); 
    //    var year = (sameyear == year ) ? '' : year +', ';

    //    var currentDate = year+" "+ monthName +" "+fullDate.getDate()+",";
    //    return currentDate+" "+hours+":"+minutes;//+":"+fullDate.getSeconds();
	return dateStringArr;
    }

    function nl2br (str, is_xhtml) {
	var breakTag = '<br>';//(is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
    }

    /**
     * Javascrit function to encode the input data
     * @author Shivaraj
     */
    function enco(str) {
	return encodeURIComponent(str);
    }

    function fail(rdata){ console.log("error"); console.log(rdata.responseText); }
    //===============================================
  </script>
  <style>
      .aflog {
	  display: none;
      }
  </style>
</body>