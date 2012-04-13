<?php

class template{
    public $template;

    function __construct($template){
        $this->template=$template;
    }
    
    function get(){
        return $this->template;
    }


}
?>
