<?php  error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
include_once 'getinfo.php';

$sessionId = samplelogon();

$plate=$_POST['plate'];
$stateDMV=$_POST['state'] . "DMV";
if($plate && $stateDMV){
	$data = lookUpPlate($plate,$stateDMV);
	$_SESSION['data']=$data;	
}else{
    $response->error="Please enter a valid plate number and state";
	$response->status=0;	
	echo json_encode($response);
	return;
}


if($_SESSION['data']=="NOTFOUND"){
	$response->error="Sorry! we were unable to locate a vehichle with the plate number  <b>$plate</b> in $stateDMV";
	$response->status=0;
}else{
	$exists = $db->selectRow("select cid from car where vin='{$data['Vin Number']}'");

	if($exists){
		$response['status']=0;
		$response['error']="Sorry! An other <b>user</b> holds the <b>Car Keys</b> to this " . $data['Make'] ;
	}else{
	
	    $moid = createMakeAndModel($data['Make'],$data['Model']);
	
	    $modelData = $db->selectRow("select make.name as make_name,model.name as model_name,make.logo as logo from model inner join make on model.mid = make.mid where model.moid='{$moid}'");
	
		$response->status=1;
		$response->make = $modelData['make_name'];
		$response->model = $modelData['model_name'];
		$response->year = $data['Model Year'];
		$response->logo = $modelData['logo'];
		$response->showTitle = " ";
	}
}

echo json_encode($response);


//----------------------------------------------------------------------------------------------------------------------------------
	function createMakeAndModel($make,$model){
	global $db;
		$mIdArray = $db->selectRow("select mid from make where code='" . strtolower($make) . "' limit 1");
			if($mIdArray){
					$mId = $mIdArray['mid']; 
					return createNewModel($mId,$model);
			}else{
					$newMake['code'] = strtolower($make);
					$newMake['name'] = strtolower($make);
					$mId = $db->insert("make",$newMake);
				 	return createNewModel($mId,$model);
			}
	}
	
	function createNewModel($mId,$model){
	global $db;
		$moIdArray = $db->selectRow("select moid from model where mid={$mId} and code='" . strtolower($model) . "' limit 1");
			if($moIdArray){
					return $moIdArray['moid'];
			}else{
					$newModel['mid'] = $mId;
					$newModel['code'] = strtolower($model);
					$newModel['name'] = strtolower($model);
				 	return $db->insert("model",$newModel);				 
			}
	}

?>