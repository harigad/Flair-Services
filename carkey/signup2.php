<?php  error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
include_once 'getinfo.php';

$sessionId = samplelogon();

$plate=$_GET['plate'];
$stateDMV=$_GET['state'] . "DMV";
if($plate && $stateDMV){
	$data = lookUpPlate($plate,$stateDMV);
	$_SESSION['data']=$data;	
}

if($_SESSION['data']=="NOTFOUND"){
	$response->error="Sorry! we were unable to locate a vehichle with the plate number  <b>$plate</b> in $stateDMV";
	$response->status=0;
}else{
	$exists = $db->selectRow("select cid from car where vin='{$data['Vin Number']}'");

	if($exists){
		$response['status']=0;
		$response['error']="Nice Try! Someone else holds <b>Car Keys</b> to this " . $data['Make'] . " " . $data['Model'] . "";
	}else{
		$response->status=1;
		$response->make = $data['Make'];
		$response->model = $data['Model'];
		$response->year = $data['Model Year'];
		$response->showTitle = "Owner Verification";
	}
}

echo json_encode($response);


?>