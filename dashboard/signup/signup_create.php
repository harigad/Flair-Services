<?php header('Access-Control-Allow-Origin: *');
      header("Access-Control-Allow-Headers: Origin, X-Requested-With,X-Titanium-Id, Content-Type, Accept");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

session_start();
include_once '../core/dateClass.php';
include_once '../core/db.php';

$db = new db();
$dateObj = new dateObj();

$name = $_REQUEST["name"];
$username = $_REQUEST["username"];
$pass = $_REQUEST["pass"];
$place_id = $_REQUEST["place_id"];
$ref = $_REQUEST["ref"];

$busidObj = $db->selectRow("select busid from bus_info where googleid='{$place_id}'");

if($busidObj == false && isset($_REQUEST["name"]) && isset($_REQUEST["username"])  && isset($_REQUEST["pass"])){
$place = askGoogle();
	$new["name"] = $place->name;
	$new["type"] = 1;
	$new["created"] = $dateObj->mysqlDate();
	$uid = $db->insert("user",$new);

	$bus["googleid"] = $place_id;
	$bus["uid"] = $uid;
	$bus["lat"] = $place->geometry->location->lat;
	$bus["lng"] = $place->geometry->location->lng;
	$bus["address"] = $place->formatted_address;
	$bus["phone"] = $place->formatted_phone_number;
	$bus["web"] = $place->website;
	
	$bus["created"] = $dateObj->mysqlDate();
	$busid = $db->insert("bus_info",$bus);
	
	$new_user_obj = $db->selectRow("select id from user where mobile='{$username}'");
	if($new_user_obj){
		$new_user = $new_user_obj[0];
	}else{
		$new_user_data["name"] = $name;
		$new_user_data["mobile"] = cleanString($username);
		$new_user_data["pass"] = $pass;
		$new_user = $db->insert("user",$new_user_data);
	}
	
	$new_admin_data["uid"] = $new_user;
	$new_admin_data["busid"] = $uid;
	$new_admin_data["created"] = $dateObj->mysqlDate();
	$adminid = $db->insert("admin",$new_admin_data);
	
	$status->status = true;
	echo json_encode($status);
}

function askGoogle(){
	global $place_id;
	    $url = "https://maps.googleapis.com/maps/api/place/details/json?";
        $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
		$par .= "&placeid=" . $place_id;
		$result = file_get_contents("{$url}{$par}");
		$place = json_decode($result);
		return $place->result;
}

function cleanString($string) {
   		$string = preg_replace("/[^0-9]/", '', $string); // Removes special chars.
		return $string;
}
?>