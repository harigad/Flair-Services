<?php

  $pid = $_POST['pid'];
  $action = $_POST['action'];

  if($action=="delete"){
	mysql_query("delete from activation where uid='{$user->id}' and pid='{$pid}' and expired=0");
	exit(0);  
  }else{
  	mysql_query("delete from role where uid='{$user->id}' and pid='{$pid}' and expired=0");
	newActivation($pid,1);
  }
  
  function newActivation($pid){
  	global $user,$db;
	$mysqldate=new dateObj();
	
	$new['uid'] = $user->id;
	$new['pid'] = $pid;
	$new['code'] = "789111";
	
	$db->insert("activation",$new);
	
	echo $new['code'];
  }
  

  function newRole($pid,$role){
	global $user,$db;
	$mysqldate=new dateObj();
	
	$newData['uid'] = $user->id;
	$newData['pid'] = $pid;
	$newData['role'] = $role;
	$newData['created'] = $mysqldate->mysqlDate();
	
	$rid = $db->insert('role', $newData);
  
    $output['status'] = true;
	
		 $role=$db->selectRow("Select place.name,place.city,place.vicinity,place.pid,lat,lng from role inner join place on role.pid=place.pid where uid={$user->id} limit 1");
						  if($role){
							$place['pid']=$role['pid'];
							$place['name']=$role['name'];
							$place['city']=$role['city'];
							$place['vicinity']=$role['vicinity'];
							$place['lat']=$role['lat'];
							$place['lng']=$role['lng'];
						  }
			$output['place'] = $place; 
	
	echo json_encode($output);
  
  }

  



?>