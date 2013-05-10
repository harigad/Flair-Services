<?php 	
	echo "111";
		if($_POST['id']=="me" || $_POST['id']==$user->id){
			$myProfile=true;
			$userid=$user->id;
		}else{
			$userid=$_POST['id'];		
		}
	
		$thisUser = $db->selectRow("select * from user where id='{$userid}'");	
		
$userObj->status = true;
$userObj->id = $thisUser->id;
$userObj->name = $thisUser->name;
$userObj->photo = $thisUser->photo;
$userObj->photo_big = $thisUser->photo_big;

$userObj->cars = $thisUser->getCars();

echo json_encode($userObj);
		
	?>