<?php
function signup_admin(){
    global $db,$user;
   // $db->debug = true;
    $place_id = $_REQUEST["place_id"];

$place = $db->selectRow("select pid from place where gid='{$place_id}'");
if($place == false && isset($place_id)){
    $pid = signup_askGoogle($place_id);
}else if($place){
    $pid = $place["pid"];
}
    
    $isadmin = $db->selectRow("select * from roles where pid='{$pid}' and access=1");
        if($isadmin){
            return false;
        }
        
    $newadmin["uid"] = $user->id;
    $newadmin["pid"] = $pid;
    $newadmin["access"] = 1;
    $db->insert("role",$newadmin);
    return true;
}

function signup_askGoogle($place_id){
    global $db;
	    $url = "https://maps.googleapis.com/maps/api/place/details/json?";
        $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
		$par = $par . "&placeid=" . $place_id;
    
		$result = file_get_contents("{$url}{$par}");
		$place = json_decode($result);
		$val = $place->result;
   
            if($val->formatted_address){
                $vicinity = $val->formatted_address;
            }else{
                $vicinity = "";
            }
   
   $newPlaceData['name'] = $val->name;
   $newPlaceData['gid'] = $val->place_id;
   $newPlaceData['gref'] = $val->reference;
   $newPlaceData['vicinity'] = $vicinity;
   $newPlaceData['lat'] = $val->geometry->location->lat;
   $newPlaceData['lng'] = $val->geometry->location->lng;
   $pid = $db->insert('place', $newPlaceData);
  
   return $pid;
}

function cleanString($string) {
   		$string = preg_replace("/[^0-9]/", '', $string); // Removes special chars.
		return $string;
}
?>