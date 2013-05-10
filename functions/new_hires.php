<?php
		
	$_new_users_sql_obj = $db->selectRows("select user.id, user.name,user.photo_big,place.pid,place.name as place,place.city,place.lat,place.lng from role 
	inner join user on role.uid = user.id 
	inner join place on role.pid = place.pid 
	order by role.created desc limit 25 
	");
	
		$_new_users = array();
		while($new_user = mysql_fetch_array($_new_users_sql_obj))
				{							
					array_push($_new_users,$new_user);
				}
				
		echo json_encode($_new_users);
?>