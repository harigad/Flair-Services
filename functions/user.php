<?php 	
		if($_POST['id']=="me"){
			$myProfile=true;
			$userid=$user->id;		
		}else{
			$userid=$_POST['id'];		
		}
	
		$thisUser = $db->selectRow("select * from user where id='{$userid}'");
	
		
					$flairs = buildStickers(-1,$userid);
						
						
					if($myProfile){
						$userJSON['id'] = "me";
					}else{
						$userJSON['id'] = $userid;
					}
					
					$userJSON['photo_big'] = $thisUser['photo_big'];
					$userJSON['flairs']=$flairs;
					
						$flair_count= $db->selectRow("select count(id) as flair_count from sticker where user='{$userid}' and status=1");					
						$place_count= $db->selectRow("select count(id) as flair_count from sticker_temp where user='{$userid}' and status is NULL");
						
					$userJSON['flair_count'] = $flair_count["flair_count"];		
					$userJSON['place_count'] = $place_count["flair_count"] + $flair_count["flair_count"];				
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