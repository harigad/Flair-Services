<?php  error_reporting (E_ALL ^ E_NOTICE); ?>
<html>
	<head>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
	 <script type="text/javascript" src="../inc/js/jquery-1.3.2.js"></script>
	 <script type="text/javascript" src="inc/core.js"></script>
	 <link type="text/css" href="inc/fb.css" rel="stylesheet" />
	</head>		
<body>
<div class='signup' >


  <div id="signup_logo"   ><div></div></div>
  
  <div id='header' >
  	<div>

8.5 

9.2 Wont Work with the LOAD


	<?php 
	
	if($_GET["page"]=="signup2"){
		include_once 'signup2.php';
	}else{
		include_once 'signup1.php';	
	}
	?>

  	</div>	
  	
  	<div id="error" ></div>
     
  	</div>
  </div>


</div>
</body>
</html>