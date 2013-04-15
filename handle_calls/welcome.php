<?php
include_once 'Sig_validation.php';
if($_GET['id']=="123456789" && $calculated_signature == $expected_signature){
include_once 'caller_log.php';
logme("welcome","","");

if($rest_obj){ 
		header("content-type: text/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		?>		
				<Response>
				<Say>Welcome to Flair!</Say>
				<Gather numDigits="6" action="process.php" method="POST">
					<Say>Please enter your 6 digit activation code.</Say>
				</Gather>
				</Response>
<?php }else{
//Ask to enter the password
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
<Say>Welcome to Flair!</Say>
<Say>Sorry! I did not recognize the restaurant you are calling from.</Say>
<Say>Please call back from a valid land line.</Say>
<Say>Bye Bye</Say>
</Response>
<?php } 
}
else
{
	echo "Requested page is Secured.";
}
?>