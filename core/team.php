<?php
class team {

 function __construct($id,$data=null){
     global $db;
      if($id>0){
            global $db;
            $row=$db->selectRow("select * from teams where id='$id'");
                 if(is_array($row)){
                    $this->init($row);
                 }
        }else{
            $this->init($data);
        }
 }

 function init($data){
     $this->data=$data;
 }

 function load(){
    global $db;
        $this->players=$db->selectRows("select * from player where team='" . $this->get('id') . "'");
 }



 function printName(){
      return "<div class='match_title' ><img src='images/flags/" . $this->get('id') . ".png' >" . ucwords($this->get('name')) . "</div>";
 }

 function get($str){
        return $this->data[$str];
 }


 function set($str,$val){
        $this->data[$str]=$val;
 }


}
?>
