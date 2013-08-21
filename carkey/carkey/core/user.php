<?php
include_once "facebook.php";

class user {
    function __construct() {
       
         
        $this->appid = "374335169286433";
        $this->secret = "44b40cce2bd2d79f368e7036ee29f376";
      
        // Create our Application instance.
        $this->facebook = new Facebook(array(
                    'appId' => $this->appid,
                    'secret' => $this->secret,
                    'cookie' => true,
                ));


		if (is_null($this->fbid) && isset($_REQUEST['accessToken'])) { 
		  $this->setAccessToken();		
		}


        $this->fbid = $this->facebook->getUser();
        
        if (is_null($this->fbid)) {   
			$this->id=-1;
			$this->fbid=-1;
			$this->loggedin = false;
        } else {   
            $this->init($this->fbid);
			$this->loggedin = true;
        }
    }
	
	    
	function setAccessToken(){
	  $this->facebook->setAccessToken($_REQUEST['accessToken']);	
	}


    function login(){
        return  $this->facebook->getLoginUrl(array('display' => 'popup', 'next' => 'http://flair.me/mobile.php','redirect_uri' => 'http://flair.me/mobile.php','req_perms' => 'publish_stream'));
    }

    function init($fbid) {
        global $db;
        $row = $db->selectRow("select id,name,photo,photo_big from user where fbid='$fbid'");
        if (is_array($row) == false) {
            $me = $this->me();
            $data['fbid'] = $fbid;
            $data['name'] = $me['name'];
            $data['photo'] = $me['pic_square'];
            $data['photo_big'] = $me['pic_big'];
            $id=$db->insert("user", $data);
        } else {        
            $id = $row['id'];
        }
        $this->name = $row['name'];
        $this->photo = $row['photo'];
        $this->photo_big = $row['photo_big'];
        $this->id = $id;
	
		}
		
    
	
	function myFlairs($passedUser){
		global $db;
		
                            $sql = "select place.pid,food.fid,user.fbid,user.name as username,user.photo as user_photo,user.id as user_id,sticker.id,food.name as foodname,food.type as foodType, place.name as placename, place.vicinity, sticker.created from sticker ";
                            $sql.=" inner join user on sticker.user=user.id ";
                            $sql.=" inner join place on sticker.noun=place.pid ";
                            $sql.=" inner join food on sticker.verb=food.fid where sticker.status=1";
							$sql.=" and sticker.user={$passedUser} ";
                            $sql.=" order by sticker.created desc limit 25";

                            $stickers = $db->selectRows($sql);
                            $i = 0;
                            while ($sticker = mysql_fetch_object($stickers)) {
                                $i++;
                                $userFirstNameObj = explode(" ", $sticker->username);
                                $userFirstName = $userFirstNameObj[0];
                                $vicinity = $sticker->vicinity;
                                $vicinityArr = explode(",", $vicinity);
                                $vicinity = $vicinityArr[count($vicinityArr) - 1];
								
									if($i==mysql_num_rows($stickers)){
										$lastRow=true;
									}else{
										$lastRow=false;
									}
								
                                sticker($sticker->user_photo, $sticker->user_id, $userFirstName, $sticker->pid, $sticker->placename, $sticker->fid, $sticker->foodname, $sticker->foodType, $vicinity, $lastRow);
                            }
	}

   
    function loadFriends($force=false) {	
		  if($this->id==-1){
			$this->friendsArr = array();
			$this->friendsStr = "";     
			return;
		  }
	
	
	
	  if(isset($this->friendsArr)!=true || $force==true){
		  $friendsFB = $this->fql("SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = '" . $this->fbid ."') and has_added_app=1");
		  $friendsStr="";
			foreach($friendsFB as $key => $val){
			  $friendsStr=$friendsStr . $val['uid'] . ",";		
			}		
			
			$friendsStr = substr($friendsStr,0,count($friendsStr)-2);
			  $this->friendsArr = $friendsFB;
			  $this->friendsStr = $friendsStr;     
		}
    }


    function printProfile(){
        $this->printMe();
        echo "<div class='date right_sub_header' >Friends</div>";
        echo $this->printFriends();
    }

    function printMe(){
        global $user;
        			 echo "<div  style='vertical-align:top;padding:10px;' >";
						      echo "<div  style='vertical-align:top;'>";		
								echo "<img src='{$this->photo_big}' style='position:relative;float:left;width:150px;vertical-align:top;margin-right:10px;'>";			
								
							
								if(isset($this->placename)){
									echo "<b>{$this->placename}</b>";
									if($this->activePlace!=true){
										echo "<br><span style='color:#aaa;' >Verfication Pending!</span>";								
									}
								}
								
								
                                                                if($user->id==$this->id && isset($this->placename)){
                                                                    echo "<br><span><a onclick='newWorkSearch();' style='font-weight:normal;' >change</a></span>";
                                                                }else{
																	echo "<span style='font-size:1.6em;'><a onclick='newWorkSearch();' >Add My Work Place</a></span>";
																}
								echo "<div style='clear:left;' ></div>"; 
								echo "</div>";													
				 echo "</div>";
    }



    function verification_pending(){

                                        echo "<div style='padding:10px;padding-top:0px;' >";
							  echo "<span style='color:#999;'>Verification Pending!</span><br>";
							  echo "<span>Call <b>(1-866-291-9993)</b> from <b>{$this->placename}</b> and enter your activation codes : \"<b>{$this->code_a}</b>\"";
                        		echo "</div>";

    }


	function deleteActiveAndPending(){
			global $db;
			$updateExisting['status']=2;			   
			$db->update("activation",$updateExisting," user='{$this->id}' and ( status=0 or status=1 ) ");
	}
	
	

    function printFriends(){
        $friends=$this->loadFriends();
        echo "<div id='friends' >";
                foreach($friends as $key=>$value){
                        echo "<img src='{$value['pic_square']}' >";
                }
                echo "<div>";
    }


    function me() {
        $fql = "SELECT name, pic_square,pic_big FROM user WHERE uid = '{$this->fbid}'";
        $result = $this->fql($fql);
        $this->me = $result[0];
        return $this->me;
    }

    function fql($fql) {
        $param = array(
            'method' => 'fql.query',
            'query' => $fql,
            'callback' => '');

        return $this->facebook->api($param);
    }

    function isEmail($email) {
        global $db;
        if (preg_match('|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$|i', $email)) {
            $row = $db->selectRow("select email from users where email='$email'");
            if (is_array($row)) {
                $this->error = "Email Already Exists!";
                return false;
            } else {
                return true;
            }
        } else {
            $this->error = "Invalid Email!";
            return false;
        }
    }
	
	function get_notices(){
		global $db;
		$recieved = $db->selectRows("select car.cid,model.name as model ,make.name as make,checkin_request.id as noticeid,user.id as uid,user.name,user.photo,user.photo_big,checkin_request.created from car
		inner join owner on car.cid = owner.cid and owner.uid = '{$this->id}' 
		inner join model on model.moid = car.moid 
		inner join make on make.mid = model.mid 
		inner join checkin_request on checkin_request.plate = car.plate and checkin_request.state = car.state and checkin_request.status=0 
		inner join user on checkin_request.uid = user.id 
		where checkin_request.status = 0");
	
		$recs = array();
		if (mysql_num_rows($recieved) > 0) {
            while ($feedObj = mysql_fetch_object($recieved)) {
            	array_push($recs,$feedObj);
            }        
		}
	
		$approved = $db->selectRows("select checkin.id as noticeid,user.id,user.name,user.photo,user.photo_big,car.cid,model.name as model,make.name as make from checkin 
		inner join user on checkin.owner = user.id  
		inner join car on checkin.cid = car.cid 
		inner join model on model.moid = car.moid 
		inner join make on make.mid = model.mid 
		where checkin.sender = '{$this->id}' and checkin.seen = 0");
	
		$approvs = array();
		if (mysql_num_rows($approved) > 0) {
            while ($feedObj = mysql_fetch_object($approved)) {
            	array_push($approvs,$feedObj);
            }        
		}
	
		$data->recieved = $recs;
		$data->approved = $approvs;
		$data->pending =  $this->get_pending_checkins();
	
		return $data;
	}
	
	function get_pending_checkins(){
		global $db;	
		$feed = array();
		$rows = $db->selectRows("select * from checkin_request where uid='{$this->id}'");
			if (mysql_num_rows($rows) > 0) {
            	while ($feedObj = mysql_fetch_object($rows)) {
        		array_push($feed,$feedObj);
            }        
		}
		return $feed;
	}
	

}

?>
