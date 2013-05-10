<?php

  $notifications = array();	

  //activation
  $activation = $db->selectRow("select activation.id,activation.code,place.pid,place.name from activation inner join place on activation.pid=place.pid  where uid={$user->id} and expired=0 ");
 
 if($activation){
  
     $activationData['title'] = "Verification Pending for " . $activation['name'];
	 $activationData['uri'] = "#page=role&title=Verification&id=" . $activation['pid'];
  
  }

   array_push($notifications,$activationData);
   echo json_encode($notifications);
   
?>