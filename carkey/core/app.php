<?php
class app{
    public $id;
    public $userid;
    public $domain;
    public $alias;
    public $app_data;

    function __construct($domain){
        $this->init($domain);
    }

    function init($value){
        global $db;
            $splitDom=$this->splitdomain($value);
            $row=$db->selectRow("select * from apps where ( domain='" . $splitDom['domain'] . "' and alias ='" . $splitDom['alias'] . "') or domain='" .  $value . "' and alias=''");
            if($row!=false){
                $this->id=$row['id'];
                $this->userid=$row['userid'];
                $this->domain=$row['domain'];
                $this->alias=$row['alias'];
                $this->app_data=unserialize($row['app_data']);
                return $this->id;
            }else{
                return false;
            }
    }


    public function isLive($device=null){
        global $devices;
            if(isset($device)==false || $device==null){
                return $devices->isLive($this->get('devices'));
            }else{
                return $this->isActivated($device);
            }
        }

    function isActivated($checkDevice){
       $acitvated_devices=$this->get('devices');
       foreach ($acitvated_devices as $dev => $active){
            if(strtolower($checkDevice)==strtolower($dev)){
                 $date=new dateObj($active);
                if($date->isFuture()){
                        return true;break;
                }
            }
        }
        return false;
    }
    

    public function getTemplate(){
      //  return "apple";
        global $devices;
        if($devices->isMobile()){
           return $this->get("mobile_template");
        }else{
           return $this->get("pc_template");
        }
    }

    public function save(){
        global $db;
        
        $mysqldate=new dateObj();
        $data['userid']=$this->userid;
        $data['domain']=$this->domain;
        $data['alias']=$this->alias;
        $data['app_data']=serialize($this->app_data);
        $data['modified']=$mysqldate->mysqlDate();
        $db->update('apps',$data,"id='" . $this->id . "'");
        return true;
    }


    public function save_empty_app_data(){
        global $db;
        
        $mysqldate=new dateObj();
        $data['userid']=$this->userid;
        $data['domain']=$this->domain;
        $data['alias']=$this->alias;
        $data['app_data']=serialize($this->app_data);
        $data['created']=$mysqldate->mysqlDate();
        $data['modified']=$mysqldate->mysqlDate();
        $this->id=$db->insert('pages',$data);
        return $this->id;
    }

    public function setUser($userid){
        $this->userid=$userid;
        return true;
    }

    public function getUser(){
        return $this->userid;
    }

    public function setDomain($domain){
        $this->domain=$domain;
        return true;
    }

    public function printDomain(){
        $alias="";
        if(isset($this->alias) && $this->alias!=""){
           $alias=$this->alias . ".";
        }
        return $alias . $this->domain;
    }

    public function getDomain(){
        return $this->domain;
    }

    public function getAlias(){
        return $this->alias;
    }

    public function setAlias($alias){
        $this->alias=$alias;
        return true;
    }

    public function get($name){
        return $this->app_data[$name];
    }

    public function set($name,$value){
        $this->app_data[$name]=$value;
        return true;
    }
    

    public function splitdomain($value){
        $arr=explode(".",$value);
        $domain=str_replace("$arr[0].", "" , $value);
        $vals['domain']=$domain;
        $vals['alias']=$arr[0];
        return $vals;
    }


    public function title(){
        return ucfirst($this->get("title"));
    }

}
?>
