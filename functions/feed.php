<?php
		
		
		
		date_default_timezone_set('America/Los_Angeles');

$dateString = 'Apr 29 2010';
$dateTime = datetime::createfromformat('M d Y',$dateString);

echo $dateTime->format('d-M-Y'); ?>



		$sql = "select typeid,feed.created,user.id as uid, user.name as name, user.photo as photo,
        user2.id as oid, user2.name as oname, user2.photo as ophoto, 
        make.mid, make.name as make,make.logo,
        model.moid,model.name as model
        
        from feed 
        inner join user on feed.uid = user.id 
        left outer join user as user2 on feed.oid = user.id 
        left outer join car on feed.cid = car.cid 
        left outer join model on model.moid = car.moid 
        left outer join make on model.mid = make.mid order by created desc ";

		$feedArr = $db->selectRows($sql);
    	$feed = array();		
        if (mysql_num_rows($feedArr) > 0) {
            while ($feedArrObj = mysql_fetch_object($feedArr)) {
           		array_push($feed,$feedArrObj);
            }        
		} 
		echo json_encode($feed);
?>