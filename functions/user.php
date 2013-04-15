<?php 	
		if($_POST['id']=="me" || $_POST['id']==$user->id){
			$myProfile=true;
			$userid=$user->id;
		}else{
			$userid=$_POST['id'];		
		}
	
		$thisUser = $db->selectRow("select * from user where id='{$userid}'");	
		
					$flairs = buildStickers(-1,$userid);
						$userJSON['uid'] = $userid;
						$userJSON['id'] = $userid;
						$userJSON['name'] = $thisUser['name'];
						
					if($myProfile){
					
						  $role=$db->selectRow("Select role.role,place.name,place.pid,lat,lng from role inner join place on role.pid=place.pid where uid={$user->id} limit 1");
						  if($role){
							$place['pid']=$role['pid'];
							$place['name']=$role['name'];
							$place['role']=$role['role'];
							$place['lat']=$act['lat'];
							$place['lng']=$act['lng'];
							$userJSON['place']=$place;					
						  }else{
						    $act=$db->selectRow("select place.pid, place.name, place.vicinity, code,lat,lng from place inner join activation on place.pid=activation.pid where activation.uid={$user->id} and expired=0");						   
						   if($act){
						     $place['pid']=$act['pid'];
							 $place['name']=$act['name'];
							 $place['vicinity']=$act['vicinity'];
							 $place['code']=$act['code'];
							 $place['lat']=$act['lat'];
							 $place['lng']=$act['lng'];
							 $userJSON['place']=$place;	

						   }
						
						}
						
						
					}else{
						  $role=$db->selectRow("Select role.role,place.name,place.pid,lat,lng from role inner join place on role.pid=place.pid where uid={$userid} limit 1");
						  if($role){
							$place['pid']=$role['pid'];
							$place['name']=$role['name'];
							$place['role']=$role['role'];
							$place['lat']=$act['lat'];
							$place['lng']=$act['lng'];
							$userJSON['place']=$place;
							}
					}
					
					$userJSON['photo'] = $thisUser['photo'];
					$userJSON['photo_big'] = $thisUser['photo_big'];
					$userJSON['feed']=$flairs;
					$userJSON['status'] = true;
						//$flair_count= $db->selectRow("select count(id) as flair_count from sticker where user='{$userid}' and status=1");					
						//$place_count= $db->selectRow("select count(id) as flair_count from sticker_temp where user='{$userid}' and status is NULL");
						//$userJSON['flair_count'] = $flair_count["flair_count"];		
						//$userJSON['place_count'] = $place_count["flair_count"] + $flair_count["flair_count"];
										
					echo json_encode($userJSON);
					
					
					function print_sticker($flair) {
				$icon = "{$flair->type}.png";	
				$str = "<div style='vertical-align:top;position:relative;' >";
					$str .= "<a href=\"#page=food&title=" . $flair->name . "&id=" . $flair->fid . "\" ><div class='flair_thumb' style='background-image:url(/images/icons/" . $icon . ");' ></div></a>";
					$str .= "<a href=\"#page=place&title={$flair->placename}&id=" . $flair->pid . "\" >";
						$str .= "<div class='flair_thumb' style='background-color:#fff;color:#999;width:182px;padding:10px;min-height:80px;text-align:left;line-height:1.5em;vertical-align:top;' >";
							$str .= "<div style='font-size:1.2em;' >";					
								$str .= "<span style='color:#666;' >" . $flair->name . "</span>";
								$str .= "<span style='color:#ccc;font-size:0.8em;' ><br>@<br></span>";
								$str .= "<span style='color:#6996F5;' >" . $flair->placename . "</span>";
								//$str .= "<span style='color:#ccc;font-size:0.8em;' ><br>in </span>";
								//$str .= "<span style='font-size:0.8em;' ><i>" . $flair->city . "</i></span>";						
							$str .= "</div>";								
						$str .= "</div>";		
					$str .= "</a>";	
					
				$str .= "</div>";
				echo $str;
					}
		
	?>