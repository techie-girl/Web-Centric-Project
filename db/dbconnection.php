<?php
	
// Import the login credentials
require_once('login.php');

// try to connect to the database
try {
	//if all goes well, assign the returned value to $pdo
	$pdo = new PDO('mysql:host=localhost; dbname=hoseyni', $user, $pswd);
	//configuring the PDO error mode to catch exceptions
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
}
	//if the connection fails, PHP will throw a PDOException
	//catch this exception and store it in $e
catch (PDOException $e)
{
	// create a variable $output to contain information on what happened
	// concatenate it with the error message given by the server
	$output = 'Unable to connect to the database server ' . $e->getMessage();
	// use the exit( ) function to have PHP stop excecuting the script
	// if it does find an issue with the TRY section of our code
	exit();
}

echo '<p class="successMSG">Successfully connected to the database</p>';

?>