<?php

$fid = $_POST['fid'];
$coid = $_POST['coid'];
$action = $_POST['action'];
$mysqldate = new dateObj();
$likes = array();


	if($action == "add"){
		$_data['uid'] = $user->id;
		$_data['created'] = $mysqldate->mysqlDate();
		if($fid){
			$_data['target_id'] = $fid;
			echo $db->insert("likes",$_data);
		}else if($coid){
			$_data['target_id'] = $coid;
			echo $db->insert("comment_like",$_data);
		}
	}else if($action == "delete"){
		if($fid){
			mysql_query("delete from likes where target_id='{$fid}' and uid='{$user->id}'");
		}else if($coid){
			mysql_query("delete from comment_like where target_id='{$coid}' and uid='{$user->id}'");
		}
	}

	//update count
	if($fid){
			$likesArr = $db->selectRows("select user.id as uid,name,photo from likes inner join user on likes.uid = user.id where target_id='{$fid}'");
			while ($like = mysql_fetch_object($likesArr)) {
           		array_push($likes,$like);
            }
			$_update['likes'] = json_encode($likes);
			$db->update("feed",$_update,"fid = '{$fid}'");
	}else if($coid){
			$likesArr = $db->selectRows("select user.id as uid,name,photo from comment_like inner join user on  comment_like.uid = user.id where target_id='{$coid}'");
			while ($like = mysql_fetch_object($likesArr)) {
           		array_push($likes,$like);
            }
			$_update['likes'] = json_encode($likes);
			$db->update("comment",$_update,"coid = '{$coid}'");
	}
	
	
	

?>