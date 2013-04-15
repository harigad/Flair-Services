<?php
function buildPlace($pid,$name,$lat,$lng,$phone=null,$city=null){
	global $db,$user;
			
			$place['id'] = $pid;
			$place['name'] = $name;
			$place['city'] = $city;
			$place['lat'] = $lat;
			$place['lng'] = $lng;
			$place['phone'] = $phone;			
			$place['session'] = $_SESSION['lat'] . $_SESSION['lng'];			
			$place['post'] = $_POST['lat'] . $_POST['lng'];

			$place['feed'] = buildStickers($pid);						
			
			//FOOD ----------------------------------------------------------------------------
				$foods = array();
				$foodsData = $db->selectRows("select distinct food.fid,food.name from food 
				inner join feed on food.fid=feed.food where feed.place={$pid}");
				while($food = mysql_fetch_array($foodsData)) {							
					array_push($foods,$food);
				}				
				$place['foods'] = $foods;					
			//CAST-----------------------------------------------------------------------------
				$cast = array();
				$castData = $db->selectRows("select distinct user.id as uid,
				user.name as name,
				user.photo as photo,
				user.photo_big as photo_big, 
				role.role as role from user 
				left outer join role on user.id=role.uid where role.pid = '{$pid}'");
				while($castMember = mysql_fetch_array($castData)) {							
					array_push($cast,$castMember);
				}

			  	$place['cast'] = $cast;				
			//---------------------------------------------------------------------------------	
			  return $place;
				
			}
			
			
			function buildStickers($pid,$userid=0,$friends="",$local=false) {
			
				global $db,$user;
				
				$extraFields="";
				$orderFields="";
			
				$stickers = array();	
				
				if($pid==-1){
				  $whereClause = " feed.user={$userid} or feed.recipient ={$userid} ";				
				}else if($friends != ""){
				  $whereClause = " user.fbid in ($friends) ";
				}else if($local){
				   $extraFields = ",( ABS({$_lat} - place.lat) + ABS({$_lng} - place.lng) ) as dist "; 
				   $whereClause = " 1=1 ";
				   $orderFields = " dist desc,";		
				}else {				  
				  $user->loadFriends();				  
				  $whereClause = " feed.place={$pid} ";				
				}
			
				$stickersDataTempArray = $db->selectRows("select 
				feed.fid, '' as feed_photo,
				feed.user as uid,
				user.name as name,
				user.photo as photo,
				user.photo_big as photo_big,				
				likes.type as isLiked, 
				feed.flair,
				feed.adjective,
				food.name as food,
				user_r.id as recipient,
				user_r.name	as recipientname,
				user_r.photo_big as recipient_photo_big,
	
				feed.likes,
	
				place.pid as pid,
				place.name as placename,
				place.city as city,
				place.lat as lat,
				place.lng as lng,
				place.vicinity as vicinity			
				
				{$extraFields} 
				
				from feed
				left outer join place on feed.place = place.pid 
				left outer join food on feed.food = food.fid 
				left outer join user as user on feed.user = user.id  
				left outer join user as user_r on feed.recipient = user_r.id 
				left outer join likes on feed.fid = likes.target_id and likes.uid = '{$user->id}' 
				
				where " . $whereClause . " order by {$orderFields} feed.created desc limit 10");
			
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