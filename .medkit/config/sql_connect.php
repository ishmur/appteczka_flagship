<?php
	
	//Connecting to the database
	$dbServer = 'mysql.agh.edu.pl';
	$dbLogin = 'matpab';
	$dbPassword = 'gr6m1XY4';
	$dbTable = $dbLogin;
	$dbPort = '3306';

	// Create connection
	$dbConnection = mysqli_connect($dbServer, $dbLogin, $dbPassword, $dbTable);
	// Check connection
	if (!$dbConnection) {
		die("Connection failed: " . mysqli_connect_error());
	}
?>