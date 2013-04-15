<?php

  $pid = $_POST['pid'];
  $action = $_POST['action'];

  if($action=="delete"){
	$updateData['expired'] = '1';
	$db->update("activation",$updateData, "uid='{$user->id}' and pid='{$pid}'");
	mysql_query("delete from role where uid='{$user->id}' and pid='{$pid}'");
	exit(0);  
  }

  $role=$db->selectRow("select * from role where uid='{$user->id}' and pid='{$pid}'");
  if($role){  
     editRole();
  }else{
    newRole($pid);
  }  

  function newRole($pid){
	global $user,$db;
	$mysqldate=new dateObj();
	
	$newData['uid'] = $user->id;
	$newData['pid'] = $pid;
	$newData['code'] = rand(100000,999999);
	$newData['created'] = $mysqldate->mysqlDate();
	
	$updateData['expired'] = '1';
	$db->update("activation",$updateData, "uid='{$user->id}'");
	
    $aid = $db->insert('activation', $newData);
  
    $output['status'] = true;
	
	$placeData=$db->selectRow("select place.pid,place.lat,place.lng,place.name, place.vicinity, code from place inner join activation on place.pid=activation.pid where activation.uid={$user->id} and expired=0");
	
	if($placeData){
		$placeObj['pid'] = $placeData['pid'];
		$placeObj['lat'] = $placeData['lat'];
		$placeObj['lng'] = $placeData['lng'];
		$placeObj['name'] = $placeData['name'];
		$placeObj['vicinity'] = $placeData['vicinity'];
		$placeObj['code'] = $placeData['code'];
	}
	
	$output['place'] = $placeObj; 
	
	echo json_encode($output);
  
  }

  



?>