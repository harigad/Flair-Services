<?php
session_start();
include_once '../core/dateClass.php';
include_once '../core/db.php';
 
$db=new db();
$d=new dateObj();
$called_number=$_REQUEST['From'];
$code_a=$_SESSION['code_a'];
$code_b=$_REQUEST['Digits'];



if(isset($code_a)==false || $code_a=="" || isset($code_b)==false || $code_b==""){
		header("Location: welcome.php?From={$_REQUEST['From']}&Digits={$_REQUEST['Digits']}");	
}


	$new_call_data['sessionid']=session_id();
	$new_call_data['phone']=$called_number;
	$new_call_data['code_a']=$_SESSION['code_a'];
	$new_call_data['code_b']=$digits;
	$new_call_data['created']=$d->mySqlDate();
	$db->insert("activation_log",$new_call_data);
	echo "select activation.id , activation.placename, user.name as name, user.id from activation inner join user on activation.user=user.id where code_a='{$code_a}' and code_b='{$code_b}' and status=0";
	$rest_obj=$db->selectRow("select activation.id , activation.placename, user.name as name, user.id from activation inner join user on activation.user=user.id where code_a='{$code_a}' and code_b='{$code_b}' and status=0");
	if($rest_obj){
	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	?>
	
<Response>
				<Say>Welcome <?php echo $rest_ob['name']; ?>! Your new work place is now activated!</Say>
</Response>
	<?php
	}else{
	
	header("Location: process2.php?From={$_REQUEST['From']}&Digits={$_REQUEST['Digits']}");	
	
	}
?>