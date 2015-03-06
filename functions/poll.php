<?php
$action=$_POST['action'];

if($action == "add"){
	$pollid = $_POST["pollid"];
	$answer = $_POST["moid"];
	
	$poll_rs = $db->selectRow("select poll.pollid,poll.question,
	make.mid,make.name as make,
	model.moid,model.name as model 
	from poll 
	inner join model on poll.moid = model.moid 
	inner join make on model.mid = make.mid 
	where pollid = '{$pollid}'");
	
	if($poll_rs){
	
	$insert_poll_data["pollid"] = $pollid;
	$insert_poll_data["uid"] = $user->id;
	$insert_poll_data["answer"] = $answer;
	
	$mysql_query("delete from poll_data where uid='{$user->id}' and pollid = '{$pollid}'");
	$db->insert("poll_data",$insert_poll_data);
	
	$poll_info["pollid"] = $pollid;
	$poll_info["question"] = $poll_rs["question"];
	$poll_info["answer"] = $answer;
	$poll_info["mid"] = $poll_rs["mid"];
	$poll_info["make"] = $poll_rs["make"];
	$poll_info["moid"] = $poll_rs["moid"];
	$poll_info["model"] = $poll_rs["model"];
	
	$user->addFeed(5,null,null,$poll_info,null);
	
	}

}else if($action == "get"){
		$moid = $_POST["moid"];
		$created = $_POST["created"];
	
		$whereClause = " where poll.moid = '{$moid}' ";
	
		if(isset($created)){
			$whereClause = $whereClause . " and poll.created < '{$created}'";
		}

		$sql = "select poll.pollid,question,options,data,poll.created,poll_data.answer,
				user.id as uid,user.name,user.photo,user.photo_big,user.plate
        from poll  
        inner join user on poll.uid = user.id  
        left outer join poll_data on poll_data.pollid = poll.pollid and poll_data.uid = '{$user->id}' 
        {$whereClause} order by poll.created desc limit 5 ";

		$feedArr = $db->selectRows($sql);
    	$feed = array();		
        if (mysql_num_rows($feedArr) > 0) {
            while ($feedArrObj = mysql_fetch_object($feedArr)) {
           		array_push($feed,$feedArrObj);
            }        
		} 
		echo json_encode($feed);
}
?>