<?php header('Access-Control-Allow-Origin: *');
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


if (isset($_POST['lat']) && isset($_POST['lat']) && $_POST['lat'] != "" && $_POST['lng'] != "") {
    $_SESSION['lat'] = $_POST['lat'];
    $_SESSION['lng'] = $_POST['lng'];
}

$search = $_POST['search'];
$type = $_POST['type'];
$id = $_POST['id'];



$debug_log["log"] = "new request: {$search} - {$type} - {$id}";
$db->insert("debug_log", $debug_log);


//echo "---------------" . $type;
//return;
//log this page
$dt=new dateObj();
$user_log['user']=$user->id;
$user_log['page']=$type;
$user_log['title']=$search;
$user_log['idnumber']=$id;
$user_log['created']=$dt->mysqlDate();
$db->insert("user_log",$user_log);

if($type=="_data" ){

	 include_once 'functions/_data.php'; 

}else if($type=="new_hires" ){

	 include_once 'functions/new_hires.php'; 

}else if($type=="like" ){

	 include_once 'functions/like.php'; 

}else if($type=="comments" ){

	 include_once 'functions/comments.php'; 

}else if($type=="user" ){

	 include_once 'functions/user.php'; 

} else if ($type == "search") {
	
	 include_once 'functions/search.php'; 
	
} else if ($type == "food") {

	include_once 'functions/food.php'; 
   
} else if ($type == "place") {
       
	   include_once 'functions/place.php';  
	   
}else if ($type == "city"){

	    include_once 'functions/city.php';  

}else if ($type == "dualFlair"){

	    include_once 'functions/dualFlair.php';  

}else if ($type == "home"){
	
		 include_once 'functions/home.php';  

}else if ($type == "flairprofile"){
	
		 include_once 'functions/flairprofile.php';  

}else if ($type == "friends"){

		 include_once 'functions/friends.php';

}else if ($type == "nearby"){

		 include_once 'functions/nearby.php';

}else if ($type == "role"){

		 include_once 'functions/role.php';
		 
}else if ($type == "notifications"){

		 include_once 'functions/notifications.php';		 
		
}else if ($type == "updatePhone") {
		updatePlace($id);		
		$placeObj = $db->selectRow("select phone from place where pid='{$id}'");
	
		if($placeObj){
			$place->pid = $id;
			$place->phone = $placeObj['phone'];
			$place->error = false;
		}else{
			$place->error = true;
		}
		
		echo json_encode($place);
}else if($type == "local"){
	$stickers = buildStickers(0,0,"",true);
    echo json_encode($stickers);
}else if($type == "updateRole"){
	$data['role']=$_POST['role'];
	$db->update("role",$data,"uid={$user->id}");
}


function clean($str){

return htmlspecialchars($str);

}