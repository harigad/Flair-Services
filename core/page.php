<?php
class page{    
    public $id;
    public $appid;
    public $uri;
    public $title;
    public $page_data;
    public $blockid;

    public function __construct($id){
        $this->id=$id;
        $this->init();
    }

    public function init(){
        global $db;
        if(isset($this->id)){
            $row=$db->selectRow("select * from pages where id='" . $this->id . "'");
            if($row!=false){
                $this->appid=$row['appid'];
                $this->uri=$row['uri'];
                $this->title=$row['title'];
                $this->page_data=unserialize($row['page_data']);
            }
        }
    }

    public function pageNotFound(){
        echo "<div class='text' >Page Not Found</div>";
    }

    public function printPage(){
        global $user;global $app;global $db;

        if((($app->isLive()==false && $user->isAdmin()==false) || $this->uri=="login" || $this->uri=="logout"  ||  $this->uri=="signup" )){
            if($this->uri=="signup"){
            $signup=new signupcore(null);
            }else{
            $login=new login($this->uri);
            }
        }else if($app->isLive()==true || $user->isAdmin()==true){
            if(isset($this->blockid)){
                $block=$db->selectRow("select type from blocks where id='" . $this->blockid . "'");
                $plugin=new $block[0]($this->blockid);
                $plugin->run();
            }else{
                if(isset($this->id)){
                    echo $this->get("content");
                }else{
                    $this->pageNotFound();
                }
            }

        }
    }


    public function load($appid,$uri){
        global $db;global $app;
        $uriStr=$this->trim_slashes($uri);
        $pageURIArr=explode("/",$uriStr);
        $uri=$pageURIArr[0];
        if(isset($pageURIArr[1])){
            $blockid=$pageURIArr[1];
            if(is_numeric($blockid)==true){
                $this->blockid=$blockid;
            }
        }else{
            if(is_numeric($uri)){
                $blockid=$uri;
                $uri="";
            }

        }

        if(!isset($this->id)){
            $row=$db->selectRow("select * from pages where appid='" . $appid . "' and uri='" . $uri . "' and deleted=0 ");
            if($row!=false){
                $this->id=$row['id'];
                $this->appid=$row['appid'];
                $this->uri=$row['uri'];
                $this->title=$row['title'];
                $this->page_data=unserialize($row['page_data']);
                return $this->id;
            }else{
                $this->uri=$uri;
            }
        }
    }


    public function trim_slashes($str){
        global $app;
        $str=urldecode($str);
        $str=str_replace("?","",$str);
        while(substr($str,0,1)=="/"){
            $str=substr($str,1,strlen($str)-1);
        }

        while(substr($str,strlen($str)-1,1)=="/"){
            $str=substr($str,0,strlen($str)-1);
        }


        if($str=="" || isset($str)==false || $str=="/" || strlen($str)==0){
            if($app->get("defaultpage")!="" && $app->get("defaultpage")!="/"){
                $str=$this->trim_slashes($app->get("defaultpage"));
            }
        }


        return $str;

    }

    public function setTitle($str){
        if(isset($str) && $str!=""){
            $this->title=$str;
            global $db;
            $data['title']=$this->title;
            $db->update("pages",$data,"id='" . $this->id . "'");
           // if($this->uri=="" || isset($this->uri)==false){
           //     $this->setUri($this->title);
           // }
            return true;
        }else{
            return false;
        }
    }

    public function getTitle(){
        return $this->title;
    }


    public function setUri($str){
        global $app;
        if(isset($str) && $str!="" && strtolower($str)!="login"){
            if($app->get("defaultpage")==$this->uri){
                $app->set("defaultpage",$str);
                $app->save();
            }
            $this->uri=$this->trim_slashes($str);
            global $db;
            $data['uri']=$this->uri;
            $db->update("pages",$data,"id='" . $this->id . "'");
            return true;
        }else{
            return false;
        }
    }

    public function getUri(){
        return $this->uri;
    }


    public function setBlog($id){
        $this->appid=$id;
        return true;
    }

    public function getBlog(){
        return $this->appid;
    }

    public function get($name){
        return $this->page_data[$name];
    }

    public function set($name,$value){
        $this->page_data[$name]=$value;
    }

    public function save(){
        global $db;
        
        $mysqldate=new dateObj();
        $data['appid']=$this->appid;
        $data['uri']=$this->uri;
        $data['title']=$this->title;
        $data['page_data']=serialize($this->page_data);
        $data['modified']=$mysqldate->mysqlDate();
        $db->update('pages',$data,"id='" . $this->id . "'");
        return true;
    }

    public function save_empty_page_data(){
        global $db;
        
        $mysqldate=new dateObj();
        $data['appid']=$this->appid;
        $data['uri']="";
        $data['title']=$this->title;
        $data['page_data']=serialize($this->page_data);
        $data['modified']=$mysqldate->mysqlDate();
        $data['created']=$mysqldate->mysqlDate();
        $this->id=$db->insert('pages',$data);

        $dataUri['uri']="pa_" . $this->id ."_ge";
        $db->update("pages",$dataUri,"id='$this->id'");

        return $this->id;
    }

    public function getBlocks(){
        global $db;
        $blocks=$db->selectRows("select * from blocks where pageid='" . $this->id . "' and deleted='0' order by sort ");
        return $blocks;
    }


    public function publish($save=false){
        $str="";
        $blocks=$this->getBlocks();
        ob_start();

        while($blockArr=mysql_fetch_array($blocks)){

              if($blockArr['type']=="header_buttons"){
                $block=new $blockArr['type']($this->id);
              }else{
                $block=new $blockArr['type']($blockArr['id']);
              }

           
            $block->print_block();
        }

        $this->page_data['content']=ob_get_contents();
        $data["page_data"]=serialize($this->page_data);
        ob_end_clean();

        if($save){
            global $db;
            $db->update("pages",$data,"id='" . $this->id . "'");
        }
    }



    public function set_empty_page_data($title="new page"){
        $this->title=$title;
    }
}
?>
