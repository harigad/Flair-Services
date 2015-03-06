<?php
    error_reporting(E_ALL ^ E_NOTICEß);
    session_start();
    include_once 'core/dateClass.php';
    include_once 'core/db.php';
    include_once 'core/Browser.php';
    include_once 'core/user.php';
    include_once 'functions/place.php';
    $db = new db();
    $browser = new browser();
    $user = new user();
    //$db->debug=true;
    $ine=$_REQUEST["i"];
    $ce=$_REQUEST["c"];
    
    $ENCRYPTION_KEY = "!^(&^)#$%^&*";
    $in = $ine;//decrypt($ine, $ENCRYPTION_KEY);
    $c = $ce;//decrypt($ce, $ENCRYPTION_KEY);
    
    
    $action = $_REQUEST['action'];
    //michelle_dmyzlba_beck@tfbnw.net;
    //anna_swkkmqf_ching@tfbnw.net;
    //mike_iaintre_lamb@tfbnw.net;
    //adam_rmtuvto_keys@tfbnw.net;
    
    $i = $db->selectRow("select invites.uid as empid,invites.inviteid,invites.code,invites.finaluid,emp.name as emp,emp.email,
                        admin.name as admin,admin.photo as adminphoto,place.pid as pid,admin.email as adminemail,
                        place.name as placename,place.vicinity as vicinity
                        from user as emp
                        inner join invites on emp.id = invites.uid
                        inner join user as admin on invites.admin = admin.id
                        inner join place on invites.pid = place.pid
                        where invites.inviteid = '{$in}' and invites.email = '{$c}'
                        ");
  
                        
if($i){
                        
                        
                        if($action=="join" && isset($user->id)){
                        
                        $rid = $db->selectRow("select * from role where uid='{$user->id}' and pid='" . $i["pid"] . "'");
                        if(!$rid){
                        mysql_query("update role set uid='{$user->id}' where uid='" . $i["empid"] . "' and pid='" . $i["pid"] . "'");
                        mysql_query("update feed set recipient = '$user->id' where recipient='" . $i["empid"] . "'");
                        mysql_query("update invites set finaluid = '{$user->id}' where inviteid='{$in}'");
                      //  mysql_query("delete from user where id='" . $i["empid"] . "' and fbid is NULL");
                        send_confirm_mail($i["adminemail"],$user->name,$user->photo,$i["placename"]);
                        }
                        
$message = '<html><body style="background-color:#eee;" >';
       $message .= '<div style="position:absolute;left:0;right:0;top:0;bottom:0;margin:auto;height:300px;width:600px;background-color:#40a3ff;padding:20px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-align:center;" >';

$message .= '<img src="' . $user->photo . '" style="-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 25px;height:50px;width:50px;background-color:#fff;margin-left:auto;margin-right:auto;" ></img>';
$message .= '<br><br><span style="font-size:30px;color:#fff;">Welcome, ' . $user->name . '</span><br><br>';
$message .= '<span style="font-size:16px;color:#fff;">you have been accepted to Flair as a team member for</span><br><br>';
$message .= '<span style="font-size:30px;color:#fff;">' . $i['placename'] . '</span><br><br><br><br>';

$message .= '</div>';
$message .= '</body></html>';
 echo $message;
                        

                        
                        
                        
      
                        }else if(!$i['finaluid']){
    
    
    
    
    $message = '<html><body style="background-color:#eee;" >';
       $message .= '<div style="position:absolute;left:0;right:0;top:0;bottom:0;margin:auto;height:300px;width:600px;background-color:#40a3ff;padding:20px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-align:center;" >';
    
    $message .= '<img src="' . $i["adminphoto"] . '" style="-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 25px;height:50px;width:50px;background-color:#fff;margin-left:auto;margin-right:auto;" ></img>';
    $message .= '<br><span style="font-size:30px;color:#fff;">' . $i['admin'] . '</span><br><br>';
    $message .= '<span style="font-size:16px;color:#fff;">has invited you to join</span><br><br>';
    $message .= '<span style="font-size:30px;color:#fff;">' . $i['placename'] . '</span><br><br><br><br>';
    $message .= '<a href=' . $user->login('http://services.flair.me/invited.php?action=join&i=' . $ine . '&c=' . $ce) . ' ><span style="background-color:#fff;padding:15px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;color:#40a3ff;" >LOGIN AND JOIN</span></a>';
    $message .= '</div>';
    $message .= '</body></html>';
    
    
    echo $message;
                        }
                        
                        
                        
                        
                        
                        
                        
                        }
                        
                        
                        
                        function send_confirm_mail($to,$username,$userphoto,$placename){
                                            $subject =  $username . " accepted your invitation";
                                            
                                            $headers = "From: Flair.me <invite@flair.me>\r\n";
                                            $headers .= "Reply-To: invite@flair.me\r\n";
                                            $headers .= "CC: hari@ridealong.mobi\r\n";
                                            $headers .= "MIME-Version: 1.0\r\n";
                                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                                            
                                            $message = '<html><body>';
                                            $message .= '<div style="background-color:#eee;padding:20px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;" >';
                                            $message .= '<div style="background-color:#40a3ff;padding:20px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-align:center;" >';
                                            
                                            $message .= '<img src="' . $userphoto . '" style="-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 25px;height:50px;width:50px;background-color:#fff;margin-left:auto;margin-right:auto;" ></img>';
                                            $message .= '<br><span style="font-size:30px;color:#fff;">' . $username . '</span><br><br>';
                                            $message .= '<span style="font-size:16px;color:#fff;">has accepted your invitation to join</span><br><br>';
                                            $message .= '<span style="font-size:30px;color:#fff;">' . $placename . '</span><br><br><br><br>';
                                            $message .= '<span style="padding:15px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;color:#40a3ff;background-color:white;" >';
                                            $message .= '<a href="http://flair.me/bus/" >Manage</a></span>';
                                            $message .= '</div></div>';
                                            $message .= '</body></html>';
                                            
                                            mail($to, $subject, $message, $headers);
                        
                                            }

                        
                        
    
    ?>


