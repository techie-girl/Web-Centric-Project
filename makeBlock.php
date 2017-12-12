<?php
session_start();

require_once('dbConnect.php');
require('functions.php');
//these values are nesscary to enter a 'Add to your schedule' block
$BlockName = '';
$DayOfWeek='';
$CRN='';
$StTime='';
$EndTime='';
$MyB00='';

//allows for session checking for signing in
if(isset($_SESSION['signin']))
{
	$MyB00=$_SESSION['B00'];
}
else{
	header('location:present/project/index.html');
	exit();
}
//retreives form values
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$BlockName = $_POST['bltitle'];
	$DayOfWeek=$_POST['BlDay'];
	$CRN=makeCRN($pdo);
	$StTime=(int)($_POST['StTime']);
	$EndTime=(int)($_POST['EndTime']);
	$binweek='';


	if($DayOfWeek=="Monday"){
		$binweek='10000';
	}
	else if($DayOfWeek=="Tuesday"){
		$binweek='01000';
	}
	else if($DayOfWeek=="Wednesday"){
				$binweek='00100';

	}
	else if($DayOfWeek=="Thursday"){
				$binweek='00010';

	}
	else if($DayOfWeek=="Friday"){
			$binweek='00001';

	}
//this queries the database into courses
	try{
		$sql="INSERT INTO courses (location, course_code, course_name, startTime, endTime, days) VALUES ('NA', ?, ?, ?, ?, ?);";
		$statement=$pdo->prepare($sql);
		$statement->execute(array("$CRN", "$BlockName", "$StTime","$EndTime", "$binweek"));
	}
	catch(PDOException $e){
		echo 'Unable to insert' .$e->getMessage();
		exit();
	}
//enrolls the user into their personal schedule time
	try{
		$sql="INSERT INTO enrollment (course_code, B00) VALUES ( ?, ?);";
		$statement=$pdo->prepare($sql);
		$statement->execute(array("$CRN", "$MyB00"));
	}
	catch(PDOException $e){
		echo 'Unable to insert' .$e->getMessage();
		exit();
	}
}
	header('location:present/project/index.html');


?>
