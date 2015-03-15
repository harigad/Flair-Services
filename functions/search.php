<?php

	$pid=$_POST['pid'];
	if($pid){
	$placeObj=$db->selectRow("select pid as id,name,vicinity as city,address,gref,lat,lng,phone from place where pid='{$pid}'");
		if($placeObj){
			 if ($placeObj['address'] == "" || isset($placeObj['address']) == false) {
                $url = "https://maps.googleapis.com/maps/api/place/details/json?";
                $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
                $par.= "&sensor=true";
                $par.= "&reference={$placeObj['gref']}";
                $results = file_get_contents("{$url}{$par}");
                //echo $par . "---->" .  $results;
                $r = json_decode($results);
              
                $updateData['phone'] = $r->result->formatted_phone_number;
                $updateData['address'] = $r->result->formatted_address;
				
				$address_components = $r->result->address_components;
				
				if($address_components) {
				
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
					}//switch
				}//foreach
					
                $db->update("place",$updateData,"pid = '{$pid}'");
                
                }//address_components
		}//$placeObj['address'] == "" || isset($placeObj['address']) == false
			$recipient_uid = $_POST["recipient_uid"];
			$recipient_name = $_POST["recipient_name"];
			$icon = $_POST["icon"];
		//	echo "------->" . 
		   if(isset($recipient_uid) || isset($recipient_name)){
		   	  createFlair($pid,$recipient_uid,$recipient_name,$icon);
		   }
		
		   $place=buildPlace($pid,$placeObj['name'],$placeObj['lat'],$placeObj['lng'],$placeObj['phone'],$placeObj['city']);
		   echo json_encode($place);
	}//placeObj	

}else if(isset($_POST['search'])){
		
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?";
        $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
		$par .= "&sensor=false";
		$par .= "&query=" . urlencode($search);
		if(isset($_POST['lat']) && isset($_POST['lng'])) {
		 	$par.="&location=" . $_POST['lat'] . "," . $_POST['lng'];
			$par.="&radius=10";
		}//lat and lng
		$results = file_get_contents("{$url}{$par}");	
	    $r = json_decode($results);
			
		$places = array();
		$count=count($r->results);	
		addPlaces($r->results);
		echo json_encode($places);
		
}else {
	
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
        $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
		$par .= "&sensor=false";
		if(isset($_POST['lat']) && isset($_POST['lng'])) {
		 	$par.="&location=" . $_POST['lat'] . "," . $_POST['lng'];
			$par.="&radius=3";
		}//lat and lng
		$results = file_get_contents("{$url}{$par}");	
	    $r = json_decode($results);
			
		$places = array();
		$count=count($r->results);	
		addPlaces($r->results);
		echo json_encode($places);
	
}
	
	function addPlaces($r) {
	global $db,$places;
	foreach ($r as $key => $val) {
            $placeObj = $db->selectRow("select place.name as placename,place.pid,phone,vicinity,user.name as founder from place
            left outer join role on place.pid = role.pid and role.role=1 
            left outer join user on role.uid = user.id 
            where gid='{$val->place_id}' ");
            
            if ($placeObj && $placeObj['vicinity']) {
                $name = $placeObj['placename'];
                $pid = $placeObj['pid'];
				$phone = $placeObj['phone'];
				$vicinity = $placeObj['vicinity'];
				$founder = $placeObj['founder'];
            } else {
                $name = $val->name;
            	$founder = "";
				if($val->formatted_address){
					$vicinity = $val->formatted_address;
				}else{
					$vicinity = "";
				}
				
                                       $newPlaceData['name'] = $name;
                $newPlaceData['gid'] = $val->place_id;
                $newPlaceData['gref'] = $val->reference;
                $newPlaceData['vicinity'] = $vicinity;
                $newPlaceData['lat'] = $val->geometry->location->lat;
                $newPlaceData['lng'] = $val->geometry->location->lng;
                $pid = $db->insert('place', $newPlaceData);
				$phone = null;
            }
		
			$place['pid'] = $pid;
            $place['placename'] = $name;
			$place['lat'] = $val->geometry->location->lat;
			$place['lng'] = $val->geometry->location->lng;
			$place['phone'] = $phone;
			$place['vicinity'] = $vicinity;
			$place['founder'] = $founder;
			
			
		array_push($places,$place);
		}

}

function createFlair($pid,$recipient_uid,$recipient_name,$icon){
	global $user,$db;
	$dt = new dateObj();
	
    $newData['user'] = $user->id;
	$newData['place'] = $pid;
	$newData['icon'] = $icon;
	$newData['recipient'] = getRecipientId($pid,$recipient_uid,$recipient_name);	
    $newData['created'] = $dt->mysqlDate();
	
	/*$isDuplicate = $db->selectRow("select fid from feed where user = '" . $user->id . "' and recipient = '" . $recipient . "'  and DATE(created) = CURDATE() limit 1");
	
	if($isDuplicate){
		 $obj->status = 0;
		 $obj->title = "";
         $obj->message = "Oops! Stop clicking so fast!";
		 echo json_encode($obj);return;	 
	}*/
	if($newData['recipient'] !== false){
    	$db->insert('feed', $newData);
	}
}

function getRecipientId($pid,$recipient_uid,$recipient_name){
	global $db;
	//echo "77" . $recipient_uid;
	if($recipient_uid){
		//echo "88";
		$recp = $db->selectRow("select * from role where pid='{$pid}' and uid='{$recipient_uid}'");
		if($recp == false){
			return false;
		}else{
			return $recipient_uid;
		}
	}else{
		$new["name"] = ucwords($recipient_name);;
		return $db->insert("user",$new);
	}
	
}
	
	
	?>