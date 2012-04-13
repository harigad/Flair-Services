<?php
include_once 'caller_log.php';
$_SESSION['code_a']="";
logme("welcome","","");

//Check to see if called_number is a valid number
	$rest_obj=$db->selectRow("select activation.id,code_a from activation inner join place on activation.place=place.pid and place.phone='{$called_number}' and status=0");

if($rest_obj==false){ 
		header("content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		?>		
				<Response>
				<Say>Welcome to Flair!</Say>
				<Gather numDigits="4" action="process2.php" method="POST">
					<Say>Please enter your four digit activation code.</Say>
				</Gather>
				</Response>
<?php }else{
//Ask to enter the password
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
<Say>Welcome to Flair!</Say>
<Gather numDigits="4" action="process.php" method="POST">
	<Say>Please enter your four digit activation code.</Say>
</Gather>
</Response>
<?php } ?>