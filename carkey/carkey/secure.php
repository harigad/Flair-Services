<?php  error_reporting (E_ALL ^ E_NOTICE); 
session_start();
include_once 'core/db.php';
include_once 'core/user.php';
include_once '../core/dateClass.php';

$dateObj = new dateObj();
$db = new db();
$user = new user();

$page=$_GET['page'];

switch ($page) {
    case "signup1":
        include_once 'functions/signup1.php';
        break;
    case "signup2":
         include_once 'functions/signup2.php';
        break; 
    case "ccprocess":
         include_once 'functions/ccprocess.php';
        break;    
}












?>