<?php
session_start();
include_once '../core/dateClass.php';
include_once '../core/db.php';
 
$db=new db();
$d=new dateObj();
$called_number=str_replace("+1","",$_REQUEST['From']);
$callername=$_REQUEST['CallerName'];
$callercity=$_REQUEST['CallerCity'];
$callerstate=$_REQUEST['CallerState'];
$callerzip=$_REQUEST['CallerZip'];
$fromzip=$_REQUEST['FromZip'];

	
	$new_call_data['sessionid']=session_id();
	$new_call_data['fromphone']=$called_number;
	$new_call_data['callername']=$callername;
	$new_call_data['callercity']=$callercity;
	$new_call_data['callerstate']=$callerstate;
	$new_call_data['callerzip']=$callerzip;
	$new_call_data['fromzip']=$fromzip;

	
	$new_call_data['created']=$d->mySqlDate();
    function logme($page,$code_a="",$code_b="",$status=0,$aid=Null){
			global $new_call_data,$db;
			$new_call_data['page']=$page;
			$new_call_data['code_a']=$code_a;
			$new_call_data['code_b']=$code_b;
			$new_call_data['status']=$status;
			$new_call_data['activationid']=$aid;
			$db->insert("activation_log",$new_call_data);
	}
?>