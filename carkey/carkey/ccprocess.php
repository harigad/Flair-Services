<?php
//API Password
//8T9KXNFZUX54WBBF
//Signature
//AuRA8YAFrqaa8el6GaeJD4DBTrntAHLbWMjYm-2vS545N94euoW86fm5

	function getTitleDate($data){
		global $dateObj;
		return $dateObj->mysqlDate();	
	}
	
	function getRegisDate($data){
		global $dateObj;
		return $dateObj->mysqlDate();
	}


	function createMakeAndModel($make,$model){
	global $db;
		$mIdArray = $db->selectRow("select mid from make where code='" . strtolower($make) . "'");
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
		$moId = $db->selectRow("select moid from model where mid={$mId} and code='" . strtolower($model) . "'");
			if($moIdArray){
					return $moIdArray['moid'];
			}else{
					$newModel['mid'] = $mId;
					$newModel['code'] = strtolower($model);
					$newModel['name'] = strtolower($model);
				 	return $db->insert("model",$newModel);				 
			}
	}
	
	

$data=$_SESSION['data'];
$amount=$_POST['amount'];
$acct=$_POST['acct'];
$expdate=$_POST['expDate'];
$cvv2=$_POST['cvv2'];

$creditcardtype=$_POST['creditcardtype'];

//include_once 'paypal/processcc.php';
$address_verified = true;

$exists = $db->selectRow("select cid from car where vin='{$data['Vin Number']}'");

if($exists){
$response['status']=0;
$response['error']="Nice Try! Someone else holds <b>Car Keys</b> to this " . $data['Make'] . " " . $data['Model'] . "";
}else{
if($address_verified){

$newCarData['uid'] = $user->id;
$newCarData['vin'] = $data['Vin Number'];
$newCarData['year'] = $data['Model Year'];
$newCarData['model'] = createMakeAndModel($data['Make'],$data['Model']);
$newCarData['body'] = $data['Vehicle Body Type'];
$newCarData['titledate'] = getTitleDate($data['Title Date']);
$newCarData['plate'] = $data['License Plate Number'];
$newCarData['state'] = $data['Owner State'];
$newCardata['regdate'] = getRegisDate($data['Registration Effective']);

//$newCardata['address'] = $data['Owner Street'];
//$newCardata['city'] = $data['Owner City'];
//$newCardata['zipcode'] = $data['Owner ZipCode']; 	
//$newCardata['created'] = $dateObj->mysqlDate();

  $newCarId = $db->insert("car",$newCarData);
  
  $response['status']=1;

}else{

 $response['status']=0;
 $response['error']='Sorry! The Name and Address on your Card DONOT Match this ' . $_SESSION['make'] . " " . $_SESSION['model'];

}

}
echo json_encode($response);


?>