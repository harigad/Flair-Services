<?php

$mysqldate=new dateObj();
$fid=$_POST['fid'];
$coid=$_POST['coid'];
$action=$_POST['action'];
$data=$_POST['data'];


if($action == "add"){
	$_data['fid'] = $fid;
	$_data['uid'] = $user->id;
	$_data['data'] = $data;
	$_data['likes'] = '';
	$_data['created'] = $mysqldate->mysqlDate();
	echo $db->insert("comment",$_data);	
		$count = $db->selectRow("select count(coid) from comment where fid ='{$fid}'");
		$_update['comments'] = $count[0];
		$db->update("feed",$_update,"fid = '{$fid}'");
}else if($action == "delete"){
	mysql_query("delete from comment where coid = '{$coid}' and uid='{$user->id}'");
		$count = $db->selectRow("select count(coid) from comment where fid ='{$fid}'");
		$_update['comments'] = $count[0];
		$db->update("feed",$_update,"fid = '{$fid}'");
} if($action == "get"){
   
   $comsArr = $db->selectRows("select comment.uid,user.name,user.photo,comment.coid,comment.data,comment.likes,comment_like.lid as liked from comment  
   inner join user on comment.uid = user.id  
   left outer join comment_like on comment.coid = comment_like.target_id and comment_like.uid = '{$user->id}' 
   where comment.fid = '{$fid}'");
   
   $coms = array();
	
            while ($com = mysql_fetch_object($comsArr)) {
            	$com->likes = json_decode($com->likes);
           		array_push($coms,$com);
            }        
	
   echo json_encode($coms);
	
}
