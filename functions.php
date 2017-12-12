<?php

require_once('dbConnect.php');


//this checks to make sure the B00 doesn't already exist
function checkExistsB00($pdo, $B00){
	$exists=false;
		
		try
		{
		$sql= "SELECT students.B00 from students where students.B00=?;";
		$statement=$pdo->prepare($sql);
		$statement->execute(array("$B00"));
		}
		catch(PDOException $e){
			echo 'Unable to query' .$e->getMessage();
			exit();
		}
		if($statement->rowCount()==0){
			$exists=false;
		}
		else{
			$exists=true;
		}
	
	return $exists;
}
//this makes sure the given netID isnt already exists. 
//error checking for registration of unique Email
function checkUniqueNetID($pdo, $netID){
	$unique=false;
	try{
		$sql= "SELECT students.net_id_email FROM students WHERE students.net_id_email=?;";
		$statement=$pdo->prepare($sql);
		$statement->execute(array("$netID"));
	}
	catch(PDOException $e){
		echo 'Unable to query' .$e->getMessage();
			exit();
	}
	if($statement->rowCount()==0){
		$unique=true;
	}
	else{
		$unique=false;
	}
	return $unique;
}
//retrieves the students schedule via database
function getStudentInfoFromDB($pdo, $B00)
{
	
		$sql = "SELECT students.name, students.B00, courses.course_code, courses.course_name, courses.location, courses.startTime, courses.endTime, courses.days FROM (courses) INNER JOIN enrollment ON courses.course_code=enrollment.course_code INNER JOIN (students) ON students.B00=enrollment.B00 WHERE students.B00=?;";
		$statement = $pdo->prepare($sql);
		$statement->execute(array("$B00"));

		$results_array = array();

		for($i=0; $i<$statement->rowCount(); $i++){
			$row=$statement->fetch(PDO::FETCH_ASSOC);
			$results_array[$i] = $row;
		}

	return $results_array;
}



//from the username gives the students B00
function getStudentB00($pdo, $uN)
{
	$statement=false;
	try{
	$sql = "SELECT students.B00 FROM students WHERE students.net_id_email=?;";

	$statement= $pdo->prepare($sql);
	$statement->execute(array("$uN"));
}
	catch(PDOException $e){
		echo 'Unable to catch error' . $e->getMessage();
		exit();
	}

	if($statement->rowCount()<1){
		exit();
	}
	else
	{
		$row=$statement->fetch(PDO::FETCH_ASSOC);
	}
	return $row['B00'];
}
//this grants a student access from 'Add a Student'
function grantAccessOnMyB00($pdo, $TheirB00, $MyB00)
{
		$result='';
		$result=getGrantedStudentsList($pdo, $MyB00);
		array_push($result, $TheirB00);

		$serializedArray=serialize($result);

		try{
			$sql="UPDATE students SET students.other_students=? WHERE students.B00=?;";
				$statement=$pdo->prepare($sql);
				$statement->execute(array("$serializedArray", "$MyB00"));			
			}
		catch(PDOException $e){
			echo 'Unable to update students with new list ' .$e->getMessage();
		}		
}
//this removes access from a student to remove
function removeAccessOnMyB00($pdo, $TheirB00, $MyB00)
{
		$result='';
		$result=getGrantedStudentsList($pdo, $MyB00);
		$key=array_search($TheirB00, $result);
	
		unset($result[$key]);
	
		$serializedArray=serialize($result);

		try{
			$sql="UPDATE students SET students.other_students=? WHERE students.B00=?;";
				$statement=$pdo->prepare($sql);
				$statement->execute(array("$serializedArray", "$MyB00"));			
			}
		catch(PDOException $e){
			echo 'Unable to update students with new list ' .$e->getMessage();
		}		
}

//gets the list of students granted via database, will always be called on reload
function getGrantedStudentsList($pdo, $MyB00){
	try
	{
		$sql="SELECT students.other_students FROM students WHERE students.B00=?;";
		$statement=$pdo->prepare($sql);
		$statement->execute(array("$MyB00"));
	}
	catch(PDOException $e)
	{
		echo 'Unable to query'.$e->getMessage();
		exit();
	}
	$row=$statement->fetch(PDO::FETCH_ASSOC);
	$result=$row['other_students'];
	if($result=='')
		{
			$result=array();
		}
		else
		{
			$result=unserialize($result);
		}
	return $result;
}



//when a student adds ot their schedule, a randomnised CRN must be created
//to display the block as a registered course on their front end
function makeCRN($pdo){
	$code='USER';
	$unique=false;
	$num='';
	$CRN='';

	while(!$unique)
	{
		$num=rand(1000, 9999);

		$CRN= "$code"."$num";
		try{
			$sql="SELECT courses.course_code from courses where courses.course_code=?;";
			$statement=$pdo->prepare($sql);
			$statement->execute(array("$CRN"));
			
		}
		catch(PDOException $e){
			echo 'Unable to send SELECT query' .$e->getMessage();
			exit();
		}
		if($statement->rowCount()==0){
			$unique=true;
		}
	}
	return $CRN;
}

//this creates a unique B00 for the student upon registration 
function makeB00($pdo){
	$B00='B00';
	$unique=false;
	$number='';
	while(!$unique)
	{
		$number=rand(100000, 999999);

		$B00="$B00"."$number";
		try
		{
		$sql= "SELECT students.B00 from students where students.B00=?;";
		$statement=$pdo->prepare($sql);
		$statement->execute(array("$B00"));
		}
		catch(PDOException $e){
			echo 'Unable to query' .$e->getMessage();
			exit();
		}
		if($statement->rowCount()==0){
			$unique=true;
		}
	}
	return $B00;
}

?>
