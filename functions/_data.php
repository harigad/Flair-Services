<?php
$data = new stdClass();

$data->_places = get_places();
$data->_people = get_people();
$data->_adjectives = get_array("adjectives");
$data->_forbidden = get_array("forbidden");

function get_people(){
	global $db;	
	$arr = array();
	$rs = $db->selectRows("select id,name,photo,photo_big from user
	inner join role on user.id = role.uid");

	while($data = mysql_fetch_array($rs)){
		array_push($arr,$data);
	}

	return $arr;
}

function get_places(){
	global $db;	
	$arr = array();
	$rs = $db->selectRows("select place.pid,name,vicinity,lat,lng from place inner join role on role.pid = place.pid");

	while($data = mysql_fetch_array($rs)){
		array_push($arr,$data);
	}

	return $arr;
}

function get_array($_table){
	global $db;	
	$arr = array();
	$rs = $db->selectRows("select distinct name from " . $_table);

	while($data = mysql_fetch_array($rs)){
		array_push($arr,$data[0]);
	}

	return $arr;
}

echo json_encode($data);
?>