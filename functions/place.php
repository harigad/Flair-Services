<?php
function buildPlace($pid,$name,$lat,$lng,$phone=null,$city=null){
	global $db,$user;

			$isAdmin = $db->selectRow("select * from role where role=1 and pid='{$pid}' and uid='{$user->id}'");
			
			$place['id'] = $pid;
			$place['name'] = $name;
			$place['city'] = $city;
			$place['lat'] = $lat;
			$place['lng'] = $lng;
			$place['phone'] = $phone;			
			$place['session'] = $_SESSION['lat'] . $_SESSION['lng'];			
			$place['post'] = $_POST['lat'] . $_POST['lng'];

			$place['feed'] = buildStickers($pid);						
			
						
			//CAST-----------------------------------------------------------------------------
				$cast = array();
		
				$castData = $db->selectRows("select user.id as uid,
				user.name as name,
				user.photo as photo,
				user.photo_big as photo_big,
				role.role as role_id 
				from user 
				left outer join role on user.id=role.uid 
				where role.pid = '{$pid}' order by name");
				while($castMember = mysql_fetch_array($castData)) {							
					array_push($cast,$castMember);
				}

			  	$place['cast'] = $cast;				
			//---------------------------------------------------------------------------------	
			  return $place;
				
			}
			
			function buildStickers($pid,$userid=0,$friends="",$local=false,$filter=false) {
			
				global $db,$user;
			//	$db->debug = true;
				
				$extraFields="";
				$orderFields="";
			
				$stickers = array();	
				
				if($userid){
				  $whereClause = " ((feed.user={$userid} or feed.recipient = {$userid}) and (user_r.approved = true or feed.user = '{$user->id}') ) ";				
				}else if($friends != ""){
				  $whereClause = " user.fbid in ($friends) ";
				}else if($local){
				   $whereClause = " user_r.approved = true";
				   if($filter){
				   	$whereClause = $whereClause . $filter;
				   }
					
				}else if($pid){				  
				  //$user->loadFriends();				  
				  $whereClause = "( feed.place={$pid}  and (user_r.approved = true or feed.user = '{$user->id}') )";				
				}
				
				if($_POST['date']){
					$whereClause = $whereClause . " and updated < '" . $_POST['date'] . "' ";
				}	
	
	//echo "-------->" . $whereClause . "----";return;
			//$db->debug = true;
			
				$whereClauseNew = "";
				if($pid==-1){
				  $whereClauseNew = " role.uid={$userid}  ";				
				}else if($friends != ""){
				  $whereClauseNew = " user.fbid in ($friends) ";
				}else if($local){
				   $whereClauseNew = " 1=1 ";
				   if($filter){
				   	$whereClauseNew = $whereClause . $filter;
				   }
				  // $orderFields = " dist desc,";		
				}else {				  
				  $whereClauseNew = " role.pid={$pid} ";				
				}
				
				if($_POST['date']){
					$whereClauseNew = $whereClauseNew . " and updated < '" . $_POST['date'] . "' ";
				}	
			
				$stickersDataTempArray = $db->selectRows("select 
				feed.fid,
				feed.user as uid,
				user.name as name,
				user.photo as photo,
				user.photo_big as photo_big,
						
				user_r.id as recipient,
				user_r.name	as recipientname,
				user_r.photo as recipient_photo,
				user_r.photo_big as recipient_photo_big,
				user_r.approved as approved,
				
				place.pid as pid,
				place.name as placename,
				place.city as city,
				place.lat as lat,
				place.lng as lng,
				place.vicinity as vicinity,
                datediff(CURDATE(), feed.created) AS days 
				
				
				from feed
				left outer join place on feed.place = place.pid 
				left outer join user as user on feed.user = user.id
				left outer join user as user_r on feed.recipient = user_r.id
				
				where " . $whereClause . " order by feed.created desc limit 10 ");
		
				while($stickerDataTemp = mysql_fetch_array($stickersDataTempArray))
				  {							
					$stickerDataTemp['likes'] = json_decode($stickerDataTemp['likes']);
					array_push($stickers,$stickerDataTemp);
				}
				
				
				
			
				
				return $stickers;	
			}
			
			
			
			function updatePlace($pid){
			global $db;
			$place=$db->selectRow("select * from place where pid='{$pid}'");
	        if ($place['address'] == "" || isset($place['address']) == false || isset($place['phone']) == false) {

                $url = "https://maps.googleapis.com/maps/api/place/details/json?";
                $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
                $par.= "&sensor=true";
                $par.= "&reference={$place['gref']}";
                $results = file_get_contents("{$url}{$par}");
                $r = json_decode($results);
                $updateData['phone'] = $r->result->formatted_phone_number;
                $updateData['address'] = $r->result->formatted_address;
				
				$address_components = $r->result->address_components;
				
				foreach($address_components  as $ad => $adc){
					
					switch ($adc->types[0]) {
						case "street_number":
							$updateData['streetnum'] = $adc->long_name;
						case "route":
							$updateData['streetname'] = $adc->long_name;
						case "locality":
							$updateData['city'] = $adc->long_name;
						case "administrative_area_level_1":
							$updateData['state'] = $adc->long_name;
						case "postal_code":
							$updateData['zip'] = $adc->long_name;						
					}
				}
				
                $db->update("place",$updateData,"pid = '{$pid}'");
                $phone = $r->result->formatted_phone_number;
                $address = $r->result->formatted_address;
			} 			
		}	
			?>