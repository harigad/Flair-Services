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

				$place['stickers'] = buildStickers($pid);						
			
				$foodsData = $db->selectRows("select distinct food.fid as fid,food.name,food.type from food inner join sticker on food.fid = sticker.verb and sticker.noun={$pid}");
				$foods = array();			
				while($food = mysql_fetch_array($foodsData)) {							
					array_push($foods,$food);
				}

				$foodsData = $db->selectRows("select distinct verb as fid,verbname as name,'' as type from sticker_temp where noun={$pid} and verb=-1 and status is NULL");
				while($food = mysql_fetch_array($foodsData)) {							
					array_push($foods,$food);
				}	
				
				$place['foods'] = $foods;					

				$castData = $db->selectRows("select distinct user.id as uid,user.name as name,user.photo as photo,user.photo_big as photo_big, role.role as role_name from user inner join role on user.id=role.uid where role.pid={$pid}");
				
				$cast = array();	
				
				while($castMember = mysql_fetch_array($castData)) {							
					array_push($cast,$castMember);
				}

				$castData = $db->selectRows("select distinct '-1'  as uid,recipientname as name, '' as photo,'' as photo_big,'' as role from sticker_temp where noun={$pid} and recipient=-1 and status is NULL");
				while($castMember = mysql_fetch_array($castData)) {						
					array_push($cast,$castMember);
				}

			  $place['cast'] = $cast;				
				
			  return $place;
				
			}
			
			
			function buildStickers($pid,$userid=0,$friends="",$local=false) {
			
				global $db,$user;
				
				$extraFields="";
				$orderFields="";
			
				$stickers = array();	
				
				if($pid==-1){
				  $whereClause = " sticker_temp.user={$userid} ";				
				}else if($friends != ""){
				  $whereClause = " user.fbid in ($friends) ";
				}else if($local){
				   $extraFields = ",( ABS({$_lat} - place.lat) + ABS({$_lng} - place.lng) ) as dist "; 
				   $whereClause = " 1=1 ";
				   $orderFields = " dist desc,";		
				}else {				  
				  $user->loadFriends();				  
				  $whereClause = " sticker_temp.noun={$pid} and ( sticker_temp.user='{$user->id}' or user.fbid in ({$user->friendsStr}) )  ";				
				}
				
			
							$stickersDataTempArray = $db->selectRows("select 
				sticker_temp.id as sid , sticker_temp.user as user,icon.id as iconid, icon.url as icon,
		
				place.pid as pid, place.name as placename,
				place.city as city,
				user.name as username,user.photo as userphoto,user.photo_big as userphotobig,
				user_r.id as recipient,user_r.name as recipientname,
				sticker_temp.recipientname as recipientname_temp,sticker_temp.verbname as verbname_temp{$extraFields} 
				from sticker_temp
				left outer join place on sticker_temp.noun = place.pid 
			
				left outer join icon on sticker_temp.icon = icon.id 
				left outer join user as user on sticker_temp.user = user.id
				left outer join user as user_r on sticker_temp.recipient = user_r.id
				
				where " . $whereClause . " order by {$orderFields} sticker_temp.created desc");
			//echo $whereClause;
			
				while($stickerDataTemp = mysql_fetch_array($stickersDataTempArray))
				  {							
			
				
					$stickerDataTemp['approved']=false;
					if(!isset($stickerDataTemp['fid'])) {
						$stickerDataTemp['name']=$stickerDataTemp['verbname_temp'];
					}
					if(!isset($stickerDataTemp['recipient'])) {
						$stickerDataTemp['recipientname']=$stickerDataTemp['recipientname_temp'];
					}
					
					
					array_push($stickers,$stickerDataTemp);
				}
		
			
			    if($pid==-1){
				  $whereClause = " sticker.user={$userid} ";				
				}else if($friends!=""){
				  $whereClause = " user.fbid in ($friends) ";
				}else if($local){
				   $whereClause = " 1=1 ";				
				}else{
				  $whereClause = " sticker.noun={$pid} ";				
				}
			
			
			
				$stickersData = $db->selectRows("select 
				sticker.id as sid , sticker.user as user,
				place.pid as pid, place.name as placename,icon.id as iconid, icon.url as icon,
				place.city as city,
				
				user.name as username,user.photo as userphoto,user.photo_big as userphotobig,			
				from sticker 				
				inner join place on sticker.noun = place.pid 
				inner join user on sticker.user = user.id
				left outer join icon on sticker.icon = icon.id  
				where " . $whereClause . " and sticker.status=1 order by sticker.created desc limit 20");
				while($sticker = mysql_fetch_array($stickersData)) {							
					array_push($stickers,$sticker);
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