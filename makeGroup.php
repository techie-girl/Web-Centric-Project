<?php
	session_start();

	require_once('dbConnect.php');
	include('functions.php');

	$TheirB00='';
	$MyB00='';
	$result='';

	//collect form data
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$TheirB00=$_POST['BInput'];
		$MyB00=$_SESSION['B00'];

		if(!(checkExistsB00($pdo,$TheirB00))){
			header('Location:present/project/index.html');
			exit();
		}
		else
		{
			//grant access to the student on your B00
			grantAccessOnMyB00($pdo, $TheirB00, $MyB00);
		}
	}
	header('Location:present/project/index.html');



?>
