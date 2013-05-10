<?php 
class Facebook {

     public function __construct($config) {
     
     }
     
     public function getUser(){
     
     	return 44405738;
     
     }
     
     
     public function setAccessToken($token){
     
     	$this->token = $token;
     
     }
     
     
     public function api(){
        
        
        $data['name']="Hari Om";
        $data['pic_square']="none";
        $data['pic_big']="none";
        
        $results[0]=$data;
        return $results;
     
     
     }
}
?>