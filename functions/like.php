<?php

$fid = $_POST['fid'];
$coid = $_POST['coid'];
$action = $_POST['action'];
$mysqldate = new dateObj();
$likes = array();
$likeType = $_POST['likeType'];

mysql_query("delete from likes where target_id='{$fid}' and uid='{$user->id}'");
	if($action == "add"){		
		$_data['uid'] = $user->id;
		$_data['created'] = $mysqldate->mysqlDate();
		if($fid){
			$_data['target_id'] = $fid;
			$_data['type'] = $likeType;
			$db->insert("likes",$_data);
		}else if($coid){
			$_data['target_id'] = $coid;
			$db->insert("comment_like",$_data);
		}
	}
			$likesArr = $db->selectRows("select user.id as uid,name,photo,photo_big,likes.type from likes inner join user on likes.uid = user.id where target_id='{$fid}' order by likes.created desc limit 7");
			while ($like = mysql_fetch_object($likesArr)) {
           		array_push($likes,$like);
            }
			$_update['likes'] = json_encode($likes);
			$db->update("feed",$_update,"fid = '{$fid}'");
			
			echo $_update['likes'];
			
?>