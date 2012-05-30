<?php
class block {
    public $id;
    public $type;
    public $pageid;
    public $sort;
    public $block_data;

    function __construct($id,$type){
        $this->id=$id;
        $this->type=$type;
        $this->init();
    }

    function init(){
        global $db;
        if(isset($this->id)){
            $row=$db->selectRow("select * from blocks where id='" . $this->id . "'");
            if($row!=false){
                $this->pageid=$row['pageid'];
                $this->sort=$row['sort'];
                $this->block_data=unserialize($row['block_data']);
            }
        }
    }

     function filter($val,$var){
        return str_replace($val,"",$var);
    }

    function save_block(){
            global $db;
            
            $mysqldate=new dateObj();
            $data['pageid']=$this->pageid;
            $data['sort']=$this->sort;
            $data['block_data']=serialize($this->block_data);
            $data['modified']=$mysqldate->mysqlDate();
            $db->update('blocks',$data,"id='" . $this->id . "'");
            return true;
    }

    function save_empty_block_data(){
        global $db;
        $mysqldate=new dateObj();
        $data['pageid']=$this->pageid;
        $data['sort']=$this->sort;
        $data['type']=$this->type;
        $data['block_data']=serialize($this->block_data);
        $data['created']=$mysqldate->mysqlDate();
        $data['modified']=$mysqldate->mysqlDate();
        $this->id=$db->insert('blocks',$data);
    }



    function get_sort(){
        return $this->sort;
    }

    function set_sort($sort){
        return $this->sort=$sort;
    }



    function get_pageid(){
        return $this->pageid;
    }

    function set_pageid($pageid){
        $this->pageid=$pageid;
        return true;
    }

    function get($name){
        return $this->block_data[$name];
    }

    function set($name,$value){
        $this->block_data[$name]=$value;
    }

    function iset($name){
        $value=$this->get($name);
        if(isset($value)){
            return true;
        }else{
            return false;
        }
    }

    function update_block($arr){
        $this->id=$arr['id'];
        $this->pageid=$arr['pageid'];
        $this->sort=$arr['sort'];
        $this->type=$arr['type'];
    }

    function edit_block_form_open($title){
        echo '<div class="admin_header" >' . $title . '</div>';
        echo '<div style="padding:0px;margin:0px;padding-top:0px;"><form name="edit_block_form" >
        <input type="hidden" name="type" value="' . $this->type . '" >
        <input type="hidden" name="id" value="' .  $this->id . '" >
        <input type="hidden" name="pageid" value="' .  $this->pageid . '" >
        <input type="hidden" name="sort" value="' .  $this->sort . '" >';
        

        //$this->edit_class_selectbox();
    }
    function edit_block_form_close(){
        echo "<a href='#' onclick='update_block(true);' ><div class='save_btn' >Save</div></a>";
        echo "<a href='#' onclick='delete_block(" . $this->id . ");' ><div class='delete_btn' >Delete</div></a>";
        echo "</div></form>";
    }


    function edit_class_selectbox(){
        echo '<select onchange="update_block();" name="class" >';
        echo '<option value="" >no style</option>';
        $classes=$this->get_all_classes();
        $selectedClass=$this->get('class');

        foreach ($classes as $className => $classTitle){
            echo '<option value="' . $className . '"';
            if($className==$selectedClass) echo ' selected ';
            echo ' >' . $classTitle . '</option>';
        }
        echo '</select>';
    }


    function delete_block(){
        global $db;
        $data['deleted']=1;
        $db->update('blocks',$data,"id=" . $this->id);
    }


    function run(){
        global $page;
        $page->pageNotFound();
    }

    function merge(){
        global $db;
            $style="";
            if(isset($this->pageid) && isset($this->id)){
            $before=$db->selectRow("select sort,type,block_data from blocks where pageid=" . $this->pageid  . " and deleted=0 and  type<>'header_buttons' and sort <" . $this->sort . " order by sort desc limit 1" );
            $after=$db->selectRow("select sort,type,block_data from blocks where pageid=" . $this->pageid  . " and deleted=0 and   type<>'header_buttons' and sort >" . $this->sort . " order by sort limit 1" );

            $beforeData=unserialize($before['block_data']);
            $afterData=unserialize($after['block_data']);

            if($before['type']==$this->type && $after['type']==$this->type && $beforeData['href']!="" && $afterData['href']!=""){
                $tp="middle";
                $style.="border-bottom-style:solid;border-bottom-width:1px;margin-top:0px;margin-bottom:0px;-moz-border-radius: 0px;-webkit-border-radius: 0px;";
            }else if($before['type']==$this->type && $beforeData['href']!=""){
                $tp="bottom";
                $style.="margin-top:0px;-moz-border-radius-topleft:0px;-webkit-border-top-left-radius:0px;-moz-border-radius-topright:0px;-webkit-border-top-right-radius:0px;";
            }else if($after['type']==$this->type && $afterData['href']!=""){
                $tp="top";
                $style.="border-bottom-style:solid;border-bottom-width:1px;margin-bottom:0px;-moz-border-radius-bottomleft:0px;-webkit-border-bottom-left-radius:0px;-moz-border-radius-bottomright:0px;-webkit-border-bottom-right-radius:0px;";
            }
            }
            return $style;
    }



}
?>