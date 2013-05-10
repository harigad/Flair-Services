<?php 
 if ($_POST['searchMode'] == "place") {

	$pid=$_POST['pid'];
	
	$out['lat'] = $_POST['lat'] . "-" . $_POST['lng'];
			
	
	if($pid){
	$placeObj=$db->selectRow("select pid as id,name,vicinity as city,address,gref,lat,lng,phone from place where pid='{$pid}'");
		if($placeObj){
		
			 if ($placeObj['address'] == "" || isset($placeObj['address']) == false) {

                $url = "https://maps.googleapis.com/maps/api/place/details/json?";
                $par = "&key=AIzaSyAZjPLQEq5tdllUCd89gV1_XFBHdjpmmEI";
                $par.= "&sensor=true";
                $par.= "&reference={$placeObj['gref']}";
                $results = file_get_contents("{$url}{$par}");
              
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
					
                $db->update("place",$updateData,"pid = '{$pid}'");
		}
		
		
		
			$place=buildPlace($pid,$placeObj['name'],$placeObj['lat'],$placeObj['lng'],$placeObj['phone'],$placeObj['city']);
			echo json_encode($place);
		}	
		return;
	}
		
		
        $url = "https://maps.googleapis.com/maps/api/place/search/json?";
        $par = "&key=AIzaSyAZjPLQEq5tdllUCd89gV1_XFBHdjpmmEI";
		$par.= "&types=bakery|bar|cafe|casino|food|meal_delivery|meal_takeaway|restaurant";
		
		
		if(isset($_POST['lat']) && isset($_POST['lng'])) {
			$_SESSION['lat'] = $_POST['lat'];
			$_SESSION['lng'] = $_POST['lng'];
		$par.="&sensor=true";
        $par.="&location=" . $_POST['lat'] . "," . $_POST['lng'];
		}else if(isset($_POST['city'])){
	
		  $urlA="http://maps.googleapis.com/maps/api/geocode/json?sensor=true&";
		  $parA = "&key=AIzaSyAZjPLQEq5tdllUCd89gV1_XFBHdjpmmEI";
		  $parA ="address=" . $_POST['city'];
		
		  $resA = file_get_contents("{$urlA}{$parA}");
		  //echo $resA;
          $rA = json_decode($resA);
		
		  $_lat=$rA->results[0]->geometry->location->lat;
		  $_lng=$rA->results[0]->geometry->location->lng;
		  
		  $par.="&sensor=true";
          $par.="&location=" . $_lat . "," . $_lng;
		}
		
		//echo $par;

		$preRadiusPar = $par;
		
        if (isset($search) && $search != "") {
            $par.="&name=" . urlencode($search);
			$par.="&radius=10000";
        }else{
			$par.="&radius=1000";
		}



        $results = file_get_contents("{$url}{$par}");
        $r = json_decode($results);
			
		$places = array();
		
		$count=count($r->results);	
		addPlaces($r->results);
		
		if($count<9){
		  $par = $preRadiusPar . "&radius=500";
		  $results = file_get_contents("{$url}{$par}");
		  $r = json_decode($results);
		  addPlaces($r->results);
		  $count=$count + count($r->results);
		}
		
		if($count<9){
		  $par = $preRadiusPar . "&radius=5000";
		  $results = file_get_contents("{$url}{$par}");
		  $r = json_decode($results);		
		  addPlaces($r->results);
		  $count=$count + count($r->results);
		}		
        		
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
	foreach ($r as $key => $val) {
            $placeObj = $db->selectRow("select pid,phone from place where gid='{$val->id}'");
            if ($placeObj) {
                $pid = $placeObj['pid'];
				$phone = $placeObj['phone'];
            } else {
                $newPlaceData['name'] = $val->name;
                $newPlaceData['gid'] = $val->id;
                $newPlaceData['gref'] = $val->reference;
                $newPlaceData['vicinity'] = $val->vicinity;
                $newPlaceData['lat'] = $val->geometry->location->lat;
                $newPlaceData['lng'] = $val->geometry->location->lng;
                $pid = $db->insert('place', $newPlaceData);
				$phone = null;
            }
			
		//$place = buildPlace($pid,$val->name,$val->geometry->location->lat,$val->geometry->location->lng,$phone);
		
			$place['id'] = $pid;
			$place['name'] = $val->name;
			$place['lat'] = $val->geometry->location->lat;
			$place['lng'] = $val->geometry->location->lng;
			$place['phone'] = $phone;
			$place['vicinity'] = $val->vicinity;
			
		array_push($places,$place);
		}

}
	
	
	?>