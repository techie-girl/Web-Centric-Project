<?php

session_start();

if(isset($_SESSION['signin'])){
	echo 'You are logging out!';
	session_destroy();
	header('location:signin.php');
	exit();
}
echo 'There was no session to begin with!';
?>
