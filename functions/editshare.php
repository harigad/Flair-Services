<?php

$mysqldate=new dateObj();
$id=$_POST['id'];
$cid=$_POST['cid'];
$action=$_POST['action'];
$fb_photo=$_POST['fb_photo'];
$fb_photo_big=$_POST['fb_photo_big'];

$userObj=getUser($id);
$car = new car($cid);

$row = $db->selectRow("select oid from owner where uid ='" . $userObj['id'] . "' and cid='" . $cid . "'");

if($row){
	delFeed($row['oid']);
	mysql_query("delete from owner where oid = '" . $row['oid'] . "'");
}else{	
	$car->addOwner($userObj['id'],false);
	
}

$shares = $car->getShares($user->id);  	

$output->status=true;
$output->shares=$shares;
$output->feed=getFeed($user->id);
echo json_encode($output);

	function getUser($id) {
        global $db;
        $row = $db->selectRow("select id,name,photo,photo_big from user where id='$id'");
        if (is_array($row) == false) {
            $me = userData($id);
            $data['fbid'] = $id;
            $data['name'] = $me['name'];
            $data['photo'] = $me['pic_square'];
            $data['photo_big'] = $me['pic_big'];
            $data['id']=$db->insert("user", $data);
            return $data;
        } else {        
            return $row;
        }         
	}
		

    function userData($fbid) {
       global $user;
        $fql = "SELECT name, pic_square,pic_big FROM user WHERE uid = '{$fbid}'";
        $result = $user->fql($fql);
        $me = $result[0];
        return $me;
    }
    
    
    
    
    

?>