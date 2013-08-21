<?php
$checkin_type = $_REQUEST['checkin_type'];

if($checkin_type == "request"){
	$plate = $_REQUEST['plate'];
	$state = $_REQUEST['state'];
	
	$data['uid'] = $user->id;
	$data['plate'] = $plate;
	$data['state'] = $state;
	
	$db->insert("checkin_request",$data);
	json_encode($user->get_notices());
	
}else if($checkin_type == "check_for_pending"){
	
	json_encode($user->get_notices());
	
}else if($checkin_type == "notices"){
	
	json_encode($user->get_notices());
	
}else if($checkin_type == "approve_checkin"){
	
	$checkin_request_id = $_REQUEST["checkin_request_id"];
	$cid = $_REQUEST["cid"];
	$sender = $_REQUEST["sender"];
	
	$data['owner'] = $user->id;
	$data['sender'] = $sender;
	$data['cid'] = $cid;
	
	$db->insert("checkin",$data);
	
	$noticeid = $_REQUEST['noticeid'];
	$update['status'] = 1;
	$db->update("checkin_request",$update,"id = '" . $noticeid . "'");
	
	json_encode($user->get_notices());
	
}else if($checkin_type == "delete_checkin"){
		
	$noticeid = $_REQUEST['noticeid'];
	$update['status'] = 2;
	$db->update("checkin_request",$update,"id = '" . $noticeid . "'");
			
}else if ($checkin_type == "notice_seen"){
	$noticeid = $_REQUEST["noticeid"];
	$data["seen"] = 1;
	$db->update("checkin",$data,"id = '" . $noticeid . "'");
	
}
?>