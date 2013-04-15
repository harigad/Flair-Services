<?php
class style{
    public $style;

    function __construct($st=""){
        $this->style=$st;
    }

    function edit(){?>

<p>Background Color:</p>
<input type="text" name="background-color" >
<p>Background Image:</p>
<input type="text" name="background-image" >
<p>Text Color:</p>
<input type="text" name="color" >
<?php }

function get($name){
return $this->style['name'];
}


function set($name,$value){
$this->style[$name]=$value;
}

function save(){
return serialize($style);
}

}
?>
