<?php

$_SESSION = array();
session_destroy();

// Import the db connection
require_once('db/dbconnection.php');

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
$userLogin = $_POST['userLogin'];
$userPass = $_POST['userPass'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {			
// Flag variable to track success:
	$okay = TRUE;

	// Validate the email address:
	if (empty($userLogin)) {
		$loginError = '<p class="loginError"> Please enter your email address.</p>';
		$okay = FALSE;
	} else {
		$userLogin = clean($userLogin);
		if (!filter_var($userLogin, FILTER_VALIDATE_EMAIL)) {
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
		require_once('db/dbconnection.php');
		
	
		try {
			$query = 'SELECT id, username FROM users WHERE (username = ? AND password = ?)';
			$statement = $pdo->prepare($query);
			
			$salt = '378570bdf03b25c8efa9bfdcfb64f99e';
			$userPass = hash_hmac('md5', $userPass, $salt);
			
			$statement->execute(array("$userLogin", "$userPass"));
			$row=$statement->fetch(PDO::FETCH_ASSOC);
			
			if($statement->rowCount() > 0) {
				if(password_verify($userPass, $row['password'])){
                	$_SESSION['user_signin'] = $row['id'];
					return true;
            	} else {
                	return false;
            	}
			}
		}
		catch (PDOException $e){
			$output = 'Unable to insert data into the database ' . $e->getMessage();
			echo $output;
			exit();
		}
		
		if(isset($_SESSION['user_signin'])){
			echo '<p class="successMSG"> hello ' . 'ss'.'</p>';
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
	  <link rel="stylesheet" href="css/styles.css">
	  <link rel="stylesheet" href="css/normalize.css">
  </head>
  <body>
  <div id="wrapper">
	<header class="header">
		<h2>Welcome to Dalonline Mock</h2>
	</header>
	    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="signin">
	    	<label for="Net id">
	          <input type="text" placeholder="Enter your Dalhousie net id" name="userLogin" id="userLogin">
	        </label>
	        <label for="password">
	          <input type="password" placeholder="Enter your password" name="userPass" id="userPass">
	          <div class="error"><?php echo $loginError ?></div>
	        </label>
	      <div id="login">
	        <input type="submit" value="Login">
	      </div>
	      <div class="clear"></div>
	    </form>
	  </div>
  </body>
  <footer>
  <p>2017 All Rights Reserved &copy; Dalhousie University Mock</p>
  </footer>
</html>
