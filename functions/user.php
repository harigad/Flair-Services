<?php 	

		if($global_user_id){
			$userid = $global_user_id;
		}else if($_POST['id']=="me" || $_POST['id']==$user->id){
			$myProfile=true;
			$userid=$user->id;
		}else{
			$userid=$_POST['id'];		
		}
		
		$thisUser = $db->selectRow("select * from user where id='{$userid}'");	
		$photo = $thisUser["photo"];
		$photo_big = $thisUser["photo_big"];
		
	    $fql = "SELECT name, pic_square,pic_big FROM user WHERE uid = '" . $thisUser['fbid'] ."'";
        $result = $user->fql($fql);
        $update_user_photos["photo"] = $result[0]["pic_square"];
		$update_user_photos["photo_big"] = $result[0]["pic_big"];
		if($result){
			if($result[0]){
				if(isset($result[0]["pic_square"])){
					$photo = $update_user_photos["photo"];
					$photo_big = $update_user_photos["photo_big"];
					$db->update("user",$update_user_photos,"fbid='" . $thisUser['fbid'] . "'");
				}
			}
		}
						$userJSON['uid'] = $userid;
						$userJSON['id'] = $userid;
						$userJSON['name'] = $thisUser['name'];
						$userJSON['photo'] = $photo;
					    $userJSON['photo_big'] = $photo_big;
					    
					      if($userid == $user->id){
						  $roles=$db->selectRows("Select role.role,place.name as name,place.city,place.vicinity,place.pid,lat,lng from role inner join place on role.pid=place.pid where uid={$user->id}");
						  $my_places = array();
						  while($role = mysql_fetch_array($roles)) {							
							array_push($my_places,$role);
						  }
						    $userJSON['places']=$my_places;	
						  }	
							$userJSON['feed']=buildStickers(null,$userid);
					
						 echo json_encode($userJSON);
					
	
	function loadFlairs($userid){
	global $db;
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
				
				place.pid as pid,
				place.name as placename,
				place.city as city,
				place.lat as lat,
				place.lng as lng,
				place.vicinity as vicinity 
				
				from feed
				left outer join place on feed.place = place.pid 
				left outer join user as user on feed.user = user.id
				left outer join user as user_r on feed.recipient = user_r.id
				
				where feed.user={$userid} or feed.recipient ={$userid} limit 20");
				$stickers = array();
				
				while($stickerDataTemp = mysql_fetch_array($stickersDataTempArray))
				  {							
					array_push($stickers,$stickerDataTemp);
				}
								
				return $stickers;
	}
	
	
	
	
	function loadFriends($fbid){
		  global $db;global $user;
		  $friendsArr = array();	
		  $friendsFB = $user->fql("SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = '" . $fbid ."') and has_added_app=1");
		  $friendsStr="";
		  foreach($friendsFB as $key => $val){
			 $friendsStr=$friendsStr . $val['uid'] . ",";		
		  }		
		  $friendsStr = substr($friendsStr,0,count($friendsStr)-2);
		  $sqlCast = "select 
			    user.id as uid,
				user.name as name,
				user.photo as photo,
				user.photo_big as photo_big,
				
				role.created as updated,
				role.role as role_id,
				1 as isCastData,
				place.pid as pid,
				place.name as placename,
				place.city as city,
				place.lat as lat,
				place.lng as lng,
				place.vicinity as vicinity 		
				from role
				inner join user on role.uid = user.id 
				inner join place on role.pid = place.pid
				where user.fbid in ({$friendsStr}) ";
				$friends = $db->selectRows($sqlCast);
				while($friend = mysql_fetch_array($friends))
				  {							
					array_push($friendsArr,$friend);
				  }
				return $friendsArr;
	}
?>