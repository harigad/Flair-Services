<?php
include_once "facebook.php";

class user {
    function __construct() {
       
        $this->appid = "201613399910723";
        $this->secret = "089cd274d97010646439dd8af891b948";

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
        if (!$this->fbid) {
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


    function login($url){
        return  $this->facebook->getLoginUrl(array('display' => 'popup', 'next' => $url,'redirect_uri' => $url,'scope' => 'email'));
    }

    function init($fbid) {
        global $db;
       // $db->debug=true;
        $row = $db->selectRow("select id,name,photo,photo_big from user where fbid='" . $fbid . "' limit 1");
        if ($row == false) {
            $me = $this->me();
			
			$name = $me['name'];
			
            $data['fbid'] = $fbid;
            $data['name'] = $name;
			$data['email'] = $me['email'];
            $data['photo'] = $me['pic_square'];
            $data['photo_big'] = $me['pic_big'];
			
			//check again
			$row = $db->selectRow("select id,name,photo,photo_big from user where fbid='" . $fbid . "' limit 1");
			if($row == false){
            	$id=$db->insert("user", $data);
				$this->name = $data['name'];
        		$this->photo = $data['photo'];
        		$this->photo_big = $data['photo_big'];
        		$this->id = $id;
			}else{
				$id = $row['id'];
				$this->name = $row['name'];
        		$this->photo = $row['photo'];
        		$this->photo_big = $row['photo_big'];
        		$this->id = $id;
			}
        } else {        
            	$id = $row['id'];
				$this->name = $row['name'];
        		$this->photo = $row['photo'];
        		$this->photo_big = $row['photo_big'];
        		$this->id = $id;
        }
		
        
	
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
	  
	  return $this->friendsStr;
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
        $fql = "SELECT name,email, pic_square,pic_big FROM user WHERE uid = '{$this->fbid}'";
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

}

?>
