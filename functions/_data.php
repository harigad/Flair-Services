<?php
$data = new stdClass();

$data->_adverbs = get_array("adverbs");
$data->_adjectives = get_array("adjectives");
$data->_food = get_array("food");
$data->_forbidden = get_array("forbidden");

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