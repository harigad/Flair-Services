<?php

  $pid = $_POST['pid'];
  $action = $_POST['action'];

  if($action=="delete"){
	$updateData['expired'] = '1';
	$db->update("activation",$updateData, "uid='{$user->id}' and pid='{$pid}'");
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
	$output['aid'] = $aid;
	$output['code'] = $newData['code'] ;
	
	$mysqldate->addDays(2);
	$output['expires'] = $mysqldate->time;
	echo json_encode($output);
  
  }

  



?>