<?php

header('Content-type:application/json');
session_start();
require_once('dbConnect.php');
require('functions.php');
//this checks our session account
if(isset($_SESSION['signin'])){
	$userName= $_SESSION['signin'];
}
else
{
	exit();
}
//this takes the B00 of the student via username 

	$B00= getStudentB00($pdo, $userName);

	$_SESSION['B00']=$B00;	
	//this grabs the students schedule
	$final = getStudentInfoFromDB($pdo, $B00);
	$studentsB00=array();
	//this will return the list of students granted in their schedule
	$studentsB00=getGrantedStudentsList($pdo, $B00);
	foreach ($studentsB00 as $TheirB00)
	{
		$theirSchedule=getStudentInfoFromDB($pdo, $TheirB00);
		$final = array_merge($final, $theirSchedule);
	}

	echo json_encode($final);
	
?>




