<?php
class header_buttons extends block{
    function __construct($pageid,$type="header_buttons"){
          global $db;
          $row=$db->selectRow("select * from blocks where pageid='$pageid' and type='header_buttons'");

          if($row!=false){
                parent::__construct($row['id'],$type);
          }else{
              $this->type="header_buttons";
              $this->pageid=$pageid;
              $this->get_empty_block_data();
              $this->save_empty_block_data();
              parent::__construct($this->id,$type);
          }
    }


 function getDefaultPage(){
  global $app;
  $page=new page($this->pageid);
    if($app->get("defaultpage")==$page->uri){
      return "yes";
    }else{
      return "no";
    }
 }


 function setDefaultPage(){
  global $app;
  $page=new page($this->pageid);
  $app->set("defaultpage",$page->uri);
  $app->save();
 }



    function edit_block(){
        global $app,$db;
        parent::edit_block_form_open("");

 ?>

<?php echo "<div class='admin_header' >Page Title</div>"; ?>
<input name="title" type="text" value="<?php echo $this->get("title"); ?>" onblur="restoreField(this,'Page Title');" onfocus="clearField(this,'Page Title');"  onkeyup="update_block();" >
<?php
echo "<div class='admin_header' >Left Button</div>";
?>
<input name="left_text" type="text" value="<?php echo $this->get("left_text"); ?>"    onkeyup="update_block();" >
<input type="hidden" name="href_left" id="href_left" value="<?php echo $this->get("href_left"); ?>" >
<?php
echo "<br>";
echo  '<select name="href_select_left" onchange="document.getElementById(\'href_left\').value=this[this.selectedIndex].value;update_block();"  >';
$rs=$db->selectRows("select uri,title from pages where deleted=false and appid='" . $app->id . "'");
echo "<option value='' >Link to a page</option>";
echo "<option value='appsoul_back_button'>Back Button</option>";
echo "<option value='signup'>Signup Button</option>";
while($row=mysql_fetch_array($rs)){
    echo "<option value='/". $row['uri'] . "' ";
    if($this->get('href_left')=="/" . $row['uri']) { echo " selected "; }
    echo " >" . $row['title'] . "</option>";
}
echo "</select>";





echo "<div class='admin_header' >Right Button</div>";
?>
<input name="right_text" type="text" value="<?php echo $this->get("right_text"); ?>" onblur="restoreField(this,'');" onfocus="clearField(this,'');"  onkeyup="update_block();" >
<input type="hidden" id="href_right" name="href_right" value="<?php echo $this->get("href_right"); ?>" >
<?php
echo "<br>";
echo  '<select name="href_select" onchange="document.getElementById(\'href_right\').value=this[this.selectedIndex].value;update_block();"  >';
$rs=$db->selectRows("select uri,title from pages where deleted=false and appid='" . $app->id . "'");
echo "<option value='' >Link to a page</option>";
echo "<option value='appsoul_back_button'>Back Button</option>";
echo "<option value='signup'>Signup Button</option>";
while($row=mysql_fetch_array($rs)){
    echo "<option value='/". $row['uri'] . "' ";
    if($this->get('href_right')=="/" . $row['uri']) { echo " selected "; }
    echo " >" . $row['title'] . "</option>";
}
echo "</select>";
?>
<input type="hidden" name="defaultpage" value="<?php echo $this->getDefaultPage(); ?>" >
<input type="radio" <?php if($this->getDefaultPage()=="yes")echo " checked " ?>  onclick="if(this.checked){document.forms[0].defaultpage.value='yes';}else{document.forms[0].defaultpage.value='no';};" ><div class='admin_header' style='padding-left:10px;display:inline-block;' >Set as Home Page</div>
<?php

echo "<a href='#' onclick='update_block(true);' ><div class='save_btn' >Save</div></a>";
echo "</div></form>";



}

function get_empty_block_data() {
$this->set("title","Page Title");

$this->sort=99999999999;
}


function update_block($arr){
$this->set("title",$arr['title']);

$this->set("left_text",$arr['left_text']);
$this->set("right_text",$arr['right_text']);

$this->set("href_left",$arr['href_left']);
$this->set("href_right",$arr['href_right']);

if($arr['defaultpage']=="yes"){
 $this->setDefaultPage();
}


}

function print_block(){

                if($this->get("left_text")!=""){

                    $onclick="";
                    if($this->get("href_left")=="appsoul_back_button"){
                        $onclick=" onclick='history.go(-1);' ";
                    }


                $str="<a href='" . $this->get("href_left") . "' $onclick >";
                $str.="<div style='border:1px solid;opacity:0.6;font-size:0.8em;-moz-border-radius: 4px;-webkit-border-radius: 4px;position:absolute;padding:5px;left:5px;top:10px;padding-left:15px;padding-right:15px;' class='header_text' >";
                $str.="<span class='header_text' >" . $this->get("left_text") . "</span>";
                $str.="</div>";
                $str.="</a>";
                }
                
                $str.="<div class='header_text' style='text-shadow: rgba(0,0,0,.3) 0px 4px 4px;font-size:1.4em;position:relative;text-align:center;height:45px;line-height:45px;' >" . $this->get("title") . "</div>";


                


                if($this->get("right_text")!=""){
                       // if($this->get("href_right")!=""){
                                  $str.="<a href='" . $this->get("href_right") . "'  >";
                        //}

                $str.="<div style='border: 1px solid;opacity:0.6;font-size:0.8em;-moz-border-radius: 4px;-webkit-border-radius: 4px;position:absolute;padding:5px;right:5px;top:10px;padding-left:15px;padding-right:15px;' class='header_text' >";
                $str.="<span class='header_text' >" . $this->get("right_text") . "</span>";
                $str.="</div>";
                  //  if($this->get("href_right")!=""){
                     $str.="</a>";
                    // }
                }

            
                
                    echo "<div id='block_" .  $this->id . "' >";
                ?>

<script>
    document.getElementById('header_text_container').style.display='none';
    document.getElementById('header_text_container').innerHTML="<?php echo $str; ?>";
    $('#header_text_container').show('slow');
   
</script>
                <?php
                echo "</div>";

}


function save_block(){
            global $db;
       
            $mysqldate=new dateObj();
            $data['pageid']=$this->pageid;
            $data['sort']="989898";
            $data['block_data']=serialize($this->block_data);
            $data['modified']=$mysqldate->mysqlDate();
              
            $db->update('blocks',$data,"id='" . $this->id . "'");


            $page=new page($this->pageid);
            $page->setTitle($this->get("title"));


              
            return true;
}




}



?>
