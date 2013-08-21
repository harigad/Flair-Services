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
$street=$_POST['street'];
$year=$_POST['year'];

$address_verified = true;

$exists = $db->selectRow("select car.cid,model.name as modelName,make.name as makeName from car 
	inner join owner on owner.cid = car.cid
	inner join model on car.moid = model.moid 
	inner join make on model.mid = make.mid 
	where car.vin='{" . $data['Vin Number'] . "'");

if($exists){
	$response['status']=0;
	$response['error']="Nice Try! Someone else holds <b>Car Keys</b> to this " . $data['Make'] . " " . $data['Model'] . "";
}else{
if($address_verified){

$newCarData['uid'] = $user->id;
$newCarData['vin'] = $data['Vin Number'];
$newCarData['year'] = $data['Model Year'];
$newCarData['moid'] = createMakeAndModel($data['Make'],$data['Model']);
$newCarData['body'] = $data['Vehicle Body Type'];
$newCarData['titledate'] = getTitleDate($data['Title Date']);
$newCarData['plate'] = $data['License Plate Number'];
$newCarData['state'] = $data['Owner State'];
$newCardata['regdate'] = getRegisDate($data['Registration Effective']);

//$newCardata['address'] = $data['Owner Street'];
//$newCardata['city'] = $data['Owner City'];
//$newCardata['zipcode'] = $data['Owner ZipCode'];

  $thisCarIdObj  = $db->selectRow("select cid from car where vin='" . $data['Vin Number'] . "'");
   
   if($thisCarIdObj){ 
   		$newCarId = $thisCarIdObj['cid'];
   		$db->update("car",$newCardata,"cid = '" . $newCarId . "'");
   } else{
  		$newCardata['created'] = $dateObj->mysqlDate();
  		$newCarId = $db->insert("car",$newCarData);
   }

  $ownerExists = $db->selectRow("select uid from owner where cid='" . $newCarId  . "'");	
  
  if($ownerExists){
  	$response['status']=0;
 	$response['error']='Sorry! The Name and Address you entered DONOT match';
   }else{
  	$ownerData['uid'] = $user->id;
	$ownerData['cid'] = $newCarId;
	$ownerData['prime'] = 1;
	$db->insert("owner",$ownerData);
    $response['status']=1;
   }//end of ownerExists

}else{

 $response['status']=0;
 $response['error']='Sorry! The Name and Address on your Card DONOT Match this ' . $_SESSION['make'] . " " . $_SESSION['model'];

}//end of $address_verified

}//end of exists
echo json_encode($response);


?>