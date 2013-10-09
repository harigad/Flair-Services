<?php header('Access-Control-Allow-Origin: *');
include_once '../core/db.php';
		$search = $_POST['search'];

		$url  = "https://maps.googleapis.com/maps/api/place/textsearch/json?";
        $par  = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
		$par .= "&sensor=true";
		$par .= "&types=bakery|bar|cafe|casino|food|meal_delivery|meal_takeaway|restaurant";
		$par .= "&query=" . urlencode($search);
		
		$results = file_get_contents("{$url}{$par}");
				
				
				//echo $results;
				
        $r = json_decode($results);
			
		$places = array();
	
		addPlaces($r->results);
		echo json_encode($places);
		
?>


<?php 

	function addPlaces($r) {
	global $places;
	$db = new db();
	
	foreach ($r as $key => $val) {
	
            $placeObj = $db->selectRow("select pid,phone,vicinity from place where gid='{$val->id}'");
            if ($placeObj) {
                $pid = $placeObj['pid'];
				$vicinity = $placeObj['vicinity'];
            } else {
    			
				$vicinity = $val->formatted_address;
				
                $newPlaceData['name'] = $val->name;
                $newPlaceData['gid'] = $val->id;
                $newPlaceData['gref'] = $val->reference;
                $newPlaceData['vicinity'] = $val->formatted_address;
                $newPlaceData['lat'] = $val->geometry->location->lat;
                $newPlaceData['lng'] = $val->geometry->location->lng;
                $pid = $db->insert('place', $newPlaceData);
				$phone = null;
            }
			
			$place['pid'] = $pid;
			$place['name'] = $val->name;
			$place['lat'] = $val->geometry->location->lat;
			$place['lng'] = $val->geometry->location->lng;
			$place['phone'] = $phone;
			$place['vicinity'] = $vicinity;
			
		array_push($places,$place);
		}

}
	
?>