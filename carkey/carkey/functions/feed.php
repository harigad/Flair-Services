<?php

function getDetails($fid){
	global $db,$user;
	
	
}

function addFeed($type,$ref_id,$cid,$target_id,$target_title,$target_photo){
	global $db,$user;

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
	
}

function getFeed($user_id){
	global $db,$user;
	
	$feed = array();
	
	$feedArr = $db->selectRows("select fid,ref_id,type,cid,feed.uid,name,photo,feed.target_id,target_title,target_photo,likes,comments,likes.lid as liked from feed 
	left outer join likes on feed.fid = likes.target_id and likes.uid = '{$user->id}' 
	where feed.uid='{$user_id}' or ((type='owner' or type='checkin') and feed.target_id='{$user_id}') order by feed.created desc");
	
	if (mysql_num_rows($feedArr) > 0) {
            while ($feedObj = mysql_fetch_object($feedArr)) {
            	$feedObj->likes = json_decode($feedObj->likes);
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