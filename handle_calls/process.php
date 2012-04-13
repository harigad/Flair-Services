<?php
include_once 'caller_log.php';

$_SESSION['code_a']="";
$code_a=$_REQUEST['Digits'];


	$rest_obj=$db->selectRow("select activation.id as aid,code_a from activation inner join place on activation.place=place.pid and place.phone='{$called_number}' and code_a='{$code_a}' and status=0");
	if($rest_obj){
	$update_activation['status']=1;
	$db->update("activation",$update_activation,"id='{$rest_obj['aid']}'");
	logme("process3",$code_a,'',1,$rest_obj['aid']);	
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	?>
	
	<Response>
	<Say>Thank You!</Say>
	<Say>You are now activated!</Say>
	<Say>Please visit us at Flayrr dott mee on your mobile phone</Say>
	</Response>
	
	<?php
	}else{
	
	logme("process3",$code_a);
	header("Location: welcome.php?From={$_REQUEST['From']}&Digits={$_REQUEST['Digits']}");	
	
	}
?>