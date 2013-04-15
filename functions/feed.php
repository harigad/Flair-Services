<?php

function addFeed($type,$target_id,$target_title,$target_photo,$cid,$ref_id){
	global $db,$user;
	$db->debug = true;
	$mysqldate=new dateObj();
	
	$data['ref_id'] = $ref_id;
	$data['type'] = $type;
	$data['uid'] = $user->id;
	$data['name'] = $user->name;
	$data['photo'] = $user->photo;
	$data['target_id'] = $target_id;
	$data['target_title'] = $target_title;
	$data['target_photo'] = $target_photo;
	$data['cid'] = $cid;
	$data['likes'] = 0;
	$data['comments'] = 0;
	$data['created'] = $mysqldate->mysqlDate();
	
	$db->insert("feed",$data);
	$db->debug = false;
}

function getFeed($user_id){
	global $db;
	
	$feed = array();

	$feedArr = $db->selectRows("select * from feed where uid='{$user_id}' order by created desc");
	
	if (mysql_num_rows($feedArr) > 0) {
            while ($feedObj = mysql_fetch_object($feedArr)) {
				array_push($feed,$feedObj);
            }        
		} 
    	
    return $feed;

}

function delFeed($id){
	global $db,$user;
	mysql_query("delete from feed where uid='{$user->id}' and ref_id='{$id}'");
}

?>