<?php
class signupcore{
    public $id;


    function __construct($id) {
        $this->id=$id;
        global $app;global $user;


        if($user->isAdmin()){
            echo '<div class="text" style="text-align:center;" ><p>You are already logged in!<br>Please logout in order to signup for a new APP!</p><a href="/logout" ><div class="btn" >Logout</div></a></div>';
            return;
        }


        if(isset($_POST["signup_form_process"])){
            $this->process($_POST['username'],$_POST['password'],$_POST['email']);
        }else if((isset($_POST["subscribe_form_process"]) || isset($_GET["subscribe_form_process"]))){// && isset($_SESSION['newappid'])){
            $this->completed();
        }else{
            $this->show($_POST['username'],$_POST['email']);
        }

    }




function show($err="",$username="",$email="") {
        global $page;
        ?>


    <?php if($err!=""){echo "<script>alert('$err');</script>";} ?>
    <form name="signup_form" action="/<?php echo $page->uri . "/" . $this->id; ?>"      method="post"  >
        <input type="hidden" name="signup_form_process"  >
        <div class='header' >choose username:</div>
        <div class='inputCover' >
            <input name="username" value="" type="text" value="<?php echo $username; ?>"  >
        </div>
        <div class='header' >email address:</div>
         <div class='inputCover' >
            <input name="email" value="" type="text" value="<?php echo $email; ?>" >
        </div>
        <div class='header' >create password:</div>
         <div class='inputCover' >
            <input name="password" value="" type="password" >
        </div>
        <a href='#' onclick="form_submit_auto(document.forms.signup_form);"    >
            <div class="btn"  >Continue</div>
        </a>
    </form>

<?php
$h=new header_buttons(null);
$h->set("left_text","Back");
$h->set("right_text","Login");
$h->set("href_left","appsoul_back_button");
$h->set("href_right","login");
$h->set("title","Free Trial - Step 1 ");
$h->print_block();
}
private function process($username,$pass,$email) {
global $db;global $user;global $page;global $app;

$row=$db->selectRow("select * from users where username='" . $username . "'");
if($row==false){

    $user=new user(null);

    if($user->isEmail($email)==true && $user->isPassword($pass)){

        $mysqldate=new dateObj();

        $data['username']=$username;
        $data['password']='';
        $data['email']='';
        $data['created']=$mysqldate->mysqlDate();
        $data['modified']=$mysqldate->mysqlDate();

        $userid=$db->insert("users",$data);
        $user=new user($userid);
        $user->setEmail($email);
        $user->setPassword($pass);

        $data2['userid']=$userid;

        $data2['domain']=$app->domain;
        $data2['alias']=$username;

        $temp['pc_template']="pc";
        $temp['mobile_template']="apple";
        $temp['title']="$username";
        $temp['theme']="default";
        $temp['defaultpage']="";
        
        $temp['devices']['nodevice']="02/02/2012";

                $styleRow=$db->selectRow("select style from themes where id='14'");
                if(isset($styleRow['style'])){
                  $temp["style"]=unserialize($styleRow['style']);
                }


        $data2['app_data']=serialize($temp);
        $data2['created']=$mysqldate->mysqlDate();
        $data2['modified']=$mysqldate->mysqlDate();

        
        $appid=$db->insert("apps",$data2);

        
        $_SESSION['newappid']=$appid;
        $_SESSION['newuserid']=$userid;
        echo "<script>gotoPage('/signup?subscribe_form_process=step_2');</script>";


    }else{
        echo "<script>alert('$user->error');</script>";
       
    }
}else{
    echo "<script>alert('Sorry!Domain already taken');</script>";    
}
}

private function completed(){

      $subscribe=new subscribe();
     
}

}
?>
