 <?php
 $sid = $_POST['id'];

    $vicinityObj = $db->selectRow("select city,state,lat,lng,zip from place where pid='{$sid}' ");
	if($vicinityObj){		
								$vicinity = $vicinityObj['vicinity'];
                                $vicinityArr = explode(",", $vicinity);
                                $vicinity = $vicinityArr[count($vicinityArr) - 1];
								$city = $vicinityObj['city'];
								$state = $vicinityObj['state'];
	
	}	
	?>
    <div id="trending_canvas" >      
        <div id="trending_canvas_result" >

		  <div id="map_canvas" onclick='showMap("<?php echo $place['lat'] ?>","<?php echo $place['lng'] ?>","<?php echo clean($place['name']) ?>");' style="height:100px;margin-left:0px;margin-right:0px;"></div>
		        <script>
                    $flair.map.showAddress("<?php echo "{$city},{$state}" ?>");
                </script>

		<div style="background-image:url(/images/small_shadow.png);background-repeat:repeat-x;" >
           <?php
                            $sql = "select place.pid,food.fid,user.fbid,user.name as username,user.photo as user_photo,user.id as user_id,sticker.id,food.name as foodname,food.type as foodType, place.name as placename, place.city, sticker.created from sticker ";
                            $sql.=" inner join user on sticker.user=user.id ";
                            $sql.=" inner join place on sticker.noun=place.pid ";
                            $sql.=" inner join food on sticker.verb=food.fid where sticker.status=1 and city ='{$city}' ";
                            $sql.=" order by sticker.created desc limit 10";

                            $stickers = $db->selectRows($sql);
                            $i = 0;
                            while ($sticker = mysql_fetch_object($stickers)) {
                                $i++;
                                $userFirstNameObj = explode(" ", $sticker->username);
                                $userFirstName = $userFirstNameObj[0];
                                $city = $sticker->city;
                               // $vicinityArr = explode(",", $vicinity);
                               // $vicinity = $vicinityArr[count($vicinityArr) - 1];
								
									if($i==mysql_num_rows($stickers)){
										$lastRow=true;
									}else{
										$lastRow=false;
									}
								
                                sticker($sticker->user_photo, $sticker->user_id, $userFirstName, $sticker->pid, $sticker->placename, $sticker->fid, $sticker->foodname, $sticker->foodType, $city, $lastRow);
                            } ?>
							</div>
    </div>
</div>

<?php

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
                                    <a href="#page=food&title=<?php echo strtolower($foodname) ?>&id=<?php echo strtolower($fid); ?>" ><span class='food' style='background:url(/images/icons/<?php echo $foodType; ?>_15.png);background-repeat: no-repeat;background-position:right center;' ><?php echo strtolower($foodname); ?></span></a>
                                    <span style="color:#999;" >@</span>
                                    <a href="#page=place&title=<?php echo strtolower($placename) ?>&id=<?php echo strtolower($pid); ?>" ><span class='place' ><?php echo $placename; ?></span></a>
                                 
                               
                                    <div style="clear:left;" ></div>
                                </div>
<?php } ?>