<?php
    
$mode = $_POST["mode"];
$uid = $_POST["uid"];
$name = $_POST["name"];
$email = $_POST["email"];
$uid = $_POST["uid"];
$role = $_POST["role"];
$pid = $_POST["pid"];
  
    
    if($mode=="get_places"){
        $places_arr = array();
         $places = $db->selectRows("select place.pid,place.name,place.vicinity from place
                                   inner join role on place.pid = role.pid and role.uid = '{$user->id}'
                                   and role.access = 1");
                                   while($place = mysql_fetch_array($places)) {
                                   array_push($places_arr,$place);
                                   }
        echo json_encode($places_arr);
        return;
    }
    
     // $db->debug = true;
$isAdmin = $db->selectRow("select * from role where access=1 and uid='{$user->id}' and pid='{$pid}'");
    
if($isAdmin){
    $pid = $isAdmin["pid"];
    $isemployee = $db->selectRow("select * from user inner join role on user.id=role.uid where role.pid='{$pid}' and user.id = '{$uid}'");
    
	if($mode == "delete"){
        if($isemployee){
		  mysql_query("delete from invites where uid='{$uid}' and pid ='{$pid}'");
          mysql_query("delete from role where uid='{$uid}' and pid ='{$pid}'");
        }
	}else if($mode == "add" && $email && $email !== "undefined"){
        if($uid)
        {
            if($isemployee)
            {
                
                if(!$isemployee["fbid"])
                {
                    $update["name"] = $name;
                    $update["email"] = $email;
                    $db->update("user",$update,"id='{$uid}'");
                    send_invite($uid,$pid,$email);
                }
                
                
                $role_update["role"] = $role;
                $db->update("role",$role_update,"uid='{$uid}' and pid='{$pid}'");
                
            }
        }else{
            $new["name"] = $name;
            $new["email"] = $email;
            $new["approved"] = 1;
            $uid = $db->insert("user",$new);
            
            $newrole["uid"] = $uid;
            $newrole["pid"] = $pid;
            $newrole["role"] = $role;
            $newrole["admin"] = $user->id;
            $db->insert("role",$newrole);
            send_invite($uid,$pid,$email);
           }
        
    }
    
  $cast = array();
    
				
				
				$castData = $db->selectRows("select user.id as uid,
                                            user.fbid,user.email,
                                            user.name as name,
                                            user.photo as photo,
                                            user.photo_big as photo_big,
                                            role.role as role,
                                            role.access,
                                            invites.inviteid,
                                            (select count(fid) from feed where recipient=user.id) as flairs
                                            from user
                                            left outer join role on user.id=role.uid
                                            left outer join invites on user.id=invites.uid
                                            where role.pid = '{$pid}' order by user.fbid desc,name");
                                            while($castMember = mysql_fetch_array($castData)) {
                                                array_push($cast,$castMember);
                                            }
                                            
                                          /*
                                            $invites = $db->selectRows("select inviteid,uid as adminid,pid,email as name from invites where pid='{$pid}' and connectid is NULL");
                                           
                                            while($invite = mysql_fetch_array($invites)) {
                                            array_push($cast,$invite);
                                            }*/
                                            
                                            $place_rs = $db->selectRow("select * from place where pid='{$pid}'");
                                            
                                            $place["place"] = $place_rs;
                                            $place["team"] = $cast;
                                           
    
    
                                            }
                                            else if($mode=="signup"){
                                            include_once 'signup_create.php';
                                            $place["status"] = signup_admin();
                                            
                                            }
                                             echo json_encode($place);
                                            
                                            
                                            function send_invite($uid,$pid,$email){
                                            global $user;
                                            global $db;
                                            mysql_query("delete from invites where uid='{$uid}' and pid='{$pid}' and finaluid is NULL ");
                                            $insertData["uid"] = $uid;
                                            $insertData["pid"] = $pid;
                                            $insertData["email"] = $email;
                                            $insertData["admin"] = $user->id;
                                        
                                            
                                            $inviteid = $db->insert("invites",$insertData);
                                            
                                            send_invite_mail($inviteid);
                                            }
                                            
                                            
                                            function send_invite_mail($inviteid){
                                            global $db,$user;
                                           
                                            $i = $db->selectRow("select invites.inviteid,invites.code,emp.name as emp,emp.email,
                                                                admin.name as admin,admin.photo as adminphoto,
                                                                place.name as placename,place.vicinity as vicinity 
                                                                from user as emp
                                                                inner join invites on emp.id = invites.uid and invites.admin = '{$user->id}'
                                                                inner join user as admin on invites.admin = admin.id
                                                                inner join place on invites.pid = place.pid
                                                                where invites.inviteid = '{$inviteid}'
                                                                ");
                                                                
                                                                if($i){
                                                                $to = $i['email'];
                                                                
                                                                $ENCRYPTION_KEY = "!^(&^)#$%^&*";
                                                                $code = $to;//encrypt($to, $ENCRYPTION_KEY);
                                                                $icode = $inviteid;//encrypt($inviteid, $ENCRYPTION_KEY);
                                                                
                                                                
                                                                $subject =  "Invitation from " . $i['admin'];
                                                                
                                                                $headers = "From: Flair.me <invite@flair.me>\r\n";
                                                                $headers .= "Reply-To: invite@flair.me\r\n";
                                                                $headers .= "CC: hari@ridealong.mobi\r\n";
                                                                $headers .= "MIME-Version: 1.0\r\n";
                                                                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                                                                
                                                                $message = '<html><body>';
                                                                $message .= '<div style="background-color:#eee;padding:20px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;" >';
                                                                $message .= '<div style="background-color:#40a3ff;padding:20px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-align:center;" >';
                                                                
                                                                $message .= '<img src="' . $i["adminphoto"] . '" style="-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 25px;height:50px;width:50px;background-color:#fff;margin-left:auto;margin-right:auto;" ></img>';
                                                                $message .= '<br><span style="font-size:30px;color:#fff;">' . $i['admin'] . '</span><br><br>';
                                                                $message .= '<span style="font-size:16px;color:#fff;">has invited you to join</span><br><br>';
                                                                $message .= '<span style="font-size:30px;color:#fff;">' . $i['placename'] . '</span><br><br><br><br>';
                                                                $message .= '<span style="padding:15px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;color:#40a3ff;background-color:white;" >';
                                                                $message .= '<a href="http://services.flair.me/invited.php?i=' . $icode . '&c=' . $code . '" >View Invitation</a></span>';
                                                                $message .= '</div></div>';
                                                                $message .= '</body></html>';
                                                                
                                                                mail($to, $subject, $message, $headers);
                                                                }
                                            }
   

?>