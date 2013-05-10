<?php 
 if ($_POST['searchMode'] == "place") {

	$pid=$_POST['pid'];
	
	$out['lat'] = $_POST['lat'] . "-" . $_POST['lng'];
			
	
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
					}
				}
					
                $db->update("place",$updateData,"pid = '{$pid}'");
                
                }
		}
		
		
		
			$place=buildPlace($pid,$placeObj['name'],$placeObj['lat'],$placeObj['lng'],$placeObj['phone'],$placeObj['city']);
			echo json_encode($place);
		}	
		return;
	}
		
		
        $url = "https://maps.googleapis.com/maps/api/place/search/json?";
        $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
		$par .= "&sensor=true";
		$par .= "&types=bakery|bar|cafe|casino|food|meal_delivery|meal_takeaway|restaurant";
		
		if(isset($_POST['lat']) && isset($_POST['lng'])) {
			$_SESSION['lat'] = $_POST['lat'];
			$_SESSION['lng'] = $_POST['lng'];

        	$par.="&location=" . $_POST['lat'] . "," . $_POST['lng'];
		    	if (isset($search) && $search != "") 
		    	{
		 	  	$par.="&name=" . urlencode($search);
		    	} 
			$preRadiusPar = $par;
		}else if(isset($_POST['city'])){
		  	$url  = "https://maps.googleapis.com/maps/api/place/textsearch/json?";
		  	$par .= "&query=" . urlencode($search . " near " . $_POST['city']);
		}
		
        if (isset($search) && $search != "") {
           //no radius
        }else{
        	$search = "";
			$par.="&radius=1000";
		}

		$results = file_get_contents("{$url}{$par}");
				
        $r = json_decode($results);
			
		$places = array();
		$count=count($r->results);	
		addPlaces($r->results);
		
		/*if($count<9 && $search===""){
		  $par = $preRadiusPar . "&radius=500";
		  $results = file_get_contents("{$url}{$par}");
		  $r = json_decode($results);
		  addPlaces($r->results);
		  $count=$count + count($r->results);
		}
		
		if($count<9 && $search===""){
		  $par = $preRadiusPar . "&radius=5000";
		  $results = file_get_contents("{$url}{$par}");
		  $r = json_decode($results);		
		  addPlaces($r->results);
		  $count=$count + count($r->results);
		}	*/	
        		
		echo json_encode($places);
		
    } else {
?>
<?php	
	
		$pageid=$_POST['id'];
		$searchMode = $_POST['searchMode'];
        $search = $_POST['search'];
        $foodsData = $db->selectRows("select distinct fid,name,food.type as type from food where type=$searchMode and name like '%$search%' limit 20 ");	
				
		$foods = array();		
		
        if (mysql_num_rows($foodsData) > 0) {
            while ($food = mysql_fetch_object($foodsData)) {
				array_push($foods,$food);
            }        
		} else {
            
				if($search!=""){
					$newfood["fid"] = -1;
					$newfood["type"] = $searchMode;
					$newfood["name"] = "$search";
					$newfood["icon"] = "add_new.png";
					array_push($foods,$newfood);
				}

		}
		
		echo json_encode($foods);
		
    }
	
	
	function addPlaces($r) {
	global $db,$places;
		/*$tw = new stdClass();
		$tw->id = "tw_career_fair";
		$tw->name = "TW Career Fair";
		$tw->geometry->location->lat = "-96.8038130";
		$tw->geometry->location->lng = "32.7964690";
		array_unshift($r,$tw);*/
	foreach ($r as $key => $val) {
            $placeObj = $db->selectRow("select pid,phone,vicinity from place where gid='{$val->id}'");
            if ($placeObj && $placeObj['vicinity']) {
                $pid = $placeObj['pid'];
				$phone = $placeObj['phone'];
            } else {
            	
				if($val->vicinity){
					$vicinity = $val->vicinity;
				}else{
					$vicinity = $val->formatted_address;
				}
				
                $newPlaceData['name'] = $val->name;
                $newPlaceData['gid'] = $val->id;
                $newPlaceData['gref'] = $val->reference;
                $newPlaceData['vicinity'] = $vicinity;
                $newPlaceData['lat'] = $val->geometry->location->lat;
                $newPlaceData['lng'] = $val->geometry->location->lng;
                $pid = $db->insert('place', $newPlaceData);
				$phone = null;
            }
			
		//$place = buildPlace($pid,$val->name,$val->geometry->location->lat,$val->geometry->location->lng,$phone);
		
			$place['pid'] = $pid;
			$place['name'] = $val->name;
			$place['lat'] = $val->geometry->location->lat;
			$place['lng'] = $val->geometry->location->lng;
			$place['phone'] = $phone;
			$place['vicinity'] = $vicinity;
			
			
				$cast = array();
				$castData = $db->selectRows("select distinct user.id as uid,
				user.name as name,
				user.photo as photo 
				from user 
				inner join role on user.id=role.uid  
				where role.pid={$pid}");
				while($castMember = mysql_fetch_object($castData)) {							
					array_push($cast,$castMember);
				}

			  	$place['cast'] = $cast;		
			
			
		array_push($places,$place);
		}

}
	
	
	?>