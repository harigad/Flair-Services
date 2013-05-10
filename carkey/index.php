<?php  error_reporting (E_ALL ^ E_NOTICE); ?>
<html>
	<head>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
	 <script type="text/javascript" src="../inc/js/jquery-1.3.2.js"></script>
	 <script type="text/javascript" src="inc/core.js"></script>
	  <script type="text/javascript" src="inc/window.js"></script>
	 <script type="text/javascript" src="inc/signup2.js"></script>
	 <script type="text/javascript" src="inc/home.js"></script>
	 <script type="text/javascript" src="inc/login.js"></script>	 
	 <script src="../inc/js/facebook_js_sdk.js"></script>
	 <link type="text/css" href="inc/fb.css" rel="stylesheet" />
	</head>		
<body>
 <div id="fb-root"></div>
<div id='main' > 
  
  <div id='header' > 	
  	<div>
	 <div id="signup_logo"   ></div>
	 <div id='content' >


	</div>
  	</div>
    	
  	</div>
<div id="error" ></div>

<div id="faq" ></div>
  </div>

  <script>               
                $(function(){
               
		FB.init({appId: '374335169286433', status: true, cookie: true, xfbml: false});
					$login.init($login.onLoginSuccess);
				});
				
				
					</script>

<div id="login_container" style="display:none;" >
Car keys
<a onclick="$login.submit();" >
					
					Login</a></div>
					
					
					
<div style='display:none;' >

<div id="signup1" >
 Car Keys<br>
 <span style='color:#aaa;font-size:0.8em;' >Please enter your licence plate number and your zipcode</span>
  		
  		<div id="header_form" style='text-align:center;' >
  		
  		<form  method="get" action="secure.php?page=signup2" >
  		<input name="plate" onfocus="clearField(this,'vehichle licence plate number');" onblur="restoreField(this,'vehichle licence plate number');"  value="vehichle licence plate number" class='signup_input' >  		
  		<input name="zipcode" onfocus="clearField(this,'zip code');" onblur="restoreField(this,'zip code');" value="zip code" class='signup_input' >
  		<input name="state" value="" type="hidden" >
  		<input name="page" value="signup2" type="hidden" >
  		</form>			  				
  			
  		</div>
  		
  		<div onclick="signup1();" class='submit_btn' >Create My Car Key</div>	
</div>


<div id="signup2" >

<b>__year__ __make__ __model__</b> ( Ownership Verification )<br>
 <span style='color:#aaa;font-size:0.8em;' >Select a small amount to be charged onto a card with the <span style='color:#939393;' ><b>same name and address</b></span> as on the <span style='color:#939393;' ><b>__make__ __model__</b></span></span>
  		
  		<div id="header_form" style='text-align:center;' >
  		
  		<form method="post" action="secure.php?page=ccprocess" >  		
  		<input style='width:80px;' onfocus="clearField(this,'$0.00');"  onblur="restoreField(this,'$0.00');"  value="$0.00" class='signup_input' >
  		<input style='width:180px;' onfocus="clearField(this,'credit card number');"  onblur="restoreField(this,'credit card number');"  value="credit card number" class='signup_input' >
  		<input style='width:80px;' onfocus="clearField(this,'MMYY');" onblur="restoreField(this,'MMYY');" value="MMYY" class='signup_input' >
  		</form>
  		
  		</div>

		<div onclick="$signup2.submit();" class='submit_btn' >Verify</div>

</div>

</div>
					
					
					
					
					
					
					


</body>
</html>