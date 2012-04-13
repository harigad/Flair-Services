<?php
include_once 'caller_log.php';

$_SESSION['code_a']="";
$code_a=$_REQUEST['Digits'];
logme("process",$code_a);
	
	$rest_obj=$db->selectRow("select activation.id,code_b from activation where code_a='{$code_a}' and status=0");
	if($rest_obj){
	$_SESSION['code_a']=$code_a;
	
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	?>
	
<Response>
				<Say>Thank You!</Say>
				<Gather numDigits="4" action="process3.php" method="POST">
					<Say>Now Please enter your four digit activation password.</Say>
				</Gather>
</Response>
	<?php
	}else{
	
	header("Location: welcome.php?From={$_REQUEST['From']}&Digits={$_REQUEST['Digits']}");	
	
	}
?>