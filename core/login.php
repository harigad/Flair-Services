<?php
class login{

    function __construct($logout="") {
        global $app;global $user;

      
       if(isset($_SESSION['login_attempts'])==false){
            $_SESSION['login_attempts']=0;
        }else{
            $_SESSION['login_attempts']=$_SESSION['login_attempts']+1;
        }




        if(isset($_POST["login_form_process"])){
            $this->process($_POST['username'],$_POST['password']);
        }else if(isset($_GET["login_forgot_password"]) || isset($_POST["login_forgot_password"])){
            $this->forgot_password("");
        }else if(isset($_GET["login_forgot_password_confirm"]) || isset($_POST["login_forgot_password_confirm"])){
              $this->forgot_password_confirm();
        }else{
            if($logout=="logout"){
                $this->logout();
            }else{
                $this->show();
            }
        }
       

    }

    function logout(){
        global $user;
        $user=null;
        unset($_SESSION['user']);
        $_SESSION['login_attempts']=0;
        $this->show("");
    }

    function forgot_password_confirm(){?>
        <div class='text' >
        A temporary password has been mailed to the email address on your account!
        </div>
    <a href='/login'  >
        <div class="btn" style="background-color:#333;color:#fff;" >Continue to Login</div>
    </a>        
        
   <?php     
    }


    function forgot_password($err=""){ 
     if(isset($_POST["login_forgot_password"])){ ?>
    <script>alert('9');gotoPage('/login?login_forgot_password_confirm=yes');</script>
    <?php }else{ ?>

    <div class="header">Forgot Password?</div>
    <form name="login_form" onsubmit="return false" action="<?php echo "/"  . $page->uri . "?login_forgot_password=yes&rand=" . rand(); ?>" method="post"  id="login_form"  >
        <input type="hidden" name="login_forgot_password_process" value="yes" >
       <div class='inputCover' >
            <input type="text" name="email"  value="enter your email address"  onblur="restoreField(this,'enter your email address');"  onfocus="clearField(this,'enter your email address');" >
        </div>
        <a href='#' onclick="form_submit_auto(document.forms.login_form);"  >
            <div class="btn"  >Retrieve My Password</div>
        </a>
    </form>
  
    <?php }

    $h=new header_buttons(null);
$h->set("left_text","login");
$h->set("right_text","Free Trial");
$h->set("href_left","appsoul_back_button");
$h->set("href_right","signup");
$h->set("title","Password ?");
$h->print_block();





    }




    function show($err="") {
      global $page;global $devices;
        ?>
<div class="header" >
    <?php
    if($devices->isMobile()){
        echo "Login - Preview My App!";
    }else{
        echo "Login - Design & Manage My App!";
    }

    $username="";
    if( $_GET['username'] !="" && isset($_GET['username'])){
        $username=$_GET['username'];
        $password="";
    }else{
        $password="password";
    }

$h=new header_buttons(null);
global $page;
if($page->getUri()!="login"){
$h->set("left_text","Free Trial");
$h->set("href_left","/signup");
}else{
$h->set("left_text","Back");
$h->set("href_left","appsoul_back_button");
}


$h->set("right_text","Password ?");
$h->set("href_right","/login?login_forgot_password=yes&rand=" . rand());
$h->set("title","Login");
$h->print_block();


    ?>
</div>

    <?php if($err!=""){echo "<div class='err' >$err</div>";} ?>
    <form name="login_form" onsubmit="return false" action="http://<?php echo $_SERVER['SERVER_NAME'] . "/"  . $page->uri; ?>" method="post"  id="login_form"  >
        <input type="hidden" name="login_form_process" >
       
           <div class='inputCover' ><input name="username"  type="text" value="<?php echo $username ?>" onblur="restoreField(this,'username');" onfocus="clearField(this,'username');" ></div>
            <div class='header' >password</div>
            <div class='inputCover' ><input name="password" value="<?php echo $password; ?>" type="password"  onblur="restoreField(this,'password');" onfocus="clearField(this,'password');" ></div>
       

        <a href='#' onclick="form_submit_auto(document.forms.login_form);"  >
            <div class="btn" >Login</div>
        </a>
    </form>
  

<?php }


function process($username,$pass) {
global $db;global $user;global $page;global $devices;global $app;
$check=false;

$row=$db->selectRow("select * from users where username='" . mysql_real_escape_string($username) . "'");

if($row!=false){
    if($row['password']==$pass){
        $row2=$db->selectRow("select * from apps where userid='" . $row['id'] . "' and domain='$app->domain' order by alias limit 1");
        if($row2!=false){
           
            $user=new user($row['id']);
             setcookie('user', $user->id, time() + 3600, '/', '.' . $app->domain);

            if($row2['alias']==$app->alias){
                if($devices->isMobile()==false){
                    echo "<script>window.location='http://" . $app->printDomain() . "/admin/';</script>";
                }else{                  
                    echo "<script>gotoPage('http://" . $app->printDomain() . "?" . rand() . "');</script>";
                }
            }else{               
                if($devices->isMobile()==false){                   
                    echo "<script>window.location='http://" . $row2['alias'] . "." .  $app->domain . "/admin';</script>";
                }else{
                    echo "<script>window.location='http://" . $row2['alias'] . "." .  $app->domain . "';</script>";
                }
            }
            $check=true;
        }
    }

}
    if($check==false){
        $this->show("Sorry!Invalid Username or Password");
    }

}
}
?>
