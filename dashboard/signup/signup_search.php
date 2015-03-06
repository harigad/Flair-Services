<?php header('Access-Control-Allow-Origin: *');
      header("Access-Control-Allow-Headers: Origin, X-Requested-With,X-Titanium-Id, Content-Type, Accept");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

$search = $_REQUEST['search'];

if(isset($search)){	
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?";
        $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
		$par .= "&sensor=false";
		$par .= "&query=" . urlencode($search);
		$results = file_get_contents("{$url}{$par}");
		echo $results;
}
?>