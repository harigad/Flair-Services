<?php

$user->loadFriends();

$friendsStr = $user->friendsStr;
$cars = array();
$carsArr = $db->selectRows("select user.fbid,user.name as fname,car.cid, model.name as carname,make.logo from user 
inner join owner on user.id = owner.uid 
inner join car on owner.cid = car.cid  
inner join model on car.moid = model.moid 
inner join make on model.mid = make.mid 
where user.fbid in ('{$friendsStr}')");

  while ($car = mysql_fetch_object($carsArr)) {
				array_push($cars,$car);
  }        

$data->cars = $cars;
$data->friendsStr = $user->friendsStr;

echo json_encode($data);

?>