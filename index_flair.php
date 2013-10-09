<?php
include_once 'core/dateClass.php';
include_once 'core/db.php';
include_once 'core/Browser.php';
include_once 'core/user.php';
$db = new db();
$browser = new browser();
$user = new user();


if($browser->isMobile()){
	
	if (isset($user->id) == true) {
		header("Location: mobile.php");
	}
	
}else{

	header("Location: /app");

}


?>

<html>
<head>
<title>Flair</title>
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<script>
 function init(){
	window.scrollTo(0, 1);
 }
</script> 

</head>
<body style="background-color:#eee;" onload="init();" >

<div style="text-align:center;padding-top:20px;height:125%;" >
<?php

$i=rand(1,5);

?>
<img src="/images/logos/flair_<?php echo $i; ?>.png" id="logo" >
<div>
<a href="<?php echo $user->login(); ?>" >
<img src="/images/Go.png" style="width:100px;border:0px;" ></a>
</div>
</div>


  <div id="fb-root"></div>
            <script>
                window.fbAsyncInit = function() {
                    FB.init({appId: '137205592962704', status: true, cookie: true, xfbml: true});
                }
                $(function(){
                    var e = document.createElement('script');
                    e.async = true;
                    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';

                    document.getElementById('fb-root').appendChild(e);
                }());
            </script>	

</body>
</html>