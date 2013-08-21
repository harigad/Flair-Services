<?php 
class car{

   function __construct($id){
        $this->id = $id;
   }
   
   function addOwner($uid,$prime){
     global $db;
     $mysqldate=new dateObj();
     	$data['cid'] = $this->id;
     	$data['uid'] = $uid;
     	
     	$countArr = $db->selectRow("select count(cid) from owner where uid='{$uid}'");
     	if($countArr == 0){
     		$prime = true;
     	}
     	
     	$data['prime'] = $prime;
     	$data['created'] = $mysqldate->mysqlDate();
		
		$oid = $db->insert("owner",$data);	
	
		$userObj = $db->selectRow("select id,name,photo,photo_big from user where id='{$uid}'");
		addFeed('owner',$oid,$this->id,$userObj['id'],$userObj['name'],$userObj['photo']);		
		return $oid;
   }
   
   function removeOwner($uid){
   		global $db;
   		$carObj = $db->selectRow("select oid from owner where uid='" . $uid . "' and cid='" . $this->id . "'");
   		if($carObj){
   			mysql_query("delete from owner where ref_id ='" . $carObj[0] . "'");
   		}
   		//delFeed($sharerObj['id']);
   }
    
   function getAll($uid){
   	 $data->radio = $this->getRadio();
   	 $data->shares = $this->getShares($uid);
   	 return $data;
   }   
   
   function getInfo(){
     global $db;
     //TODO
   }
   
   function getShares($uid){
   	 global $db;
   	 $sharesArr = $db->selectRows("select id,name,photo,photo_big from owner inner join user on owner.uid=user.id where owner.cid='{$this->id}' and owner.uid<>'{$uid}'"); 
		
		$shares = array();
        if (mysql_num_rows($sharesArr) > 0) {
            while ($share = mysql_fetch_object($sharesArr)) {
				array_push($shares,$share);
            }        
		} 		
		return $shares;
   
    }
    
   function getRadio(){
    global $db;
   	  $radios = array();
 
	  $radiosArr = $db->selectRows("select name from radio where cid='{$this->cid}'"); 
		$radios = array();		
	    if (mysql_num_rows($radiosArr) > 0) {
            while ($radio = mysql_fetch_object($radiosArr)) {
				array_push($radios,$radio);
            }        
		}     	
   	 return $radios;
   }
   
} 
?>