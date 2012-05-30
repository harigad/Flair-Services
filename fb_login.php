<?php
include_once 'core/dateClass.php';
include_once 'core/db.php';
include_once 'core/Browser.php';
include_once 'core/user.php';
$db = new db();
$browser = new browser();
$user = new user();

if (isset($user->id) == true) {
    header("Location: mobile.php");
}else{
    header("Location: welcome.php");
   
}


?>