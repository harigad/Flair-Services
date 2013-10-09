<?php  error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
include_once 'getinfo.php';

$sessionId = samplelogon();

$plate=$_POST['plate'];
$zipcode=$_POST['zipcode'];
//********************************
$state = "TX";

//$data['make'] = "honda";
//$data['model'] = "civic";

//*******************************
$stateDMV=$state . "DMV";

if($plate && $state){
	$data = lookUpPlate($plate,$stateDMV);
	$_SESSION['data']=$data;	
}else{
    $response->error="Please enter a valid plate number and state";
	$response->status=0;	
	echo json_encode($response);
	return;
}

//$_SESSION['data'] = "FOUND";
if($_SESSION['data']=="NOTFOUND"){
	$response->error="We were unable to locate a vehichle with the plate number $plate";
	$response->status=0;
}else{
	//$exists = $db->selectRow("select cid from car where vin='{$data['Vin Number']}'");
	$exists = $db->selectRow("select car.cid,model.name as modelName,make.name as makeName from car 
	inner join owner on owner.cid = car.cid
	inner join model on car.moid = model.moid 
	inner join make on model.mid = make.mid 
	where car.vin='" . $data['Vin Number'] . "'");

	if($exists){
		$response['status']=0;
		$response['error']="The Car Keys of this " . $exists['makeName']  . " " . $exists['modelName'] . " is already taken!" ;
	}else{
	
	    $moid = createMakeAndModel($data['Make'],$data['Model']);
	
	    $modelData = $db->selectRow("select make.name as make_name,model.name as model_name,make.logo as logo from model inner join make on model.mid = make.mid where model.moid='{$moid}'");
	
		$response->status=1;
		$response->make = $modelData['make_name'];
		$response->model = $modelData['model_name'];
		$response->year = $data['Model Year'];
		$response->logo = $modelData['logo'];
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