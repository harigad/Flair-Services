<?php
class player{

    function __construct($id,$data=null){
        if($id>0){
            global $db;
            $row=$db->selectRow("select * from players where id='$id'");
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


    function thumb($selected=false){
        if($selected==true){
            $selectedBool="true";
            $selectedStr=" selected";
            $selectedStrPlayer="";
        }else{
            $selectedBool="false";
            $selectedStr="";
            $selectedStrPlayer=" selected";
        }

        echo "<div class='player' id='player_" . $this->get('id') . "' onclick='updateThumb(this);' onmouseover='thumbRollOver(this);' onmouseout='thumbRollOut(this);'  >";
                echo "<div class='thumb$selectedStr' style='background-image:url(" . $this->get('icon') . ");' >";               
                echo "</div>";
                echo "<div class='player_name' >" . $this->printName() . "</div>";
                echo "<div class='thumb_points' style='display:none;' >$" . $this->get('points') . "</div>";
        echo "</div>";

    }

    function printName(){
        return ucwords($this->get('name'));

    }

    function get($str){
        return $this->data[$str];
    }


    function set($str,$val){
        $this->data[$str]=$val;
    }
}





?>
