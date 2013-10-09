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
		$moId = $db->selectRow("select moid from model where mid={$mId} and code='" . strtolower($model) . "' limit 1");
			if($moId){
					return $moId['moid'];
			}else{
					$newModel['mid'] = $mId;
					$newModel['code'] = strtolower($model);
					$newModel['name'] = strtolower($model);
				 	return $db->insert("model",$newModel);				 
			}
	}
	

$data=$_SESSION['data'];

$firstname = $data['Owner Name'];
$lastname = " ";
$street = $data['Owner Street'];
$city = $data['Owner City'];
$state = $data['Owner State']; 

$acct=$_POST['acct'];
$year=$_POST['year'];
$expdate=$_POST['expDate'];
$cvv2=$_POST['cvv2'];


$exists = $db->selectRow("select car.cid,model.name as modelName,make.name as makeName from car 
	inner join owner on owner.cid = car.cid
	inner join model on car.moid = model.moid 
	inner join make on model.mid = make.mid 
	where car.vin='{" . $data['Vin Number'] . "'");


if($exists){	
	$response['status']=0;
	$response['error']="Nice Try! Someone else holds <b>Car Keys</b> to this " . $exists['makeName'] . " " . $exists['modelName'] . "";
}else{

include_once 'paypal/processcc.php';

if($address_verified){
	$newCarData['vin'] = $data['Vin Number'];
	$newCarData['year'] = $data['Model Year'];
	$newCarData['moid'] = createMakeAndModel($data['Make'],$data['Model']);
	$newCarData['body'] = $data['Vehicle Body Type'];
	
	$thisCarIdObj  = $db->selectRow("select cid from car where vin='" . $data['Vin Number'] . "'");
   
   if($thisCarIdObj){ 
   		$newCarId = $thisCarIdObj['cid'];
   } else{
  		$newCardata['created'] = $dateObj->mysqlDate();
  		$newCarId = $db->insert("car",$newCarData);
   }

  $ownerExists = $db->selectRow("select uid from owner where cid='" . $newCarId  . "'");	
  
  if($ownerExists){
  	$response['status']=0;
 	$response['error']='Sorry! This car is already registered under someone else';
   }else{
   	
  	$ownerData['uid'] = $user->id;
	$ownerData['cid'] = $newCarId;
	$ownerData['prime'] = 1;
	
	$ownerData['plate'] = $data['License Plate Number'];
	$ownerData['state'] = $data['Owner State'];
	$ownerData['titledate'] = getTitleDate($data['Title Date']);
	$ownerData['regdate'] = getRegisDate($data['Registration Effective']);

	$ownerData['lat'] = "";
	$ownerData['lng'] = "";

	//$newCardata['address'] = $data['Owner Street'];
	//$newCardata['city'] = $data['Owner City'];
	//$newCardata['zipcode'] = $data['Owner ZipCode'];
	
	$db->insert("owner",$ownerData);
    $response['status']=1;
   }//end of ownerExists

}else{
 
 $response['status']=0;
 $response['error']='Sorry! The address on your card does not match with your ' . $data['Make'];

}//end of address verified

}//end of exists = false

echo json_encode($response);


?>