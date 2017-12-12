<?php
require_once('login.php');

try{
	$pdo= new PDO("mysql:host=$host;port=$port; dbname=$dbname", $user, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	$pdo->exec("SET NAMES 'UTF8'");
}

catch (PDOException $e)
{
	// create a variable $output to contain information on what happened
	// concatenate it with the error message given by the server
	echo 'Unable to connect to the database server ' . $e->getMessage();
	// use the exit( ) function to have PHP stop excecuting the script
	// if it does find an issue with the TRY section of our code
	exit();
}
?>