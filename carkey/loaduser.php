<?php  error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
session_start();
include_once '../core/dateClass.php';
include_once 'core/db.php';
include_once 'core/user.php';
$db = new db();

$user = new user();

$userObj->status = true;
$userObj->id = $user->id;
$userObj->name = $user->name;
$userObj->photo = $user->photo;
$userObj->photo_big = $user->photo_big;

$userObj->cars = $user->getCars();

echo json_encode($userObj);

?>