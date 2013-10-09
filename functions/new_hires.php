<?php

	$where = "";//user.fbid IS NOT NULL";
	if($_POST['date']){
		$where = " where feed.created < '" . $_POST['date'] . "'";
	}	
	
	$_new_users_sql_obj = $db->selectRows("select distinct max(feed.created) as updated,user.id, user.name,user.photo_big,place.pid,place.name as place,place.city,place.lat,place.lng from role 
	inner join user on role.uid = user.id 
	inner join place on role.pid = place.pid 
	inner join feed on user.id = feed.recipient 
	" . $where . " GROUP BY user.id, user.name, user.photo_big, place.pid, place.name, place.city, place.lat, place.lng
	 order by feed.created desc limit 5");
	
		$_new_users = array();
		while($new_user = mysql_fetch_array($_new_users_sql_obj))
				{							
					array_push($_new_users,$new_user);
				}
				
		echo json_encode($_new_users);
?>