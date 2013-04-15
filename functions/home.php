 <?php   if(true) { ?>
 <div style='padding-top:10px;text-align:center;' >
 
			<a href="#page=type&title=boys and girls&id=boy" ><div style='background-image:url(/images/icons/boy.png);' class='flair_thumb' ></div></a>			
			<a href="#page=type&title=friends&id=friends" ><div style='background-image:url(/images/icons/friends.png);'  class='flair_thumb' ></div></a>
			<a href="#page=type&title=me&id=me" ><div class='flair_thumb' ></div></a>
			
			<a href="#page=type&title=boys and girls&id=boy" ><div style='background-image:url(/images/icons/boy.png);' class='flair_thumb' ></div></a>			
			<a href="#page=type&title=coffee&id=coffee" ><div style='background-image:url(/images/icons/coffee.png);'  class='flair_thumb' ></div></a>				
			<a href="#page=type&title=dessert&id=dessert" ><div style='background-image:url(/images/icons/dessert.png);'  class='flair_thumb' ></div></a>
			
			<a href="#page=type&title=veggies&id=veggies" ><div  style='background-image:url(/images/icons/veggies.png);'  class='flair_thumb' ></div></a>			
			<a href="#page=type&title=seafood&id=seafood" ><div  style='background-image:url(/images/icons/seafood.png);' class='flair_thumb' ></div></a>
			<a href="#page=type&title=meat&id=meat" ><div style='background-image:url(/images/icons/meat.png);' class='flair_thumb' ></div></a>
			
			<a href="#page=type&title=veggie&id=veggie" ><div  style='background-image:url(/images/icons/veggie.png);'  class='flair_thumb' ></div></a>
			<a href="#page=type&title=meat&id=meat" ><div style='background-image:url(/images/icons/meat.png);' class='flair_thumb' ></div></a>
			<a href="#page=type&title=seafood&id=seafood" ><div  style='background-image:url(/images/icons/seafoods.png);' class='flair_thumb' ></div></a>
			
 </div>
 <?php  } 
 if(false) { ?>
<div class='tab_canvas' style='display:none;'  >
 	<div class='tab selected' >near me</div>
	<div class='tab' style='background-image:url(/images/settings.png);background-repeat:no-repeat;padding-left:35px;background-position:left center;' >friends</div>
 </div> 
 <div id="default_content_nearme" >           
						
                            <?php
                            $sql = "select place.pid,food.fid,user.fbid,user.name as username,user.photo as user_photo,user.id as user_id,sticker.id,food.name as foodname,food.type as foodType, place.name as placename, place.city, sticker.created from sticker ";
                            $sql.=" inner join user on sticker.user=user.id ";
                            $sql.=" inner join place on sticker.noun=place.pid ";
                            $sql.=" inner join food on sticker.verb=food.fid and food.status=1 where sticker.status=1";
                            
							$sql.=" order by sticker.created desc limit 25";

                            $stickers = $db->selectRows($sql);
                            $i = 0;
                            while ($sticker = mysql_fetch_object($stickers)) {
                                $i++;
                                $userFirstNameObj = explode(" ", $sticker->username);
                                $userFirstName = $userFirstNameObj[0];
                                $vicinity = $sticker->city;

                               
									if($i==mysql_num_rows($stickers)){
										$lastRow=true;
									}else{
										$lastRow=false;
									}
								
                                sticker($sticker->user_photo, $sticker->user_id, $userFirstName, $sticker->pid, $sticker->placename, $sticker->fid, $sticker->foodname, $sticker->foodType, $vicinity, $lastRow);
                            } ?>

                    </div>

<?php } 
            function sticker($user_photo, $user_id, $user_name, $pid, $placename, $fid, $foodname,$foodType, $vicinity, $lastRow=false) { 
							$style='';
							if($lastRow){
									$style='style="border-bottom:0px;"';							
							}
							
							?>
                                 <div class="text"  <?php echo $style; ?> >							 
								 
                                   <a href="#page=user&title=<?php echo $user_name; ?>&id=<?php echo $user_id ?>" >
								   <div class='user_thumb_image' >
										<img src="<?php echo $user_photo ?>" style="float:left;vertical-align:top;">
								   </div>
            <span style="color:#999;font-weight:bold;" ><?php echo $user_name ?></span></a><br>
                                    <a href="#page=food&title=<?php echo strtolower($foodname) ?>&id=<?php echo strtolower($fid); ?>" >
                                    	<span class='food' style='background-image:url(/images/icons/<?php echo $foodType; ?>_15.png);background-repeat: no-repeat;' ><?php echo strtolower($foodname); ?></span>
                                    </a>
                                    <span style="color:#999;" >@</span>
                                    <a href="#page=place&title=<?php echo strtolower($placename) ?>&id=<?php echo strtolower($pid); ?>" ><span class='place' ><?php echo $placename; ?></span></a>
                                     <span style="color:#999;" >in</span> <a href="#page=city&title=<?php echo $vicinity; ?>&id=<?php echo $pid ?>" ><span class="city"><?php echo $vicinity; ?></span></a>
                               		
                                    <div style="clear:left;" ></div>
                                </div>
<?php } ?>