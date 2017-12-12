<?php
session_start();

$_SESSION = array();
//session_destroy();

// Import the db connection
require_once('dbConnect.php');

// Custom function to sanitize our data before sending it to the DB Server
function clean($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
// $_POST[] Superglobal can be used since our sign up form uses the POST method
// We are pulling all the data inserted into our form and assigning them to variables
// These values will also be accessible through our Session Superglobal Array

$userName = '';
$userPass = '';
$loginError='';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {			
// Flag variable to track success:
	$okay = TRUE;

	$userName=$_POST['userLogin'];
	$userPass=$_POST['userPass'];

	// Validate the email address:
	if (empty($userName)) {
		$loginError = '<p class="loginError"> Please enter your email address.</p>';
		$okay = FALSE;
	} else {
		$userLogin = clean($userName);
		if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
			$loginError = '<p class="loginError"> Please enter a valid email address.</p>';
			$okay = FALSE;
		}
	}	// Validate the password:
	if (empty($userPass)) {
		$loginError = '<p class="loginError"> Please enter your password.</p>';
		$okay = FALSE;
	} else {
		$userPass = clean($userPass);
	}
	
	// If our form validates then go through with login
	if ($okay) {
		require_once('dbConnect.php');
		
	
		try {
			$query = 'SELECT usernameemail, password FROM userpass WHERE (usernameemail = ? AND password = ?);';
			$statement = $pdo->prepare($query);
			
			$salt = '378570bdf03b25c8efa9bfdcfb64f99e';
			$hashed = hash_hmac('md5', $userPass, $salt);
			
			$statement->execute(array("$userLogin", "$hashed"));
			$row=$statement->fetch(PDO::FETCH_ASSOC);

		

		}
		catch (PDOException $e){
			$loginError = 'Unable to insert data into the database ' . $e->getMessage();
			echo $loginError;
			exit();
		}
		
			if(($statement->rowCount()) > 0) {
                	$_SESSION['signin'] = $row['usernameemail'];
                				}
            else{
            	$loginError='<p class="loginError">There is no account with this credentials!</p>';
            }


		if(isset($_SESSION['signin'])){
			header('Location:present/project/index.html');
		}
	
	}
}



?>

<!DOCTYPE html>
<html lang="en">
  <head>
	  <title>Login Form </title>
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <meta name="login" content="dalonline">
	  <meta name="ARAZOO" content="ARAZOO HOSEYNI">
	  <link rel="stylesheet" href="css/Astyles.css">
	  <link rel="stylesheet" href="css/Anormalize.css">
	  <meta charset="UTF-8">
	  <intercept-url pattern="/favicon.ico" access="ROLE_ANONYMOUS" />
  </head>
  <body>
  <div id="wrapper">
	<header class="header">
		<h2>Dal Quick Collab&nbsp;</h2>
	</header>
	    <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> method="post" id="signin">
	    	<label for="Net id">
	          <input type="text" placeholder="Enter your Dalhousie net id" name="userLogin" id="userLogin">
	        </label>
	        <label for="password">
	          <input type="password" placeholder="Enter your password" name="userPass" id="userPass">
	          <div class="error"><?php echo $loginError ?></div>
	        </label>
	        <div class="clear"></div>
	   			<div id="login">
	   		   	<input type="submit" value="Login" name="submit">
	      </div> 
	      <div class="clear"></div>
	    </form>
	    <form action="registration.php">
	    <div id="goback">
	    	<input type="submit" value="Register Now"/>
	    	</div>
	    	<div class="clear"></div>
	   </form>
	  </div>
  </body>

  <?php include('footer.php'); ?>
</html>

