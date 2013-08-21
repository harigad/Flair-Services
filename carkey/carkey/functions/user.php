<?php 	

		if($_POST['id']=="me" || $_POST['id']==$user->id){
			$myProfile=true;
			$userid=$user->id;
		}else{
			$userid=$_POST['id'];		
		}
	
		$thisUser = $db->selectRow("select * from user where id='{$userid}'");	
		
$userObj->status = true;
$userObj->id = $thisUser['id'];
$userObj->name = $thisUser['name'];
$userObj->photo = $thisUser['photo'];
$userObj->photo_big = $thisUser['photo_big'];

$userObj->cars = getCars($userid);
$userObj->checkins = getCheckins($userid);

//$userObj->feed = getFeed($userid);

echo json_encode($userObj);	

	function getCars($userid){
    	global $db;
    	$str = "select car.cid,model.mid,make.logo,model.moid,model.name as model,year,state,owner.prime from owner "; 
    	$str .= "inner join car on owner.cid = car.cid ";   
    	$str .= "inner join model on car.moid=model.moid ";  
    	$str .= "inner join make on model.mid = make.mid "; 
    	$str .= "where owner.uid='{$userid}' order by prime desc";
    	
    	$carsArr = $db->selectRows($str);
    	$cars = array();		
        if (mysql_num_rows($carsArr) > 0) {
            while ($car = mysql_fetch_object($carsArr)) {
            	$newCar = new car($car->cid);
            	$car->shares = $newCar->getShares($userid);
				$car->radios = $newCar->getRadio();
				array_push($cars,$car);
            }        
		} 
    	return $cars;
    }
	
	
	function getCheckins($userid){
		global $db;
		$str = "select distinct user.id, user.photo,user.name,user.photo_big from user 
		inner join checkin checkinA on checkinA.owner = user.id and checkinA.sender = '" . $userid . "' 
		inner join checkin on checkin.sender = user.id and checkin.owner = '" . $userid . "' 
		where user.id != '" . $userid . "'";
		
		$checkinsArr = $db->selectRows($str);
    	$checkins = array();		
        if (mysql_num_rows($checkinsArr) > 0) {
            while ($checkin = mysql_fetch_object($checkinsArr)) {
				array_push($checkins,$checkin);
            }        
		} 
    	return $checkins;
		
		
	}
	
	
	
	
	
		
	?>