<?php
// Start a PHP Session since we'll be passing the form values to a DB
// and a confirmation page should the form validate

if(isset($_SESSION['signin'])||isset($_SESSION['register'])){
	session_destroy();
}
session_start();
// Import the db connection
require_once('dbConnect.php');
require('functions.php');

// Custom function to sanitize our data before sending it to the DB Server
function clean($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
// Import our form validation code
//require('includes/formValidation.php');
// $_POST[] Superglobal can be used since our sign up form uses the POST method
// We are pulling all the data inserted into our form and assigning them to variables
// These values will also be accessible through our Session Superglobal Array
	$fnameError='';
	$loginError='';
	$lnameError='';
	$passError='';
	$firstName ='';
	$lastName = '';
	$password = '';
	$confirm = '';
	$userLogin ='';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {			
// Flag variable to track success:
	$okay = TRUE;
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];
	$userLogin = $_POST['userLogin'];


	// Validate the net id:
	if (empty($userLogin)) {
		$loginError = '<p class="loginError"> Please enter your net id email.</p>';
		$okay = FALSE;
	} else {
		$userLogin = clean($userLogin);
		if (!filter_var($userLogin, FILTER_VALIDATE_EMAIL)) {
			$loginError = '<p class="loginError"> Please enter a valid net id.</p>';
			$okay = FALSE;
		}
		if(!checkUniqueNetID($pdo, $userLogin)){
			$loginError='<p class="loginError"> There is an account already registered to this netID!</p>';
			$okay=FALSE;
		}
	}

echo "Thank You for Registering with Dal Quick Collab!";
	//Validate first and last name:
	if (empty($firstName)) {
		$fnameError = '<p class="fnError">Please provide your first name.</p>';
		$okay = FALSE;
	} else {
		//Our name should contain letters, -, a space, and '
		$firstName = clean($firstName);
		if (!preg_match("/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/",$firstName)) {
			$fnameError = '<p class="fnError">Only letters can be used.</p>';
			$okay = FALSE;
		}
	}
	if (empty($lastName)) {
		$lnameError = '<p class="lnError">Please provide your last name.</p>';
		$okay = FALSE;
	} else {
		//Our name should contain letters, -, a space, and '
		$lastName = clean($lastName);
		if (!preg_match("/^([a-zA-Z]+[\'-]?[a-zA-Z]+[ ]?)+$/",$lastName)) {
			$lnameError = '<p class="lnError">Only letters can be used.</p>';
			$okay = FALSE;
		}
	}

	// Validate the password:
	if (empty($password)) {
		$passError = '<p class="passError">Please enter your password.</p>';
		$okay = FALSE;
	} else {
		$password = clean($password);
		// Check the two passwords:
		if ($password != $confirm) {
			$passError = '<p class="passError">Your confirmed password does not match the original password.</p>';
			$okay = FALSE;
		}
	}
	
	// If our form validates then send the values to our confirmation page
	if ($okay) {
		$_SESSION['register']['email'] = $userLogin;
		$_SESSION['register']['firstName'] = $firstName;
		$_SESSION['register']['lastName'] = $lastName;
		$_SESSION['register']['password'] = $password; 
		// We assign our sanitized variables to our session
		header('Location:confirmation.php');
		exit();
	}
	echo "Thank You for Registering with Dalonline Mock!";
}   


?>
<!DOCTYPE html>
<html lang="en">
<head>
	  <title>Registration Form</title>
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <meta name="Registeration" content="dalonline">
	  <meta name="ARAZOO" content="ARAZOO HOSEYNI">
	  <link rel="stylesheet" href="css/Astyles.css">
	  <link rel="stylesheet" href="css/Anormalize.css">
	  <meta charset="UTF-8">
</head>
<body>
	<div id="wrapper">
	<header class="header">
		<h2>Register with Dal Quick Collab today!</h2>
	</header>
	    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> method="post" id="register">
			<div id="name">
			  <label for="netid">
	          <input type="text" placeholder="Enter your Dalhousie net id emails" name="userLogin" id="netid" >
	        </label>
	        <div class="error"><?php echo $loginError ?></div>
	        <div id="clear">
				<label for="firstName">
					<input type="text" placeholder="First Name" name="firstName" id="firstName">
				</label>

				<label for="lastName">
					<input type="text" placeholder="Last Name" name="lastName" id="lastName">
				</label>
				<div class="error"><?php echo $fnameError ?></div>
				<div class="error"><?php echo $lnameError ?></div>
			</div>
				<div class="clear">
				 <label for="password">
					<input type="password" placeholder="Password" name="password" id="password">
					<div class="error"><?php echo $passError ?></div>
				 </label>
				</div>
				<div class="clear">
				 <labsel for="confirm">
					<input type="password" placeholder="Confirm Password" name="confirm" id="confirm">
				 </labsel>
				</div>
				<div class="clear"></div>
				 <div id="reg">
   			<input type="submit" value="Register" /></a>
				 </div>
				 </div>
				 </div>
				  <div class="clear"></div>
		</form>
		<div class="clear"></div>
		<form action="signin.php">
		<div id="goback">
	    	<input type="submit" value="Go Back"/>
	    	</div>
	    	<div class="clear"></div>
	    </form>
		
	</div>
</body>
   <?php include('footer.php'); ?>
</html>
