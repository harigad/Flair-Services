<?php header('Access-Control-Allow-Origin: *');

//if(basename($_SERVER['HTTP_REFERER']) == basename($_SERVER['HTTP_SELF'])) {

	include_once '../core/db.php';
	include_once '../functions/place.php';
	$db = new db();
	$user = new stdClass();
	$user->id = -1;

	$flairs = buildStickers(null,null,null,true);

	echo json_encode($flairs);

//}

?>