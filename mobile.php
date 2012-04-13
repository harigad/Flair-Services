<?php
error_reporting(E_ALL ^ E_NOTICE);

include_once 'core/dateClass.php';
include_once 'core/db.php';
include_once 'core/Browser.php';
include_once 'core/user.php';
$db = new db();
$browser = new browser();
$user = new user();

if (isset($user->id) != true) {
    header("Location: {$user->login()}");
}

?>
<html>
    <head>
    	<title>Flair</title>
    	<link rel="icon" type="image/ico" href="favicon.ico"> 
	
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
        <link type="text/css" href="inc/mobile.css" rel="stylesheet" />    
		<link type="text/css" href="inc/icons.css" rel="stylesheet" />    		
		
			<?php
				if($browser->isMobile()!=true){
					echo '<link type="text/css" href="inc/pc.css" rel="stylesheet" />';
				}			
			?>
		
		
        <script type="text/javascript" src="/inc/js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="inc/tree.js"></script>		
		<script type="text/javascript" src="inc/flair.js"></script>		
		<script type="text/javascript" src="inc/window.js"></script>		
		<script type="text/javascript" src="inc/header.js"></script>		
		<script type="text/javascript" src="inc/go.js"></script>		
		<script type="text/javascript" src="inc/location.js"></script>
		<script type="text/javascript" src="inc/scroll.js"></script>	
		<script type="text/javascript" src="inc/menu.js"></script>
		<script type="text/javascript" src="inc/search.js"></script>
		<script type="text/javascript" src="inc/map.js"></script>
		<script type="text/javascript" src="inc/user.js"></script>
		<script type="text/javascript" src="inc/food.js"></script>
		<script type="text/javascript" src="inc/core.js"></script>
        <script type="text/javascript" src="inc/mobile.js"></script>
		<script type="text/javascript" src="inc/flairThumb.js"></script>
		
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places"></script>
        <style>            
			 .boy{
				background-image:url(/images/icons/boy.png);
			 }
			 .girl{
				background-image:url(/images/icons/girl.png);
			 }
        </style>
    </head>
    <html>
        <body onload="init();" >
            <div id="canvas" >
				<!--------------------------Header---------------------------------------->
                <div class="headerBg" >				
				<div id="header_content" >
                    <div id="header_default" >                    	
						<a onclick="$flair.go.back();" >
							<div id="header_default_left_btn" >
							</div>
						</a>
						
						<div id="logo" ></div>
						
						<div id="search_menu" >
							<div id="header_default_search_btn" >                    
							</div>
							<input maxlength="60"   autocorrect="off" autocapitalize="off" autocomplete="off" id="searchinput" onkeyup="search();" type="text" value="search" class="searchinput" >
							<a onclick="cancel();" >
							<div  class="header_button" style="width:75px;" >
								cancel
							</div>
							</a>
						</div>
						
						<a href="#" >
                        <div class="header_button" id="home_menu" style="display:none;background-image:url(/images/home_icon.png);background-position:center center;background-repeat:no-repeat;display:none;height:20px;width:75px;" >

                        </div>		
						</a>
						
						<a href="#" >
						<div class="header_button" id="page_menu" style="display:none;width:60px;" >
                            <img src="/images/icons/home.jpg" style="display:none;vertical-align:top;position:relative;top:-8px;" >
                        </div>
						</a>
						
						<a href="#" >
						<div class="header_button" id="settings_menu" style="display:none;width:75px;" >
                            <img src="/images/settings2.png" style="vertical-align:top;position:relative;top:-4px;" >
                        </div>
						</a>
						
						
                    </div>
					<div id="header_fullscreen" >
					
					</div>
                </div>
				</div>
				
				

				<!--------------------------Header---------------------------------------->
                <div id="main" >  
				<div id="scroller" >
					<div id="fullScreen"  style="display:inline-block;margin-top:auto;margin-bottom:auto;vertical-align:middle;display:none;" ></div>
				
				
					<!--------------------------page content---------------------------------------->
                    <div id="page_content" class="content" style="display:none;" >
					
					
					</div>
					<!--------------------------page content---------------------------------------->
					
					<!--------------------------search content---------------------------------------->
                    <div class="content" id="search_content" style="display:none;" >
                        <div id="search_content_food" class="tab_canvas" style='display:none;' >
                            <div class="tab selected" >food</div>
                            <div class="tab"  ><a href="#" onclick="searchModeClick('place');" >places</a></div>
                        </div>
                        <div id="search_content_place" class="tab_canvas" style="display:none;" >
                            <div class="tab"  ><a href="#" onclick="searchModeClick('food');" >food</a></div>
                            <div class="tab selected" >places</div>
                        </div>
                        <div id="result" >
                        </div>
                    </div>
					<!--------------------------search content---------------------------------------->		
				</div>
				</div>
			
			<!--------------------------Footer---------------------------------------->
			<div id="menu"  class="menu_container" >			
				<div id="menu_main"  >			
                    
				</div>
				
				<div onclick="$flair.window.cancelFullScreen();" id="menu_cancel" class="menu_menu_container" style="display:none;"  >
					<div id="menu_cancel_cancel" class="menu menu_cancel" >
						cancel
					</div>
				</div>
				
			</div>
			<!--------------------------Footer---------------------------------------->
           
            <div id="fb-root"></div>
            <script>
                window.fbAsyncInit = function() {
                    FB.init({appId: '137205592962704', status: true, cookie: true, xfbml: true});
                }
                $(function(){
                    var e = document.createElement('script');
                    e.async = true;
                    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';

                    document.getElementById('fb-root').appendChild(e);
                }());
            </script>			
			
        </body>
    </html>
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