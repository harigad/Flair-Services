<?php
include_once 'caller_log.php';

$code_a=$_SESSION['code_a'];
$code_b=$_REQUEST['Digits'];



if(isset($code_a)==false || $code_a=="" || isset($code_b)==false || $code_b==""){
		header("Location: welcome.php?From={$_REQUEST['From']}&Digits={$_REQUEST['Digits']}");	
}

	$rest_obj=$db->selectRow("select activation.id as aid, activation.placename, user.name as name, user.id as userid from activation inner join user on activation.user=user.id where code_a='{$code_a}' and code_b='{$code_b}' and status=0");
	if($rest_obj){
	$db->update("activation",$update_activation,"id='{$rest_obj['aid']}'");
	logme("process3",$code_a,$code_b,1,$rest_obj['aid']);
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";	
	?>	
<Response>
		<Say>Thank You!</Say>
	<Say>You are now activated!</Say>
	<Say>Please visit us at Flayr dott mee on your mobile phone</Say>
</Response>
	<?php
	}else{
	logme("process3",$code_a,$code_b);	
	header("Location: process2.php?From={$_REQUEST['From']}&Digits={$_REQUEST['Digits']}");	
	
	}
?>