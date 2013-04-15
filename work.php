<?php
session_start();
include_once 'core/dateClass.php';
include_once 'core/db.php';
include_once 'core/Browser.php';
include_once 'core/user.php';
$db = new db();
$browser = new browser();
$user = new user();

$noun = $_POST['noun'];
$nounName = $_POST['nounName'];
$currentPage = $_POST['currentPage'];
                       
//log this page
$dt=new dateObj();
$user_log['user']=$user->id;
$user_log['page']="new_work_add";
$user_log['title']=$nounName;
$user_log['idnumber']=$noun;
$user_log['created']=$dt->mysqlDate();
$db->insert("user_log",$user_log);

					   
					   
					   
if (isset($noun) && $noun!=-1 && $noun!="-1"){
    $dt = new dateObj();
	$placeArr=$db->selectRow("select * from place where pid='" . mysql_real_escape_string($noun) . "'");
	if($placeArr!=false){

             if ($place['phone'] == "" || isset($place['phone']) == false) {

                $url = "https://maps.googleapis.com/maps/api/place/details/json?";
                $par = "&key=AIzaSyAqYsZa6MJ97_Q-8NlafqfvIAki3W8pRQU";
                $par.= "&sensor=true";
                $par.= "&reference={$place['gref']}";
                $results = file_get_contents("{$url}{$par}");
                $r = json_decode($results);
                $updateData['phone'] = $r->result->formatted_phone_number;
                $updateData['address'] = $r->result->formatted_address;
                $db->update("place",$updateData,"pid = '{$pid}'");
                $place['phone'] = $r->result->formatted_phone_number;
                $place['address'] = $r->result->formatted_address;

            }

                           $newCode=rand(1111,9999);
                           	   $is_valid_code=$db->selectRow("select place from activation where place='$noun' and code_a='{$newCode}' and status=0");
                           while($is_valid_code!=false){
                               $newCode=rand(1111,9999);
                               $is_valid_code=$db->selectRow("select place from activation where place='$noun' and code_a='{$newCode}' and status=0");
                           }

			   $newData['user']=$user->id;
			   $newData['place']=$noun;
			   $newData['code_a']=$newCode;
			   $newData['created']=$dt->mysqlDate();
			   $newData['updated']=$dt->mysqlDate();
			   $newData['status']=0;
			   
			
						   //Delete Active activation codes
			    			$user->deleteActiveAndPending();	
			   
			   $db->insert("activation",$newData);
			   
			      			 $obj->status=true;
                              $obj->id=$user->id;
                              $obj->title="My Profile";
                              $obj->page="user";
                      
       
	}else{
                           $obj->id=$user->id;
                           $obj->title="My Profile";
                           $obj->page="user";
                           $obj->status = false;
                           $obj->error = "Sorry! unexpected error!";
                           
	}

}else if($noun=="-1" || $noun==-1){
			
			     		   $code_a=rand(1111,9999);
			     		   $code_b=rand(1111,9999);
                           	   $is_valid_code=$db->selectRow("select place from activation where code_a='{$code_a}' and code_b='{$code_b}' and status=0");
                           while($is_valid_code!=false){
                               $code_a=rand(1111,9999);
			     		   	   $code_b=rand(1111,9999);
                               $is_valid_code=$db->selectRow("select place from activation where code_a='{$code_a}' and code_b='{$code_b}' and status=0");
                           }
							
						   //Delete Active activation codes
			    			$user->deleteActiveAndPending();							
							
			                $newData['user']=$user->id;
			  				$newData['code_a']=$code_a;
			  				$newData['code_b']=$code_b;
							$newData['placename']=$nounName;
			  				$newData['created']=$dt->mysqlDate();
			 			    $newData['updated']=$dt->mysqlDate();
						    $newData['status']=0;
			               
			                $db->insert("activation",$newData);	
			
			       		    $obj->id=$user->id;
                            $obj->title="My Profile";
                            $obj->page="user";
                            $obj->status = true;

}
	echo json_encode($obj);
?>