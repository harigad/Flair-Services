<?php
include_once 'caller_log.php';
logme("process3",$code_a,'',1,$rest_obj['aid']);	
	$code=$_REQUEST['Digits'];
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
if($rest_obj && $code==$rest_obj['code']){
	echo "<Response>";
	echo "<Say>Thank You!</Say>";
	echo "<Say>You are now activated!</Say>";
	echo "<Say>Please visit us at Flayrr dott mee or on your iPhone</Say>";
	echo "</Response>";	
	
	$insertData['uid']=$rest_obj['uid'];
	$insertData['pid']=$rest_obj['pid'];
	  $rid=$db->insert("role",$insertData);
	
	$updateData['expired'] = 2;
	$updateData['rid'] = $rid;
	  $db->update("activation",$updateData,"id='" . $rest_obj['id'] . "'");
	
}else{
	echo "<Response>";
	echo "<Say>Sorry! Inn valid Code</Say>";
	echo "<Gather numDigits=\"6\" action=\"process.php\" method=\"POST\" >";
	echo "<Say>Please re-enter your 6 digit activation code.</Say>";
	echo "</Gather>";
	echo "</Response>";
}
?>