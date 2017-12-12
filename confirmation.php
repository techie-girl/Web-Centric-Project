<?php

// We start a PHP Session since we need to use the values from our registration form
// we will display the values to the user as a 'confrimation page' and use them
// to create a new user in our database
session_start();

// We don't want users coming to the confirmation page without registering

$firstName='';
$lastName='';
$email='';
$salt='';
$hash='';
//to make sure that this page has accessed via registration 
if (!isset($_SESSION['register'])){
	header('Location:registration.php');
	exit();
}
//assessing our session values via registration form
else{
	$firstName = $_SESSION['register']['firstName'];
	$lastName = $_SESSION['register']['lastName'];
	$email = $_SESSION['register']['email'];
	
	$salt = '378570bdf03b25c8efa9bfdcfb64f99e';
	$hash = hash_hmac('md5', $_SESSION['register']['password'], $salt);
	echo  $email;
}
// Import the db connection
require_once('dbConnect.php');
require('functions.php');

// We assign our session variables to our variables
$B00= makeB00($pdo);
$name= $firstName." ".$lastName;
//we insert out student info via try catch into the backend
try {
	$query1 = 'INSERT INTO students ( B00, name, net_id_email) VALUES (?, ?, ?)';
	$statement = $pdo->prepare($query1);
	$statement->execute(array("$B00","$name", "$email" ));
	
	$query2 = 'INSERT INTO userpass (usernameemail, password) VALUES ( ?, ?)';
	$statement = $pdo->prepare($query2);
	$statement->execute(array("$email", "$hash"));		
}
catch (PDOException $e){
	 echo 'Unable to insert data into the database ' . $e->getMessage();
	exit();
}

echo '<p class="successMSG">Successfully registered user</p>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	  <meta charset="utf-8">
	  <title>Confirmation</title>
	  <meta name="description" content="Title of Site">
	  <meta name="author" content="Author Name">
	  <link rel="stylesheet" href="css/Astyles.css">
</head>
<body>
	<div id="wrap">
		<h2>Thank You for Registering</h2>
		<p>The following User Account was created</p>
		<p><em>Name:</em> <span class="info"><?php echo $firstName . ' ' . $lastName; ?></span></p>
		<p><em>Username:</em> <span class="infoEmail"><?php echo $email; ?></span></p>
		<p><em>B00:</em> <span class="infoEmail"><?php echo $B00; ?></span></p>
	</div>
	<li><a href="signin.php">Sign In</a></li>
</body>
</html>
