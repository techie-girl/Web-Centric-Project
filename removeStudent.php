<?php
session_start();

require('dbConnect.php');
require('functions.php');

$myB00='';
//validation for session checking
if(isset($_SESSION['signin'])){
	$myB00=$_SESSION['B00'];
}
else{
	header('location:present/project/index.html');
	exit();
}
//this calls the remove students post
if( $_SERVER['REQUEST_METHOD']=='POST' ){
	$TheirB00=$_POST['RemovableStudents'];

//from the functions.php, will check for the b00 of the other student and remove it from my b00 list
	removeAccessOnMyB00($pdo, $TheirB00, $myB00);

}
	header('location:present/project/index.html');
exit();
?>
