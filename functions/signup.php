<?php
 
 $mode = $_POST["mode"];


 $inviteid = $_POST["inviteid"];
 $code = $_POST["code"];

if($mode == "getinvite" ) {
 $invite = $db->selectRow("select inviteid,uid,invites.role_id,invites.pid,invites.name as name,place.name as placename,user.name as username,user.photo as userphoto from invites
 inner join place on invites.pid = place.pid 
 inner join user on invites.uid = user.id 
 where invites.expired=0 and invites.inviteid='{$inviteid}' and code='{$code}'");

if($invite){

echo json_encode($invite);


}else{

echo -1;

}

}else if($mode=="approve"){
	  if($user->loggedin){
	
			$invite = $db->selectRow("select inviteid,uid,invites.role_id,invites.pid,invites.name as name,place.name as placename,user.name as username,user.photo as userphoto from invites
 			inner join place on invites.pid = place.pid 
 			inner join user on invites.uid = user.id 
 			where invites.inviteid='{$inviteid}' and code='{$code}'");
	  		
	  		if($invite){
	  			$exists = $db->selectRow("select * from role where uid='" . $invite["uid"] . "' and pid='" . $invite["pid"] . "' and role='" . $invite["role_id"] . "'");
	  			if($exists === false){
	  	   			$newRole["uid"] = $user->id;
			    	$newRole["pid"] = $invite["pid"];
			    	$newRole["role"] = $invite["role_id"];
	
			   		$db->insert("role",$newRole);
			   		
			   		$newEmail["email"] = $invite["email"];
			   		$db->update("user",$newEmail,"id='{$user->id}'");
			   		
			   		$expiredInvite["expired"] = 1;
			   		$db->update("invites",$expiredInvite,"inviteid='{$inviteid}'");
			   		
			   		$output->status = true;
			   		echo json_encode($output);
				}else{
					$output->status = false;
			   		echo json_encode($output);
				}	  
			
			}else{
				$output->status = false;
			   	echo json_encode($output);
			}   		
	  }else{
	  	$output->status = false;
		echo json_encode($output);
	  }
}
?>