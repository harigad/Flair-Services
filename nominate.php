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

$verb = $_POST['verb'];
$noun = $_POST['noun'];
$verbName = $_POST['verbName'];
$verbType = $_POST['verbType'];
$recipient = $_POST['people'];
$recipientName = $_POST['peopleName'];

if (isset($verb) && isset($noun) && isset($recipient) && $noun!="" && $verb!="" && ($recipient!="" || $recipientName!="") ) {
    $dt = new dateObj();
	
    $newData['user'] = $user->id;	
    $newData['noun'] = $noun;
	
    $newData['verb'] = $verb;
	$newData['verbName'] = $verbName;
	
	$newData['recipient'] = $recipient;
	$newData['recipientName'] = $recipientName;
	
    $newData['created'] = $dt->mysqlDate();
    $newData['updated'] = $dt->mysqlDate();
	
    $sid = $db->insert('sticker_temp', $newData);
	
	//Update Place address info---------------------------------------------------------------------
		$place=$db->selectRow("select * from place where pid='{$noun}'");
	        if ($place['address'] == "" || isset($place['address']) == false) {

                $url = "https://maps.googleapis.com/maps/api/place/details/json?";
                $par = "&key=AIzaSyAZjPLQEq5tdllUCd89gV1_XFBHdjpmmEI";
                $par.= "&sensor=true";
                $par.= "&reference={$place['gref']}";
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
				
                $db->update("place",$updateData,"pid = '{$noun}'");
                $phone = $r->result->formatted_phone_number;
                $address = $r->result->formatted_address;
        } 
	//---------------------------------------------------------------------------------
	
		$obj->pid = $noun;	
		$obj->stickers = buildStickers($noun);;						
			
				$foodsData = $db->selectRows("select distinct food.fid as fid,food.name,food.type from food inner join sticker on food.fid = sticker.verb and sticker.noun={$noun}");
				$foods = array();			
					while($food = mysql_fetch_array($foodsData)) {							
						array_push($foods,$food);
					}			
				
				$obj->foods = $foods;						
				$obj->status = 1;
}else{
    $obj->status = 0;
	$obj->title = "";
    $obj->message = "Sorry! unexpected error!";
}



echo json_encode($obj);
?>
