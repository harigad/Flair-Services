<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include_once 'core/dateClass.php';
include_once 'core/db.php';
include_once 'core/Browser.php';
include_once 'core/user.php';
include_once 'functions/place.php';
$db = new db();
$browser = new browser();
$user = new user();

if($user->loggedin != true) {
  return;
}

$action = $_POST['action'];
$fid = $_POST['fid'];

if($action === "delete" && isset($fid)){
	$flairObj = $db->selectRow("select recipient from feed where fid = '{$fid}'");
	if($flairObj){
		mysql_query("delete from feed where fid = '{$fid}' and (user = {$user->id} or recipient = {$user->id})");
	    $recpObj = $db->selectRow("select count(feed.fid),user.fbid from feed 
	    inner join user on feed.recipient = user.id 
	    where feed.recipient = ". $flairObj[0]);
		if($recpObj){
			if($recpObj[0] == 0 && isset($recpObj[1]) == false){
				mysql_query("delete from role where uid = " . $flairObj[0]);
				mysql_query("delete from user where id = " . $flairObj[0]);
			}
		}
	}
	return;
}

$place = $_POST['place'];

$flair = $_POST['flair'];
$adjective = $_POST['adjective'];
$food = trim($_POST['food']);
$recipientName = $_POST['recipientName'];
$recipient = "";

$recipient = _getRecipient($recipientName,$place);

if (isset($flair) && isset($adjective) && isset($food) && ($recipient!="") ) {
    $dt = new dateObj();
	
    $newData['user'] = $user->id;
			
	$newData['flair'] = $flair;	
    $newData['adjective'] = $adjective;	
    $adj_obj = _get_adj_id($adjective);
    $food_obj = _get_food_id($food);
    	$newData['food'] = $food_obj->fid;
    	if($food_obj->approved && $adj_obj->approved){
    		$newData['approved'] = true;
    	}else{
    		$newData['approved'] = false;
    	}
	$newData['place'] = $place;
	$newData['recipient'] = $recipient;	
    $newData['created'] = $dt->mysqlDate();
	
	
	
    $sid = $db->insert('feed', $newData);
	
	_update_recipient_photo($recipient,$place);
	
///*	//Update Place address info---------------------------------------------------------------------
		$placeObj=$db->selectRow("select * from place where pid='{$place}'");
	        if ($placeObj['address'] === "" || isset($placeObj['address']) === false) {

                $url = "https://maps.googleapis.com/maps/api/place/details/json?";
                $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
                $par.= "&sensor=true";
                $par.= "&reference={$placeObj['gref']}";
                $results = file_get_contents("{$url}{$par}");
				//echo "{$url}{$par}";
				//echo $results;
                $r = json_decode($results);
                $updateData['phone'] = $r->result->formatted_phone_number;
                $updateData['address'] = $r->result->formatted_address;
				
				$address_components = $r->result->address_components;
				
				foreach($address_components  as $ad => $adc){
					
					switch ($adc->types[0]) {
						case "street_number":
							$updateData['streetnum'] = $adc->long_name;
						case "route":
							$updateData['streetname'] = $adc->long_name;
						case "locality":
							$updateData['city'] = $adc->long_name;
						case "administrative_area_level_1":
							$updateData['state'] = $adc->long_name;
						case "postal_code":
							$updateData['zip'] = $adc->long_name;						
					}
				}
				
                $db->update("place",$updateData,"pid = '{$place}'");
                $phone = $r->result->formatted_phone_number;
                $address = $r->result->formatted_address;
        } 
	//---------------------------------------------------------------------------------*/

		$obj->stickers = buildStickers(-1,$user->id);
		$obj->status = 1;
		
		ob_start();
		$global_user_id = $recipient;
		include_once 'functions/user.php'; 
		    $recp_data = ob_get_contents();
			$obj->recipient =  json_decode($recp_data);
		ob_end_clean();
}else{
    $obj->status = 0;
	$obj->title = "";
    $obj->message = "Sorry! unexpected error!";
}

echo json_encode($obj);


function _get_adj_id($adj){
	global $db;
	$return_obj = new stdClass();
	$adj = str_replace("#", "", $adj);
	$fid_obj = $db->selectRow("select id from adjectives where name = '{$adj}'");
	if($fid_obj){
		$return_obj->approved = true;
	}else{
		$return_obj->approved = false;
	}
	return $return_obj;	
}

function _get_food_id($food){
	global $db;
	$return_obj = new stdClass();
	$fid_obj = $db->selectRow("select fid from food where name = '{$food}'");
	if($fid_obj){
		$return_obj->approved = true;
		$return_obj->fid = $fid_obj[0];
	}else{
		$mysqldate = new dateObj();
		$_data['name'] = $food;
		$_data['created'] = $mysqldate->mysqlDate();
		$fid = 	$db->insert("food",$_data);
		$return_obj->fid = $fid;
		$return_obj->approved = false;
	}
	
	return $return_obj;		
	
}

function _getRecipient($recipientName,$pid){
	global $db;
	$recp_Obj = $db->selectRow("select role.uid from role 
	inner join user on role.uid = user.id 
	where role.pid='{$pid}' and user.name='{$recipientName}'");
	
	if($recp_Obj){
		return $recp_Obj[0];
	}else{
		$mysqldate = new dateObj();
		$_data['name'] = $recipientName;
		$_data['created'] = $mysqldate->mysqlDate();
		$uid = $db->insert("user",$_data);
		
		$role_data['uid'] = $uid;
		$role_data['pid'] = $pid;
		$rid = $db->insert("role",$role_data);
		
		return $uid;
	}
}

function _update_recipient_photo($recipient,$pid){
	global $db;
	$_roles = array();
	$_roles[0] = "";
	$_roles[1] = "The Ninja";
	$_roles[2] = "The Angel";
	$_roles[3] = "The Warrior";
	
	$_roles[4] = "The Wicked";
	$_roles[5] = "The Funny One";
	$_roles[6] = "The Pirate";
	
	$_roles[7] = "The Prince";
	$_roles[8] = "The Warrior";
    $_roles[9] = "The Princess";

	$recp_obj = $db->selectRow("select fbid from user where id='{$recipient}'");
	if($recp_obj){
		if($recp_obj[0]>0){
			//User has registered with facebook;
		}else{
		  $count_obj = $db->selectRow("select count(fid) as ct,flair from feed 
		  WHERE recipient = '{$recipient}' 
		  group by flair order by ct desc limit 1");
		  
		  if($count_obj){
		  	$icon = $count_obj[1];
		  	$_data_photo['photo'] = "images/flairs/100/" . $icon . ".png";
			$_data_photo['photo_big'] = "images/flairs/300/" . $icon . ".png";
			$db->update("user",$_data_photo,"id = '{$recipient}'");
			
		  	$flair_icons_arr = $db->selectRows("select flair from feed 
		  	WHERE recipient = '{$recipient}' order by created desc limit 5");
			$flair_icons = array();
				while($flair_icon = mysql_fetch_array($flair_icons_arr)){
					array_push($flair_icons,$flair_icon[0]);
				}
		  
			$_role_data['role'] = $_roles[$icon];
			$_role_data['flairs'] = json_encode($flair_icons);
			$db->update("role",$_role_data,"uid = '{$recipient}' and pid = '{$pid}'");
			}	
		}
	}
}

?>
