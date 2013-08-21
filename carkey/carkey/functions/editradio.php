<?php

$mysqldate=new dateObj();
$radio_name=$_POST['radio_name'];
$action=$_POST['action'];

$row = $db->selectRow("select * from radio where uid='{$user->id}' and name='$radio_name'");

if($row){
	mysql_query("delete from radio where rid = '{$row['rid']}'");
}else{	
	$data['uid'] = $user->id;
	$data['name'] = $sharer_id;
	$data['created']=$mysqldate->mysqlDate();
	$db->insert("share",$data);
}

$radiosArr = $db->selectRows("select name from radio where uid='{$user->id}'"); 
		
		$radios = array();		
		
        if (mysql_num_rows($radiosArr) > 0) {
            while ($radio = mysql_fetch_object($radiosArr)) {
				array_push($radios,$radio);
            }        
		}     	

$output->status=true;
$output->radios=$radios;
echo json_encode($output);
?>