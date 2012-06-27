<?php
session_start();
include_once 'getinfo.php';

$sessionId = samplelogon();

$plate=$_GET['plate'];
$stateDMV=$_GET['state'] . "DMV";
if($plate && $stateDMV){
	$data = lookUpPlate($plate,$stateDMV);
	$_SESSION['data']=$data;
}

?>

<?php echo $data['Model Year'] . " " . $data['Make']  . " " . $data['Model']; ?> ( Ownership Verification )<br>
 <span style='color:#aaa;font-size:0.8em;' >Select a small amount to be charged onto a card with the <span style='color:#939393;' ><b>same name and address</b></span> as on the <span style='color:#939393;' ><?php echo $data['Make']  . " " . $data['Model']; ?></span></span>
  		
  		<div id="header_form" style='text-align:center;' >
  		
  		<form name="signup" method="post" action="index.php?page=signup2" >  		
  		<input style='width:80px;' onfocus="clearField(this,'$0.00');"  onblur="restoreField(this,'$0.00');"  value="$0.00" class='signup_input' >
  		<input style='width:180px;' onfocus="clearField(this,'credit card number');"  onblur="restoreField(this,'credit card number');"  value="credit card number" class='signup_input' >
  		<input style='width:80px;' onfocus="clearField(this,'MMYY');" onblur="restoreField(this,'MMYY');" value="MMYY" class='signup_input' >
  		</form>
  		
  		</div>

		<div onclick="signup1();" class='submit_btn' >Verify</div>
