<?php
session_start();
include_once 'core/dateClass.php';
include_once 'core/db.php';
include_once 'core/Browser.php';
include_once 'core/user.php';
$db = new db();
$browser = new browser();
$user = new user();

?>

 	<div id="default_content_nearme" >           
						<div class="tab_canvas" >
                            <div class="tab selected" >
                                near me
                            </div>
                            <div class="tab" >
                                <a href="#" onclick="switchTab('friends');">all friends</a>
                            </div>
                        </div>
 


                            <?php
                            $sql = "select place.pid,food.fid,user.fbid,user.name as username,user.photo as user_photo,user.id as user_id,sticker.id,food.name as foodname,food.type as foodType, place.name as placename, place.city, sticker.created from sticker ";
                            $sql.=" inner join user on sticker.user=user.id ";
                            $sql.=" inner join place on sticker.noun=place.pid ";
                            $sql.=" inner join food on sticker.verb=food.fid where sticker.status=1";
                            
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

<?php
            function sticker($user_photo, $user_id, $user_name, $pid, $placename, $fid, $foodname,$foodType, $vicinity, $lastRow=false) { 
							$style='';
							if($lastRow){
									$style='style="border-bottom:0px;"';							
							}
							
							?>
                                 <div class="text"  <?php echo $style; ?> >
                                   <a href="#page=user&title=<?php echo $user_name; ?>&id=<?php echo $user_id ?>" ><img src="<?php echo $user_photo ?>" style="float:left;vertical-align:top;">
            <span style="color:#999;font-weight:bold;" ><?php echo $user_name ?></span></a><br>
                                    <a href="#page=food&title=<?php echo strtolower($foodname) ?>&id=<?php echo strtolower($fid); ?>" ><span class='food <?php echo $foodType ?>' ><?php echo strtolower($foodname); ?></span></a>
                                    <span style="color:#999;" >@</span>
                                    <a href="#page=place&title=<?php echo strtolower($placename) ?>&id=<?php echo strtolower($pid); ?>" ><span class='place' ><?php echo $placename; ?></span></a>
                                     <span style="color:#999;" >in</span> <a href="#page=city&title=<?php echo $vicinity; ?>&id=<?php echo $pid ?>" ><span class="city"><?php echo $vicinity; ?></span></a>
                               
                                    <div style="clear:left;" ></div>
                                </div>
<?php } ?>